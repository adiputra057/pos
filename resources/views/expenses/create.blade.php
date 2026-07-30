<x-admin-layout title="Catat Pengeluaran" subtitle="Input data pengeluaran operasional baru">
    <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('expenses.store') }}" method="POST">
            @csrf
            
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">Judul Pengeluaran</label>
                <input type="text" name="title" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Contoh: Bayar Listrik, Beli Plastik" required>
                @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-5 mb-5">
                <div>
                     <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                     <select name="category" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="Operasional">Operasional</option>
                        <option value="Gaji">Gaji Karyawan</option>
                        <option value="Maintenance">Maintenance</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div>
                     <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                     <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>
            </div>

            <div class="mb-5">
                 <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah (Rp)</label>
                 <input type="number" name="amount" min="0" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" placeholder="0" required>
                 @error('amount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-5">
                 <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan (Optional)</label>
                 <textarea name="description" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" rows="3"></textarea>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('expenses.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">Batal</a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Simpan</button>
            </div>
        </form>
    </div>
</x-admin-layout>
