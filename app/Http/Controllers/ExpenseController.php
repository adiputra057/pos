<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $query = Expense::with('user');

        // Default to today if no date range provided
        $dateFrom = $request->input('date_from', now()->format('Y-m-d'));
        $dateTo = $request->input('date_to', now()->format('Y-m-d'));
        
        $query->whereDate('date', '>=', $dateFrom)
              ->whereDate('date', '<=', $dateTo);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $expenses = $query->latest('date')->paginate(10)->withQueryString();
        return view('expenses.index', compact('expenses', 'dateFrom', 'dateTo'));
    }

    public function create()
    {
        return view('expenses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'category' => 'nullable|string',
            'date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        Expense::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'amount' => $request->amount,
            'category' => $request->category,
            'date' => $request->date,
            'description' => $request->description,
        ]);

        return redirect()->route('reports.index', ['tab' => 'expenses'])->with('success', 'Pengeluaran berhasil dicatat.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('reports.index', ['tab' => 'expenses'])->with('success', 'Pengeluaran dihapus.');
    }

    /**
     * Display expense report with analytics
     */
    public function report(Request $request)
    {
        if ($request->filled('date')) {
            $dateFrom = $request->date;
            $dateTo = $request->date;
        } else {
            $dateFrom = $request->input('date_from', now()->startOfMonth()->format('Y-m-d'));
            $dateTo = $request->input('date_to', now()->endOfMonth()->format('Y-m-d'));
        }

        // Base Query for calculation
        $query = Expense::whereBetween('date', [$dateFrom, $dateTo]);

        // Calculate totals from full query
        $totalExpense = $query->sum('amount');

        // Group by category from full query
        $byCategory = Expense::whereBetween('date', [$dateFrom, $dateTo])
            ->select('category', \Illuminate\Support\Facades\DB::raw('count(*) as count'), \Illuminate\Support\Facades\DB::raw('sum(amount) as total'))
            ->groupBy('category')
            ->get()
            ->keyBy('category')
            ->map(function ($item) {
                return [
                    'count' => $item->count,
                    'total' => $item->total,
                ];
            });

        // Group by date for chart from full query
        $byDate = Expense::whereBetween('date', [$dateFrom, $dateTo])
            ->select(\Illuminate\Support\Facades\DB::raw('DATE(date) as date_only'), \Illuminate\Support\Facades\DB::raw('sum(amount) as total'))
            ->groupBy('date_only')
            ->orderBy('date_only')
            ->get()
            ->pluck('total', 'date_only');

        // Get expenses within date range with pagination for the table
        $expenses = Expense::with('user')
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->orderBy('date', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('expenses.report', compact(
            'expenses',
            'totalExpense',
            'byCategory',
            'byDate',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * Export expense report to Excel
     */
    public function exportExcel(Request $request)
    {
        $dateFrom = $request->input('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->input('date_to', now()->endOfMonth()->format('Y-m-d'));

        $expenses = Expense::with('user')
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->orderBy('date', 'desc')
            ->get();

        $filename = 'laporan-pengeluaran-' . $dateFrom . '-to-' . $dateTo . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($expenses) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Tanggal', 'Judul', 'Kategori', 'Jumlah', 'Keterangan', 'Dibuat Oleh']);

            foreach ($expenses as $expense) {
                fputcsv($file, [
                    $expense->date->format('d/m/Y'),
                    $expense->title,
                    $expense->category ?? '-',
                    $expense->amount,
                    $expense->description ?? '-',
                    $expense->user->name ?? '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

