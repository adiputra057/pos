<x-admin-layout>
    <div x-data="{}" class="w-full space-y-6">
        
        <!-- Header with Title and Action Buttons -->
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-8">
            <!-- Title Section -->
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight mb-1.5">Riwayat Penjualan</h1>
                <p class="text-sm text-gray-500 font-medium">Lihat dan kelola semua transaksi penjualan toko Anda secara detail.</p>
            </div>
            
            <!-- Action Buttons - Aligned to Right -->
            <div class="flex items-center gap-3 flex-shrink-0 ml-auto">
                <a href="{{ route('orders.export-excel', request()->all()) }}" hx-boost="false" class="flex items-center gap-2 px-6 py-3 bg-emerald-50 text-emerald-700 rounded-2xl font-black uppercase tracking-widest text-[10px] hover:bg-emerald-100 transition-all border border-emerald-100 shadow-sm whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    Ekspor Excel
                </a>
                <a href="{{ route('orders.print', request()->all()) }}" hx-boost="false" target="_blank" class="flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white rounded-2xl font-black uppercase tracking-widest text-[10px] hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-100 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                    Cetak PDF
                </a>
            </div>
        </div>
        
    

    <div class="mb-6 bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
        <form action="{{ route('orders.index') }}" method="GET" class="flex flex-col lg:flex-row items-center gap-4">
            <!-- Search field -->
            <div class="relative flex-grow w-full lg:max-w-md">
                <input type="text" name="search" value="{{ request('search') }}" 
                       oninput="clearTimeout(this.delay); this.delay = setTimeout(() => this.form.submit(), 500)"
                       placeholder="Cari nomor invoice atau nama pelanggan..." 
                       class="w-full pl-10 pr-4 py-2.5 text-sm rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 transition-all bg-gray-50/30 font-medium">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>

            <div class="flex flex-wrap lg:flex-nowrap items-center gap-3 w-full lg:w-auto">
                <!-- Payment Method Filter -->
                <div class="w-full sm:w-40">
                    <select name="payment_method" onchange="this.form.submit()" class="w-full py-2.5 pl-3 pr-10 text-sm rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 transition-all bg-white shadow-sm cursor-pointer font-bold text-gray-600">
                        <option value="">Semua Bayar</option>
                        <option value="Tunai" {{ request('payment_method') == 'Tunai' ? 'selected' : '' }}>Tunai</option>
                        <option value="QRIS" {{ request('payment_method') == 'QRIS' ? 'selected' : '' }}>QRIS</option>
                        <option value="transfer" {{ in_array(request('payment_method'), ['transfer', 'Transfer']) ? 'selected' : '' }}>Transfer Bank</option>
                    </select>
                </div>

                <!-- Cashier Filter -->
                <div class="w-full sm:w-44">
                    <select name="user_id" onchange="this.form.submit()" class="w-full py-2.5 pl-3 pr-10 text-sm rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 transition-all bg-white shadow-sm cursor-pointer font-bold text-gray-600">
                        <option value="">Semua Kasir</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Date Filter -->
                <div class="flex items-center gap-2 px-4 py-2.5 bg-gray-50/50 border border-gray-100 rounded-xl w-full sm:w-auto">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest mr-1">Kalender</span>
                    <input type="date" name="date" value="{{ $date }}" onchange="this.form.submit()" class="bg-transparent border-none p-0 text-sm font-bold text-gray-700 focus:ring-0 cursor-pointer">
                </div>

                <!-- Reset Button -->
                @if(request()->hasAny(['search', 'payment_method', 'user_id', 'date']))
                    <a href="{{ route('orders.index') }}" class="p-2.5 bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-100 transition-colors flex items-center justify-center" title="Reset Filter">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        @if($orders->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-[10px] uppercase text-gray-500 font-black tracking-widest">
                        <th class="px-6 py-4">ID Pesanan</th>
                        <th class="px-6 py-4">Tanggal & Waktu</th>
                        <th class="px-6 py-4">Kasir</th>
                        <th class="px-6 py-4">Jumlah Barang</th>
                        <th class="px-6 py-4">Total Belanja</th>
                        <th class="px-6 py-4">Pembayaran</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($orders as $order)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <span class="text-sm font-mono font-bold text-gray-900">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-gray-900">{{ $order->created_at->format('d M Y') }}</span>
                                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">{{ $order->created_at->format('h:i A') }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-indigo-100 flex items-center justify-center">
                                    <span class="text-xs font-medium text-indigo-600">{{ strtoupper(substr($order->user->name ?? 'N/A', 0, 1)) }}</span>
                                </div>
                                <span class="text-sm text-gray-600">{{ $order->user->name ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-[10px] font-black uppercase tracking-widest">
                                {{ $order->items->count() }} Barang
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-gray-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                @if($order->discount > 0)
                                <span class="text-xs text-green-600">-Rp {{ number_format($order->discount, 0, ',', '.') }} diskon</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-green-50 text-green-700 rounded-full text-xs font-semibold uppercase">
                                {{ strtolower($order->payment_method ?? '') === 'transfer' ? 'Transfer Bank' : ($order->payment_method ?? 'Cash') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center items-center gap-2">
                                <button @click="$dispatch('open-modal', 'order-detail'); $dispatch('fetch-order', { id: {{ $order->id }} })" class="p-2 text-gray-400 hover:text-indigo-600 transition-all hover:bg-indigo-50 rounded-lg" title="Lihat Detail">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </button>
                                <button onclick="window.open('{{ route('orders.show', $order) }}', '_blank'); setTimeout(() => window.print(), 500)" class="p-2 text-gray-400 hover:text-blue-600 transition-all hover:bg-blue-50 rounded-lg" title="Print Struk">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Table Footer: Pagination & Entry Info -->
        <div class="bg-white border-t border-gray-100 px-6 py-8 flex flex-col md:flex-row justify-between items-center gap-8">
            @if($orders->total() > 0)
            <p class="text-xs text-gray-400 font-medium italic order-2 md:order-1">
                Menampilkan <span class="text-gray-700 font-bold">{{ $orders->firstItem() }}</span> sampai <span class="text-gray-700 font-bold">{{ $orders->lastItem() }}</span> dari <span class="text-gray-700 font-bold">{{ $orders->total() }}</span> transaksi
            </p>
            @endif
            <div class="order-1 md:order-2">
                {{ $orders->links() }}
            </div>
        </div>
        @endif
    </div>
    <x-modal name="order-detail" :show="false" maxWidth="2xl">
        <div class="p-6" x-data="{ 
            order: null, 
            loading: false,
            fetchOrderDetail(id) {
                this.loading = true;
                this.order = null;
                fetch(`/orders/${id}`, {
                    headers: { 'Accept': 'application/json' }
                })
                .then(r => r.json())
                .then(data => {
                    this.order = data.order;
                    this.loading = false;
                });
            },
            formatCurrency(val) {
                return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(val);
            }
        }" x-on:fetch-order.window="fetchOrderDetail($event.detail.id)">
            
            <div class="flex items-center justify-between mb-6 border-b border-gray-100 pb-4">
                <h2 class="text-xl font-bold text-gray-800">Detail Transaksi</h2>
                <button x-on:click="$dispatch('close-modal', 'order-detail')" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <div x-show="loading" class="py-12 flex flex-col items-center justify-center text-gray-500">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600 mb-4"></div>
                <p>Memuat detail...</p>
            </div>

            <div x-show="!loading && order">
                <div class="bg-gray-50 rounded-2xl p-4 mb-6 border border-gray-100 grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Nomor Invoice</p>
                        <p class="text-sm font-bold text-gray-900" x-text="order?.invoice_number"></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Tanggal</p>
                        <p class="text-sm font-medium text-gray-900" x-text="new Date(order?.created_at).toLocaleString('id-ID', { day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' })"></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Kasir</p>
                        <p class="text-sm font-medium text-gray-900" x-text="order?.user?.name"></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Pelanggan</p>
                        <p class="text-sm font-medium text-gray-900" x-text="order?.customer?.name || 'Umum'"></p>
                    </div>
                </div>

                <div class="mb-6">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Item Pesanan</p>
                    <div class="space-y-2 max-h-60 overflow-y-auto pr-2">
                        <template x-for="item in order?.items" :key="item.id">
                            <div class="flex justify-between items-center p-3 bg-white border border-gray-100 rounded-xl shadow-sm">
                                <div class="flex-1">
                                    <p class="text-sm font-bold text-gray-800" x-text="item.product.name"></p>
                                    <p class="text-xs text-gray-500" x-text="`${item.quantity} ${item.unit.toUpperCase()} x ${formatCurrency(item.price)}`"></p>
                                </div>
                                <p class="text-sm font-black text-indigo-600" x-text="formatCurrency(item.quantity * item.price)"></p>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="bg-indigo-600 rounded-2xl p-6 text-white shadow-xl shadow-indigo-100">
                    <div class="flex justify-between items-center mb-2">
                        <p class="text-indigo-200 text-xs font-bold uppercase tracking-widest">Subtotal</p>
                        <p class="text-lg font-bold" x-text="formatCurrency(order?.total_price)"></p>
                    </div>
                    <div class="flex justify-between items-center mb-4 pb-4 border-b border-indigo-500">
                        <p class="text-indigo-200 text-xs font-bold uppercase tracking-widest">Metode Bayar</p>
                        <p class="text-sm font-black uppercase" x-text="order?.payment_method"></p>
                    </div>
                    <div class="flex justify-between items-center opacity-80 mb-1">
                        <p class="text-xs font-bold uppercase tracking-widest">Bayar</p>
                        <p class="text-sm font-bold" x-text="formatCurrency(order?.payment_amount)"></p>
                    </div>
                    <div class="flex justify-between items-center">
                        <p class="text-xs font-bold uppercase tracking-widest">Kembali</p>
                        <p class="text-sm font-bold" x-text="formatCurrency(order?.change_amount)"></p>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex gap-2 justify-between">
                <button type="button" @click="$dispatch('close-modal', 'order-detail')" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-xl text-sm font-bold hover:bg-gray-200 transition-all">
                    Tutup
                </button>
                <div class="flex gap-2">
                    <button type="button" onclick="window.print()" class="px-4 py-2 bg-indigo-50 text-indigo-600 rounded-xl text-sm font-black uppercase tracking-widest hover:bg-indigo-100 transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        Cetak Struk
                    </button>
                </div>
            </div>
        </div>
    </x-modal>
    </div>
</x-admin-layout>
