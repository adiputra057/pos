<x-admin-layout>
    <div x-data="supplierManagement({ errors: {{ json_encode($errors->messages() ?? []) }} })" class="space-y-6">
        
        <!-- Header with Title and Action Button -->
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-8">
            <!-- Title Section -->
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight mb-1.5">Manajemen Supplier</h1>
                <p class="text-sm text-gray-500 font-medium">Kelola daftar pemasok dan vendor barang toko Anda.</p>
            </div>
            
            <!-- Action Button - Aligned to Right -->
            <div class="flex-shrink-0 ml-auto">
                <button @click="openCreateModal()" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-md font-bold transition-all hover:scale-[1.02] active:scale-[0.98] flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Supplier Baru
                </button>
            </div>
        </div>
        <!-- Filters Section -->
        <div class="mb-6 bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
            <form method="GET" action="{{ route('suppliers.index') }}" class="flex flex-col md:flex-row items-center gap-4">
                <div class="relative flex-grow w-full">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           oninput="clearTimeout(this.delay); this.delay = setTimeout(() => this.form.submit(), 500)"
                           placeholder="Cari nama supplier, telepon, atau alamat..." class="w-full pl-10 pr-4 py-2 text-sm rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 transition-all bg-gray-50/30">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                @if(request('search'))
                    <a href="{{ route('suppliers.index') }}" class="p-2 bg-gray-100 text-gray-500 rounded-xl hover:bg-gray-200 transition-colors" title="Hapus Filter">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </a>
                @endif
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            @if($suppliers->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase text-gray-900 font-bold">
                            <th class="px-6 py-4">Nama Pemasok</th>
                            <th class="px-6 py-4">Kontak (WA/Telp)</th>
                            <th class="px-6 py-4">Alamat</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($suppliers as $supplier)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-900">{{ $supplier->name }}</h4>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">{{ $supplier->products->count() }} Produk Tersedia</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    {{ $supplier->phone }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs text-gray-500 line-clamp-1 max-w-[200px]">
                                    {{ $supplier->address ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center items-center gap-2">
                                    <button @click='openDetailModal(@json($supplier->load("products")))' class="p-2 text-gray-400 hover:text-emerald-600 transition-all hover:bg-emerald-50 rounded-lg" title="Lihat Detail">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </button>
                                    <button @click='openEditModal(@json($supplier))' class="p-2 text-gray-400 hover:text-indigo-600 transition-all hover:bg-indigo-50 rounded-lg" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
                                    <form id="delete-supplier-{{ $supplier->id }}" action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" 
                                                class="p-2 text-gray-400 hover:text-red-600 transition-all hover:bg-red-50 rounded-lg" 
                                                @click="$dispatch('confirm', { 
                                                    title: 'Hapus Supplier?', 
                                                    message: 'Supplier \'{{ $supplier->name }}\' akan dihapus. Tindakan ini tidak dapat dibatalkan.', 
                                                    formId: 'delete-supplier-{{ $supplier->id }}' 
                                                })"
                                                title="Hapus">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="p-12 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gray-50 mb-4 border border-gray-100">
                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-1">Belum Ada Supplier</h3>
                <p class="text-sm text-gray-500 mb-6">Daftar pemasok Anda akan muncul di sini setelah Anda menambahkannya.</p>
                <button @click="openCreateModal()" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold transition-all shadow-md">
                    Tambah Supplier Pertama
                </button>
            </div>
            @endif

            @if($suppliers->hasPages())
            <div class="px-6 py-8 border-t border-gray-100 bg-white flex flex-col md:flex-row justify-between items-center gap-8">
                @if($suppliers->total() > 0)
                <p class="text-xs text-gray-400 font-medium italic order-2 md:order-1">
                    Menampilkan <span class="text-gray-700 font-bold">{{ $suppliers->firstItem() }}</span> sampai <span class="text-gray-700 font-bold">{{ $suppliers->lastItem() }}</span> dari <span class="text-gray-700 font-bold">{{ $suppliers->total() }}</span> supplier
                </p>
                @endif
                <div class="order-1 md:order-2">
                    {{ $suppliers->links() }}
                </div>
            </div>
            @endif
        </div>

        <!-- Detail Modal -->
        <div x-show="showDetailModal" 
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto" 
             aria-labelledby="modal-title" 
             role="dialog" 
             aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showDetailModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     @click="showDetailModal = false"
                     class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm transition-opacity"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showDetailModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-gray-100">
                    
                    <template x-if="selectedSupplier">
                        <div>
                            <div class="bg-indigo-600 px-8 py-10 text-white relative overflow-hidden">
                                <div class="relative z-10 flex justify-between items-start">
                                    <div>
                                        <span class="px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-bold uppercase tracking-wider mb-2 inline-block">Supplier Detail</span>
                                        <h2 class="text-3xl font-bold tracking-tight" x-text="selectedSupplier.name"></h2>
                                        <p class="text-indigo-100 mt-1 flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                            <span x-text="selectedSupplier.phone"></span>
                                        </p>
                                    </div>
                                    <button @click="showDetailModal = false" class="bg-white/10 hover:bg-white/20 p-2 rounded-xl transition-colors">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                                <div class="absolute -right-10 -bottom-10 opacity-10">
                                    <svg class="w-48 h-48" fill="currentColor" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                </div>
                            </div>

                            <div class="px-8 py-8 space-y-6">
                                <div>
                                    <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-3">Statistik Kerjasama</h4>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100">
                                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Produk</p>
                                            <p class="text-2xl font-black text-indigo-600" x-text="selectedSupplier.products?.length || 0"></p>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Alamat Kantor</h4>
                                    <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100 flex gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-white shadow-sm border border-gray-100 flex items-center justify-center text-gray-400 shrink-0">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        </div>
                                        <p class="text-sm text-gray-600 leading-relaxed" x-text="selectedSupplier.address || 'Alamat belum dilengkapi.'"></p>
                                    </div>
                                </div>
                            </div>

                            <div class="px-8 py-6 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                                <button @click="showDetailModal = false; openEditModal(selectedSupplier)" class="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 font-bold rounded-xl hover:bg-gray-100 transition-colors shadow-sm">
                                    Edit Supplier
                                </button>
                                <button @click="showDetailModal = false" class="px-6 py-2.5 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-md">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Create/Edit Modal -->
        <div x-show="showModal" 
             x-cloak
             @click.away="showModal = false"
             class="fixed inset-0 z-50 overflow-y-auto" 
             aria-labelledby="modal-title" 
             role="dialog" 
             aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full">
                    
                    <div class="bg-white px-8 py-6 border-b border-gray-100 flex justify-between items-center">
                        <h2 class="text-xl font-bold text-gray-800" x-text="modalTitle"></h2>
                        <button @click="showModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div x-show="Object.keys(errors).length > 0" class="mx-8 mt-6 p-4 bg-red-50 border border-red-100 rounded-xl">
                        <div class="flex gap-3">
                            <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <div>
                                <p class="text-sm font-bold text-red-800 mb-1">Terjadi Kesalahan:</p>
                                <ul class="list-disc list-inside text-xs text-red-700 space-y-0.5">
                                    <template x-for="(messages, field) in errors" :key="field">
                                        <template x-for="message in messages" :key="message">
                                            <li x-text="message"></li>
                                        </template>
                                    </template>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="p-8">
                        <form id="supplierForm" @submit.prevent="submitForm" hx-boost="false">
                            @csrf
                            <input type="hidden" name="_method" :value="isEdit ? 'PUT' : 'POST'">
                            <div class="space-y-5">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Perusahaan / Vendor</label>
                                    <input type="text" name="name" x-model="formData.name" :class="errors.name ? 'border-red-300 ring-red-100' : 'border-gray-300'" class="w-full bg-white rounded-xl focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-all" placeholder="Contoh: PT. Sumber Makmur" required>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon / WhatsApp</label>
                                    <input type="text" name="phone" x-model="formData.phone" :class="errors.phone ? 'border-red-300 ring-red-100' : 'border-gray-300'" class="w-full bg-white rounded-xl focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-all" placeholder="Contoh: 08123456789" required>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                                    <textarea name="address" x-model="formData.address" :class="errors.address ? 'border-red-300 ring-red-100' : 'border-gray-300'" rows="3" class="w-full bg-white rounded-xl focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-all" placeholder="Masukkan alamat lengkap supplier..."></textarea>
                                </div>
                            </div>

                            <div class="mt-8 flex justify-end gap-3 pt-6 border-t border-gray-100">
                                <button type="button" @click="showModal = false" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl transition-colors">
                                    Batal
                                </button>
                                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-md transition-all">
                                    Simpan Supplier
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
       
