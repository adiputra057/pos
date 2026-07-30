<x-admin-layout title="Laporan Pengeluaran">
    <div x-data="{ showModal: false }" @keydown.escape.window="showModal = false">
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight mb-1.5">Laporan Pengeluaran</h1>
                <p class="text-sm text-gray-500 font-medium">Analisis pengeluaran dan modal usaha periode ini.</p>
            </div>
            <div class="flex items-center gap-3">
                <button @click="showModal = true" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-bold hover:bg-indigo-700 flex items-center gap-2 shadow-sm transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Catat Pengeluaran
                </button>
                <a href="{{ route('expenses.report.export', ['date_from' => $dateFrom, 'date_to' => $dateTo]) }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 flex items-center gap-2 shadow-sm transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Export
                </a>
            </div>
        </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Expense -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-red-50 rounded-xl">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <span class="bg-red-50 text-red-700 px-2 py-1 rounded-lg text-xs font-semibold">{{ $expenses->count() }} Transaksi</span>
            </div>
            <p class="text-sm font-medium text-gray-500">Total Pengeluaran</p>
            <h3 class="text-2xl font-bold text-gray-900 mt-1">Rp {{ number_format($totalExpense, 0, ',', '.') }}</h3>
        </div>

        <!-- Average per Transaction -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-orange-50 rounded-xl">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                </div>
            </div>
            <p class="text-sm font-medium text-gray-500">Rata-rata per Transaksi</p>
            <h3 class="text-2xl font-bold text-gray-900 mt-1">Rp {{ $expenses->count() > 0 ? number_format($totalExpense / $expenses->count(), 0, ',', '.') : '0' }}</h3>
        </div>

        <!-- Categories Count -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-purple-50 rounded-xl">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                </div>
                <span class="bg-purple-50 text-purple-700 px-2 py-1 rounded-lg text-xs font-semibold">{{ $byCategory->count() }} Kategori</span>
            </div>
            <p class="text-sm font-medium text-gray-500">Kategori Pengeluaran</p>
            <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $byCategory->count() }}</h3>
        </div>
    </div>


    <!-- Expense Details Table -->
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <h3 class="font-bold text-gray-900 text-lg">Detail Pengeluaran</h3>
            <form method="GET" action="{{ route('expenses.report') }}" class="flex items-center gap-2 bg-white p-2 rounded-xl border border-gray-100 shadow-sm">
                <div class="flex items-center gap-2 px-3">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Periode</span>
                    <input type="date" name="date_from" value="{{ $dateFrom }}" onchange="this.form.submit()" class="bg-transparent border-none p-0 text-sm font-bold text-gray-700 focus:ring-0 cursor-pointer">
                    <span class="text-gray-300">-</span>
                    <input type="date" name="date_to" value="{{ $dateTo }}" onchange="this.form.submit()" class="bg-transparent border-none p-0 text-sm font-bold text-gray-700 focus:ring-0 cursor-pointer">
                </div>
                @if(request('date_from') || request('date_to'))
                    <a href="{{ route('expenses.report') }}" class="p-1 px-2 text-[10px] font-bold text-red-500 hover:text-red-700 bg-red-50 rounded-lg transition-colors" title="Reset Filter">
                        RESET
                    </a>
                @endif
            </form>
        </div>
        @if($expenses->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-xs text-gray-500 uppercase font-semibold">
                        <tr>
                            <th class="px-4 py-3 rounded-l-lg">Tanggal</th>
                            <th class="px-4 py-3">Judul</th>
                            <th class="px-4 py-3">Kategori</th>
                            <th class="px-4 py-3 text-right">Jumlah</th>
                            <th class="px-4 py-3 rounded-r-lg">Dibuat Oleh</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($expenses as $expense)
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-500 font-medium">{{ $expense->date->format('d M Y') }}</td>
                            <td class="px-4 py-3">
                                <p class="text-sm font-bold text-gray-900">{{ $expense->title }}</p>
                                @if($expense->description)
                                    <p class="text-xs text-gray-500 mt-0.5 line-clamp-1">{{ Str::limit($expense->description, 60) }}</p>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex px-2 py-1 text-[10px] font-bold uppercase tracking-wider rounded-md bg-indigo-50 text-indigo-700">
                                    {{ $expense->category ?: 'Lainnya' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right font-bold text-red-600">
                                Rp {{ number_format($expense->amount, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-indigo-100 flex items-center justify-center text-[10px] font-bold text-indigo-700">
                                        {{ substr($expense->user->name ?? 'A', 0, 1) }}
                                    </div>
                                    <span class="text-sm text-gray-600 font-medium">{{ $expense->user->name ?? '-' }}</span>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Table Footer: Pagination & Entry Info -->
            <div class="mt-6 pt-6 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center gap-6">
                @if($expenses->total() > 0)
                <p class="text-xs text-gray-400 font-medium italic order-2 md:order-1">
                    Menampilkan <span class="text-gray-700 font-bold">{{ $expenses->firstItem() }}</span> sampai <span class="text-gray-700 font-bold">{{ $expenses->lastItem() }}</span> dari <span class="text-gray-700 font-bold">{{ $expenses->total() }}</span> transaksi
                </p>
                @endif
                <div class="order-1 md:order-2">
                    {{ $expenses->links() }}
                </div>
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <p class="text-gray-500 font-medium">Tidak ada data untuk periode ini</p>
            </div>
        @endif
    </div>

    <!-- Modal Record Expense -->
    <template x-teleport="body">
        <div x-show="showModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[999] overflow-y-auto" style="display: none;">
            
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black/40 backdrop-blur-sm transition-opacity" @click="showModal = false"></div>

            <!-- Modal Dialog -->
            <div class="flex min-h-full items-center justify-center p-4">
                <div x-show="showModal"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="relative w-full max-w-lg transform overflow-hidden rounded-2xl bg-white shadow-2xl transition-all border border-gray-100"
                     @click.away="showModal = false">
                    
                    <!-- Header -->
                    <div class="px-6 py-4 flex justify-between items-center bg-gray-50 border-b border-gray-100">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Catat Pengeluaran Baru</h3>
                            <p class="text-xs text-gray-500">Input data pengeluaran operasional Anda.</p>
                        </div>
                        <button @click="showModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="px-6 py-6">
                        <form action="{{ route('expenses.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="redirect_to" value="report">
                            
                            <div class="mb-5">
                                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Judul Pengeluaran</label>
                                <input type="text" name="title" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:border-indigo-500 focus:ring-indigo-500 py-3 text-sm transition-all" placeholder="Contoh: Bayar Listrik, Beli Plastik" required>
                            </div>

                            <div class="grid grid-cols-2 gap-5 mb-5">
                                <div>
                                     <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Kategori</label>
                                     <select name="category" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:border-indigo-500 focus:ring-indigo-500 py-3 text-sm transition-all">
                                        <option value="Operasional">Operasional</option>
                                        <option value="Gaji">Gaji Karyawan</option>
                                        <option value="Maintenance">Maintenance</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                </div>
                                <div>
                                     <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Tanggal</label>
                                     <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:border-indigo-500 focus:ring-indigo-500 py-3 text-sm transition-all" required>
                                </div>
                            </div>

                            <div class="mb-5">
                                 <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Jumlah (Rp)</label>
                                 <div class="relative">
                                     <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">Rp</span>
                                     <input type="number" name="amount" min="0" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:border-indigo-500 focus:ring-indigo-500 py-3 pl-12 text-sm transition-all font-bold" placeholder="0" required>
                                 </div>
                            </div>

                            <div class="mb-6">
                                 <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Keterangan <span class="text-[10px] text-gray-400 font-medium font-normal">(OPTIONAL)</span></label>
                                 <textarea name="description" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:border-indigo-500 focus:ring-indigo-500 py-3 text-sm transition-all" rows="3" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                            </div>

                            <!-- Footer -->
                            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                                <button type="button" @click="showModal = false" class="px-5 py-2.5 text-sm font-bold text-gray-500 hover:text-gray-700 transition-colors uppercase tracking-wider">Batal</button>
                                <button type="submit" class="px-8 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition-all focus:ring-4 focus:ring-indigo-500/20">Simpan Data</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </template>
    </div>
</x-admin-layout>
