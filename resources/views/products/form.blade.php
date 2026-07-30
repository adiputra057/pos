<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Product Name -->
    <div class="col-span-1">
        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Produk</label>
        <input type="text" name="name" id="name" value="{{ old('name', $product->name ?? '') }}" class="w-full bg-white rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" placeholder="Contoh: Mouse Wireless" required>
        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <!-- SKU -->
    <div class="col-span-1">
        <label for="sku" class="block text-sm font-medium text-gray-700 mb-1">SKU (Kode Internal)</label>
        <div class="relative">
            <input type="text" name="sku" id="sku" value="{{ old('sku', $product->sku ?? $suggestedSku ?? '') }}" class="w-full bg-gray-50 dark:bg-gray-800 border-gray-300 shadow-sm pl-10 cursor-not-allowed" placeholder="Contoh: IND-GOR" readonly required>
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
            </div>
        </div>
        @error('sku') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <!-- Barcode -->
    <div class="col-span-1">
        <label for="barcode" class="block text-sm font-medium text-gray-700 mb-1">Barcode (Scan dengan Alat Scanner)</label>
        <div class="relative">
            <input type="text" name="barcode" id="barcode" value="{{ old('barcode', $product->barcode ?? '') }}" class="w-full bg-white rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm pl-10" placeholder="Arahkan kursor & scan barcode fisik...">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
            </div>
        </div>
        @error('barcode') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <!-- Category -->
    <div class="col-span-1">
        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
        <select name="category_id" id="category_id" class="w-full bg-white rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
            <option value="">Pilih Kategori</option>
            @foreach(\App\Models\Category::all() as $category)
                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <!-- Supplier -->
    <div class="col-span-1">
        <label for="supplier_id" class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
        <select name="supplier_id" id="supplier_id" class="w-full bg-white rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
            <option value="">Pilih Supplier</option>
            @foreach(\App\Models\Supplier::all() as $supplier)
                <option value="{{ $supplier->id }}" {{ old('supplier_id', $product->supplier_id ?? '') == $supplier->id ? 'selected' : '' }}>
                    {{ $supplier->name }}
                </option>
            @endforeach
        </select>
        @error('supplier_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <!-- Buying Price -->
    <div class="col-span-1">
        <label for="buying_price" class="block text-sm font-medium text-gray-700 mb-1">Harga Beli (Rp)</label>
        <div class="relative">
             <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <span class="text-gray-500 sm:text-sm">Rp</span>
            </div>
            <input type="number" name="buying_price" id="buying_price" value="{{ old('buying_price', $product->buying_price ?? '') }}" class="w-full bg-white rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm pl-10" placeholder="0.00" step="0.01" required>
        </div>
        @error('buying_price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <!-- Selling Price -->
    <div class="col-span-1">
        <label for="selling_price" class="block text-sm font-medium text-gray-700 mb-1">Harga Jual (Rp)</label>
        <div class="relative">
             <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <span class="text-gray-500 sm:text-sm">Rp</span>
            </div>
            <input type="number" name="selling_price" id="selling_price" value="{{ old('selling_price', $product->selling_price ?? '') }}" class="w-full bg-white rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm pl-10" placeholder="0.00" step="0.01" required>
        </div>
        @error('selling_price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <!-- Wholesale Price -->
    <div class="col-span-1">
        <label for="wholesale_price" class="block text-sm font-medium text-gray-700 mb-1">Harga Grosir Satuan (Rp) (Opsional)</label>
        <div class="relative">
             <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <span class="text-gray-500 sm:text-sm">Rp</span>
            </div>
            <input type="number" name="wholesale_price" id="wholesale_price" value="{{ old('wholesale_price', $product->wholesale_price ?? '') }}" class="w-full bg-white rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm pl-10" placeholder="0.00" step="0.01">
        </div>
        @error('wholesale_price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <!-- Min Wholesale Qty -->
    <div class="col-span-1">
        <label for="min_wholesale_qty" class="block text-sm font-medium text-gray-700 mb-1">Min. Qty Grosir</label>
        <input type="number" name="min_wholesale_qty" id="min_wholesale_qty" value="{{ old('min_wholesale_qty', $product->min_wholesale_qty ?? 5) }}" class="w-full bg-white rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" placeholder="5">
        @error('min_wholesale_qty') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>


    @if(\App\Models\Setting::get('track_stock', '1') === '1')
    <!-- Initial Stock -->
    <div class="col-span-1">
        <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">Stok Awal</label>
        <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock ?? 0) }}" class="w-full bg-white rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" placeholder="0" required>
        @error('stock') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <!-- Min Stock Alert -->
    <div class="col-span-1">
        <label for="min_stock" class="block text-sm font-medium text-gray-700 mb-1">Peringatan Stok Minimum</label>
        <input type="number" name="min_stock" id="min_stock" value="{{ old('min_stock', $product->min_stock ?? 0) }}" class="w-full bg-white rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" placeholder="0">
        @error('min_stock') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
    @endif
    
     <!-- Image Upload (Optional, full-width) -->
     <div class="col-span-1 md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-1">Gambar Produk</label>
        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-indigo-500 transition-colors">
            <div class="space-y-1 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <div class="flex text-sm text-gray-600">
                    <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                        <span>Upload file</span>
                        <input id="image" name="image" type="file" class="sr-only">
                    </label>
                    <p class="pl-1">atau drag and drop</p>
                </div>
                <p class="text-xs text-gray-500">PNG, JPG, GIF hingga 2MB</p>
            </div>
        </div>
        @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
</div>

<div class="mt-8 flex justify-end gap-3 pt-6 border-t border-gray-100">
    <a href="{{ route('products.index') }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
        Batal
    </a>
    <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-sm transition-colors">
        Simpan Produk
    </button>
</div>
