<x-admin-layout>
    <div x-data="categoryManagement" class="space-y-6">
        
        <!-- Header Section - Sejajar -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight mb-1.5">Manajemen Kategori</h1>
                <p class="text-sm text-gray-500 font-medium">Kelola kategori produk toko Anda.</p>
            </div>
            <button @click="openCreateModal()" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-md font-bold transition-all hover:scale-[1.02] active:scale-[0.98] flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Kategori
            </button>
        </div>

        <!-- Filters Section -->
        <div class="mb-6 bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
            <form method="GET" action="{{ route('categories.index') }}" class="flex flex-col md:flex-row items-center gap-4">
                <!-- Search -->
                <div class="relative flex-grow w-full">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           oninput="clearTimeout(this.delay); this.delay = setTimeout(() => this.form.submit(), 500)"
                           placeholder="Cari nama kategori atau deskripsi..." class="w-full pl-10 pr-4 py-2 text-sm rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 transition-all bg-gray-50/30">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>

                <!-- Reset Button -->
                @if(request()->has('search'))
                    <a href="{{ route('categories.index') }}" class="p-2 bg-gray-100 text-gray-500 rounded-xl hover:bg-gray-200 transition-colors flex items-center justify-center" title="Reset Filter">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </a>
                @endif
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            @if($categories->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase text-gray-500 font-semibold">
                            <th class="px-6 py-4">Nama Kategori</th>
                            <th class="px-6 py-4">Deskripsi</th>
                            <th class="px-6 py-4">Produk</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($categories as $category)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-900">{{ $category->name }}</h4>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-600">{{ $category->description ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-indigo-50 text-indigo-600 rounded-full text-xs font-semibold">
                                    {{ $category->products_count }} items
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center items-center gap-2">
                                    <button @click='openEditModal(@json($category))' class="p-2 text-gray-400 hover:text-indigo-600 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
                                    <form id="delete-category-{{ $category->id }}" action="{{ route('categories.destroy', $category) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" 
                                                class="p-2 text-gray-400 hover:text-red-600 transition-colors" 
                                                @click="$dispatch('confirm', { 
                                                    title: 'Hapus Kategori?', 
                                                    message: 'Kategori \'{{ $category->name }}\' akan dihapus secara permanen. Tindakan ini tidak dapat dibatalkan.', 
                                                    formId: 'delete-category-{{ $category->id }}' 
                                                })">
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
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-1">Belum ada kategori.</h3>
                <p class="text-sm text-gray-500 mb-4">Mulai dengan menambahkan kategori pertama Anda.</p>
                <button @click="openCreateModal()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-colors">
                    Tambah Kategori Pertama
                </button>
            </div>
            @endif

            @if($categories->hasPages())
            <div class="px-6 py-8 border-t border-gray-100 bg-white flex flex-col md:flex-row justify-between items-center gap-8">
                @if($categories->total() > 0)
                <p class="text-xs text-gray-400 font-medium italic order-2 md:order-1">
                    Menampilkan <span class="text-gray-700 font-bold">{{ $categories->firstItem() }}</span> sampai <span class="text-gray-700 font-bold">{{ $categories->lastItem() }}</span> dari <span class="text-gray-700 font-bold">{{ $categories->total() }}</span> kategori
                </p>
                @endif
                <div class="order-1 md:order-2">
                    {{ $categories->links() }}
                </div>
            </div>
            @endif
        </div>

        <!-- Modal -->
        <div x-show="showModal" 
             x-cloak
             @click.away="showModal = false"
             class="fixed inset-0 z-50 overflow-y-auto" 
             aria-labelledby="modal-title" 
             role="dialog" 
             aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div x-show="showModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                     aria-hidden="true"></div>

                <!-- Center modal -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <!-- Modal panel -->
                <div x-show="showModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    
                    <!-- Modal Header -->
                    <div class="bg-white px-8 py-6 border-b border-gray-100 flex justify-between items-center">
                        <h2 class="text-xl font-bold text-gray-800" x-text="modalTitle"></h2>
                        <button @click="showModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-8">
                        <form id="categoryForm" @submit.prevent="submitForm" hx-boost="false">
                            @csrf
                            <input type="hidden" name="_method" :value="isEdit ? 'PUT' : 'POST'">
                            <div class="space-y-6">
                                <!-- Category Name -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori</label>
                                    <input type="text" name="name" x-model="formData.name" class="w-full bg-white rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" placeholder="Contoh: Minuman" required>
                                </div>

                                <!-- Description -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi (Opsional)</label>
                                    <textarea name="description" x-model="formData.description" rows="3" class="w-full bg-white rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" placeholder="Jelaskan kategori ini..."></textarea>
                                </div>
                            </div>

                            <div class="mt-8 flex justify-end gap-3 pt-6 border-t border-gray-100">
                                <button type="button" @click="showModal = false" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                                    Batal
                                </button>
                                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-sm transition-colors">
                                    Simpan Kategori
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