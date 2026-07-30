<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Stats Cards Data
        $today = now()->startOfDay();
        $startOfMonth = now()->startOfMonth();
        $startOfLastMonth = now()->subMonth()->startOfMonth();
        $endOfLastMonth = now()->subMonth()->endOfMonth();

        // Revenue (Today vs Yesterday)
        $todayRevenue = \App\Models\Order::whereDate('created_at', now())->sum('total_price');
        $yesterdayRevenue = \App\Models\Order::whereDate('created_at', now()->subDay())->sum('total_price');
        $revenueGrowth = $yesterdayRevenue > 0 ? (($todayRevenue - $yesterdayRevenue) / $yesterdayRevenue) * 100 : 100;

        // Profit (Today)
        $todayProfit = \App\Models\OrderItem::whereHas('order', function($q) {
                $q->whereDate('created_at', now());
            })
            ->with('product')
            ->get()
            ->sum(function($item) {
                $buyingPrice = $item->product->buying_price ?? 0;
                return ($item->price - $buyingPrice) * $item->quantity;
            });
            
        // Low Stock Alerts (Etalase)
        $lowStockItems = \App\Models\Product::whereColumn('stock', '<=', 'min_stock')->count();
        
        // Total Inventory Value
        $totalInventoryValue = \App\Models\Product::selectRaw('SUM((stock + warehouse_stock) * buying_price) as total')->value('total') ?? 0;

        // 2. Chart Data (Daily Revenue Trends - Current Month)
        $chartLabels = [];
        $chartData = [];
        
        $startOfMonth = now()->startOfMonth();
        $daysInMonth = $startOfMonth->daysInMonth;
        
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $date = $startOfMonth->copy()->day($i);
            $chartLabels[] = $date->format('d M');
            $chartData[] = \App\Models\Order::whereDate('created_at', $date)
                ->sum('total_price');
        }

        // 3. Top Selling Products
        $topProducts = \App\Models\Product::withSum(['orderItems as total_sold' => function($query) {
                $query->whereHas('order', function($q) {
                    $q->where('created_at', '>=', now()->startOfYear());
                });
            }], 'quantity')
            ->orderByDesc('total_sold')
            ->take(4)
            ->get();

        // 4. Recent Activity
        $recentTransactions = \App\Models\Order::with(['user', 'customer'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'todayRevenue', 'revenueGrowth',
            'todayProfit',
            'lowStockItems',
            'totalInventoryValue',
            'chartLabels', 'chartData',
            'topProducts',
            'recentTransactions'
        ));
    }
}
