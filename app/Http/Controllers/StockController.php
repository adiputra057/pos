<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockLog;
use App\Models\Supplier;
use App\Services\StockService;
use Illuminate\Http\Request;

class StockController extends Controller
{
    protected $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    public function index(Request $request)
    {
        $query = StockLog::with(['product', 'user']);

        // Default to today if no date provided
        $date = $request->input('date', now()->format('Y-m-d'));
        $query->whereDate('created_at', $date);

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('product', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Type Filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $logs = $query->latest()->paginate(20)->withQueryString();
        $products = Product::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        $tab = $request->input('tab', 'incoming');
        
        return view('stocks.index', compact('logs', 'products', 'suppliers', 'date', 'tab'));
    }

    public function create()
    {
        $products = Product::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get(); // Optional if we want to associate supplier with stock in
        return view('stocks.create', compact('products', 'suppliers'));
    }

    public function store(Request $request)
    {
        if ($request->has('items')) {
            $request->validate([
                'items' => 'required|array',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.unit' => 'nullable|in:pcs,box',
                'type' => 'required|in:in,out,adjustment',
                'location' => 'required|in:display,warehouse',
                'note' => 'nullable|string',
                'receipt_date' => 'nullable|date',
                'supplier_id' => 'nullable|exists:suppliers,id',
            ]);

            $date = $request->input('receipt_date', now());
            $supplierName = '';
            if ($request->filled('supplier_id')) {
                $supplierName = Supplier::find($request->supplier_id)->name ?? '';
            }

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $unit = $item['unit'] ?? 'pcs';
                $qty = $item['quantity'];

                // Convert box to units if applicable
                if ($unit === 'box' && $product->box_quantity > 0) {
                    $qty = $qty * $product->box_quantity;
                }
                
                if ($request->type === 'out') {
                    $qty = -abs($qty);
                    $currentStock = ($request->location ?? 'display') === 'warehouse' ? $product->warehouse_stock : $product->stock;
                    if ($currentStock + $qty < 0) {
                        return back()->withErrors(['quantity' => "Stok produk {$product->name} tidak mencukupi di " . (($request->location ?? 'display') === 'warehouse' ? 'Gudang' : 'Etalase') . "!"]);
                    }
                }
                
                $note = $request->note;
                if ($unit === 'box') {
                    $note = ($note ? $note . " | " : "") . "Satuan: Dus (Isi {$product->box_quantity})";
                }

                if ($supplierName) {
                    $note = ($note ? $note . " | " : "") . "Supplier: " . $supplierName;
                }

                $this->stockService->adjustStock(
                    product: $product, 
                    quantity: $qty, 
                    type: $request->type, 
                    note: $note,
                    date: $date,
                    location: $request->location ?? 'display'
                );
            }
             return redirect()->route('stocks.index')->with('success', 'Stok berhasil diperbarui (Massal).');
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:in,out,adjustment',
            'location' => 'required|in:display,warehouse',
            'quantity' => 'required|integer|min:1',
            'unit' => 'nullable|in:pcs,box',
            'note' => 'nullable|string',
        ]);

        $product = Product::findOrFail($request->product_id);
        $unit = $request->unit ?? 'pcs';
        $qty = $request->quantity;

        if ($unit === 'box' && $product->box_quantity > 0) {
            $qty = $qty * $product->box_quantity;
        }
        if ($request->type === 'out') {
            $qty = -abs($qty);
            $currentStock = $request->location === 'warehouse' ? $product->warehouse_stock : $product->stock;
            if ($currentStock + $qty < 0) {
                 return back()->withErrors(['quantity' => 'Stok tidak mencukupi!']);
            }
        }

        $note = $request->note;
        if ($unit === 'box') {
            $note = ($note ? $note . " | " : "") . "Satuan: Dus (Isi {$product->box_quantity})";
        }

        $this->stockService->adjustStock(
            product: $product, 
            quantity: $qty, 
            type: $request->type, 
            note: $note,
            location: $request->location
        );

        return redirect()->route('stocks.index')->with('success', 'Stok berhasil diperbarui.');
    }

    public function transfer(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'unit' => 'nullable|in:pcs,box',
            'from' => 'required|in:display,warehouse',
            'to' => 'required|in:display,warehouse',
            'note' => 'nullable|string',
        ]);

        if ($request->from === $request->to) {
            if ($request->wantsJson()) {
                return response()->json(['errors' => ['to' => ['Lokasi asal dan tujuan tidak boleh sama.']]], 422);
            }
            return back()->withErrors(['to' => 'Lokasi asal dan tujuan tidak boleh sama.']);
        }

        try {
            $product = Product::findOrFail($request->product_id);
            $qty = $request->quantity;
            $unit = $request->unit ?? 'pcs';
            $note = $request->note;

            if ($unit === 'box' && $product->box_quantity > 0) {
                $qty = $qty * $product->box_quantity;
                $note = ($note ? $note . " | " : "") . "Satuan: Dus (Isi {$product->box_quantity})";
            }

            $this->stockService->transferStock(
                $product, 
                $qty, 
                $request->from, 
                $request->to, 
                $note
            );

            if ($request->wantsJson()) {
                return response()->json(['message' => 'Stok berhasil dipindahkan.']);
            }
            return redirect()->route('stocks.index', ['tab' => 'history'])->with('success', 'Stok berhasil dipindahkan.');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['errors' => ['quantity' => [$e->getMessage()]]], 422);
            }
            return back()->withErrors(['quantity' => $e->getMessage()]);
        }
    }

    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template_upload_stok.csv"',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['sku', 'product_name', 'quantity', 'unit', 'location', 'note']);
            
            // Add some example data from existing products if any
            $products = Product::limit(3)->get();
            foreach ($products as $product) {
                fputcsv($file, [$product->sku, $product->name, '1', 'pcs', 'display', 'Contoh pengisian']);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
            'type' => 'required|in:in,out,adjustment',
        ]);

        $file = $request->file('file');
        $path = $file->getRealPath();
        $importedCount = 0;
        $errors = [];

        if (($handle = fopen($path, 'r')) !== FALSE) {
            $header = fgetcsv($handle, 1000, ','); // Skip header row
            
            while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                if (count($data) < 3) continue;

                $sku = trim($data[0]);
                $name = trim($data[1]);
                $quantity = (int)$data[2];
                $unit = isset($data[3]) ? strtolower(trim($data[3])) : 'pcs';
                $location = isset($data[4]) ? strtolower(trim($data[4])) : 'display';
                $note = isset($data[5]) ? trim($data[5]) : 'Import Massal';

                if (!in_array($unit, ['pcs', 'box'])) $unit = 'pcs';
                if (!in_array($location, ['display', 'warehouse'])) $location = 'display';

                if ($quantity <= 0 && $request->type !== 'adjustment') continue;

                try {
                    // Try to find by SKU first, then by exact name
                    $product = Product::where('sku', $sku)->first();
                    if (!$product && $name) {
                        $product = Product::where('name', $name)->first();
                    }

                    if ($product) {
                        $qty = $quantity;
                        if ($unit === 'box' && $product->box_quantity > 0) {
                            $qty = $qty * $product->box_quantity;
                        }

                        if ($request->type === 'out') {
                            $qty = -abs($qty);
                        }

                        $currentStock = $location === 'warehouse' ? $product->warehouse_stock : $product->stock;
                        if ($request->type === 'out' && ($currentStock + $qty < 0)) {
                            $errors[] = "Stok {$product->name} di {$location} tidak cukup (Sisa: {$currentStock})";
                            continue;
                        }

                        $importNote = $note;
                        if ($unit === 'box') {
                            $importNote = ($importNote ? $importNote . " | " : "") . "Satuan: Dus (Isi {$product->box_quantity})";
                        }

                        $this->stockService->adjustStock(
                            product: $product,
                            quantity: $qty,
                            type: $request->type,
                            note: $importNote,
                            location: $location
                        );
                        $importedCount++;
                    } else {
                        $errors[] = "Produk dengan SKU/Nama '{$sku}' atau '{$name}' tidak ditemukan.";
                    }
                } catch (\Exception $e) {
                    $errors[] = "Error pada SKU {$sku}: " . $e->getMessage();
                }
            }
            fclose($handle);
        }

        $message = "Berhasil mengimpor {$importedCount} data stok ({$request->type}).";
        if (count($errors) > 0) {
            return redirect()->route('stocks.index', ['tab' => 'history'])
                ->with('success', $message)
                ->with('error', "Ada beberapa kendala: " . implode(', ', array_slice($errors, 0, 3)) . (count($errors) > 3 ? " dan lainnya." : ""));
        }

        return redirect()->route('stocks.index', ['tab' => 'history'])->with('success', $message);
    }
}
