<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->input('tab', 'sales');
        
        // Date Filtering (Default: Current Month)
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();

        // --- SALES DATA ---
        $ordersQuery = Order::whereBetween('created_at', [$startDate, $endDate]);
        $totalRevenue = $ordersQuery->sum('total_price');
        $transactionCount = $ordersQuery->count();

        $orderItems = OrderItem::whereHas('order', function($q) use ($startDate, $endDate) {
            $q->whereBetween('created_at', [$startDate, $endDate]);
        })->with('product')->get();

        $cogs = 0;
        foreach ($orderItems as $item) {
            if ($item->product) {
                $cogs += $item->quantity * $item->product->buying_price;
            }
        }

        $grossProfit = $totalRevenue - $cogs;
        $netMargin = $totalRevenue > 0 ? ($grossProfit / $totalRevenue) * 100 : 0;

        $allMonthlyData = $this->getMonthlyProfitLoss();
        $firstIndexWithData = 0;
        foreach ($allMonthlyData as $index => $data) {
            if ($data['revenue'] > 0 || $data['expenses'] > 0 || $data['cogs'] > 0) {
                $firstIndexWithData = $index;
                break;
            }
        }
        $monthlyData = array_values(array_slice($allMonthlyData, $firstIndexWithData));
        $trendLabels = array_column($monthlyData, 'month_short');
        $trendData = array_column($monthlyData, 'revenue');

        $salesByCategory = OrderItem::select(
            'categories.name as category_name',
            DB::raw('SUM(order_items.quantity) as total_qty'),
            DB::raw('SUM(order_items.quantity * order_items.price) as total_sales')
        )
        ->join('products', 'order_items.product_id', '=', 'products.id')
        ->join('categories', 'products.category_id', '=', 'categories.id')
        ->whereHas('order', function($q) use ($startDate, $endDate) {
            $q->whereBetween('created_at', [$startDate, $endDate]);
        })
        ->groupBy('categories.name')
        ->get();

        $categoryLabels = $salesByCategory->pluck('category_name')->toArray();
        $categoryData = $salesByCategory->pluck('total_qty')->toArray();

        $bestSellers = Product::withCount(['orderItems as total_sold' => function($query) use ($startDate, $endDate) {
            $query->whereHas('order', function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            });
            $query->select(DB::raw('SUM(quantity)'));
        }])
        ->orderByDesc('total_sold')
        ->take(5)
        ->get();

        $criticalStock = Product::whereColumn('stock', '<=', 'min_stock')->take(5)->get();

        $inventoryStats = Product::selectRaw('SUM((stock + warehouse_stock) * buying_price) as total_value, SUM(stock + warehouse_stock) as total_qty')->first();
        $totalInventoryValue = $inventoryStats->total_value ?? 0;
        $totalInventoryQty = $inventoryStats->total_qty ?? 0;

        // --- EXPENSES DATA ---
        $dateFrom = $startDate->format('Y-m-d');
        $dateTo = $endDate->format('Y-m-d');
        
        $expensesQuery = Expense::whereBetween('date', [$dateFrom, $dateTo]);
        if ($request->filled('search')) {
            $search = $request->search;
            $expensesQuery->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                      ->orWhere('category', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $totalExpense = $expensesQuery->sum('amount');
        $expenseCount = $expensesQuery->count();
        $averageExpense = $expenseCount > 0 ? $totalExpense / $expenseCount : 0;
        $categoryCount = Expense::whereBetween('date', [$dateFrom, $dateTo])->distinct('category')->count('category');

        $byCategory = Expense::whereBetween('date', [$dateFrom, $dateTo])
            ->select('category', DB::raw('count(*) as count'), DB::raw('sum(amount) as total'))
            ->groupBy('category')
            ->get()
            ->keyBy('category')
            ->map(function ($item) {
                return ['count' => $item->count, 'total' => $item->total];
            });

        $byDate = Expense::whereBetween('date', [$dateFrom, $dateTo])
            ->select(DB::raw('DATE(date) as date_only'), DB::raw('sum(amount) as total'))
            ->groupBy('date_only')
            ->orderBy('date_only')
            ->get()
            ->pluck('total', 'date_only');

        $expenses = Expense::with('user')
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->orderBy('date', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('reports.index', compact(
            'tab', 'startDate', 'endDate',
            'totalRevenue', 'grossProfit', 'netMargin', 'transactionCount',
            'totalInventoryValue', 'totalInventoryQty',
            'trendLabels', 'trendData',
            'categoryLabels', 'categoryData',
            'bestSellers', 'criticalStock',
            'monthlyData',
            'expenses', 'totalExpense', 'expenseCount', 'averageExpense', 'categoryCount', 'byCategory', 'byDate', 'dateFrom', 'dateTo'
        ));
    }

    /**
     * Calculate monthly profit/loss for the last 12 months
     */
    private function getMonthlyProfitLoss()
    {
        $monthlyData = [];
        
        // Get data for last 12 months
        for ($i = 11; $i >= 0; $i--) {
            $monthStart = Carbon::now()->subMonths($i)->startOfMonth();
            $monthEnd = Carbon::now()->subMonths($i)->endOfMonth();
            
            // Revenue from orders
            $revenue = Order::whereBetween('created_at', [$monthStart, $monthEnd])
                ->sum('total_price');
            
            // COGS (Cost of Goods Sold)
            $orderItems = OrderItem::whereHas('order', function($q) use ($monthStart, $monthEnd) {
                $q->whereBetween('created_at', [$monthStart, $monthEnd]);
            })->with('product')->get();
            
            $cogs = 0;
            foreach ($orderItems as $item) {
                if ($item->product) {
                    $cogs += $item->quantity * $item->product->buying_price;
                }
            }
            
            // Operational Expenses
            $expenses = Expense::whereBetween('date', [$monthStart, $monthEnd])
                ->sum('amount');
            
            // Skip months with no data - REMOVED to let chart show partial data
            // if ($revenue == 0 && $expenses == 0) {
            //     continue;
            // }
            
            // Calculations
            $grossProfit = $revenue - $cogs;
            $totalExpenses = $cogs + $expenses;
            $netProfit = $revenue - $totalExpenses;
            $margin = $revenue > 0 ? ($netProfit / $revenue) * 100 : 0;
            $status = $netProfit >= 0 ? 'profit' : 'loss';
            
            $monthlyData[] = [
                'month' => $monthStart->format('M Y'),
                'month_short' => $monthStart->format('M'),
                'revenue' => $revenue,
                'cogs' => $cogs,
                'expenses' => $expenses,
                'total_expenses' => $totalExpenses,
                'gross_profit' => $grossProfit,
                'net_profit' => $netProfit,
                'margin' => $margin,
                'status' => $status,
            ];
        }
        
        return $monthlyData;
    }

    /**
     * Export monthly profit/loss report to Excel (CSV)
     */
    public function exportMonthlyExcel()
    {
        $monthlyData = $this->getMonthlyProfitLoss();
        
        // Filter empty months
        $monthlyData = array_filter($monthlyData, function($item) {
            return $item['revenue'] > 0 || $item['expenses'] > 0 || $item['cogs'] > 0;
        });
        
        $filename = 'laporan-laba-rugi-bulanan-' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($monthlyData) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for Excel UTF-8 support
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header row
            fputcsv($file, [
                'Bulan',
                'Pendapatan',
                'COGS',
                'Pengeluaran',
                'Total Biaya',
                'Laba Bersih',
                'Margin (%)',
                'Status'
            ]);
            
            // Data rows
            foreach ($monthlyData as $data) {
                fputcsv($file, [
                    $data['month'],
                    $data['revenue'],
                    $data['cogs'],
                    $data['expenses'],
                    $data['total_expenses'],
                    $data['net_profit'],
                    number_format($data['margin'], 2),
                    $data['status'] == 'profit' ? 'UNTUNG' : 'RUGI'
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Print monthly profit/loss report to PDF
     */
    public function printMonthlyPDF()
    {
        $monthlyData = $this->getMonthlyProfitLoss();
        
        // Filter empty months
        $monthlyData = array_filter($monthlyData, function($item) {
            return $item['revenue'] > 0 || $item['expenses'] > 0 || $item['cogs'] > 0;
        });
        
        return view('reports.monthly-print', compact('monthlyData'));
    }
}
