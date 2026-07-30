<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\Order::with(['user', 'items', 'customer']);

        // Default to today if no date provided
        // Use provided date, or null if clearing filters
        $date = $request->input('date');
        
        if ($request->filled('date')) {
            $query->whereDate('created_at', $date);
        } elseif (!$request->filled('search') && !$request->filled('payment_method') && !$request->filled('user_id')) {
            $date = now()->format('Y-m-d');
            $query->whereDate('created_at', $date);
        }

        // Search Filter (ID/Invoice, Customer Name)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                // Strip # from search if present to match ID
                $cleanSearch = ltrim($search, '#');
                if (is_numeric($cleanSearch)) {
                    $q->where('id', $cleanSearch);
                }
                
                $q->orWhereHas('customer', function($cq) use ($search) {
                    $cq->where('name', 'like', "%{$search}%");
                });
            });
        }

        // Payment Method Filter
        if ($request->filled('payment_method')) {
            $query->whereRaw('LOWER(payment_method) = ?', [strtolower($request->payment_method)]);
        }

        // Cashier Filter
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $orders = $query->latest()->paginate(15)->withQueryString();
        $users = \App\Models\User::all();
        
        return view('orders.index', compact('orders', 'date', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $order = \App\Models\Order::with(['items.product', 'user', 'customer'])->findOrFail($id);
        
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'order' => $order
            ]);
        }

        return view('orders.receipt', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function exportExcel(Request $request)
    {
        $query = \App\Models\Order::with(['user', 'customer']);

        // Support both single date and date range
        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = \Carbon\Carbon::parse($request->start_date)->startOfDay();
            $endDate = \Carbon\Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } else {
            // Default to today or specific date if no range provided
            $date = $request->input('date', now()->format('Y-m-d'));
            $query->whereDate('created_at', $date);
        }

        $orders = $query->latest()->get();

        $filename = "riwayat_penjualan_" . date('Ymd_His') . ".csv";
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID Order', 'Invoice', 'Tanggal', 'Kasir', 'Pelanggan', 'Metode Bayar', 'Total'];

        $callback = function() use ($orders, $columns) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for Excel UTF-8 support
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, $columns);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->id,
                    $order->invoice_number,
                    $order->created_at->format('d/m/Y H:i'),
                    $order->user->name,
                    $order->customer->name ?? 'Umum',
                    $order->payment_method,
                    $order->total_price,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function printPDF(Request $request)
    {
        $query = \App\Models\Order::with(['user', 'customer', 'items.product']);

        // Support both single date and date range
        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = \Carbon\Carbon::parse($request->start_date)->startOfDay();
            $endDate = \Carbon\Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
            $period = $startDate->format('d/m/Y') . ' - ' . $endDate->format('d/m/Y');
        } else {
            $date = $request->input('date', now()->format('Y-m-d'));
            $query->whereDate('created_at', $date);
            $period = \Carbon\Carbon::parse($date)->format('d/m/Y');
        }

        $orders = $query->latest()->get();
        return view('orders.print', compact('orders', 'period'));
    }
}
