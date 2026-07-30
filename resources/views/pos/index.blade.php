<x-pos-layout>
    <div class="flex flex-col h-screen bg-[#F4F7FE] dark:bg-[#0B0E14] overflow-hidden scrollbar-hide transition-colors duration-300 relative" x-data="posSystem()" @profile-updated.window="userName = $event.detail.name">
        <!-- Floating Restore Navbar Button -->
        <button @click="isNavbarCollapsed = false" x-show="isNavbarCollapsed" x-cloak
                class="fixed top-0 left-6 z-40 bg-[#1e66f5] hover:bg-blue-600 text-white w-8 h-6 rounded-b-xl shadow-md transition-all flex items-center justify-center hover:h-7 focus:outline-none"
                title="Tampilkan Menu">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
        </button>

        <!-- Top Navigation Bar -->
        <header :class="isNavbarCollapsed ? 'h-0 overflow-hidden border-b-0 opacity-0 py-0' : 'h-16 flex border-b py-3'" class="bg-white dark:bg-[#11141D] border-gray-100 dark:border-gray-800 px-6 items-center justify-between shrink-0 shadow-sm z-30 transition-all duration-300">
            <div class="flex items-center gap-10">
                <!-- Logo -->
                <div class="flex items-center gap-2">
                    <div class="w-9 h-9 bg-[#1e66f5] rounded-xl flex items-center justify-center shadow-lg shadow-blue-100">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <span class="text-lg font-black tracking-tight text-[#16192C] dark:text-white">MiniMart<span class="text-[#1e66f5]">POS</span></span>
                </div>

                <!-- Nav Links -->
                <nav class="hidden lg:flex items-center gap-6">
                    <a href="{{ route('pos.index') }}" class="flex items-center gap-2 px-4 py-2 bg-blue-50 dark:bg-blue-500/10 text-[#1e66f5] rounded-xl font-bold text-sm transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        Kasir
                    </a>

                    @if(!Auth::user()->hasRole('cashier'))
                    <a href="{{ route('products.index') }}" class="flex items-center gap-2 px-4 py-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 font-bold text-sm transition-all hover:bg-gray-50 dark:hover:bg-gray-800 rounded-xl">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        Dashboard
                    </a>
                    @endif
                    <button @click.prevent="isCustomerModalOpen = true" class="flex items-center gap-2 px-4 py-2 text-gray-400 hover:text-[#1e66f5] dark:hover:text-[#1e66f5] hover:bg-gray-50 dark:hover:bg-gray-800 rounded-xl font-bold text-sm transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Pelanggan
                    </button>

                    <!-- Settings Button -->
                    <button @click.prevent="$dispatch('open-modal', 'opsi-kasir')" class="flex items-center gap-2 px-3 py-2 text-gray-400 hover:text-[#1e66f5] hover:bg-gray-50 dark:hover:bg-gray-800 rounded-xl font-bold text-sm transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Pengaturan
                    </button>
                </nav>
            </div>

            <div class="flex items-center gap-6">
                <!-- Theme toggle -->
                <button @click="$store.theme.toggle()" class="text-gray-400 hover:text-[#1e66f5] transition-all p-2 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-xl">
                    <template x-if="!$store.theme.darkMode">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                    </template>
                    <template x-if="$store.theme.darkMode">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M16.95 16.95l.707.707M7.05 7.05l.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </template>
                </button>

                <!-- Collapse Navbar Button -->
                <button @click="isNavbarCollapsed = true" class="text-gray-400 hover:text-[#1e66f5] transition-all p-2 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-xl" title="Sembunyikan Navbar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"></path></svg>
                </button>


                <!-- User Info -->
                <div class="relative" @click.away="isProfileOpen = false">
                    <button @click="isProfileOpen = !isProfileOpen" class="flex items-center gap-3 pl-6 border-l border-gray-100 dark:border-gray-800 hover:opacity-80 transition-all">
                        <div class="text-right hidden sm:flex flex-col items-end gap-0.5">
                            <span class="text-sm font-bold text-gray-800 dark:text-gray-200" x-text="userName">{{ Auth::user()->name }}</span>
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ Auth::user()->roles->first()->name ?? 'Administrator' }}</span>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-[#1e66f5] flex items-center justify-center text-white font-bold text-sm shadow-md ring-2 ring-white dark:ring-[#11141D] overflow-hidden">
                             <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=FFFFFF&background=1e66f5" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                        </div>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="isProfileOpen" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-3 w-48 bg-white dark:bg-[#11141D] rounded-2xl shadow-xl border border-gray-100 dark:border-gray-800 py-2 z-50">
                        <a href="#" @click.prevent="$dispatch('open-modal', 'profile-edit'); isProfileOpen = false" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                            <svg class="w-4 h-4 text-[#1e66f5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Profil Saya
                        </a>
                        <div class="h-[1px] bg-gray-50 dark:bg-gray-800 my-1 mx-4"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 flex overflow-hidden">
            <!-- Left Side: Products area -->
            <div x-show="cartMode === 'fnb'" x-cloak :class="isNavbarCollapsed ? 'p-4 pt-2' : 'p-6'" class="flex-1 flex flex-col h-full overflow-hidden transition-all duration-200">

                <!-- Search Row -->
                <div class="mb-4 flex gap-4">
                    <div class="flex-1 bg-white dark:bg-[#11141D] rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 flex items-center px-4 py-0.5 transition-colors">
                        <svg class="w-5 h-5 text-gray-300 dark:text-gray-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" x-model="search" @input.debounce.300ms="fetchProducts()" @focus="activeVirtualInput = 'search'" placeholder="Cari menu atau barcode..." class="flex-1 border-none focus:ring-0 text-sm font-medium placeholder:text-gray-300 dark:placeholder:text-gray-600 py-3 bg-transparent dark:text-gray-200">
                        <button @click="openScanner('fnb')" class="p-2 mr-2 text-gray-400 hover:text-[#1e66f5] transition-colors rounded-xl flex items-center justify-center bg-gray-50 dark:bg-gray-800">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm14 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V4zM3 16a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H4a1 1 0 01-1-1v-4zm14 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"></path></svg>
                        </button>
                    </div>
                    
                    <button class="w-12 h-12 bg-[#1e66f5] rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-100 dark:shadow-none hover:bg-blue-600 transition-all group shrink-0">
                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Category Row -->
                <div class="flex items-center gap-2 overflow-x-auto py-2 mb-4 scrollbar-hide">
                    <button @click="selectedCategory = null; fetchProducts()" 
                            :class="selectedCategory === null ? 'bg-[#1e66f5] text-white shadow-blue-100 dark:shadow-none shadow-md border-[#1e66f5]' : 'bg-white dark:bg-[#11141D] text-gray-400 dark:text-gray-500 border-gray-100 dark:border-gray-800 hover:border-blue-100 dark:hover:border-blue-900/50 hover:text-blue-500'"
                            class="px-5 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest border transition-all whitespace-nowrap">
                        Semua
                    </button>
                    <template x-for="category in categories" :key="category.id">
                        <button @click="selectedCategory = category.id; fetchProducts()" 
                                :class="selectedCategory == category.id ? 'bg-[#1e66f5] text-white shadow-blue-100 dark:shadow-none shadow-md border-[#1e66f5]' : 'bg-white dark:bg-[#11141D] text-gray-400 dark:text-gray-500 border-gray-100 dark:border-gray-800 hover:border-blue-100 dark:hover:border-blue-900/50 hover:text-blue-500'"
                                class="px-5 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest border transition-all whitespace-nowrap"
                                x-text="category.name">
                        </button>
                    </template>
                </div>

                <!-- Scrollable Product Area -->
                <div class="flex-1 overflow-y-auto scrollbar-hide pb-6">
                    <!-- Product Grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4" x-show="!isLoading">
                        <template x-for="product in products" :key="product.id">
                            <div @click="addToCart(product)" 
                                 class="bg-white dark:bg-[#11141D] rounded-2xl shadow-sm hover:shadow-lg dark:hover:shadow-blue-500/10 hover:-translate-y-1 transition-all duration-300 flex flex-col group cursor-pointer overflow-hidden border border-transparent dark:border-gray-800 hover:border-blue-50 dark:hover:border-blue-900/50">
                                <!-- Image container -->
                                <div class="aspect-square bg-gray-50 dark:bg-[#0B0E14] relative overflow-hidden">
                                    <template x-if="product.image">
                                        <img :src="'/storage/' + product.image" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    </template>
                                    <template x-if="!product.image">
                                        <div class="w-full h-full flex items-center justify-center text-gray-200 dark:text-gray-800">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                    </template>
                                </div>
                                
                                <div class="p-3">
                                    <div class="flex items-start justify-between mb-2">
                                        <span class="text-[8px] font-black uppercase tracking-widest px-1.5 py-0.5 rounded shadow-sm bg-blue-50 dark:bg-blue-500/10 text-blue-500" 
                                              :class="product.category?.name?.toLowerCase().includes('makanan') ? 'bg-blue-50 dark:bg-blue-500/10 text-blue-500' : 'bg-orange-50 dark:bg-orange-500/10 text-orange-500'"
                                              x-text="product.category?.name || 'Umum'"></span>
                                        <span class="text-[8px] font-bold text-gray-400 dark:text-gray-500" x-show="trackStock" x-text="'Stok: ' + product.stock"></span>
                                    </div>
                                    <h3 class="text-[11px] font-black text-gray-800 dark:text-gray-200 mb-1 line-clamp-1 group-hover:text-[#1e66f5] transition-colors" x-text="product.name"></h3>
                                    <p class="text-[11px] font-black text-[#1e66f5]" x-text="formatCurrency(product.selling_price)"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Right Side: Cart Sidebar -->
            <aside :class="cartMode === 'grosir' ? (isNavbarCollapsed ? 'flex-1 m-4 rounded-3xl overflow-hidden' : 'flex-1 m-6 rounded-3xl overflow-hidden') : (isNavbarCollapsed ? 'w-[460px] m-4 mt-2 ml-0 rounded-3xl overflow-hidden' : 'w-[460px] m-6 ml-0 rounded-3xl overflow-hidden')" class="bg-white dark:bg-[#11141D] border-l border-gray-100 dark:border-gray-800 flex flex-col shadow-xl dark:shadow-blue-500/5 scrollbar-hide transition-all">
                <!-- Cart Header -->
                <div class="bg-[#1e66f5] py-4 px-4 sm:px-6 flex items-center justify-between text-white shrink-0">
                    <div class="flex w-full items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <div>
                                <h2 class="text-base sm:text-lg font-black tracking-tight shrink-0">Pesanan</h2>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            <!-- Search SKU Input -->
                            <div x-show="cartMode === 'grosir'" class="w-48 sm:w-56 md:w-64 relative" @click.away="if (!$event.target.closest('.virtual-keyboard-container')) skuSuggestions = []">
                                <input x-ref="skuInput" x-model="skuInput" @keydown="handleSkuKey($event)" @focus="activeVirtualInput = 'skuInput'" 
                                       placeholder="Cari SKU..." 
                                       class="w-full pl-4 pr-4 py-2 rounded-full text-xs font-medium text-gray-900 placeholder-gray-400 bg-white dark:bg-[#0B0E14] dark:text-gray-200 border-none focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-900/50 shadow-sm" />

                                <!-- Dropdown Suggestions -->
                                <div x-show="skuSuggestions.length > 0" x-cloak 
                                     x-transition:enter="transition ease-out duration-150"
                                     x-transition:enter-start="opacity-0 translate-y-1"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     x-transition:leave="transition ease-in duration-100"
                                     x-transition:leave-start="opacity-100 translate-y-0"
                                     x-transition:leave-end="opacity-0 translate-y-1"
                                     x-ref="skuList" 
                                     class="absolute right-0 w-72 xs:w-80 sm:w-[420px] mt-3 bg-white/95 dark:bg-[#11141D]/95 backdrop-blur-xl border border-gray-200/60 dark:border-gray-700/60 rounded-2xl shadow-2xl shadow-black/10 dark:shadow-black/40 overflow-hidden max-h-56 z-50">
                                    <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-800">
                                        <span class="text-[9px] font-black text-gray-400 dark:text-gray-600 uppercase tracking-widest" x-text="skuSuggestions.length + ' produk ditemukan'"></span>
                                    </div>
                                    <div class="overflow-y-auto max-h-48 scrollbar-hide">
                                        <template x-for="(product, idx) in skuSuggestions" :key="product.id">
                                            <div @click="addFromSuggestion(product)" @mouseenter="skuActiveIndex = idx" 
                                                 :data-sku-item="true" :data-sku-active="skuActiveIndex === idx" 
                                                 :class="skuActiveIndex === idx 
                                                     ? 'px-4 py-3 bg-blue-50 dark:bg-blue-500/10 cursor-pointer flex items-center justify-between border-l-[3px] border-[#1e66f5]' 
                                                     : 'px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-800/50 cursor-pointer flex items-center justify-between border-l-[3px] border-transparent border-b border-b-gray-50 dark:border-b-gray-800/50 last:border-b-0'">
                                                <div class="min-w-0 flex-1 mr-4">
                                                    <div class="text-[11px] font-black text-gray-800 dark:text-gray-200 truncate" x-text="product.name"></div>
                                                    <div class="text-[9px] font-medium text-gray-400 dark:text-gray-500 mt-0.5" x-text="product.sku ? 'SKU: ' + product.sku : 'Tanpa SKU'"></div>
                                                </div>
                                                <div class="text-[11px] font-black text-[#1e66f5] shrink-0" x-text="formatCurrency(product.selling_price)"></div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-- Segmented Toggle Control -->
                            <div x-show="businessMode === 'all'" class="bg-blue-600/30 dark:bg-black/20 p-0.5 rounded-full flex items-center shrink-0" x-cloak>
                                <button @click="cartMode='fnb'" 
                                        :class="cartMode === 'fnb' ? 'bg-white text-[#1e66f5] shadow-sm' : 'text-white/80 hover:text-white'" 
                                        class="px-4 py-1.5 rounded-full text-xs font-black tracking-wider transition-all uppercase">
                                    F&B
                                </button>
                                <button @click="cartMode='grosir'" 
                                        :class="cartMode === 'grosir' ? 'bg-white text-[#1e66f5] shadow-sm' : 'text-white/80 hover:text-white'" 
                                        class="px-4 py-1.5 rounded-full text-xs font-black tracking-wider transition-all uppercase">
                                    Grosir
                                </button>
                            </div>

                            <!-- Pelanggan Selector di header -->
                            <div class="relative" x-data="{}">
                                <div x-show="selectedCustomer" @click="isCustomerModalOpen = true"
                                     class="flex items-center gap-2 bg-white/20 hover:bg-white/30 border border-white/30 px-3 py-1.5 rounded-full cursor-pointer transition-all">
                                    <svg class="w-3.5 h-3.5 text-white shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    <span class="text-[11px] font-black text-white max-w-[80px] truncate" x-text="selectedCustomer ? selectedCustomer.name : ''"></span>
                                    <button type="button" @click.stop="clearCustomer()" class="text-white/70 hover:text-white transition-colors ml-0.5">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                                <div x-show="!selectedCustomer" @click="isCustomerModalOpen = true"
                                     class="flex items-center gap-2 bg-white/10 hover:bg-white/20 border border-white/20 px-3 py-1.5 rounded-full cursor-pointer transition-all">
                                    <svg class="w-3.5 h-3.5 text-white/70 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    <span class="text-[11px] font-black text-white/70">Umum</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex-1 flex flex-col overflow-hidden" @scroll="skuSuggestions = []; skuActiveIndex = -1">
                    <div :class="cartMode === 'grosir' ? 'flex-1 flex flex-col sm:flex-row gap-4 overflow-hidden' : 'flex-1 flex flex-col overflow-hidden'">
                        <div :class="cartMode === 'grosir' ? 'pt-3 px-4 pb-4 w-full sm:w-3/5 overflow-y-auto h-full scrollbar-hide sm:border-r sm:border-gray-100 sm:dark:border-gray-800' : 'pt-3 px-4 flex-1 overflow-y-auto scrollbar-hide'" @scroll="skuSuggestions = []; skuActiveIndex = -1">
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-[9px] font-bold text-gray-400 dark:text-gray-600 uppercase tracking-widest">No. Invoice: <span class="text-gray-800 dark:text-gray-400">{{ \App\Models\Setting::get('invoice_prefix', 'INV') }}-[Otomatis]</span></span>
                                <span class="text-[9px] font-bold text-gray-400 dark:text-gray-600 uppercase tracking-widest" x-text="new Date().toLocaleDateString('id-ID', { year: 'numeric', month: '2-digit', day: '2-digit' })"></span>
                            </div>

                            <!-- Cart Content -->
                            <div class="flex-1">
                                <template x-if="cart.length === 0">
                                     <div class="flex flex-col items-center justify-center h-48 text-gray-300 dark:text-gray-800">
                                        <p class="text-[10px] font-bold uppercase tracking-widest">Pesanan Kosong</p>
                                    </div>
                                </template>
                                <template x-for="(item, index) in cart" :key="index">
                                    <div class="flex items-center justify-between gap-3 mb-4 group animate-in fade-in slide-in-from-right-2">
                                        <!-- Column 1: Product Identity (Image + Text) -->
                                        <div class="flex items-center gap-3 flex-1 min-w-0">
                                            <div class="w-10 h-10 rounded-lg bg-gray-50 dark:bg-[#0B0E14] overflow-hidden shrink-0 transition-colors">
                                                <template x-if="item.image">
                                                    <img :src="'/storage/' + item.image" class="w-full h-full object-cover">
                                                </template>
                                                <template x-if="!item.image">
                                                    <div class="w-full h-full flex items-center justify-center text-gray-200 bg-blue-50">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    </div>
                                                </template>
                                            </div>

                                            <div class="flex-1 min-w-0">
                                                <h4 :class="cartMode === 'grosir' ? 'text-sm font-black text-gray-800 dark:text-gray-200 line-clamp-1 truncate' : 'text-[11px] font-black text-gray-800 dark:text-gray-200 line-clamp-1 truncate'" x-text="item.name"></h4>
                                                 <div class="flex items-center gap-1.5 mt-1.5 flex-wrap">
                                                     <template x-for="u in item.units" :key="u.unit_name">
                                                         <button @click="switchUnit(index, u.unit_name)" 
                                                                 class="px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider border transition-all shadow-sm"
                                                                 :class="item.unit.toLowerCase() === u.unit_name.toLowerCase() 
                                                                     ? 'bg-blue-50 dark:bg-blue-900/30 text-[#1e66f5] dark:text-blue-400 border-blue-200 dark:border-blue-800' 
                                                                     : 'bg-white dark:bg-[#11141D] text-gray-400 dark:text-gray-500 border-gray-200 dark:border-gray-800 hover:border-gray-300 dark:hover:border-gray-700'"
                                                                 x-text="u.unit_name">
                                                         </button>
                                                     </template>
                                                     <template x-if="(item.units.find(u => u.unit_name.toLowerCase() === item.unit.toLowerCase()) || {}).is_base && item.wholesale_price && item.qty >= item.min_wholesale_qty">
                                                         <span class="text-[9px] font-black px-2 py-1 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600 dark:text-emerald-400 rounded-md border border-emerald-200 dark:border-emerald-800/30 uppercase tracking-wider animate-pulse">
                                                             GROSIR
                                                         </span>
                                                     </template>
                                                 </div></div>
                                            </div>

                                        <!-- Column 2: Quantity Selector -->
                                        <div class="w-20 shrink-0 flex items-center justify-center ml-auto">
                                            <div class="flex items-center bg-gray-50 dark:bg-[#0B0E14] rounded-lg p-0.5 gap-1">
                                                <button @click="updateQty(index, -1)" class="w-7 h-7 flex items-center justify-center text-gray-400 dark:text-gray-600 hover:text-[#1e66f5] transition-colors rounded">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4"></path></svg>
                                                </button>
                                                <span :class="cartMode === 'grosir' ? 'w-8 text-center text-base font-extrabold text-gray-800 dark:text-gray-200' : 'w-6 text-center text-sm font-extrabold text-gray-800 dark:text-gray-200'" x-text="item.qty"></span>
                                                <button @click="updateQty(index, 1)" class="w-7 h-7 flex items-center justify-center text-gray-400 dark:text-gray-600 hover:text-[#1e66f5] transition-colors rounded">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Column 3: Total Price -->
                                        <div :class="cartMode === 'grosir' ? 'w-28 sm:w-32 shrink-0 text-right text-base font-black text-[#1e66f5]' : 'w-24 sm:w-28 shrink-0 text-right text-sm font-black text-[#1e66f5]'" x-text="formatCurrency(item.price * item.qty)"></div>

                                        <!-- Column 4: Delete Button -->
                                        <div class="w-8 sm:w-10 shrink-0 flex items-center justify-end">
                                            <button @click="removeFromCart(index)" class="p-1 text-gray-400 dark:text-gray-600 hover:text-red-500 transition-colors rounded">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Payment Section -->
                        <div :class="cartMode === 'grosir' ? 'w-full sm:w-2/5 px-4 pb-4' : 'px-4 pb-4'">
                            <div :class="cartMode === 'grosir' ? 'h-full bg-white dark:bg-[#11141D] border-l border-dashed border-gray-100 dark:border-gray-800 rounded-2xl p-4 flex flex-col justify-between' : 'px-0 pb-0'">
                                <div>


                                    <div class="grid grid-cols-1 gap-3">
                                        <!-- Baris: Bayar (kiri) | Metode (kanan) -->
                                        <div class="grid grid-cols-2 gap-3">
                                            <div>
                                                <div class="flex justify-between items-center mb-1.5 ml-1">
                                                    <label :class="cartMode === 'grosir' ? 'text-[10px] font-black text-gray-400 dark:text-gray-600 uppercase tracking-widest block' : 'text-[9px] font-black text-gray-400 dark:text-gray-600 uppercase tracking-widest block'" x-text="'Bayar (' + formatPaymentMethod(paymentMethod) + ')'"></label>
                                                </div>
                                                <!-- Input editable untuk Cash -->
                                                <input x-show="(paymentMethod || '').toLowerCase() === 'cash'"
                                                       type="text" x-model="displayPaymentAmount" 
                                                       @focus="activeVirtualInput = 'payment'"
                                                       @input="paymentAmount = displayPaymentAmount.replace(/\D/g, ''); displayPaymentAmount = formatInput(paymentAmount)"
                                                       :class="cartMode === 'grosir' ? 'w-full bg-white dark:bg-[#11141D] border-none rounded-xl text-base font-black text-gray-800 dark:text-gray-100 focus:ring-1 focus:ring-blue-100 transition-colors py-2 px-3 h-[42px]' : 'w-full bg-white dark:bg-[#11141D] border-none rounded-xl text-sm font-black text-gray-800 dark:text-gray-100 focus:ring-1 focus:ring-blue-100 transition-colors py-2 px-3 h-[42px]'" placeholder="0">
                                                <!-- Input readonly untuk non-Cash (tampil total tagihan) -->
                                                <div x-show="(paymentMethod || '').toLowerCase() !== 'cash'"
                                                     :class="cartMode === 'grosir' ? 'w-full bg-gray-50 dark:bg-[#0B0E14] border border-dashed border-gray-200 dark:border-gray-700 rounded-xl text-base font-black text-gray-500 dark:text-gray-400 px-3 py-2 h-[42px] flex items-center select-none cursor-not-allowed' : 'w-full bg-gray-50 dark:bg-[#0B0E14] border border-dashed border-gray-200 dark:border-gray-700 rounded-xl text-sm font-black text-gray-500 dark:text-gray-400 px-3 py-2 h-[42px] flex items-center select-none cursor-not-allowed'"
                                                     x-text="formatCurrency(totalPrice)">
                                                </div>
                                            </div>
                                            <!-- Metode di kanan Bayar -->
                                            <div>
                                                <label :class="cartMode === 'grosir' ? 'text-[10px] font-black text-gray-400 dark:text-gray-600 uppercase tracking-widest block mb-1.5 ml-1' : 'text-[9px] font-black text-gray-400 dark:text-gray-600 uppercase tracking-widest block mb-1.5 ml-1'">Metode</label>
                                                <select x-model="paymentMethod" @change="setPaymentMethod($event.target.value)" 
                                                        :class="cartMode === 'grosir' ? 'w-full bg-white dark:bg-[#11141D] border border-gray-200 dark:border-gray-800 rounded-xl text-sm font-black text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-[#1e66f5]/20 focus:border-[#1e66f5] transition-all pr-8 h-[42px]' : 'w-full bg-white dark:bg-[#11141D] border border-gray-200 dark:border-gray-800 rounded-xl text-sm font-black text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-[#1e66f5]/20 focus:border-[#1e66f5] transition-all pr-8 h-[42px]'">
                                                    @foreach($paymentMethods as $method)
                                                        <option value="{{ $method->method_name }}">{{ $method->display_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Tampilkan Keyboard Button di bawah Bayar dan Metode -->
                                        <div x-show="cartMode === 'fnb' && !showNumpad" class="flex justify-end mt-0.5">
                                            <button type="button" @click="showNumpad = true" class="text-[#1e66f5] hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-500 transition-colors flex items-center gap-1 text-[8px] font-bold uppercase tracking-wider focus:outline-none py-0.5 px-2 bg-blue-50 dark:bg-blue-900/10 rounded-lg">
                                                <span>Tampilkan Keyboard</span>
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                            </button>
                                        </div>
                                    </div>

                                    <template x-if="((paymentMethod || '').toLowerCase() === 'cash') && predictedCashNominals.length > 0">
                                        <div class="flex flex-wrap gap-2 mt-2">
                                            <template x-for="nominal in predictedCashNominals" :key="nominal">
                                                <button @click="paymentAmount = parseInt(nominal)" 
                                                        :class="cartMode === 'grosir' ? 'px-4 py-2 bg-white dark:bg-[#11141D] border border-gray-100 dark:border-gray-800 rounded-2xl text-xs font-black text-gray-700 dark:text-gray-300 hover:bg-indigo-50 hover:border-indigo-200 hover:text-indigo-600 transition-all shadow-sm' : 'px-3 py-1.5 bg-white dark:bg-[#11141D] border border-gray-100 dark:border-gray-800 rounded-lg text-[9px] font-black text-gray-600 dark:text-gray-400 hover:bg-indigo-50 hover:border-indigo-200 hover:text-indigo-600 transition-all shadow-sm'"
                                                        x-text="formatCurrency(nominal)">
                                                </button>
                                            </template>
                                            <button @click="paymentAmount = totalPrice" 
                                                    :class="cartMode === 'grosir' ? 'px-4 py-2 bg-white dark:bg-[#11141D] border border-gray-100 dark:border-gray-800 rounded-2xl text-xs font-black text-gray-700 dark:text-gray-300 hover:bg-indigo-50 hover:border-indigo-200 hover:text-indigo-600 transition-all shadow-sm' : 'px-3 py-1.5 bg-white dark:bg-[#11141D] border border-gray-100 dark:border-gray-800 rounded-lg text-[9px] font-black text-gray-600 dark:text-gray-400 hover:bg-indigo-50 hover:border-indigo-200 hover:text-indigo-600 transition-all shadow-sm'">
                                                Uang Pas
                                            </button>
                                        </div>
                                    </template>

                                    <style>
                                        .virtual-keyboard-container {
                                            height: 180px;
                                        }
                                        @media (min-width: 768px) {
                                            .virtual-keyboard-container {
                                                height: 210px;
                                            }
                                        }
                                    </style>

                                    <!-- Touch Numpad / Keyboard Grid -->
                                    <!-- Keyboard hanya tampil untuk Cash (di FnB showNumpad toggle, di Grosir selalu) -->
                                    <div x-show="(cartMode === 'grosir' || ((paymentMethod || '').toLowerCase() === 'cash' && showNumpad))" 
                                         class="mt-2.5 border-t border-gray-100 dark:border-gray-800 pt-2 virtual-keyboard-container" x-data="{ keyboardMode: 'numpad' }">
                                        <!-- Header row of the keyboard section: Switch Button & Hide Button -->
                                         <div class="flex justify-between items-center mb-2 px-1">
                                             <!-- Toggle Button (Left) -->
                                             <button type="button" @click="keyboardMode = (keyboardMode === 'numpad' ? 'qwerty' : 'numpad')" 
                                                     class="px-2.5 py-1 bg-blue-50 dark:bg-blue-500/10 text-[#1e66f5] rounded-lg text-[9px] font-black uppercase tracking-widest hover:bg-blue-100 transition-colors focus:outline-none flex items-center gap-1">
                                                 <span x-text="keyboardMode === 'numpad' ? 'Switch ke Huruf 🔤' : 'Switch ke Angka 🔢'"></span>
                                             </button>

                                             <!-- Hide Keyboard Button (Right, FnB mode only) -->
                                             <template x-if="cartMode === 'fnb'">
                                                 <button type="button" @click="showNumpad = false" 
                                                         class="px-2 py-0.5 border border-red-200 dark:border-red-900/50 bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 rounded-lg text-[9px] font-black uppercase tracking-widest hover:bg-red-100 transition-colors focus:outline-none flex items-center gap-1">
                                                     <span>Sembunyikan Keyboard ❌</span>
                                                 </button>
                                             </template>
                                             <template x-if="cartMode !== 'fnb'">
                                                 <div></div>
                                             </template>
                                         </div>

                                        <!-- NUMPAD VIEW -->
                                        <div x-show="keyboardMode === 'numpad'">
                                            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 4px;">
                                                <!-- Row 1 -->
                                                <button type="button" @click="virtualKeyPress('7')" class="h-8 md:h-10 rounded-xl bg-gray-50 dark:bg-[#0B0E14] text-xs md:text-sm font-extrabold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-[#1e66f5] transition-all border border-gray-100/50 dark:border-gray-800 shadow-sm flex items-center justify-center active:scale-95">7</button>
                                                <button type="button" @click="virtualKeyPress('8')" class="h-8 md:h-10 rounded-xl bg-gray-50 dark:bg-[#0B0E14] text-xs md:text-sm font-extrabold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-[#1e66f5] transition-all border border-gray-100/50 dark:border-gray-800 shadow-sm flex items-center justify-center active:scale-95">8</button>
                                                <button type="button" @click="virtualKeyPress('9')" class="h-8 md:h-10 rounded-xl bg-gray-50 dark:bg-[#0B0E14] text-xs md:text-sm font-extrabold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-[#1e66f5] transition-all border border-gray-100/50 dark:border-gray-800 shadow-sm flex items-center justify-center active:scale-95">9</button>
                                                <button type="button" @click="virtualKeyPress('⌫')" class="h-8 md:h-10 rounded-xl bg-red-50 dark:bg-red-500/10 text-xs md:text-sm font-extrabold text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-500/20 transition-all border border-red-100 dark:border-red-900/30 shadow-sm flex items-center justify-center active:scale-95">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M3 12l6.414-6.414A2 2 0 0110.828 5H20a2 2 0 012 2v10a2 2 0 01-2 2h-9.172a2 2 0 01-1.414-.586L3 12z"></path></svg>
                                                </button>

                                                <!-- Row 2 -->
                                                <button type="button" @click="virtualKeyPress('4')" class="h-8 md:h-10 rounded-xl bg-gray-50 dark:bg-[#0B0E14] text-xs md:text-sm font-extrabold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-[#1e66f5] transition-all border border-gray-100/50 dark:border-gray-800 shadow-sm flex items-center justify-center active:scale-95">4</button>
                                                <button type="button" @click="virtualKeyPress('5')" class="h-8 md:h-10 rounded-xl bg-gray-50 dark:bg-[#0B0E14] text-xs md:text-sm font-extrabold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-[#1e66f5] transition-all border border-gray-100/50 dark:border-gray-800 shadow-sm flex items-center justify-center active:scale-95">5</button>
                                                <button type="button" @click="virtualKeyPress('6')" class="h-8 md:h-10 rounded-xl bg-gray-50 dark:bg-[#0B0E14] text-xs md:text-sm font-extrabold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-[#1e66f5] transition-all border border-gray-100/50 dark:border-gray-800 shadow-sm flex items-center justify-center active:scale-95">6</button>
                                                <button type="button" @click="virtualKeyPress('C')" class="h-8 md:h-10 rounded-xl bg-orange-50 dark:bg-orange-500/10 text-[10px] md:text-xs font-black text-orange-600 dark:text-orange-400 hover:bg-orange-100 dark:hover:bg-orange-500/20 transition-all border border-orange-100 dark:border-orange-900/30 shadow-sm flex items-center justify-center active:scale-95">C</button>

                                                <!-- Row 3 -->
                                                <button type="button" @click="virtualKeyPress('1')" class="h-8 md:h-10 rounded-xl bg-gray-50 dark:bg-[#0B0E14] text-xs md:text-sm font-extrabold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-[#1e66f5] transition-all border border-gray-100/50 dark:border-gray-800 shadow-sm flex items-center justify-center active:scale-95">1</button>
                                                <button type="button" @click="virtualKeyPress('2')" class="h-8 md:h-10 rounded-xl bg-gray-50 dark:bg-[#0B0E14] text-xs md:text-sm font-extrabold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-[#1e66f5] transition-all border border-gray-100/50 dark:border-gray-800 shadow-sm flex items-center justify-center active:scale-95">2</button>
                                                <button type="button" @click="virtualKeyPress('3')" class="h-8 md:h-10 rounded-xl bg-gray-50 dark:bg-[#0B0E14] text-xs md:text-sm font-extrabold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-[#1e66f5] transition-all border border-gray-100/50 dark:border-gray-800 shadow-sm flex items-center justify-center active:scale-95">3</button>
                                                <button type="button" @click="virtualKeyPress('Pas')" class="h-8 md:h-10 rounded-xl bg-indigo-50 dark:bg-indigo-500/10 text-[10px] md:text-xs font-black text-indigo-600 dark:text-indigo-400 hover:bg-indigo-100 dark:hover:bg-indigo-500/20 transition-all border border-indigo-100 dark:border-indigo-900/30 shadow-sm flex items-center justify-center active:scale-95">Pas</button>

                                                <!-- Row 4 -->
                                                <button type="button" @click="virtualKeyPress('0')" class="h-8 md:h-10 rounded-xl bg-gray-50 dark:bg-[#0B0E14] text-xs md:text-sm font-extrabold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-[#1e66f5] transition-all border border-gray-100/50 dark:border-gray-800 shadow-sm flex items-center justify-center active:scale-95">0</button>
                                                <button type="button" @click="virtualKeyPress('00')" class="h-8 md:h-10 rounded-xl bg-gray-50 dark:bg-[#0B0E14] text-xs md:text-sm font-extrabold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-[#1e66f5] transition-all border border-gray-100/50 dark:border-gray-800 shadow-sm flex items-center justify-center active:scale-95">00</button>
                                                <button type="button" @click="virtualKeyPress('000')" class="col-span-2 h-8 md:h-10 rounded-xl bg-gray-50 dark:bg-[#0B0E14] text-xs md:text-sm font-extrabold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-[#1e66f5] transition-all border border-gray-100/50 dark:border-gray-800 shadow-sm flex items-center justify-center active:scale-95" style="grid-column: span 2 / span 2;">000</button>
                                            </div>
                                        </div>

                                        <!-- QWERTY ALPHABET KEYBOARD VIEW -->
                                        <div x-show="keyboardMode === 'qwerty'" class="space-y-1 md:space-y-1.5" x-cloak>
                                            <!-- Row 1: QWERTYUIOP -->
                                            <div style="display: grid; grid-template-columns: repeat(10, 1fr); gap: 3px;">
                                                <template x-for="char in ['Q','W','E','R','T','Y','U','I','O','P']">
                                                    <button type="button" @click="virtualKeyPress(char)" class="h-8 md:h-9 rounded-lg bg-gray-50 dark:bg-[#0B0E14] text-[10px] md:text-xs font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-all border border-gray-100/50 dark:border-gray-800/80 shadow-sm flex items-center justify-center" x-text="char"></button>
                                                </template>
                                            </div>
                                            <!-- Row 2: ASDFGHJKL -->
                                            <div style="display: grid; grid-template-columns: repeat(9, 1fr); gap: 3px; padding: 0 4px;">
                                                <template x-for="char in ['A','S','D','F','G','H','J','K','L']">
                                                    <button type="button" @click="virtualKeyPress(char)" class="h-8 md:h-9 rounded-lg bg-gray-50 dark:bg-[#0B0E14] text-[10px] md:text-xs font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-all border border-gray-100/50 dark:border-gray-800/80 shadow-sm flex items-center justify-center" x-text="char"></button>
                                                </template>
                                            </div>
                                            <!-- Row 3: ZXCVBNM and Backspace -->
                                            <div style="display: grid; grid-template-columns: repeat(9, 1fr); gap: 3px;">
                                                <div style="grid-column: span 1 / span 1;"></div>
                                                <template x-for="char in ['Z','X','C','V','B','N','M']">
                                                    <button type="button" @click="virtualKeyPress(char)" class="h-8 md:h-9 rounded-lg bg-gray-50 dark:bg-[#0B0E14] text-[10px] md:text-xs font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-all border border-gray-100/50 dark:border-gray-800/80 shadow-sm flex items-center justify-center" x-text="char"></button>
                                                </template>
                                                <button type="button" @click="virtualKeyPress('⌫')" class="h-8 md:h-9 rounded-lg bg-red-50 dark:bg-red-500/10 text-[10px] md:text-xs font-bold text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-500/20 transition-all border border-red-100 dark:border-red-900/30 shadow-sm flex items-center justify-center">
                                                    ⌫
                                                </button>
                                            </div>
                                            <!-- Row 4: Space, Clear, and numbers 0-9 shortcuts -->
                                            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 4px;">
                                                <button type="button" @click="virtualKeyPress('C')" class="h-8 md:h-9 rounded-lg bg-orange-50 dark:bg-orange-500/10 text-[9px] md:text-xs font-black text-orange-600 dark:text-orange-400 hover:bg-orange-100 dark:hover:bg-orange-500/20 transition-all border border-orange-100 dark:border-orange-900/30 shadow-sm flex items-center justify-center">CLEAR</button>
                                                <button type="button" @click="virtualKeyPress('Space')" class="col-span-2 h-8 md:h-9 rounded-lg bg-gray-50 dark:bg-[#0B0E14] text-[9px] md:text-xs font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-all border border-gray-100/50 dark:border-gray-800 shadow-sm flex items-center justify-center" style="grid-column: span 2 / span 2;">SPACE</button>
                                                <button type="button" @click="keyboardMode = 'numpad'" class="h-8 md:h-9 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 text-[9px] md:text-xs font-black text-indigo-600 dark:text-indigo-400 hover:bg-indigo-100 dark:hover:bg-indigo-500/20 transition-all border border-indigo-100 dark:border-indigo-900/30 shadow-sm flex items-center justify-center">NUM</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-auto">
                                    <!-- Total Tagihan selalu tampil, nominal hanya saat ada produk -->
                                    <div class="flex justify-between items-center mt-3 pt-2.5 border-t border-gray-50 dark:border-gray-800">
                                        <span :class="cartMode === 'grosir' ? 'text-base font-black text-gray-400 dark:text-gray-600 uppercase tracking-widest' : 'text-sm font-black text-gray-400 dark:text-gray-600 uppercase tracking-widest'">Total Tagihan</span>
                                        <span x-show="cart.length > 0"
                                              :class="cartMode === 'grosir' ? 'text-base font-black text-gray-800 dark:text-white' : 'text-sm font-black text-gray-800 dark:text-white'"
                                              x-text="formatCurrency(totalPrice)"></span>
                                    </div>

                                    <!-- Kembalian selalu tampil saat metode cash, nominal hanya saat ada input bayar -->
                                    <div class="flex justify-between items-center mt-2.5"
                                         x-show="(paymentMethod || '').toLowerCase() === 'cash'">
                                        <span :class="changeAmount < 0 ? (cartMode === 'grosir' ? 'text-base font-black text-red-500' : 'text-sm font-black text-red-500') : (cartMode === 'grosir' ? 'text-base font-black text-green-500' : 'text-sm font-black text-green-500')">Kembalian</span>
                                        <span x-show="cart.length > 0 && parseInt(paymentAmount || 0) > 0"
                                              :class="changeAmount < 0 ? (cartMode === 'grosir' ? 'text-base font-black text-red-500' : 'text-sm font-black text-red-500') : (cartMode === 'grosir' ? 'text-base font-black text-green-500' : 'text-sm font-black text-green-500')"
                                              x-text="formatCurrency(changeAmount)"></span>
                                    </div>

                                    <template x-if="((paymentMethod || '').toLowerCase() === 'qris') && '{{ $qrisImage }}' !== ''">
                                        <div class="mt-3 p-3 bg-white dark:bg-[#11141D] border border-gray-100 dark:border-gray-800 rounded-2xl shadow-sm text-center">
                                            <p class="text-[9px] font-black text-gray-400 dark:text-gray-600 uppercase tracking-widest mb-2">Scan QRIS Berikut</p>
                                            <img src="{{ asset('storage/' . $qrisImage) }}" class="mx-auto w-32 h-32 object-contain rounded-lg border border-gray-50 dark:border-gray-800">
                                        </div>
                                    </template>
                                </div>

                                <div class="mt-2.5">
                                        <button @click="processPayment()" :disabled="cart.length === 0 || isProcessingPayment" 
                                            :class="cartMode === 'grosir' ? 'w-full flex items-center justify-center gap-2 py-4 bg-[#1e66f5] rounded-xl text-sm font-black text-white shadow-lg shadow-blue-100 dark:shadow-none hover:bg-blue-600 transition-all disabled:opacity-50' : 'w-full flex items-center justify-center gap-2 py-3.5 bg-[#1e66f5] rounded-xl text-xs font-black text-white shadow-lg shadow-blue-100 dark:shadow-none hover:bg-blue-600 transition-all disabled:opacity-50'">
                                        <template x-if="!isProcessingPayment">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                        </template>
                                        <template x-if="isProcessingPayment">
                                            <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </template>
                                        <span x-text="isProcessingPayment ? 'Memproses...' : 'Proses Pembayaran'"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
        </main>

    <script>
        async function getLogoEscPosBytes(logoUrl, targetWidth = 192) {
            return new Promise((resolve) => {
                const img = new Image();
                img.crossOrigin = 'Anonymous';
                img.onload = function() {
                    try {
                        const scale = targetWidth / img.width;
                        const targetHeight = Math.round(img.height * scale);
                        
                        const canvas = document.createElement('canvas');
                        canvas.width = targetWidth;
                        canvas.height = targetHeight;
                        const ctx = canvas.getContext('2d');
                        ctx.drawImage(img, 0, 0, targetWidth, targetHeight);
                        
                        const imgData = ctx.getImageData(0, 0, targetWidth, targetHeight);
                        const pixels = imgData.data;
                        
                        const widthBytes = targetWidth / 8;
                        const escPosBytes = [];
                        
                        const xL = widthBytes & 0xFF;
                        const xH = (widthBytes >> 8) & 0xFF;
                        const yL = targetHeight & 0xFF;
                        const yH = (targetHeight >> 8) & 0xFF;
                        
                        const header = [0x1D, 0x76, 0x30, 0, xL, xH, yL, yH];
                        escPosBytes.push(...header);
                        
                        for (let y = 0; y < targetHeight; y++) {
                            for (let xByte = 0; xByte < widthBytes; xByte++) {
                                let byteVal = 0;
                                for (let bit = 0; bit < 8; bit++) {
                                    const xDot = xByte * 8 + bit;
                                    const pixelIdx = (y * targetWidth + xDot) * 4;
                                    
                                    const r = pixels[pixelIdx];
                                    const g = pixels[pixelIdx + 1];
                                    const b = pixels[pixelIdx + 2];
                                    const a = pixels[pixelIdx + 3];
                                    
                                    let isBlack = false;
                                    if (a > 128) {
                                        const grayscale = 0.299 * r + 0.587 * g + 0.114 * b;
                                        isBlack = grayscale < 128;
                                    }
                                    
                                    if (isBlack) {
                                        byteVal |= (1 << (7 - bit));
                                    }
                                }
                                escPosBytes.push(byteVal);
                            }
                        }
                        resolve(new Uint8Array(escPosBytes));
                    } catch (err) {
                        console.error('Error processing logo image:', err);
                        resolve(null);
                    }
                };
                img.onerror = function() {
                    resolve(null);
                };
                img.src = logoUrl;
            });
        }

        function posSystem() {
            return {
                userName: '{{ Auth::user()->name }}',
                isSettingsOpen: false,
                businessMode: localStorage.getItem('posBusinessMode') || 'fnb',
                isScannerActive: true,
                isPrinterActive: true,
                isProfileOpen: false,
                isScannerOpen: false,
                isPaymentSuccessOpen: false,
                isProcessingPayment: false,
                lastTransaction: {},
                scannerMode: 'fnb',
                html5QrcodeScanner: null,
                search: '',
                selectedCategory: null,
                categories: @json($categories),
                products: @json($products),
                allProducts: @json($products),
                cart: [],
                isLoading: false,
                paymentAmount: 0,
                displayPaymentAmount: '',
                paymentMethod: '{{ $paymentMethods->where('method_name', 'cash')->isNotEmpty() ? 'cash' : ($paymentMethods->first()->method_name ?? 'cash') }}',
                customerId: '', 
                customers: @json($customers),
                selectedCustomer: null,
                isCustomerModalOpen: false,
                customerSearch: '',
                newCustomer: { name: '', phone: '', address: '' },
                isSavingCustomer: false,
                showNumpad: true,
                activeVirtualInput: 'skuInput', // 'skuInput', 'search', or 'payment'
                isNavbarCollapsed: false,
                isBluetoothPrinterConnected: false,
                bluetoothDevice: null,
                bluetoothCharacteristic: null,
                recentOrders: @json($recentOrders),
                get predictedCashNominals() {
                    const total = this.totalPrice;
                    if (total === 0) return [10000, 20000, 50000, 100000];
                    
                    let suggestions = new Set();
                    // suggestions.add(total); // removed to avoid duplicate 'Uang Pas' button
                    
                    const bills = [1000, 2000, 5000, 10000, 20000, 50000, 100000];
                    for (let bill of bills) {
                        if (bill > total) suggestions.add(bill);
                    }
                    
                    const roundings = [5000, 10000, 20000, 50000, 100000];
                    for (let r of roundings) {
                        let rounded = Math.ceil(total / r) * r;
                        if (rounded > total) suggestions.add(rounded);
                    }
                    
                    return Array.from(suggestions).sort((a, b) => a - b).slice(0, 5);
                },
                // Cart mode: 'fnb' = original product grid view, 'grosir' = retail/grosir cart-only view
                cartMode: localStorage.getItem('posCartMode') || 'grosir',
                maxUniqueItems: 20,
                trackStock: {{ \App\Models\Setting::get('track_stock', '1') == '1' ? 'true' : 'false' }},
                skuInput: '',
                skuSuggestions: [],
                skuActiveIndex: -1,
                
                // Physical Scanner State
                scannerBuffer: '',
                scannerLastTime: 0,
                scannerThreshold: 50, // ms

                init() {
                    this.initGlobalScanner();
                    
                    if (this.businessMode === 'fnb') this.cartMode = 'fnb';
                    if (this.businessMode === 'grosir') this.cartMode = 'grosir';

                    this.$watch('businessMode', (v) => {
                        localStorage.setItem('posBusinessMode', v);
                        if (v === 'fnb') this.cartMode = 'fnb';
                        if (v === 'grosir') this.cartMode = 'grosir';
                    });
                    
                    // Refresh products from allProducts if empty
                    if (!this.products || this.products.length === 0) {
                        this.products = this.allProducts;
                    }

                    // Watch for programmatic changes to paymentAmount (like quick cash buttons)
                    this.$watch('paymentAmount', (value) => {
                        const formatted = this.formatInput(value);
                        if (this.displayPaymentAmount !== formatted) {
                            this.displayPaymentAmount = formatted;
                        }
                    });

                    // restore cart mode preference and focus sku input when switching to grosir
                    this.cartMode = localStorage.getItem('posCartMode') || this.cartMode;
                    this.$watch('cartMode', (v) => {
                        localStorage.setItem('posCartMode', v);
                        this.activeVirtualInput = v === 'grosir' ? 'skuInput' : 'search';
                        this.$nextTick(() => {
                            if (v === 'grosir' && this.$refs.skuInput) {
                                try { this.$refs.skuInput.focus(); } catch(e){}
                            }
                        });
                    });

                    // watch skuInput to provide suggestions in grosir mode
                    this.$watch('skuInput', (val) => {
                        if (this.cartMode !== 'grosir') {
                            this.skuSuggestions = [];
                            this.skuActiveIndex = -1;
                            return;
                        }
                        const term = (val || '').toString().trim().toLowerCase();
                        if (!term) { this.skuSuggestions = []; this.skuActiveIndex = -1; return; }

                        // find products matching sku, barcode or name
                        const matches = this.allProducts.filter(p => {
                            const sku = (p.sku || '').toString().toLowerCase();
                            const barcode = (p.barcode || '').toString().toLowerCase();
                            const name = (p.name || '').toString().toLowerCase();
                            return sku.includes(term) || barcode.includes(term) || name.includes(term);
                        }).slice(0, 8);
                        this.skuSuggestions = matches;
                        this.skuActiveIndex = matches.length ? 0 : -1;
                    });

                    // Reset payment when cart becomes empty
                    this.$watch('cart', (val) => {
                        if (!val || val.length === 0) {
                            this.paymentAmount = '';
                            this.displayPaymentAmount = '';
                        } else if ((this.paymentMethod || '').toLowerCase() !== 'cash') {
                            // Non-cash: selalu ikuti total tagihan otomatis
                            this.$nextTick(() => {
                                this.paymentAmount = this.totalPrice;
                            });
                        }
                    }, { deep: true });

                    // autofocus if starting in grosir mode
                    this.$nextTick(() => {
                        if (this.cartMode === 'grosir' && this.$refs.skuInput) {
                            try { this.$refs.skuInput.focus(); } catch(e){}
                        }
                    });
                },

                testPrint() {
                    window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Test print berhasil dikirim ke printer!', type: 'success' } }));
                },

                formatInput(val) {
                    if (val === null || val === undefined || val === '') return '';
                    let str = val.toString();
                    if (str.includes('.') && (str.match(/\./g) || []).length === 1 && /^\d+\.\d{1,2}$/.test(str)) {
                        str = Math.floor(parseFloat(str)).toString();
                    }
                    str = str.replace(/\D/g, '');
                    return str.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                },
                
                initGlobalScanner() {
                    document.addEventListener('keydown', (e) => {
                        // Abaikan jika menekan modifier
                        if (e.ctrlKey || e.altKey || e.metaKey) return;
                        
                        const currentTime = new Date().getTime();
                        
                        // Jika jeda antar tuts terlalu lama (> 50ms), kemungkinan ini adalah ketikan manual manusia, bukan scanner.
                        // Maka reset buffernya.
                        if (currentTime - this.scannerLastTime > this.scannerThreshold) {
                            this.scannerBuffer = '';
                        }
                        
                        this.scannerLastTime = currentTime;
                        
                        if (e.key === 'Enter') {
                            // Jika buffer ada isinya (minimal 3 karakter), anggap ini hasil scan barcode
                            if (this.scannerBuffer.length >= 3) {
                                // Jangan jalankan aksi default Enter (seperti submit form)
                                e.preventDefault();
                                
                                // Cari dan tambahkan produk ke keranjang
                                this.addBySku(this.scannerBuffer);
                                
                                // Bersihkan buffer untuk scan berikutnya
                                this.scannerBuffer = '';
                                
                                // Kosongkan search/input manual jika ada agar tidak double
                                if (this.$refs.skuInput) {
                                    this.skuInput = '';
                                }
                            }
                        } else if (e.key.length === 1) { 
                            // Hanya ambil karakter yang bisa dicetak (angka/huruf)
                            this.scannerBuffer += e.key;
                        }
                    });
                },

                fetchProducts() {
                    this.isLoading = true;
                    this.products = this.allProducts.filter(p => {
                        if (this.trackStock && p.stock <= 0) {
                            return false;
                        }
                        const searchLower = (this.search || '').toLowerCase();
                        const matchSearch = (p.name || '').toLowerCase().includes(searchLower) || 
                                           (p.sku || '').toLowerCase().includes(searchLower);
                        
                        // Handle null or undefined selectedCategory as "All"
                        const matchCategory = this.selectedCategory === null || 
                                            this.selectedCategory === undefined || 
                                            p.category_id == this.selectedCategory;
                        
                        return matchSearch && matchCategory;
                    });
                    this.isLoading = false;
                },

                setPaymentMethod(method) {
                    this.paymentMethod = method;
                    if ((method || '').toLowerCase() !== 'cash') {
                        // Non-cash: auto-fill dengan total tagihan, tidak perlu input manual
                        this.paymentAmount = this.totalPrice;
                        this.showNumpad = false; // sembunyikan keyboard
                    } else {
                        // Cash: reset untuk input manual
                        this.paymentAmount = 0;
                        this.displayPaymentAmount = '';
                        this.showNumpad = true; // tampilkan keyboard
                    }
                },

                numpadPress(key) {
                    if (key === 'C') {
                        this.paymentAmount = 0;
                        this.displayPaymentAmount = '';
                        return;
                    }
                    
                    if (key === '⌫') {
                        let str = (this.paymentAmount || 0).toString();
                        if (str.length > 1) {
                            this.paymentAmount = parseInt(str.slice(0, -1)) || 0;
                        } else {
                            this.paymentAmount = 0;
                            this.displayPaymentAmount = '';
                        }
                        return;
                    }
                    
                    if (key === 'Pas') {
                        this.paymentAmount = this.totalPrice;
                        return;
                    }
                    
                    let str = (this.paymentAmount || 0).toString();
                    if (str === '0') str = '';
                    
                    if (key === '00') {
                        str += '00';
                    } else if (key === '000') {
                        str += '000';
                    } else {
                        str += key;
                    }
                    
                    this.paymentAmount = parseInt(str) || 0;
                },

                virtualKeyPress(key) {
                    if (this.activeVirtualInput === 'payment') {
                        if (/[0-9]/.test(key) || key === '00' || key === '000' || key === '⌫' || key === 'C' || key === 'Pas') {
                            this.numpadPress(key);
                        }
                        return;
                    }
                    
                    let currentVal = '';
                    if (this.activeVirtualInput === 'search') {
                        currentVal = this.search || '';
                    } else if (this.activeVirtualInput === 'skuInput') {
                        currentVal = this.skuInput || '';
                    }
                    
                    if (key === '⌫') {
                        currentVal = currentVal.slice(0, -1);
                    } else if (key === 'C') {
                        currentVal = '';
                    } else if (key === 'Space') {
                        currentVal += ' ';
                    } else {
                        currentVal += key;
                    }
                    
                    if (this.activeVirtualInput === 'search') {
                        this.search = currentVal;
                        this.fetchProducts();
                    } else if (this.activeVirtualInput === 'skuInput') {
                        this.skuInput = currentVal;
                    }
                },

                addBySku(sku) {
                    if (!sku) return;
                    const code = sku.toString().trim();
                    if (!code) return;

                    // exact match by SKU or barcode (case-insensitive)
                    let product = this.allProducts.find(p => ((p.sku || '').toString().toLowerCase() === code.toLowerCase()) || ((p.barcode || '').toString().toLowerCase() === code.toLowerCase()));
                    if (product) {
                        this.addToCart(product);
                        this.skuInput = '';
                        this.skuSuggestions = [];
                        return;
                    }

                    // if no exact match, try fuzzy name/sku contains
                    const term = code.toLowerCase();
                    const matches = this.allProducts.filter(p => {
                        const sku = (p.sku || '').toString().toLowerCase();
                        const barcode = (p.barcode || '').toString().toLowerCase();
                        const name = (p.name || '').toString().toLowerCase();
                        return sku.includes(term) || barcode.includes(term) || name.includes(term);
                    });

                    if (matches.length === 0) {
                        window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Produk tidak ditemukan: ' + code, type: 'warning' } }));
                        this.skuSuggestions = [];
                        // keep the input so user can edit
                        return;
                    }

                    if (matches.length === 1) {
                        this.addToCart(matches[0]);
                        this.skuInput = '';
                        this.skuSuggestions = [];
                        return;
                    }

                    // multiple matches: show suggestions for user to pick
                    this.skuSuggestions = matches.slice(0, 8);
                    this.skuActiveIndex = this.skuSuggestions.length ? 0 : -1;
                },

                handleSkuKey(event) {
                    const key = event.key;
                    if (key === 'ArrowDown') {
                        if (this.skuSuggestions.length === 0) return;
                        this.skuActiveIndex = Math.min(this.skuActiveIndex + 1, this.skuSuggestions.length - 1);
                        this.scrollActiveIntoView();
                        event.preventDefault();
                    } else if (key === 'ArrowUp') {
                        if (this.skuSuggestions.length === 0) return;
                        this.skuActiveIndex = Math.max(this.skuActiveIndex - 1, 0);
                        this.scrollActiveIntoView();
                        event.preventDefault();
                    } else if (key === 'Escape') {
                        this.skuSuggestions = [];
                        this.skuActiveIndex = -1;
                    } else if (key === 'Enter') {
                        if (this.skuActiveIndex >= 0 && this.skuSuggestions[this.skuActiveIndex]) {
                            this.addFromSuggestion(this.skuSuggestions[this.skuActiveIndex]);
                        } else {
                            this.addBySku(this.skuInput);
                        }
                        event.preventDefault();
                    }
                },

                scrollActiveIntoView() {
                    this.$nextTick(() => {
                        try {
                            const list = this.$refs.skuList;
                            if (!list) return;
                            const items = list.querySelectorAll('[data-sku-item]');
                            const active = items[this.skuActiveIndex];
                            if (active) active.scrollIntoView({ block: 'nearest' });
                        } catch (e) { }
                    });
                },

                openScanner(mode) {
                    this.scannerMode = mode;
                    this.isScannerOpen = true;
                    
                    this.$nextTick(() => {
                        if (!this.html5QrcodeScanner) {
                            this.html5QrcodeScanner = new Html5QrcodeScanner(
                                "reader",
                                { fps: 10, qrbox: {width: 250, height: 250} },
                                false
                            );
                        }
                        
                        this.html5QrcodeScanner.render((decodedText, decodedResult) => {
                            // On success
                            this.closeScanner();
                            
                            if (this.scannerMode === 'grosir') {
                                this.skuInput = decodedText;
                                this.addBySku(decodedText);
                            } else {
                                this.search = decodedText;
                                this.fetchProducts();
                                this.addBySku(decodedText);
                                this.search = '';
                                this.fetchProducts();
                            }
                        }, (errorMessage) => {
                            // Handle error (mostly just ignores frames without codes)
                        });
                    });
                },

                closeScanner() {
                    this.isScannerOpen = false;
                    if (this.html5QrcodeScanner) {
                        try {
                            this.html5QrcodeScanner.clear();
                        } catch(e) {}
                    }
                },

                filterSkuSuggestions() {
                    const code = (this.skuInput || '').toString().trim().toLowerCase();
                    if (!code) {
                        this.skuSuggestions = [];
                        return;
                    }

                    const matches = this.allProducts.filter(p => {
                        const sku = (p.sku || '').toString().toLowerCase();
                        const barcode = (p.barcode || '').toString().toLowerCase();
                        const name = (p.name || '').toString().toLowerCase();
                        return sku.includes(code) || barcode.includes(code) || name.includes(code);
                    });

                    this.skuSuggestions = matches.slice(0, 8);
                },

                addFromSuggestion(product) {
                    if (!product) return;
                    this.addToCart(product);
                    this.skuInput = '';
                    this.skuSuggestions = [];
                    this.$nextTick(() => { try { this.$refs.skuInput.focus(); } catch(e){} });
                },

                 addToCart(product) {
                    if (this.trackStock && product.stock <= 0) {
                        window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Stok habis!', type: 'error' } }));
                        return;
                    }

                    // Default unit depends on mode: grosir prefers dynamic non-base unit when available
                    let defaultUnitObj = null;
                    if (product.units && product.units.length > 0) {
                        if (this.cartMode === 'grosir') {
                            defaultUnitObj = product.units.find(u => !u.is_base && u.conversion_factor > 1)
                                             || product.units.find(u => u.is_base)
                                             || product.units[0];
                        } else {
                            defaultUnitObj = product.units.find(u => u.is_base) || product.units[0];
                        }
                    }
                    const defaultUnit = defaultUnitObj ? defaultUnitObj.unit_name : 'pcs';

                    let existingItem = this.cart.find(item => item.id === product.id && item.unit === defaultUnit);

                    // enforce max unique items when adding new unique item
                    if (!existingItem && this.cart.length >= this.maxUniqueItems) {
                        window.dispatchEvent(new CustomEvent('notify', { detail: { message: `Maksimal ${this.maxUniqueItems} item unik di keranjang.`, type: 'warning' } }));
                        return;
                    }

                    if (existingItem) {
                        this.updateQty(this.cart.indexOf(existingItem), 1);
                    } else {
                        // calculate initial price
                        let initialPrice = product.selling_price;
                        if (defaultUnitObj) {
                            initialPrice = defaultUnitObj.price;
                        }

                        if (defaultUnitObj && defaultUnitObj.is_base) {
                            const minWholesale = product.min_wholesale_qty !== null ? product.min_wholesale_qty : 5;
                            if (product.wholesale_price !== null && 1 >= minWholesale) {
                                initialPrice = product.wholesale_price;
                            }
                        }

                        this.cart.push({
                            id: product.id,
                            sku: product.sku || '',
                            name: product.name,
                            image: product.image,
                            price: initialPrice,
                            units: product.units && product.units.length > 0 ? product.units : [
                                { unit_name: 'pcs', conversion_factor: 1, price: product.selling_price, is_base: true }
                            ],
                            retail_price: product.selling_price,
                            wholesale_price: product.wholesale_price || null,
                            min_wholesale_qty: product.min_wholesale_qty !== null ? product.min_wholesale_qty : 5,
                            qty: 1,
                            unit: defaultUnit,
                            stock: product.stock
                        });
                    }
                },

                switchUnit(index, unit) {
                    let item = this.cart[index];
                    if (item.unit === unit) return;

                    // Find if a duplicate item of the same product with the target unit already exists
                    let duplicateIndex = this.cart.findIndex((i, idx) => i.id === item.id && i.unit === unit && idx !== index);

                    const targetUnitObj = item.units.find(u => u.unit_name.toLowerCase() === unit.toLowerCase());
                    const conversionFactor = targetUnitObj ? parseInt(targetUnitObj.conversion_factor) : 1;

                    if (duplicateIndex !== -1) {
                        let duplicateItem = this.cart[duplicateIndex];
                        // Merge quantities
                        let newQty = duplicateItem.qty + item.qty;
                        const neededUnits = newQty * conversionFactor;

                        if (!this.trackStock || neededUnits <= item.stock) {
                            duplicateItem.qty = newQty;
                            if (targetUnitObj && targetUnitObj.is_base && duplicateItem.wholesale_price !== null) {
                                const minQty = duplicateItem.min_wholesale_qty !== null ? duplicateItem.min_wholesale_qty : 5;
                                duplicateItem.price = (newQty >= minQty) ? duplicateItem.wholesale_price : duplicateItem.retail_price;
                            }
                        } else {
                            // Limit to max available stock
                            duplicateItem.qty = Math.floor(item.stock / conversionFactor);
                            if (duplicateItem.qty === 0) duplicateItem.qty = 1;
                            if (targetUnitObj && targetUnitObj.is_base && duplicateItem.wholesale_price !== null) {
                                const minQty = duplicateItem.min_wholesale_qty !== null ? duplicateItem.min_wholesale_qty : 5;
                                duplicateItem.price = (duplicateItem.qty >= minQty) ? duplicateItem.wholesale_price : duplicateItem.retail_price;
                            }
                            window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Beberapa jumlah digabungkan dan dibatasi oleh stok.', type: 'warning' } }));
                        }
                        // Remove the current item since it has been merged into the duplicate
                        this.removeFromCart(index);
                    } else {
                        // Standard unit change
                        item.unit = unit;
                        if (targetUnitObj) {
                            item.price = targetUnitObj.price;
                        }
                        
                        if (targetUnitObj && targetUnitObj.is_base && item.wholesale_price !== null) {
                            const minQty = item.min_wholesale_qty !== null ? item.min_wholesale_qty : 5;
                            item.price = (item.qty >= minQty) ? item.wholesale_price : item.retail_price;
                        }
                        
                        // Recalculate if quantity in target units exceeds stock
                        const totalNeeded = item.qty * conversionFactor;
                        if (this.trackStock && totalNeeded > item.stock) {
                            item.qty = Math.floor(item.stock / conversionFactor);
                            if (item.qty === 0) item.qty = 1;
                            if (targetUnitObj && targetUnitObj.is_base && item.wholesale_price !== null) {
                                const minQty = item.min_wholesale_qty !== null ? item.min_wholesale_qty : 5;
                                item.price = (item.qty >= minQty) ? item.wholesale_price : item.retail_price;
                            }
                        }
                    }
                },

                removeFromCart(index) {
                    this.cart.splice(index, 1);
                    if (this.cart.length === 0) {
                        this.paymentAmount = '';
                        this.displayPaymentAmount = '';
                    }
                },

                updateQty(index, change) {
                    let item = this.cart[index];
                    let newQty = item.qty + change;
                    
                    const targetUnitObj = item.units.find(u => u.unit_name.toLowerCase() === item.unit.toLowerCase());
                    const conversionFactor = targetUnitObj ? parseInt(targetUnitObj.conversion_factor) : 1;
                    const neededUnits = newQty * conversionFactor;

                    if (newQty > 0 && (!this.trackStock || neededUnits <= item.stock)) {
                        item.qty = newQty;
                        if (targetUnitObj && targetUnitObj.is_base && item.wholesale_price !== null) {
                            const minQty = item.min_wholesale_qty !== null ? item.min_wholesale_qty : 5;
                            item.price = (newQty >= minQty) ? item.wholesale_price : item.retail_price;
                        }
                    } else if (this.trackStock && neededUnits > item.stock) {
                        window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Stok tidak mencukupi untuk jumlah ini!', type: 'warning' } }));
                    }
                },

                get totalPrice() {
                    return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
                },

                get changeAmount() {
                    return (this.paymentAmount || 0) - this.totalPrice;
                },

                formatCurrency(value) {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value);
                },

                formatPaymentMethod(method) {
                    if (!method) return '';
                    const m = method.toLowerCase();
                    if (m === 'transfer') return 'Transfer Bank';
                    if (m === 'qris') return 'QRIS';
                    return method.charAt(0).toUpperCase() + method.slice(1).toLowerCase();
                },

                async processPayment() {
                    if (this.cart.length === 0) return;
                    
                    // If not cash, assume exact payment if empty
                    if ((this.paymentMethod || '').toLowerCase() !== 'cash' && !this.paymentAmount) {
                        this.paymentAmount = this.totalPrice;
                    }

                    const payment = parseInt(this.paymentAmount || 0);

                    if (payment === 0) {
                        window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Masukkan nominal pembayaran terlebih dahulu!', type: 'warning' } }));
                        return;
                    }

                    if (payment < this.totalPrice) {
                        window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Pembayaran kurang!', type: 'error' } }));
                        return;
                    }

                    this.isProcessingPayment = true;

                    try {
                        const response = await fetch('{{ route("pos.store") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                cart: this.cart.map(item => ({
                                    id: item.id,
                                    qty: item.qty,
                                    unit: item.unit
                                })),
                                payment_amount: this.paymentAmount,
                                customer_id: this.customerId,
                                payment_method: this.paymentMethod
                            })
                        });

                        const result = await response.json();

                        if (result.success) {
                             // Update local stock state with mapping to ensure reactivity
                            if (this.trackStock) {
                                this.allProducts = this.allProducts.map(p => {
                                    let item = this.cart.find(i => i.id === p.id);
                                    if (item) {
                                        const targetUnitObj = item.units.find(u => u.unit_name.toLowerCase() === item.unit.toLowerCase());
                                        const conversionFactor = targetUnitObj ? parseInt(targetUnitObj.conversion_factor) : 1;
                                        const deduct = item.qty * conversionFactor;
                                        p.stock -= deduct;
                                    }
                                    return p;
                                });
                            }

                            this.lastTransaction = {
                                orderId: result.order_id,
                                invoiceNumber: result.invoice_number,
                                receiptUrl: `/orders/${result.order_id}`,
                                total: this.totalPrice,
                                paymentMethod: this.paymentMethod,
                                paymentAmount: this.paymentAmount,
                                changeAmount: this.changeAmount
                            };
                            
                            // Update recent orders history
                            let customerName = this.selectedCustomer ? this.selectedCustomer.name : 'Umum';
                            let itemC = this.cart.length;
                            this.recentOrders.unshift({
                                id: result.order_id,
                                invoice_number: result.invoice_number,
                                created_at: new Date().toISOString(),
                                total_price: this.totalPrice,
                                customer: { name: customerName },
                                items: this.cart
                            });
                            if(this.recentOrders.length > 10) this.recentOrders.pop();
                            
                            this.isPaymentSuccessOpen = true;
                            
                        } else {
                            window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Gagal: ' + result.message, type: 'error' } }));
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Terjadi kesalahan sistem.', type: 'error' } }));
                    } finally {
                        this.isProcessingPayment = false;
                    }
                },
                
                async printReceipt() {
                    if (this.isBluetoothPrinterConnected && this.bluetoothCharacteristic) {
                        try {
                            await this.printReceiptViaBluetooth();
                        } catch (err) {
                            console.error('Bluetooth printing failed, fallback to print dialog:', err);
                            if (this.lastTransaction && this.lastTransaction.receiptUrl) {
                                const iframe = document.getElementById('print-iframe');
                                iframe.src = this.lastTransaction.receiptUrl;
                            }
                        }
                    } else if (this.lastTransaction && this.lastTransaction.receiptUrl) {
                        const iframe = document.getElementById('print-iframe');
                        iframe.src = this.lastTransaction.receiptUrl;
                    }
                },

                async toggleBluetoothPrinter() {
                    if (this.isBluetoothPrinterConnected) {
                        try {
                            if (this.bluetoothDevice && this.bluetoothDevice.gatt.connected) {
                                this.bluetoothDevice.gatt.disconnect();
                            }
                        } catch (e) {}
                        this.isBluetoothPrinterConnected = false;
                        this.bluetoothDevice = null;
                        this.bluetoothCharacteristic = null;
                        window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Printer Bluetooth terputus.', type: 'info' } }));
                    } else {
                        try {
                            const device = await navigator.bluetooth.requestDevice({
                                acceptAllDevices: true,
                                optionalServices: [
                                    '000018f0-0000-1000-8000-00805f9b34fb',
                                    '0000ffe0-0000-1000-8000-00805f9b34fb'
                                ]
                            });

                            window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Menghubungkan ke ' + device.name + '...', type: 'info' } }));

                            const server = await device.gatt.connect();
                            let service, characteristic;

                            try {
                                service = await server.getPrimaryService('000018f0-0000-1000-8000-00805f9b34fb');
                                characteristic = await service.getCharacteristic('00002af1-0000-1000-8000-00805f9b34fb');
                            } catch (e) {
                                service = await server.getPrimaryService('0000ffe0-0000-1000-8000-00805f9b34fb');
                                characteristic = await service.getCharacteristic('0000ffe1-0000-1000-8000-00805f9b34fb');
                            }

                            this.bluetoothDevice = device;
                            this.bluetoothCharacteristic = characteristic;
                            this.isBluetoothPrinterConnected = true;

                            device.addEventListener('gattserverdisconnected', () => {
                                this.isBluetoothPrinterConnected = false;
                                this.bluetoothDevice = null;
                                this.bluetoothCharacteristic = null;
                                window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Printer Bluetooth terputus.', type: 'warning' } }));
                            });

                            window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Printer Bluetooth terhubung: ' + device.name, type: 'success' } }));
                        } catch (err) {
                            console.error('Bluetooth connection failed:', err);
                            window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Gagal menghubungkan printer: ' + err.message, type: 'error' } }));
                        }
                    }
                },

                async printReceiptViaBluetooth() {
                    if (!this.bluetoothCharacteristic) return;

                    const encoder = new TextEncoder();
                    const chunks = [];

                    const addCommand = (bytes) => {
                        chunks.push(new Uint8Array(bytes));
                    };

                    const addText = (text) => {
                        chunks.push(encoder.encode(text + '\n'));
                    };

                    const formatLine = (left, right) => {
                        const maxChars = 32;
                        const spaceCount = maxChars - left.length - right.length;
                        if (spaceCount > 0) {
                            return left + ' '.repeat(spaceCount) + right;
                        }
                        return left + ' ' + right;
                    };

                    // Initialize
                    addCommand([0x1B, 0x40]);

                    // Logo (Web Bluetooth)
                    @if(\App\Models\Setting::get('show_logo_on_receipt', '1') == '1' && \App\Models\Setting::get('store_logo'))
                        try {
                            const logoUrl = '{{ asset('storage/' . \App\Models\Setting::get('store_logo')) }}';
                            const logoBytes = await getLogoEscPosBytes(logoUrl, 192);
                            if (logoBytes) {
                                addCommand([0x1B, 0x61, 1]); // Center logo
                                chunks.push(logoBytes);
                                addCommand([0x1B, 0x61, 0]); // Reset to Left
                            }
                        } catch (e) {
                            console.error('Failed to print Bluetooth logo:', e);
                        }
                    @endif

                    // Header
                    addCommand([0x1B, 0x61, 1]); // Center
                    addCommand([0x1B, 0x45, 1]); // Bold
                    addCommand([0x1B, 0x21, 0x10]); // Double height
                    chunks.push(encoder.encode('{{ strtoupper(\App\Models\Setting::get('store_name', 'MINIMART POS')) }}\n'));
                    addCommand([0x1B, 0x21, 0x00]); // Normal size
                    addCommand([0x1B, 0x45, 0]); // Bold Off
                    @if(\App\Models\Setting::get('store_address'))
                        addText('{{ \App\Models\Setting::get('store_address') }}');
                    @endif
                    @if(\App\Models\Setting::get('store_phone'))
                        addText('Telp: {{ \App\Models\Setting::get('store_phone') }}');
                    @endif
                    @if(\App\Models\Setting::get('receipt_header'))
                        addText('{{ \App\Models\Setting::get('receipt_header') }}');
                    @endif
                    
                    addCommand([0x1B, 0x61, 0]); // Left
                    addText('--------------------------------');

                    // Meta
                    const now = new Date();
                    const dateStr = now.toLocaleDateString('id-ID', {day: '2-digit', month: '2-digit', year: '2-digit'}) + ' ' + now.toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'}) + ' WITA';
                    const invStr = (this.lastTransaction.invoiceNumber || 'INV').slice(-6);
                    addText(formatLine(dateStr, invStr));
                    const kasirName = this.userName.substring(0, 15);
                    if (this.selectedCustomer) {
                        const pelangganName = this.selectedCustomer.name.substring(0, 15);
                        addText(formatLine(kasirName, pelangganName));
                    } else {
                        addText(kasirName);
                    }
                    addText('--------------------------------');

                    // Items
                    this.cart.forEach(item => {
                        const itemNameFormatted = item.name.toLowerCase().replace(/\b\w/g, c => c.toUpperCase()).substring(0, 32);
                        addText(itemNameFormatted);
                        const unitLabel = item.unit ? ' ' + item.unit.charAt(0).toUpperCase() + item.unit.slice(1).toLowerCase() : '';
                        const qtyPrice = item.qty + unitLabel + ' x ' + new Intl.NumberFormat('id-ID').format(item.price);
                        const subtotal = new Intl.NumberFormat('id-ID').format(item.price * item.qty);
                        addText(formatLine(qtyPrice, subtotal));
                    });
                    addText('--------------------------------');

                    // Totals
                    const totalFormatted = new Intl.NumberFormat('id-ID').format(this.totalPrice);
                    addCommand([0x1B, 0x45, 1]); // Bold
                    addText(formatLine('Total:', 'Rp. ' + totalFormatted));
                    addCommand([0x1B, 0x45, 0]); // Bold Off
                    
                    let paymentMethodName = '';
                    if (this.paymentMethod.toLowerCase() === 'transfer') {
                        paymentMethodName = 'Transfer Bank';
                    } else if (this.paymentMethod.toLowerCase() === 'qris') {
                        paymentMethodName = 'QRIS';
                    } else {
                        paymentMethodName = this.paymentMethod.charAt(0).toUpperCase() + this.paymentMethod.slice(1).toLowerCase();
                    }
                    const paidFormatted = new Intl.NumberFormat('id-ID').format(this.paymentAmount);
                    const changeFormatted = new Intl.NumberFormat('id-ID').format(this.changeAmount);
                    
                    addText(formatLine(paymentMethodName + ':', 'Rp. ' + paidFormatted));
                    if (this.paymentMethod.toLowerCase() === 'cash') {
                        addText(formatLine('Kembalian:', 'Rp. ' + changeFormatted));
                    }
                    addText('--------------------------------');

                    // Footer
                    addCommand([0x1B, 0x61, 1]); // Center
                    addText('TERIMA KASIH');
                    @if(\App\Models\Setting::get('receipt_footer'))
                        @foreach(explode("\n", str_replace("\r", "", \App\Models\Setting::get('receipt_footer'))) as $line)
                            addText('{{ $line }}');
                        @endforeach
                    @else
                        addText('Barang yang sudah dibeli');
                        addText('tidak dapat ditukar/dikembalikan');
                    @endif
                    addText('\n\n\n');
                    
                    // Cut paper
                    addCommand([0x1D, 0x56, 66, 0]);

                    let totalLength = chunks.reduce((acc, chunk) => acc + chunk.length, 0);
                    let resultBytes = new Uint8Array(totalLength);
                    let offset = 0;
                    chunks.forEach(chunk => {
                        resultBytes.set(chunk, offset);
                        offset += chunk.length;
                    });

                    const chunkSize = 20;
                    for (let i = 0; i < resultBytes.length; i += chunkSize) {
                        const chunk = resultBytes.slice(i, i + chunkSize);
                        await this.bluetoothCharacteristic.writeValue(chunk);
                        await new Promise(resolve => setTimeout(resolve, 30));
                    }
                },

                // Cetak struk dari riwayat via Bluetooth atau popup
                async printHistoryOrder(order) {
                    if (this.isBluetoothPrinterConnected && this.bluetoothCharacteristic) {
                        try {
                            await this.printHistoryOrderViaBluetooth(order);
                        } catch (err) {
                            console.error('Bluetooth print failed, fallback to popup:', err);
                            window.open('/orders/' + order.id, 'cetak-struk', 'width=500,height=700,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=0');
                        }
                    } else {
                        window.open('/orders/' + order.id, 'cetak-struk', 'width=500,height=700,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=0');
                    }
                },

                async printHistoryOrderViaBluetooth(order) {
                    if (!this.bluetoothCharacteristic) return;

                    const encoder = new TextEncoder();
                    const chunks = [];

                    const addCommand = (bytes) => { chunks.push(new Uint8Array(bytes)); };
                    const addText = (text) => { chunks.push(encoder.encode(text + '\n')); };
                    const formatLine = (left, right) => {
                        const maxChars = 32;
                        const spaceCount = maxChars - left.length - right.length;
                        return spaceCount > 0 ? left + ' '.repeat(spaceCount) + right : left + ' ' + right;
                    };
                    const fmtNum = (n) => new Intl.NumberFormat('id-ID').format(n || 0);

                    // Initialize
                    addCommand([0x1B, 0x40]);

                    // Logo
                    @if(\App\Models\Setting::get('show_logo_on_receipt', '1') == '1' && \App\Models\Setting::get('store_logo'))
                        try {
                            const logoUrl = '{{ asset('storage/' . \App\Models\Setting::get('store_logo')) }}';
                            const logoBytes = await getLogoEscPosBytes(logoUrl, 192);
                            if (logoBytes) {
                                addCommand([0x1B, 0x61, 1]);
                                chunks.push(logoBytes);
                                addCommand([0x1B, 0x61, 0]);
                            }
                        } catch (e) {}
                    @endif

                    // Header
                    addCommand([0x1B, 0x61, 1]);
                    addCommand([0x1B, 0x45, 1]);
                    addCommand([0x1B, 0x21, 0x10]);
                    chunks.push(encoder.encode('{{ strtoupper(\App\Models\Setting::get('store_name', 'MINIMART POS')) }}\n'));
                    addCommand([0x1B, 0x21, 0x00]);
                    addCommand([0x1B, 0x45, 0]);
                    @if(\App\Models\Setting::get('store_address'))
                        addText('{{ \App\Models\Setting::get('store_address') }}');
                    @endif
                    @if(\App\Models\Setting::get('store_phone'))
                        addText('Telp: {{ \App\Models\Setting::get('store_phone') }}');
                    @endif
                    @if(\App\Models\Setting::get('receipt_header'))
                        addText('{{ \App\Models\Setting::get('receipt_header') }}');
                    @endif

                    addCommand([0x1B, 0x61, 0]);
                    addText('--------------------------------');

                    // Meta — tanggal & invoice
                    const orderDate = new Date(order.created_at);
                    const dateStr = orderDate.toLocaleDateString('id-ID', {day:'2-digit',month:'2-digit',year:'2-digit'})
                                  + ' ' + orderDate.toLocaleTimeString('id-ID', {hour:'2-digit',minute:'2-digit'})
                                  + ' WITA';
                    const invStr = (order.invoice_number || '').slice(-6);
                    addText(formatLine(dateStr, invStr));
                    const kasirName = (this.userName || '').substring(0, 15);
                    const pelangganName = (order.customer?.name || 'Umum').substring(0, 15);
                    addText(formatLine(kasirName, pelangganName));
                    addText('--------------------------------');

                    // Items
                    const items = order.items || [];
                    items.forEach(item => {
                        const name = (item.product?.name || item.name || '').toLowerCase()
                                        .replace(/\b\w/g, c => c.toUpperCase()).substring(0, 32);
                        addText(name);
                        const qty   = item.quantity || item.qty || 0;
                        const unit  = item.unit ? ' ' + item.unit.charAt(0).toUpperCase() + item.unit.slice(1).toLowerCase() : '';
                        const price = item.price || 0;
                        const qtyStr = qty + unit + ' x ' + fmtNum(price);
                        const subStr = fmtNum(qty * price);
                        addText(formatLine(qtyStr, subStr));
                    });
                    addText('--------------------------------');

                    // Totals
                    const total = order.total_price || 0;
                    addCommand([0x1B, 0x45, 1]);
                    addText(formatLine('Total:', 'Rp. ' + fmtNum(total)));
                    addCommand([0x1B, 0x45, 0]);

                    const pm = (order.payment_method || 'cash').toLowerCase();
                    const pmName = pm === 'transfer' ? 'Transfer Bank' : pm === 'qris' ? 'QRIS' : 'Cash';
                    const paid   = order.payment_amount || total;
                    const change = order.change_amount  || 0;
                    addText(formatLine(pmName + ':', 'Rp. ' + fmtNum(paid)));
                    if (pm === 'cash') {
                        addText(formatLine('Kembalian:', 'Rp. ' + fmtNum(change)));
                    }
                    addText('--------------------------------');

                    // Footer
                    addCommand([0x1B, 0x61, 1]);
                    addText('TERIMA KASIH');
                    @if(\App\Models\Setting::get('receipt_footer'))
                        @foreach(explode("\n", str_replace("\r", "", \App\Models\Setting::get('receipt_footer'))) as $line)
                            addText('{{ $line }}');
                        @endforeach
                    @else
                        addText('Barang yang sudah dibeli');
                        addText('tidak dapat ditukar/dikembalikan');
                    @endif
                    addText('\n\n\n');
                    addCommand([0x1D, 0x56, 66, 0]); // Cut paper

                    // Send to printer
                    let totalLength = chunks.reduce((acc, c) => acc + c.length, 0);
                    let resultBytes = new Uint8Array(totalLength);
                    let offset = 0;
                    chunks.forEach(chunk => { resultBytes.set(chunk, offset); offset += chunk.length; });

                    const chunkSize = 20;
                    for (let i = 0; i < resultBytes.length; i += chunkSize) {
                        await this.bluetoothCharacteristic.writeValue(resultBytes.slice(i, i + chunkSize));
                        await new Promise(resolve => setTimeout(resolve, 30));
                    }

                    window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Struk berhasil dicetak via Bluetooth!', type: 'success' } }));
                },
                
                newTransaction() {
                    this.isPaymentSuccessOpen = false;
                    this.cart = [];
                    this.paymentAmount = '';
                    this.paymentMethod = '{{ $paymentMethods->where('method_name', 'cash')->isNotEmpty() ? 'cash' : ($paymentMethods->first()->method_name ?? 'cash') }}';
                    this.customerId = ''; 
                    this.selectedCustomer = null;
                    this.search = '';
                    this.fetchProducts();
                },

                get filteredCustomers() {
                    if (!this.customerSearch) return this.customers;
                    const searchLower = this.customerSearch.toLowerCase();
                    return this.customers.filter(c => 
                        (c.name || '').toLowerCase().includes(searchLower) || 
                        (c.phone || '').includes(searchLower)
                    );
                },
                
                selectCustomer(customer) {
                    this.selectedCustomer = customer;
                    this.customerId = customer.id;
                    this.isCustomerModalOpen = false;
                    window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Pelanggan ' + customer.name + ' terpilih.', type: 'success' } }));
                },
                
                clearCustomer() {
                    this.selectedCustomer = null;
                    this.customerId = '';
                    window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Pelanggan dikosongkan (Umum).', type: 'info' } }));
                },
                
                async submitNewCustomer() {
                    if (!this.newCustomer.name) {
                        window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Nama pelanggan wajib diisi!', type: 'warning' } }));
                        return;
                    }
                    
                    this.isSavingCustomer = true;
                    
                    try {
                        const response = await fetch('{{ route("customers.store") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify(this.newCustomer)
                        });
                        
                        const result = await response.json();
                        
                        if (result.success) {
                            const addedCustomer = result.customer;
                            this.customers.push(addedCustomer);
                            this.selectCustomer(addedCustomer);
                            
                            // Reset form
                            this.newCustomer = { name: '', phone: '', email: '', address: '' };
                            this.customerSearch = '';
                        } else {
                            window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Gagal mendaftarkan pelanggan: ' + result.message, type: 'error' } }));
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Gagal menghubungkan ke server.', type: 'error' } }));
                    } finally {
                        this.isSavingCustomer = false;
                    }
                }
            }
        }
    </script>

    <style>
        .scrollbar-hide::-webkit-scrollbar { display: none !important; }
        .scrollbar-hide { -ms-overflow-style: none !important; scrollbar-width: none !important; }
    </style>

    <x-profile-edit-modal />

    <!-- Barcode Scanner Modal -->
    <div x-show="isScannerOpen" class="fixed inset-0 z-50 flex items-center justify-center" x-cloak>
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="closeScanner()"></div>
        <div class="bg-white dark:bg-[#11141D] rounded-3xl shadow-2xl w-full max-w-md mx-4 relative z-10 overflow-hidden"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-90 translate-y-4">
            
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center">
                <h3 class="text-lg font-black text-gray-800 dark:text-white">Scan Barcode</h3>
                <button @click="closeScanner()" class="text-gray-400 hover:text-red-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <div class="p-6">
                <div id="reader" class="w-full bg-gray-50 dark:bg-[#0B0E14] rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-800"></div>
                <p class="text-center text-xs font-medium text-gray-400 dark:text-gray-500 mt-4">Arahkan kamera ke barcode produk</p>
            </div>
        </div>
    </div>

    <!-- Customer/Member Modal -->
    <div x-show="isCustomerModalOpen" class="fixed inset-0 z-50 flex items-center justify-center" x-cloak>
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="isCustomerModalOpen = false"></div>
        <div class="bg-white dark:bg-[#11141D] rounded-3xl shadow-2xl w-full max-w-3xl mx-4 relative z-10 overflow-hidden flex flex-col max-h-[90vh]"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-90 translate-y-4">
            
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center bg-gray-50/50 dark:bg-[#161924]/50">
                <div>
                    <h3 class="text-lg font-black text-gray-800 dark:text-white">Kelola Pelanggan (Member)</h3>
                    <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-0.5">Pilih member aktif atau daftarkan member baru</p>
                </div>
                <button @click="isCustomerModalOpen = false" class="text-gray-400 hover:text-red-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <div class="flex-grow overflow-y-auto p-6 grid grid-cols-1 md:grid-cols-5 gap-6 scrollbar-hide">
                <!-- Left: List and Search -->
                <div class="md:col-span-3 flex flex-col h-full border-r border-gray-100 dark:border-gray-800 pr-0 md:pr-6">
                    <div class="mb-4">
                        <label class="block text-xs font-black text-gray-800 dark:text-gray-300 uppercase tracking-widest mb-1.5">Cari Pelanggan</label>
                        <div class="relative">
                            <input type="text" x-model="customerSearch" placeholder="Cari nama atau No. HP..." 
                                   class="w-full pl-10 pr-4 py-2.5 text-sm rounded-xl border border-gray-200 dark:border-gray-800 focus:outline-none focus:ring-2 focus:ring-[#1e66f5]/20 focus:border-[#1e66f5] bg-gray-50 dark:bg-[#0B0E14] dark:text-white transition-all">
                            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>
                    
                    <div class="flex-grow overflow-y-auto max-h-[300px] space-y-2 pr-1 scrollbar-hide">
                        <template x-for="c in filteredCustomers" :key="c.id">
                            <div @click="selectCustomer(c)" 
                                 class="p-4 bg-gray-50 dark:bg-[#0B0E14] border border-gray-100 dark:border-gray-800 rounded-2xl cursor-pointer hover:border-[#1e66f5] dark:hover:border-[#1e66f5] hover:bg-blue-50/20 dark:hover:bg-blue-500/5 transition-all flex items-center justify-between group"
                                 :class="{'border-[#1e66f5] dark:border-[#1e66f5] bg-blue-50/20 dark:bg-blue-500/5': selectedCustomer && selectedCustomer.id === c.id}">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-500/10 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold text-sm">
                                        <span x-text="c.name.substring(0, 2).toUpperCase()"></span>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-800 dark:text-white" x-text="c.name"></h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5" x-text="c.phone || 'Tidak ada No. HP'"></p>
                                    </div>
                                </div>
                                <span class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-opacity">Pilih &raquo;</span>
                            </div>
                        </template>
                        <template x-if="filteredCustomers.length === 0">
                            <div class="text-center py-8 text-gray-500 dark:text-gray-450">
                                <svg class="w-12 h-12 mx-auto mb-2 text-gray-300 dark:text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <p class="text-sm font-bold">Pelanggan tidak ditemukan</p>
                            </div>
                        </template>
                    </div>
                </div>
                
                <!-- Right: Register Form -->
                <div class="md:col-span-2 flex flex-col justify-between">
                    <div>
                        <h4 class="text-xs font-black text-gray-800 dark:text-gray-200 uppercase tracking-widest mb-4">Daftar Member Baru</h4>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-black text-gray-800 dark:text-gray-300 uppercase tracking-widest mb-1.5 ml-1">Nama Lengkap</label>
                                <input type="text" x-model="newCustomer.name" placeholder="Nama Pelanggan" required
                                       class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-800 focus:outline-none focus:ring-2 focus:ring-[#1e66f5]/20 focus:border-[#1e66f5] bg-gray-50 dark:bg-[#0B0E14] dark:text-white transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-black text-gray-800 dark:text-gray-300 uppercase tracking-widest mb-1.5 ml-1">Nomor HP</label>
                                <input type="text" x-model="newCustomer.phone" placeholder="Contoh: 08123456789"
                                       class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-800 focus:outline-none focus:ring-2 focus:ring-[#1e66f5]/20 focus:border-[#1e66f5] bg-gray-50 dark:bg-[#0B0E14] dark:text-white transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-black text-gray-800 dark:text-gray-300 uppercase tracking-widest mb-1.5 ml-1">Alamat</label>
                                <textarea x-model="newCustomer.address" placeholder="Alamat lengkap..." rows="2"
                                       class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-800 focus:outline-none focus:ring-2 focus:ring-[#1e66f5]/20 focus:border-[#1e66f5] bg-gray-50 dark:bg-[#0B0E14] dark:text-white transition-all"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <button type="button" @click="submitNewCustomer()" :disabled="isSavingCustomer"
                                class="w-full py-3 bg-[#1e66f5] hover:bg-blue-600 disabled:bg-gray-400 text-white rounded-xl font-bold text-sm shadow-lg shadow-blue-100 dark:shadow-none flex items-center justify-center gap-2 transition-all">
                            <template x-if="isSavingCustomer">
                                <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </template>
                            <span x-text="isSavingCustomer ? 'Menyimpan...' : 'Daftar & Pilih'"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <x-opsi-kasir-modal :recent-orders="$recentOrders" />

    <!-- Payment Success Modal -->
    <div x-show="isPaymentSuccessOpen" class="fixed inset-0 z-50 flex items-center justify-center" x-cloak>
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="bg-white dark:bg-[#11141D] rounded-3xl shadow-2xl w-full max-w-sm mx-4 relative z-10 overflow-hidden text-center"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-90 translate-y-4">
             
             <div class="p-8">
                <!-- Success Icon -->
                <div class="w-20 h-20 bg-green-50 dark:bg-green-500/10 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                </div>
                
                <h3 class="text-xl font-black text-gray-800 dark:text-white mb-2">Transaksi Berhasil!</h3>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-6">Pembayaran telah diterima dan disimpan.</p>

                <!-- Transaction Details -->
                <div class="bg-gray-50 dark:bg-[#0B0E14] rounded-2xl p-4 mb-8 text-left border border-gray-100 dark:border-gray-800">
                    <div class="flex justify-between mb-2">
                        <span class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">Total Belanja</span>
                        <span class="text-xs font-black text-gray-800 dark:text-gray-200" x-text="formatCurrency(lastTransaction.total)"></span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest" x-text="((lastTransaction.paymentMethod || '').toLowerCase() === 'cash') ? 'Nominal Tunai' : 'Metode Bayar'"></span>
                        <span class="text-xs font-black text-gray-800 dark:text-gray-200" x-text="((lastTransaction.paymentMethod || '').toLowerCase() === 'cash') ? formatCurrency(lastTransaction.paymentAmount) : formatPaymentMethod(lastTransaction.paymentMethod)"></span>
                    </div>
                    <template x-if="(lastTransaction.paymentMethod || '').toLowerCase() === 'cash'">
                        <div class="flex justify-between pt-2 mt-2 border-t border-gray-200 dark:border-gray-700">
                            <span class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Kembalian</span>
                            <span class="text-sm font-black text-[#1e66f5]" x-text="formatCurrency(lastTransaction.changeAmount)"></span>
                        </div>
                    </template>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col gap-3">
                    <button @click="printReceipt()" class="w-full flex items-center justify-center gap-2 py-3 bg-white dark:bg-[#11141D] border-2 border-[#1e66f5] rounded-xl text-sm font-black text-[#1e66f5] hover:bg-blue-50 dark:hover:bg-[#1e66f5] dark:hover:text-white transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        Cetak Struk
                    </button>
                    <button @click="newTransaction()" class="w-full py-3 bg-[#1e66f5] rounded-xl text-sm font-black text-white hover:bg-blue-600 transition-all shadow-lg shadow-blue-100 dark:shadow-none">
                        Transaksi Baru
                    </button>
                </div>
             </div>
        </div>
    </div>
    </div> <!-- End of posSystem wrapper -->

    <!-- Hidden Iframe for Printing -->
    <iframe id="print-iframe" style="display:none;"></iframe>
</x-pos-layout>
