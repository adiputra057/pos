<x-admin-layout>
    <div x-data="{ 
        showModal: false, 
        modalTitle: 'Catat Pengeluaran Baru',
        isEdit: false,
        expenseId: null,
        formData: {
            title: '',
            amount: '',
            category: 'Umum',
            date: '{{ date('Y-m-d') }}',
            description: ''
        },
        openCreateModal() {
            this.modalTitle = 'Catat Pengeluaran Baru';
            this.isEdit = false;
            this.expenseId = null;
            this.resetForm();
            this.showModal = true;
        },
        resetForm() {
            this.formData = {
                title: '',
                amount: '',
                category: 'Umum',
                date: '{{ date('Y-m-d') }}',
                description: ''
            };
        },
        async submitForm() {
            const url = this.isEdit ? `/expenses/${this.expenseId}` : '/expenses';
            const method = this.isEdit ? 'PUT' : 'POST';
            
            try {
                const data = { ...this.formData, _token: '{{ csrf_token() }}' };
                if (this.isEdit) data._method = 'PUT';
                
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });
                
                if (response.ok) {
                    window.location.reload();
                } else {
                    const result = await response.json();
                    alert(result.message || 'Gagal menyimpan data');
                }
            } catch (error) {
                alert('Error: ' + error.message);
            }
        }
    }" class="space-y-6">
        <div class="mb-8">
            <h1 class="text-3xl font-black text-gray-900 tracking-tight mb-1.5">Pengeluaran Operasional</h1>
            <p class="text-sm text-gray-500 font-medium">Catat dan pantau pengeluaran toko Anda secara berkala.</p>
        </div>
        <div class="flex justify-start mb-6">
            
            <button @click="openCreateModal()" class="w-full md:w-auto px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl shadow-md font-bold transition-all hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Catat Pengeluaran
            </button>
        </div>

        <!-- Filters Section -->
        <div class="mb-6 bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
            <form method="GET" action="{{ route('expenses.index') }}" class="flex flex-col lg:flex-row items-center gap-4">
                <!-- Search -->
                <div class="relative flex-grow w-full lg:max-w-md">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           oninput="clearTimeout(this.delay); this.delay = setTimeout(() => this.form.submit(), 500)"
                           placeholder="Cari judul, kategori, atau deskripsi..." class="w-full pl-10 pr-4 py-2 text-sm rounded-xl border-gray-200 focus:border-red-500 focus:ring-red-500 transition-all bg-gray-50/30">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>

                <div class="flex flex-wrap lg:flex-nowrap items-center gap-3 w-full lg:w-auto">
                    <div class="flex items-center gap-2 w-full lg:w-auto">
                        <input type="date" name="date_from" value="{{ $dateFrom }}" onchange="this.form.submit()" class="w-full py-2 px-3 text-sm rounded-xl border-gray-200 focus:border-red-500 focus:ring-red-500 transition-all shadow-sm">
                        <span class="text-gray-400">-</span>
                        <input type="date" name="date_to" value="{{ $dateTo }}" onchange="this.form.submit()" class="w-full py-2 px-3 text-sm rounded-xl border-gray-200 focus:border-red-500 focus:ring-red-500 transition-all shadow-sm">
                    </div>

                    @if(request()->hasAny(['search', 'date_from', 'date_to']))
                        <a href="{{ route('expenses.index') }}" class="p-2 bg-gray-100 text-gray-500 rounded-xl hover:bg-gray-200 transition-colors flex items-center justify-center" title="Reset Filter">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase text-gray-500 font-semibold tracking-wider">
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4">Judul</th>
                            <th class="px-6 py-4">Kategori</th>
                            <th class="px-6 py-4 text-right">Jumlah</th>
                            <th class="px-6 py-4">Keterangan</th>
                            <th class="px-6 py-4">Oleh</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($expenses as $expense)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $expense->date->format('d M Y') }}</td>
                            <td class="px-6 py-4 font-bold text-gray-900">{{ $expense->title }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 bg-gray-100 text-gray-600 rounded-lg text-[10px] font-black uppercase">{{ $expense->category ?? 'Umum' }}</span>
                            </td>
                            <td class="px-6 py-4 text-right font-black text-red-600">Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-gray-500 max-w-xs truncate" title="{{ $expense->description }}">{{ $expense->description ?? '-' }}</td>
                            <td class="px-6 py-4 text-gray-500 flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-[10px] font-bold">
                                    {{ strtoupper(substr($expense->user->name ?? 'S', 0, 1)) }}
                                </div>
                                {{ $expense->user->name ?? 'System' }}
                            </td>
                             <td class="px-6 py-4 text-center">
                                <div class="flex justify-center items-center gap-2">
                                    <form action="{{ route('expenses.destroy', $expense) }}" method="POST" onsubmit="return confirm('Hapus data pengeluaran ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-gray-400 hover:text-red-600 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.407 2.5 1M12 17c-1.11 0-2.08-.407-2.5-1m-4-4h14"></path></svg>
                                    </div>
                                    <p class="text-gray-400 font-medium">Belum ada data pengeluaran.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($expenses->hasPages())
                <div class="p-4 border-t border-gray-100 bg-gray-50">
                    {{ $expenses->links() }}
                </div>
            @endif
        </div>

        <!-- Create Modal -->
        <div x-show="showModal" 
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto" 
             aria-labelledby="modal-title" 
             role="dialog" 
             aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                     @click="showModal = false"
                     aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    
                    <div class="bg-white px-8 py-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                        <h2 class="text-xl font-black text-gray-800 tracking-tight" x-text="modalTitle"></h2>
                        <button @click="showModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="p-8">
                        <form @submit.prevent="submitForm" hx-boost="false">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="col-span-2">
                                    <label class="block text-xs font-black uppercase tracking-wider text-gray-400 mb-2">Judul Pengeluaran</label>
                                    <input type="text" x-model="formData.title" class="w-full h-12 px-4 rounded-xl border-gray-200 focus:border-red-500 focus:ring-red-500 transition-all bg-gray-50/50 font-bold" placeholder="e.g. Bayar Listrik Toko" required>
                                </div>

                                <div>
                                    <label class="block text-xs font-black uppercase tracking-wider text-gray-400 mb-2">Jumlah (Rp)</label>
                                    <input type="number" x-model="formData.amount" class="w-full h-12 px-4 rounded-xl border-gray-200 focus:border-red-500 focus:ring-red-500 transition-all bg-gray-50/50 font-bold text-red-600" placeholder="0" required>
                                </div>

                                <div>
                                    <label class="block text-xs font-black uppercase tracking-wider text-gray-400 mb-2">Kategori</label>
                                    <select x-model="formData.category" class="w-full h-12 px-4 rounded-xl border-gray-200 focus:border-red-500 focus:ring-red-500 transition-all bg-gray-50/50 font-bold">
                                        <option value="Umum">Umum</option>
                                        <option value="Operasional">Operasional</option>
                                        <option value="Gaji Karyawan">Gaji Karyawan</option>
                                        <option value="Sewa Tempat">Sewa Tempat</option>
                                        <option value="Utilitas">Utilitas (Listrik/Air/Wifi)</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-black uppercase tracking-wider text-gray-400 mb-2">Tanggal</label>
                                    <input type="date" x-model="formData.date" class="w-full h-12 px-4 rounded-xl border-gray-200 focus:border-red-500 focus:ring-red-500 transition-all bg-gray-50/50 font-bold" required>
                                </div>

                                <div class="col-span-2">
                                    <label class="block text-xs font-black uppercase tracking-wider text-gray-400 mb-2">Keterangan (Opsional)</label>
                                    <textarea x-model="formData.description" rows="3" class="w-full p-4 rounded-xl border-gray-200 focus:border-red-500 focus:ring-red-500 transition-all bg-gray-50/50 font-medium" placeholder="Detail pengeluaran..."></textarea>
                                </div>
                            </div>

                            <div class="mt-10 flex justify-end gap-3 pt-6 border-t border-gray-100">
                                <button type="button" @click="showModal = false" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl transition-all active:scale-95">
                                    Batal
                                </button>
                                <button type="submit" class="px-8 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl shadow-lg shadow-red-100 transition-all hover:scale-[1.02] active:scale-[0.98]">
                                    Simpan Pengeluaran
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-admin-layout>
