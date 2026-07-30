<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(\Illuminate\Http\Request $request)
    {
        $query = \App\Models\Product::with(['category', 'supplier', 'units']);

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Category Filter
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Supplier Filter
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        // Stock Status Filter
        if ($request->filled('stock_status')) {
            $status = $request->stock_status;
            if ($status === 'safe') {
                $query->where('stock', '>', 10);
            } elseif ($status === 'low') {
                $query->whereColumn('stock', '<=', 'min_stock')
                      ->where('stock', '>', 0);
            } elseif ($status === 'restock_ready') {
                $query->whereColumn('stock', '<=', 'min_stock')
                      ->where('warehouse_stock', '>', 0);
            } elseif ($status === 'out_of_stock') {
                $query->where('stock', '<=', 0)
                      ->where('warehouse_stock', '<=', 0);
            }
        }

        if ($request->wantsJson()) {
            return response()->json($query->paginate(20));
        }

        $products = $query->latest()->paginate(10)->withQueryString();
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = \App\Models\Category::all();
        $suppliers = \App\Models\Supplier::all();
        
        // Generate sequential SKU: PRD-001, PRD-002, etc.
        $lastProduct = \App\Models\Product::where('sku', 'like', 'PRD-%')
            ->orderByRaw("CAST(SUBSTRING(sku, 5) AS UNSIGNED) DESC")
            ->first();
        
        $nextNumber = 1;
        if ($lastProduct) {
            $lastNumber = (int) substr($lastProduct->sku, 4); // extract number after "PRD-"
            $nextNumber = $lastNumber + 1;
        }
        $suggestedSku = 'PRD-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        return view('products.create', compact('categories', 'suppliers', 'suggestedSku'));
    }

    /**
     * Return the next sequential SKU via JSON (for AJAX modal).
     */
    public function nextSku()
    {
        $lastProduct = \App\Models\Product::where('sku', 'like', 'PRD-%')
            ->orderByRaw("CAST(SUBSTRING(sku, 5) AS UNSIGNED) DESC")
            ->first();

        $nextNumber = 1;
        if ($lastProduct) {
            $lastNumber = (int) substr($lastProduct->sku, 4);
            $nextNumber = $lastNumber + 1;
        }

        return response()->json([
            'sku' => 'PRD-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(\Illuminate\Http\Request $request)
    {
        if ($request->has('warehouse_stock') && $request->filled('warehouse_stock')) {
            $val = str_replace(',', '.', $request->input('warehouse_stock'));
            $request->merge(['warehouse_stock' => $val]);
        }

        if ($request->has('units') && is_array($request->input('units'))) {
            $units = $request->input('units');
            foreach ($units as $i => $unit) {
                if (isset($unit['price'])) {
                    // Normalize price: remove thousands separator dot and replace comma decimal with dot
                    $cleanPrice = str_replace('.', '', $unit['price']);
                    $cleanPrice = str_replace(',', '.', $cleanPrice);
                    $units[$i]['price'] = (float)$cleanPrice;
                }
                $units[$i]['is_base'] = isset($unit['is_base']) && filter_var($unit['is_base'], FILTER_VALIDATE_BOOLEAN);
            }
            $request->merge(['units' => $units]);
        }

        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'name' => 'required',
            'sku' => 'required|unique:products',
            'barcode' => 'nullable|string|unique:products',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'buying_price' => 'required|numeric',
            'stock' => 'required|integer',
            'warehouse_stock' => 'nullable|numeric',
            'min_stock' => 'nullable|integer',
            'units' => 'required|array|min:1',
            'units.*.unit_name' => 'required|string',
            'units.*.conversion_factor' => 'required|integer|min:1',
            'units.*.price' => 'required|numeric|min:0',
        ]);

        $data = $request->except(['image', 'units']);

        // Determine base price and box price to sync back to legacy product fields
        $baseUnit = collect($request->input('units'))->firstWhere('is_base', true)
                    ?? collect($request->input('units'))->firstWhere('conversion_factor', 1)
                    ?? collect($request->input('units'))->first();
        
        $data['selling_price'] = $baseUnit ? (float)$baseUnit['price'] : 0;

        $boxUnit = collect($request->input('units'))->first(function($u) {
            return strtolower($u['unit_name']) === 'dus' || strtolower($u['unit_name']) === 'box' || (int)$u['conversion_factor'] > 1;
        });

        if ($boxUnit) {
            $data['box_price'] = (float)$boxUnit['price'];
            $data['box_quantity'] = (int)$boxUnit['conversion_factor'];
        } else {
            $data['box_price'] = null;
            $data['box_quantity'] = null;
        }

        // Save warehouse stock directly in pieces (calculated from frontend UOM cards)
        $data['warehouse_stock'] = isset($data['warehouse_stock']) ? (int)$data['warehouse_stock'] : 0;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product = \App\Models\Product::create($data);

        // Save new UOM units
        foreach ($request->input('units') as $unitData) {
            $product->units()->create([
                'unit_name' => $unitData['unit_name'],
                'conversion_factor' => $unitData['conversion_factor'],
                'price' => $unitData['price'],
                'is_base' => $unitData['is_base'] ?? false,
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan'
            ]);
        }

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = \App\Models\Product::findOrFail($id);
        $categories = \App\Models\Category::all();
        $suppliers = \App\Models\Supplier::all();
        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(\Illuminate\Http\Request $request, string $id)
    {
        $product = \App\Models\Product::findOrFail($id);

        if ($request->has('warehouse_stock') && $request->filled('warehouse_stock')) {
            $val = str_replace(',', '.', $request->input('warehouse_stock'));
            $request->merge(['warehouse_stock' => $val]);
        }

        if ($request->has('units') && is_array($request->input('units'))) {
            $units = $request->input('units');
            foreach ($units as $i => $unit) {
                if (isset($unit['price'])) {
                    // Normalize price: remove thousands separator dot and replace comma decimal with dot
                    $cleanPrice = str_replace('.', '', $unit['price']);
                    $cleanPrice = str_replace(',', '.', $cleanPrice);
                    $units[$i]['price'] = (float)$cleanPrice;
                }
                $units[$i]['is_base'] = isset($unit['is_base']) && filter_var($unit['is_base'], FILTER_VALIDATE_BOOLEAN);
            }
            $request->merge(['units' => $units]);
        }

        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'name' => 'required',
            'sku' => 'required|unique:products,sku,' . $product->id,
            'barcode' => 'nullable|string|unique:products,barcode,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'buying_price' => 'required|numeric',
            'stock' => 'required|integer',
            'warehouse_stock' => 'nullable|numeric',
            'min_stock' => 'nullable|integer',
            'units' => 'required|array|min:1',
            'units.*.unit_name' => 'required|string',
            'units.*.conversion_factor' => 'required|integer|min:1',
            'units.*.price' => 'required|numeric|min:0',
        ]);

        $data = $request->except(['image', 'units']); // Changed from $request->all() for consistency

        // Determine base price and box price to sync back to legacy product fields
        $baseUnit = collect($request->input('units'))->firstWhere('is_base', true)
                    ?? collect($request->input('units'))->firstWhere('conversion_factor', 1)
                    ?? collect($request->input('units'))->first();
        
        $data['selling_price'] = $baseUnit ? (float)$baseUnit['price'] : 0;

        $boxUnit = collect($request->input('units'))->first(function($u) {
            return strtolower($u['unit_name']) === 'dus' || strtolower($u['unit_name']) === 'box' || (int)$u['conversion_factor'] > 1;
        });

        if ($boxUnit) {
            $data['box_price'] = (float)$boxUnit['price'];
            $data['box_quantity'] = (int)$boxUnit['conversion_factor'];
        } else {
            $data['box_price'] = null;
            $data['box_quantity'] = null;
        }

        // Save warehouse stock directly in pieces (calculated from frontend UOM cards)
        $data['warehouse_stock'] = isset($data['warehouse_stock']) ? (int)$data['warehouse_stock'] : 0;

        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        } elseif ($request->boolean('remove_image')) { // Added to handle explicit image removal
            if ($product->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image);
            }
            $data['image'] = null;
        }

        $product->update($data);

        // Sync product units: delete existing and recreate
        $product->units()->delete();
        foreach ($request->input('units') as $unitData) {
            $product->units()->create([
                'unit_name' => $unitData['unit_name'],
                'conversion_factor' => $unitData['conversion_factor'],
                'price' => $unitData['price'],
                'is_base' => $unitData['is_base'] ?? false,
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil diperbarui'
            ]);
        }

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = \App\Models\Product::findOrFail($id);
        if ($product->image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }

    public function exportExcel(\Illuminate\Http\Request $request)
    {
        $query = \App\Models\Product::with(['category', 'supplier', 'units']);

        // Apply filters (Same as index)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }
        if ($request->filled('category_id')) $query->where('category_id', $request->category_id);
        if ($request->filled('supplier_id')) $query->where('supplier_id', $request->supplier_id);
        if ($request->filled('stock_status')) {
            $status = $request->stock_status;
            if ($status === 'safe') $query->where('stock', '>', 10);
            elseif ($status === 'low') $query->whereColumn('stock', '<=', 'min_stock')->where('stock', '>', 0);
            elseif ($status === 'out_of_stock') $query->where('stock', '<=', 0);
        }

        $products = $query->latest()->get();

        $filename = "produk_ekspor_" . date('Ymd_His') . ".csv";
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['SKU', 'Nama Produk', 'Kategori', 'Supplier', 'Harga Beli', 'Harga Jual', 'Stok Etalase', 'Stok Gudang', 'Total Stok'];

        $callback = function() use ($products, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($products as $product) {
                fputcsv($file, [
                    $product->sku,
                    $product->name,
                    $product->category->name,
                    $product->supplier->name ?? '-',
                    $product->buying_price,
                    $product->selling_price,
                    $product->stock,
                    $product->warehouse_stock,
                    ($product->stock + $product->warehouse_stock),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function printPDF(\Illuminate\Http\Request $request)
    {
        $query = \App\Models\Product::with(['category', 'supplier', 'units']);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }
        if ($request->filled('category_id')) $query->where('category_id', $request->category_id);
        if ($request->filled('supplier_id')) $query->where('supplier_id', $request->supplier_id);
        if ($request->filled('stock_status')) {
            $status = $request->stock_status;
            if ($status === 'safe') $query->where('stock', '>', 10);
            elseif ($status === 'low') $query->whereColumn('stock', '<=', 'min_stock')->where('stock', '>', 0);
            elseif ($status === 'out_of_stock') $query->where('stock', '<=', 0);
        }

        $products = $query->latest()->get();
        return view('products.print', compact('products'));
    }
}
