<x-admin-layout title="Catat Stok" subtitle="Tambah stok masuk atau catat stok keluar">
    <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('stocks.store') }}" method="POST">
            @csrf
            
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">Produk</label>
                <select name="product_id" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                    <option value="">-- Pilih Produk --</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }} (Stok: {{ $product->stock }})</option>
                    @endforeach
                </select>
                @error('product_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-5 mb-5">
                <div>
                     <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Pergerakan</label>
                     <select name="type" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                        <option value="in">Stok Masuk (+)</option>
                        <option value="out">Stok Keluar (-)</option>
                        <option value="adjustment">Penyesuaian (Opname)</option>
                    </select>
                </div>
                <div>
                     <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                     <input type="number" name="quantity" min="1" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" placeholder="0" required>
                     @error('quantity') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mb-5">
                 <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Optional)</label>
                 <textarea name="note" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" rows="3" placeholder="Contoh: Barang datang dari supplier, atau barang rusak."></textarea>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('stocks.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">Batal</a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Simpan</button>
            </div>
        </form>
    </div>
</x-admin-layout>
