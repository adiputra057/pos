@props(['recentOrders' => []])
<x-modal name="opsi-kasir" :show="false" focusable>
    <div class="bg-white dark:bg-[#11141D] transition-colors duration-300 rounded-2xl overflow-hidden"
         x-data="{ activeTab: 'usaha' }">

        {{-- Header --}}
        <div class="relative px-7 pt-7 pb-5 border-b border-gray-100 dark:border-gray-800">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-[#1e66f5]/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-[#1e66f5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-black text-gray-900 dark:text-white tracking-tight leading-none">
                            Pengaturan
                        </h2>
                        <p class="text-[11px] text-gray-400 dark:text-gray-500 font-medium mt-0.5">Kelola pengaturan kasir dan perangkat keras</p>
                    </div>
                </div>
                <button x-on:click="$dispatch('close-modal', 'opsi-kasir')"
                        class="w-8 h-8 flex items-center justify-center rounded-xl text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-all">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Tab Navigation --}}
            <div class="flex gap-0 mt-5 border-b border-gray-100 dark:border-gray-800 -mb-5">
                <button @click="activeTab = 'usaha'"
                        :class="activeTab === 'usaha'
                            ? 'text-[#1e66f5] border-b-2 border-[#1e66f5] bg-transparent font-black'
                            : 'text-gray-400 dark:text-gray-500 border-b-2 border-transparent hover:text-gray-600 dark:hover:text-gray-300 font-bold'"
                        class="flex items-center gap-2 px-5 py-3 text-xs uppercase tracking-widest transition-all duration-200 relative">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Tipe Usaha
                </button>
                <button @click="activeTab = 'perangkat'"
                        :class="activeTab === 'perangkat'
                            ? 'text-[#1e66f5] border-b-2 border-[#1e66f5] bg-transparent font-black'
                            : 'text-gray-400 dark:text-gray-500 border-b-2 border-transparent hover:text-gray-600 dark:hover:text-gray-300 font-bold'"
                        class="flex items-center gap-2 px-5 py-3 text-xs uppercase tracking-widest transition-all duration-200">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1z" />
                    </svg>
                    Perangkat
                </button>
                <button @click="activeTab = 'riwayat'"
                        :class="activeTab === 'riwayat'
                            ? 'text-[#1e66f5] border-b-2 border-[#1e66f5] bg-transparent font-black'
                            : 'text-gray-400 dark:text-gray-500 border-b-2 border-transparent hover:text-gray-600 dark:hover:text-gray-300 font-bold'"
                        class="flex items-center gap-2 px-5 py-3 text-xs uppercase tracking-widest transition-all duration-200">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Riwayat
                </button>
            </div>
        </div>

        {{-- Tab Content --}}
        <div class="px-7 py-6">

            {{-- ── TIPE USAHA TAB ── --}}
            <div x-show="activeTab === 'usaha'"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0">

                <div class="mb-5">
                    <h3 class="text-sm font-black text-gray-800 dark:text-gray-200">Pilih Tipe Usaha</h3>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Sesuaikan tampilan kasir dengan jenis bisnis Anda.</p>
                </div>

                <div class="space-y-4">
                    <label class="flex items-center justify-between p-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-[#0B0E14] cursor-pointer hover:border-[#1e66f5] dark:hover:border-[#1e66f5] transition-all"
                           :class="businessMode === 'fnb' ? 'border-[#1e66f5] ring-1 ring-[#1e66f5] dark:border-[#1e66f5] dark:ring-[#1e66f5]' : ''">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-blue-50 dark:bg-[#11141D] flex items-center justify-center text-[#1e66f5]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z"></path></svg>
                            </div>
                            <div>
                                <span class="block text-sm font-bold text-gray-800 dark:text-gray-200">F&B Saja</span>
                                <span class="block text-xs font-medium text-gray-500 dark:text-gray-400 mt-0.5">Tampilan khusus untuk makanan & minuman</span>
                            </div>
                        </div>
                        <input type="radio" x-model="businessMode" value="fnb" class="text-[#1e66f5] focus:ring-[#1e66f5] border-gray-300 w-5 h-5">
                    </label>

                    <label class="flex items-center justify-between p-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-[#0B0E14] cursor-pointer hover:border-[#1e66f5] dark:hover:border-[#1e66f5] transition-all"
                           :class="businessMode === 'grosir' ? 'border-[#1e66f5] ring-1 ring-[#1e66f5] dark:border-[#1e66f5] dark:ring-[#1e66f5]' : ''">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-blue-50 dark:bg-[#11141D] flex items-center justify-center text-[#1e66f5]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                            <div>
                                <span class="block text-sm font-bold text-gray-800 dark:text-gray-200">Grosir / Retail</span>
                                <span class="block text-xs font-medium text-gray-500 dark:text-gray-400 mt-0.5">Tampilan untuk toko barang umum</span>
                            </div>
                        </div>
                        <input type="radio" x-model="businessMode" value="grosir" class="text-[#1e66f5] focus:ring-[#1e66f5] border-gray-300 w-5 h-5">
                    </label>
                </div>
            </div>

            {{-- ── PERANGKAT TAB ── --}}
            <div x-show="activeTab === 'perangkat'"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-cloak>

                <div class="mb-5">
                    <h3 class="text-sm font-black text-gray-800 dark:text-gray-200">Pengaturan Perangkat</h3>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Hubungkan perangkat eksternal ke sistem kasir Anda.</p>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-[#0B0E14]">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-blue-50 dark:bg-[#11141D] flex items-center justify-center text-[#1e66f5]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                            </div>
                            <div>
                                <span class="block text-sm font-bold text-gray-800 dark:text-gray-200">Barcode Scanner</span>
                                <span class="block text-xs font-medium text-gray-500 dark:text-gray-400 mt-0.5">Aktifkan pemindaian via kamera</span>
                            </div>
                        </div>
                        <button @click="isScannerActive = !isScannerActive" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none" :class="isScannerActive ? 'bg-[#1e66f5]' : 'bg-gray-200 dark:bg-gray-700'">
                            <span class="inline-block h-5 w-5 transform rounded-full bg-white transition-transform" :class="isScannerActive ? 'translate-x-5' : 'translate-x-1'"></span>
                        </button>
                    </div>

                    <div class="flex items-center justify-between p-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-[#0B0E14]">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-blue-50 dark:bg-[#11141D] flex items-center justify-center text-[#1e66f5]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            </div>
                            <div>
                                <span class="block text-sm font-bold text-gray-800 dark:text-gray-200">Printer Struk</span>
                                <span class="block text-xs font-medium text-gray-500 dark:text-gray-400 mt-0.5" x-text="isBluetoothPrinterConnected ? 'Printer Bluetooth Terhubung' : 'Hubungkan printer Bluetooth'"></span>
                            </div>
                        </div>
                        <button @click="toggleBluetoothPrinter()" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none" :class="isBluetoothPrinterConnected ? 'bg-[#1e66f5]' : 'bg-gray-200 dark:bg-gray-700'">
                            <span class="inline-block h-5 w-5 transform rounded-full bg-white transition-transform" :class="isBluetoothPrinterConnected ? 'translate-x-5' : 'translate-x-1'"></span>
                        </button>
                    </div>
                </div>
            </div>
            {{-- ── RIWAYAT TAB ── --}}
            <div x-show="activeTab === 'riwayat'"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-cloak>

                <div class="mb-5">
                    <h3 class="text-sm font-black text-gray-800 dark:text-gray-200">Riwayat Singkat Hari Ini</h3>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Daftar 10 transaksi terakhir yang Anda lakukan hari ini.</p>
                </div>

                <div class="space-y-3 max-h-80 overflow-y-auto pr-2 scrollbar-hide">
                    <template x-for="order in recentOrders" :key="order.id">
                        <div x-data="{ expanded: false }" class="border border-gray-100 dark:border-gray-800 rounded-xl overflow-hidden bg-gray-50/50 dark:bg-[#0B0E14] transition-all">
                            <!-- Header / Clickable Area -->
                            <div @click="expanded = !expanded" class="p-4 flex flex-col sm:flex-row gap-3 justify-between items-start sm:items-center cursor-pointer hover:bg-gray-100/50 dark:hover:bg-gray-800/50 transition-colors">
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-xs font-black text-[#1e66f5] bg-blue-50 dark:bg-blue-500/10 px-2 py-0.5 rounded-md" x-text="'#' + order.invoice_number"></span>
                                        <span class="text-[10px] font-bold text-gray-400" x-text="new Date(order.created_at).toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'})"></span>
                                    </div>
                                    <p class="text-xs font-medium text-gray-600 dark:text-gray-300">
                                        Pelanggan: <span class="font-bold" x-text="order.customer?.name || 'Umum'"></span> &bull; <span x-text="order.items ? order.items.length : 0"></span> item
                                    </p>
                                </div>
                                <div class="flex items-center gap-4 w-full sm:w-auto justify-between sm:justify-end">
                                    <div class="text-left sm:text-right">
                                        <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total</span>
                                        <span class="block text-sm font-black text-gray-800 dark:text-gray-200" x-text="formatCurrency(order.total_price)"></span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button @click.stop="printHistoryOrder(order)" class="w-8 h-8 flex items-center justify-center rounded-xl bg-white dark:bg-[#11141D] text-gray-400 hover:text-[#1e66f5] shadow-sm border border-gray-100 dark:border-gray-800 transition-all shrink-0" title="Cetak Struk">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                        </button>
                                        <div class="w-8 h-8 flex items-center justify-center rounded-xl bg-transparent text-gray-400 transition-all shrink-0">
                                            <svg class="w-4 h-4 transition-transform duration-200" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Expandable Details -->
                            <div x-show="expanded" x-collapse>
                                <div class="px-4 pb-4 pt-1 border-t border-gray-100 dark:border-gray-800 border-dashed mx-4">
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 mt-2">Daftar Produk</p>
                                    <div class="space-y-2">
                                        <template x-for="item in order.items" :key="item.id || item.product_id || Math.random()">
                                            <div class="flex justify-between items-center text-xs">
                                                <div class="flex-1">
                                                    <span class="font-bold text-gray-700 dark:text-gray-300" x-text="item.product ? item.product.name : item.name"></span>
                                                    <div class="text-gray-500 dark:text-gray-400 mt-0.5">
                                                        <span x-text="(item.quantity || item.qty) + ' ' + (item.unit || '').toUpperCase()"></span> &times; <span x-text="formatCurrency(item.price)"></span>
                                                    </div>
                                                </div>
                                                <div class="font-black text-gray-800 dark:text-gray-200" x-text="formatCurrency((item.quantity || item.qty) * item.price)"></div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                    
                    <div x-show="recentOrders.length === 0" class="py-10 text-center flex flex-col items-center" x-cloak>
                        <div class="w-12 h-12 rounded-full bg-gray-50 dark:bg-gray-800 flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        </div>
                        <p class="text-sm font-bold text-gray-600 dark:text-gray-400">Belum ada transaksi hari ini.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-modal>
