<x-admin-layout>
@php
    $trackStock = \App\Models\Setting::get('track_stock', '1') === '1';
@endphp
    <div x-data="productManagement" class="space-y-6">
        
        <!-- Header with Title and Action Buttons -->
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-8">
            <!-- Title Section -->
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight mb-1.5">Manajemen Inventaris</h1>
                <p class="text-sm text-gray-500 font-medium">Tambah, perbarui, dan kelola produk toko Anda.</p>
            </div>

            <!-- Action Buttons - Aligned to Right -->
            <div class="flex items-center gap-3 flex-shrink-0 ml-auto">
                <button @click="openCreateModal()" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 font-medium flex items-center gap-2 shadow-lg shadow-indigo-200 transition-all transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Produk
                </button>

                <!-- Export Dropdown -->
                <div class="relative">
                    <button @click="openExport = !openExport" class="px-5 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 flex items-center gap-2 text-sm font-bold transition-all shadow-sm">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Ekspor
                        <svg class="w-4 h-4 transition-transform" :class="openExport ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    
                    <div x-show="openExport" 
                         @click.away="openExport = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden"
                         x-cloak>
                        <a href="{{ route('products.export-excel', request()->all()) }}" hx-boost="false" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-600 hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                            <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-600 font-bold text-xs">XLS</div>
                            Excel (.csv)
                        </a>
                        <a href="{{ route('products.print', request()->all()) }}" hx-boost="false" target="_blank" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-600 hover:bg-red-50 hover:text-red-700 transition-colors">
                            <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center text-red-600 font-bold text-xs">PDF</div>
                            Cetak PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="mb-6 bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
            <form method="GET" action="{{ route('products.index') }}" class="flex flex-col lg:flex-row items-center gap-4">
                <!-- Search -->
                <div class="relative flex-grow w-full lg:max-w-md">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           oninput="clearTimeout(this.delay); this.delay = setTimeout(() => this.form.submit(), 500)"
                           placeholder="Cari nama produk atau SKU..." class="w-full pl-10 pr-4 py-2 text-sm rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 transition-all bg-gray-50/30">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>

                <div class="flex flex-wrap lg:flex-nowrap items-center gap-3 w-full lg:w-auto">
                    <!-- Category Filter -->
                    <div class="w-full sm:w-44">
                        <select name="category_id" onchange="this.form.submit()" class="w-full py-2 pl-3 pr-10 text-sm rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 transition-all bg-white shadow-sm cursor-pointer">
                            <option value="">Semua Kategori</option>
                            @foreach(\App\Models\Category::all() as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Supplier Filter -->
                    <div class="w-full sm:w-44">
                        <select name="supplier_id" onchange="this.form.submit()" class="w-full py-2 pl-3 pr-10 text-sm rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 transition-all bg-white shadow-sm cursor-pointer">
                            <option value="">Semua Supplier</option>
                            @foreach(\App\Models\Supplier::all() as $supplier)
                                <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    @if($trackStock)
                    <!-- Stock Status Filter -->
                    <div class="w-full sm:w-44">
                        <select name="stock_status" onchange="this.form.submit()" class="w-full py-2 pl-3 pr-10 text-sm rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 transition-all bg-white shadow-sm cursor-pointer">
                            <option value="">Status Stok</option>
                            <option value="safe" {{ request('stock_status') == 'safe' ? 'selected' : '' }}>Stok Aman</option>
                            <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Stok Menipis</option>
                            <option value="out_of_stock" {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>Stok Habis</option>
                        </select>
                    </div>
                    @endif

                    <!-- Reset Button -->
                    @if(request()->hasAny(['search', 'category_id', 'supplier_id', 'stock_status']))
                        <a href="{{ route('products.index') }}" class="p-2 bg-gray-100 text-gray-500 rounded-xl hover:bg-gray-200 transition-colors flex items-center justify-center" title="Reset Filter">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase text-gray-900 font-bold">
                            <th class="px-6 py-4 text-left">Produk</th>
                            <th class="px-6 py-4 text-center">Harga Beli</th>
                            <th class="px-6 py-4 text-center">Harga Jual</th>
                            @if($trackStock)
                            <th class="px-6 py-4 text-center">Stok Etalase</th>
                            <th class="px-6 py-4 text-center">Stok Gudang</th>
                            @endif
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($products as $product)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" class="w-12 h-12 rounded-lg object-cover bg-gray-100">
                                    @else
                                        <div class="w-12 h-12 rounded-lg bg-gray-200 flex items-center justify-center text-gray-400">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                    @endif
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-900 text-left">{{ $product->name }}</h4>
                                        <div class="flex items-center gap-2 mt-0.5">
                                            <span class="px-2 py-0.5 bg-gray-100 text-gray-600 rounded text-[10px] font-mono font-bold" title="SKU">{{ $product->sku }}</span>
                                            @if($product->barcode)
                                                <span class="px-2 py-0.5 bg-indigo-50 text-indigo-600 rounded text-[10px] font-mono font-bold" title="Barcode">{{ $product->barcode }}</span>
                                            @endif
                                            <span class="text-xs text-gray-400">&bull;</span>
                                            @php
                                                $catColors = [
                                                    ['bg-blue-100','text-blue-700'],
                                                    ['bg-violet-100','text-violet-700'],
                                                    ['bg-amber-100','text-amber-700'],
                                                    ['bg-rose-100','text-rose-700'],
                                                    ['bg-emerald-100','text-emerald-700'],
                                                    ['bg-orange-100','text-orange-700'],
                                                    ['bg-cyan-100','text-cyan-700'],
                                                    ['bg-pink-100','text-pink-700'],
                                                    ['bg-teal-100','text-teal-700'],
                                                    ['bg-indigo-100','text-indigo-700'],
                                                    ['bg-lime-100','text-lime-700'],
                                                    ['bg-fuchsia-100','text-fuchsia-700'],
                                                ];
                                                $catIdx = ($product->category->id - 1) % count($catColors);
                                                [$catBg, $catText] = $catColors[$catIdx];
                                            @endphp
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $catBg }} {{ $catText }}">{{ $product->category->name }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-600 text-center">
                                Rp {{ number_format($product->buying_price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-sm font-bold text-indigo-600">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</span>
                            </td>
                            @if($trackStock)
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $product->stock > 10 ? 'bg-green-500' : ($product->stock > 0 ? 'bg-yellow-500' : 'bg-red-500') }}"></span>
                                    <span class="text-sm font-bold text-gray-700">{{ $product->stock }}</span>
                                    <span class="text-xs font-bold text-gray-500 uppercase">pcs</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $product->warehouse_stock > 10 ? 'bg-blue-500' : ($product->warehouse_stock > 0 ? 'bg-yellow-500' : 'bg-red-500') }}"></span>
                                    <span class="text-sm font-bold text-gray-700">{{ $product->warehouse_stock }}</span>
                                    <span class="text-xs font-bold text-gray-500 uppercase">pcs</span>
                                </div>
                            </td>
                            @endif
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center items-center gap-1">
                                    @if($trackStock)
                                    <button @click='openTransferModal(@json($product, JSON_HEX_APOS))' class="p-2 text-gray-400 hover:text-green-600 transition-colors" title="Pindah Stok">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                    </button>
                                    @endif
                                    <button @click='openDetailModal(@json($product->load(["category", "supplier"]), JSON_HEX_APOS))' class="p-2 text-gray-400 hover:text-blue-600 transition-colors" title="Lihat Detail">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </button>
                                    <button @click='openEditModal(@json($product, JSON_HEX_APOS))' class="p-2 text-gray-400 hover:text-indigo-600 transition-colors" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
                                    <form id="delete-product-{{ $product->id }}" action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" 
                                                class="p-2 text-gray-400 hover:text-red-600 transition-colors" 
                                                @click="$dispatch('confirm', { 
                                                    title: 'Hapus Produk?', 
                                                    message: 'Produk \'{{ $product->name }}\' akan dihapus secara permanen. Tindakan ini tidak dapat dibatalkan.', 
                                                    formId: 'delete-product-{{ $product->id }}' 
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
            @if($products->hasPages())
            <div class="px-6 py-8 border-t border-gray-100 bg-white flex flex-col md:flex-row justify-between items-center gap-8">
                @if($products->total() > 0)
                <p class="text-xs text-gray-400 font-medium italic order-2 md:order-1">
                    Menampilkan <span class="text-gray-700 font-bold">{{ $products->firstItem() }}</span> sampai <span class="text-gray-700 font-bold">{{ $products->lastItem() }}</span> dari <span class="text-gray-700 font-bold">{{ $products->total() }}</span> produk
                </p>
                @endif
                <div class="order-1 md:order-2">
                    {{ $products->links() }}
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
                     class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-gray-100">
                    
                    <template x-if="selectedProductDetail">
                        <div>
                            <!-- Header -->
                            <div class="bg-indigo-600 px-8 py-10 text-white relative overflow-hidden">
                                    <div class="flex items-center gap-6">
                                        <div class="w-24 h-24 rounded-2xl bg-white/20 backdrop-blur-md border border-white/30 p-1 shrink-0 overflow-hidden shadow-xl">
                                            <template x-if="selectedProductDetail.image">
                                                <img :src="'/storage/' + selectedProductDetail.image" class="w-full h-full object-cover rounded-xl">
                                            </template>
                                            <template x-if="!selectedProductDetail.image">
                                                <div class="w-full h-full flex items-center justify-center text-white/50">
                                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                </div>
                                            </template>
                                        </div>
                                        <div>
                                            <span class="px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-[10px] font-bold uppercase tracking-wider mb-2 inline-block" x-text="selectedProductDetail.category.name"></span>
                                            <h2 class="text-3xl font-bold tracking-tight" x-text="selectedProductDetail.name"></h2>
                                            <p class="text-indigo-50 mt-1 flex flex-wrap items-center gap-x-4 gap-y-1 text-xs">
                                                <span class="flex items-center gap-1.5">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h10c.466 0 .894.195 1.199.507A1.731 1.731 0 0118.5 4.75v14.5c0 .341-.1.666-.279.94a1.736 1.736 0 01-1.221.75c-.322.04-.654.01-.968-.09L12 18.5l-4.032 2.35c-.314.1-.646.13-.968.09a1.736 1.736 0 01-1.221-.75 1.731 1.731 0 01-.279-.94V4.75c0-.466.185-.892.484-1.201.314-.32.748-.521 1.217-.549H7z"></path></svg>
                                                    <span x-text="'SKU: ' + selectedProductDetail.sku"></span>
                                                </span>
                                                <template x-if="selectedProductDetail.barcode">
                                                    <span class="flex items-center gap-1.5">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                                        <span x-text="'Barcode: ' + selectedProductDetail.barcode"></span>
                                                    </span>
                                                </template>
                                            </p>
                                        </div>
                                    </div>
                                    <button @click="showDetailModal = false" class="absolute top-6 right-6 z-10 bg-white/10 hover:bg-white/20 p-2 rounded-xl transition-colors text-white">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                <!-- Background Decoration -->
                                <div class="absolute -right-10 -bottom-10 opacity-10">
                                    <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24"><path d="M20 7h-4V4c0-1.1-.9-2-2-2h-4c-1.1 0-2 .9-2 2v3H4c-1.1 0-2 .9-2 2v11c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V9c0-1.1-.9-2-2-2zM10 4h4v3h-4V4zm10 16H4V9h16v11z"></path></svg>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="px-8 py-8">
                                <div class="{{ $trackStock ? 'grid grid-cols-2' : 'grid grid-cols-1' }} gap-8">
                                    @if($trackStock)
                                    <!-- Stock Info -->
                                    <div class="space-y-6">
                                        <div>
                                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Informasi Stok</h4>
                                            <div class="grid grid-cols-2 gap-4">
                                                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-1">Stok Etalase</p>
                                                    <div class="flex items-end gap-2">
                                                        <span class="text-3xl font-black text-gray-900" x-text="selectedProductDetail.stock"></span>
                                                        <span class="text-[10px] font-bold uppercase mb-1" :class="selectedProductDetail.stock > 10 ? 'text-green-600' : (selectedProductDetail.stock > 0 ? 'text-yellow-600' : 'text-red-600')">Pcs</span>
                                                    </div>
                                                </div>
                                                <div class="bg-blue-50/50 rounded-xl p-4 border border-blue-100/50">
                                                    <p class="text-[10px] font-black text-blue-400 uppercase tracking-wider mb-1">Stok Gudang</p>
                                                    <div class="flex flex-col">
                                                        <div class="flex items-end gap-2">
                                                            <span class="text-3xl font-black text-blue-900" x-text="selectedProductDetail.warehouse_stock"></span>
                                                            <span class="text-[10px] font-bold uppercase mb-1 text-blue-500">Pcs</span>
                                                        </div>
                                                        <template x-for="unit in (selectedProductDetail.units || []).filter(u => !u.is_base && u.conversion_factor > 1)">
                                                             <p class="text-[10px] font-bold text-blue-400 mt-1" x-text="'(Setara ' + (selectedProductDetail.warehouse_stock / unit.conversion_factor).toFixed(2).replace('.', ',') + ' ' + unit.unit_name + ')'"></p>
                                                        </template>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Supplier Info -->
                                        <div>
                                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Informasi Supplier</h4>
                                            <div class="flex items-center gap-4 bg-gray-50 rounded-2xl p-4 border border-gray-100">
                                                <div class="w-10 h-10 bg-white rounded-xl shadow-sm border border-gray-100 flex items-center justify-center text-indigo-600">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-bold text-gray-900" x-text="selectedProductDetail.supplier ? selectedProductDetail.supplier.name : 'Tanpa Supplier'"></p>
                                                    <p class="text-xs text-gray-500" x-text="selectedProductDetail.supplier ? selectedProductDetail.supplier.contact : '-'"></p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                            <p class="text-xs text-gray-500 mb-1">Peringatan Minimal (Etalase)</p>
                                            <p class="font-bold text-gray-900" x-text="selectedProductDetail.min_stock + ' Units'"></p>
                                        </div>
                                    </div>
                                    @endif

                                    <!-- Price Info -->
                                    <div class="space-y-6">
                                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Informasi Harga</h4>
                                        <div class="grid grid-cols-1 gap-4">
                                            <div>
                                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-1">Harga Satuan</p>
                                                <p class="text-xl font-bold text-indigo-600 tracking-tight" x-text="formatCurrency(selectedProductDetail.selling_price)"></p>
                                            </div>
                                            <template x-if="selectedProductDetail.box_price">
                                                <div class="p-3 bg-indigo-50/50 rounded-xl border border-indigo-100">
                                                    <p class="text-[10px] font-black text-indigo-400 uppercase tracking-wider mb-1">Harga Grosir (Dus)</p>
                                                    <div class="flex justify-between items-end">
                                                        <p class="text-lg font-bold text-indigo-700 tracking-tight" x-text="formatCurrency(selectedProductDetail.box_price)"></p>
                                                        <p class="text-xs font-bold text-indigo-500" x-text="'Isi: ' + selectedProductDetail.box_quantity"></p>
                                                    </div>
                                                </div>
                                            </template>
                                            <template x-if="selectedProductDetail.wholesale_price">
                                                <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-100">
                                                    <p class="text-[10px] font-black text-emerald-500 uppercase tracking-wider mb-1">Harga Grosir (Pcs)</p>
                                                    <div class="flex justify-between items-end">
                                                        <p class="text-lg font-bold text-emerald-700 tracking-tight" x-text="formatCurrency(selectedProductDetail.wholesale_price)"></p>
                                                        <p class="text-xs font-bold text-emerald-600" x-text="'Min. Qty: ' + selectedProductDetail.min_wholesale_qty + ' pcs'"></p>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                        <div class="pt-4 border-t border-gray-100">
                                            <p class="text-xs text-gray-500 mb-1">Harga Beli (Modal)</p>
                                            <p class="text-lg font-bold text-gray-700 tracking-tight" x-text="formatCurrency(selectedProductDetail.buying_price)"></p>
                                        </div>
                                        <div class="bg-blue-50 rounded-lg px-3 py-2 border border-blue-100 flex justify-between items-center">
                                            <span class="text-[10px] font-bold text-blue-700 uppercase">Profit Satuan</span>
                                            <span class="text-sm font-black text-blue-800" x-text="formatCurrency(selectedProductDetail.selling_price - selectedProductDetail.buying_price)"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div class="px-8 py-6 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                                <button @click="showDetailModal = false; openEditModal(selectedProductDetail)" class="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 font-bold rounded-xl hover:bg-gray-100 transition-colors shadow-sm">
                                    Edit Produk
                                </button>
                                <button @click="showDetailModal = false" class="px-6 py-2.5 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-md shadow-indigo-100">
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
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                    
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
                        <form id="productForm" @submit.prevent="submitForm" enctype="multipart/form-data" hx-boost="false">
                            @csrf
                            <input type="hidden" name="_method" :value="isEdit ? 'PUT' : 'POST'">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="col-span-1 md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Produk</label>
                                    <div class="flex items-center gap-6 p-4 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200 hover:border-indigo-400 transition-colors group cursor-pointer relative">
                                        <input type="file" name="image" @change="handleImageChange" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept="image/*">
                                        <div class="w-24 h-24 rounded-xl bg-white shadow-sm border border-gray-100 overflow-hidden flex items-center justify-center shrink-0">
                                            <template x-if="imagePreview">
                                                <img :src="imagePreview" class="w-full h-full object-cover">
                                            </template>
                                            <template x-if="!imagePreview">
                                                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </template>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-700 mb-1 group-hover:text-indigo-600 transition-colors">Pilih Foto Produk</p>
                                            <p class="text-xs text-gray-500">Format: JPG, PNG, atau GIF (Maks. 2MB)</p>
                                            <template x-if="imageFile">
                                                <p class="text-[10px] font-bold text-indigo-600 mt-2 bg-indigo-50 px-2 py-0.5 rounded inline-block" x-text="'File terpilih: ' + imageFile.name"></p>
                                            </template>
                                        </div>
                                    </div>
                                    <template x-if="errors.image">
                                        <p class="text-xs text-red-600 mt-1 font-medium" x-text="errors.image[0]"></p>
                                    </template>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk</label>
                                    <input type="text" name="name" x-model="formData.name" :class="errors.name ? 'border-red-300 ring-red-100' : 'border-gray-300'" class="w-full bg-white rounded-lg focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-all" placeholder="Contoh: Mouse Wireless" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">SKU (Kode Internal)</label>
                                    <input type="text" name="sku" x-model="formData.sku" class="w-full bg-gray-50 dark:bg-gray-800 border-gray-300 rounded-lg shadow-sm transition-all cursor-not-allowed" placeholder="Contoh: IND-GOR" readonly required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Barcode (Scan dengan Alat Scanner)</label>
                                    <input type="text" name="barcode" x-model="formData.barcode" :class="errors.barcode ? 'border-red-300 ring-red-100' : 'border-gray-300'" class="w-full bg-white rounded-lg focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-all" placeholder="Scan barcode produk di sini...">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                                    <select name="category_id" x-model="formData.category_id" :class="errors.category_id ? 'border-red-300 ring-red-100' : 'border-gray-300'" class="w-full bg-white rounded-lg focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-all" required>
                                        <option value="">Pilih Kategori</option>
                                        @foreach(\App\Models\Category::all() as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                                    <select name="supplier_id" x-model="formData.supplier_id" :class="errors.supplier_id ? 'border-red-300 ring-red-100' : 'border-gray-300'" class="w-full bg-white rounded-lg focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-all">
                                        <option value="">Pilih Supplier</option>
                                        @foreach(\App\Models\Supplier::all() as $supplier)
                                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Beli (Rp)</label>
                                    <input type="text" name="buying_price" x-model="formData.buying_price" @input="formData.buying_price = formatInput($event.target.value)" :class="errors.buying_price ? 'border-red-300 ring-red-100' : 'border-gray-300'" class="w-full bg-white rounded-lg focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-all font-bold" placeholder="0" required>
                                </div>
                                <div class="col-span-1 md:col-span-2 p-5 bg-indigo-50/30 rounded-2xl border border-indigo-100/50">
                                    <div class="flex items-center justify-between mb-4">
                                        <p class="text-xs font-bold text-indigo-700 uppercase tracking-wider flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                            Manajemen Satuan & Harga Jual
                                        </p>
                                        <button type="button" @click="addUnit()" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold transition-colors flex items-center gap-1 shadow-sm">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                                            Tambah Satuan
                                        </button>
                                    </div>
                                    
                                    <div class="space-y-3">
                                        <template x-for="(unit, index) in formData.units" :key="index">
                                            <div class="grid grid-cols-12 gap-3 items-center bg-white p-3 rounded-xl border border-gray-100 shadow-sm relative">
                                                
                                                <!-- Unit Name -->
                                                <div class="col-span-4 md:col-span-3">
                                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Nama Satuan</label>
                                                    <input type="text" :name="'units['+index+'][unit_name]'" x-model="unit.unit_name" class="w-full bg-white rounded-lg border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-xs font-bold" :placeholder="unit.is_base ? 'Satuan dasar (bebas)' : 'Misal: renteng, dus, bal'">
                                                </div>

                                                <!-- Conversion Factor -->
                                                <div class="col-span-4 md:col-span-3">
                                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1" x-text="'Isi (' + (formData.units.find(u => u.is_base)?.unit_name || 'Satuan') + ')'">Isi</label>
                                                    <input type="number" :name="'units['+index+'][conversion_factor]'" x-model="unit.conversion_factor" @input="updateWarehouseStockFromBoxPcs()" class="w-full bg-white rounded-lg border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-xs font-bold" :placeholder="unit.is_base ? '1' : 'Jumlah satuan dasar'">
                                                </div>

                                                <!-- Price -->
                                                <div class="col-span-4 md:col-span-4">
                                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Harga Jual (Rp)</label>
                                                    <div class="relative">
                                                        <span class="text-[10px] font-bold text-gray-400 absolute left-2.5 top-2.5">Rp</span>
                                                        <input type="text" :name="'units['+index+'][price]'" x-model="unit.price" @input="unit.price = formatInput($event.target.value); updateWarehouseStockFromBoxPcs()" class="w-full bg-white rounded-lg border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-xs font-bold pl-7" placeholder="0">
                                                    </div>
                                                </div>

                                                <!-- Hidden Field for is_base -->
                                                <input type="hidden" :name="'units['+index+'][is_base]'" :value="unit.is_base ? '1' : '0'">

                                                <!-- Action: Remove -->
                                                <div class="col-span-12 md:col-span-2 flex flex-col items-center">
                                                    <!-- Invisible label helper to align with input labels on desktop -->
                                                    <span class="hidden md:block text-[10px] font-bold select-none text-transparent mb-1">Aksi</span>
                                                    <div class="flex items-center justify-center h-[34px]">
                                                        <template x-if="!unit.is_base">
                                                            <button type="button" @click="removeUnit(index)" class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                            </button>
                                                        </template>
                                                        <template x-if="unit.is_base">
                                                            <span class="text-[9px] font-black text-indigo-500 bg-indigo-50 px-2 py-1 rounded uppercase tracking-wider whitespace-nowrap">Base Unit</span>
                                                        </template>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
 
                                <div class="col-span-1 md:col-span-2 p-4 bg-emerald-50 rounded-xl border border-emerald-100">
                                    <p class="text-xs font-bold text-emerald-600 uppercase tracking-wider mb-4 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                                        Konfigurasi Harga Grosir Pcs (Opsional)
                                    </p>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-600 mb-1">Harga Grosir Satuan (Pcs) (Rp)</label>
                                            <input type="text" name="wholesale_price" x-model="formData.wholesale_price" @input="formData.wholesale_price = formatInput($event.target.value)" :class="errors.wholesale_price ? 'border-red-300 ring-red-100' : 'border-gray-300'" class="w-full bg-white rounded-lg focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm transition-all font-bold" placeholder="Biarkan kosong jika tidak ada">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-600 mb-1">Min. Qty Grosir Satuan (Pcs)</label>
                                            <input type="number" name="min_wholesale_qty" x-model="formData.min_wholesale_qty" class="w-full bg-white rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm" placeholder="Misal: 5">
                                        </div>
                                    </div>
                                </div>
 
                                 @if($trackStock)
                                 <div>
                                     <label class="block text-sm font-medium text-gray-700 mb-1">Stok Etalase (Pcs)</label>
                                     <input type="number" name="stock" x-model="formData.stock" :readonly="isEdit" :class="isEdit ? 'bg-gray-50 text-gray-400 cursor-not-allowed border-gray-200' : (errors.stock ? 'border-red-300 ring-red-100' : 'border-gray-300')" class="w-full rounded-lg focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-all" placeholder="0" required>
                                 </div>
                                 <div>
                                     <label class="block text-sm font-medium text-gray-700 mb-1">Batas Minim (Etalase)</label>
                                     <input type="number" name="min_stock" x-model="formData.min_stock" class="w-full bg-white rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" placeholder="0">
                                 </div>

                                 <!-- Stok Gudang dynamic card list -->
                                 <div class="col-span-1 md:col-span-2 mt-2">
                                     <label class="block text-sm font-semibold text-gray-700 mb-2">Stok Gudang (Berdasarkan Kemasan)</label>
                                     
                                     <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 p-4 bg-gray-50/50 rounded-2xl border border-gray-100/50">
                                         <template x-for="(unit, index) in formData.units" :key="index">
                                             <div class="bg-white p-3.5 rounded-xl border border-gray-200/60 shadow-sm flex flex-col justify-between">
                                                 <div class="mb-2">
                                                     <span class="text-[9px] font-black text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded uppercase tracking-wider whitespace-nowrap" x-text="unit.unit_name || 'Satuan'"></span>
                                                     <span class="text-[9px] text-gray-400 font-bold block mt-1" x-text="'Isi: ' + (unit.conversion_factor || 1) + ' Pcs'"></span>
                                                 </div>
                                                 <input type="number" x-model="unit.warehouse_stock_qty" @input="updateWarehouseStockFromUnits()" :readonly="isEdit" :class="isEdit ? 'bg-gray-50 text-gray-400 cursor-not-allowed border-gray-200' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-500'" class="w-full rounded-lg text-xs font-bold py-1 px-2" placeholder="0">
                                             </div>
                                         </template>
                                     </div>
                                 </div>
                                 @endif
                                 <input type="hidden" name="warehouse_stock" x-model="formData.warehouse_stock">
                            </div>

                            <div class="mt-8 flex justify-end gap-3 pt-6 border-t border-gray-100">
                                <button type="button" @click="showModal = false" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                                    Batal
                                </button>
                                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-sm transition-colors">
                                    Simpan Produk
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                </div>
            </div>
        <!-- Pindah Stok Modal -->
        <div x-show="showTransferModal" 
             style="display: none;" 
             class="fixed inset-0 z-[60] overflow-y-auto" 
             role="dialog" 
             aria-modal="true">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="closeTransferModal()"></div>
            
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div x-show="showTransferModal" 
                     x-transition:enter="ease-out duration-300" 
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md">
                    
                    <form @submit.prevent="submitTransfer">
                        @csrf
                        <input type="hidden" name="product_id" x-model="transferForm.product_id">
                        
                        <!-- Header -->
                        <div class="px-6 py-4 bg-indigo-600 flex justify-between items-center">
                            <h3 class="text-lg font-bold text-white">Pindah Stok</h3>
                            <button type="button" @click="closeTransferModal()" class="text-indigo-100 hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        
                        <div class="p-6 space-y-5">
                            <!-- Product Name -->
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Produk</label>
                                <input type="text" x-model="transferProductName" readonly class="w-full bg-gray-50 border-gray-200 rounded-lg text-sm text-gray-500 font-medium">
                            </div>

                            <!-- Direction -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Dari</label>
                                    <select name="from" x-model="transferForm.from" 
                                            @change="transferForm.to = (transferForm.from === 'warehouse' ? 'display' : 'warehouse')"
                                            class="w-full bg-white border border-gray-300 text-gray-700 py-2 px-3 pr-10 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-indigo-500 text-sm">
                                        <option value="warehouse">Gudang</option>
                                        <option value="display">Etalase</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Ke</label>
                                    <select name="to" x-model="transferForm.to"
                                            @change="transferForm.from = (transferForm.to === 'warehouse' ? 'display' : 'warehouse')"
                                            class="w-full bg-white border border-gray-300 text-gray-700 py-2 px-3 pr-10 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-indigo-500 text-sm">
                                        <option value="display">Etalase</option>
                                        <option value="warehouse">Gudang</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Quantity & Unit -->
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Jumlah Pindah</label>
                                <div class="flex">
                                    <input type="number" name="quantity" min="1" x-model="transferForm.quantity" required 
                                           class="w-full rounded-l-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm font-bold" placeholder="0">
                                    <select name="unit" x-model="transferForm.unit" 
                                            class="w-24 rounded-r-lg border-l-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-xs font-bold uppercase focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="pcs">Pcs</option>
                                        <option value="box">Dus</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Note -->
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Catatan</label>
                                <textarea name="note" x-model="transferForm.note" rows="2" 
                                          class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" 
                                          placeholder="Opsional..."></textarea>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3 rounded-b-2xl">
                            <button type="button" @click="closeTransferModal()" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                Batal
                            </button>
                            <button type="button" @click="submitTransfer($event)" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-sm transition-colors">
                                Konfirmasi Pindah
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-admin-layout>
