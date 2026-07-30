<x-admin-layout>
    <script>
        function stockManagementData(initialTab = 'incoming') {
            return {
                activeTab: initialTab,
                items: [],
                supplier_id: '',
                receipt_date: '{{ date('Y-m-d') }}',
                selectedProduct: '',
                qtyInput: 1,
                unitInput: 'pcs',
                locationInput: 'display',
                noteInput: '',
                products: {!! json_encode($products->map(function($p) { 
                    return [
                        'id' => $p->id, 
                        'name' => $p->name, 
                        'sku' => $p->sku, 
                        'stock' => $p->stock, 
                        'warehouse_stock' => $p->warehouse_stock, 
                        'buying_price' => $p->buying_price, 
                        'box_quantity' => $p->box_quantity,
                        'image' => $p->image
                    ]; 
                })) !!},
                allProducts: @json($products),
                
                addItem() {
                    if (!this.selectedProduct) return;
                    const product = this.products.find(p => p.id == this.selectedProduct);
                    this.items.push({
                        product_id: product.id,
                        name: product.name,
                        sku: product.sku,
                        stock: product.stock,
                        quantity: parseInt(this.qtyInput),
                        unit: this.unitInput,
                        unit_cost: product.buying_price,
                        image: product.image,
                        subtotal: product.buying_price * parseInt(this.qtyInput)
                    });
                    this.selectedProduct = '';
                    this.qtyInput = 1;
                    this.unitInput = 'pcs';
                },
                
                removeItem(index) {
                    this.items.splice(index, 1);
                },
                
                get totalQty() {
                    return this.items.reduce((sum, item) => {
                        const product = this.products.find(p => p.id == item.product_id);
                        const multiplier = (item.unit === 'box') ? (product?.box_quantity || 1) : 1;
                        return sum + (item.quantity * multiplier);
                    }, 0);
                },
                
                get totalValue() {
                    return this.items.reduce((sum, item) => {
                        const product = this.products.find(p => p.id == item.product_id);
                        const multiplier = (item.unit === 'box') ? (product?.box_quantity || 1) : 1;
                        return sum + (item.unit_cost * item.quantity * multiplier);
                    }, 0);
                },

                showManualModal: false,
                manualForm: { product_id: '', type: 'in', location: 'display', quantity: 1, note: '', unit: 'pcs' },
                openManualModal() {
                    this.showManualModal = true;
                    this.manualForm = { product_id: '', type: 'in', location: 'display', quantity: 1, note: '', unit: 'pcs' };
                },
                closeManualModal() { this.showManualModal = false; },

                showUploadModal: false,
                openUploadModal() { this.showUploadModal = true; },
                closeUploadModal() { this.showUploadModal = false; },

                showTransferModal: false,
                transferForm: { product_id: '', quantity: 1, from: 'warehouse', to: 'display', unit: 'pcs', note: '' },
                transferProductName: '',
                openTransferModal(product = null) {
                    this.showTransferModal = true;
                    if (product) {
                        this.transferForm = { product_id: product.id, quantity: 1, unit: 'pcs', from: 'warehouse', to: 'display', note: '' };
                        this.transferProductName = product.name;
                    } else {
                        this.transferForm = { product_id: '', quantity: 1, unit: 'pcs', from: 'warehouse', to: 'display', note: '' };
                        this.transferProductName = '';
                    }
                    document.body.classList.add('overflow-hidden');
                },
                closeTransferModal() {
                    this.showTransferModal = false;
                    document.body.classList.remove('overflow-hidden');
                },
                submitTransfer(event) {
                    let form = event.target;
                    if (form.tagName !== 'FORM') form = form.closest('form');
                    const formData = new FormData(form);
                    fetch('/stocks/transfer', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) {
                            if (response.status === 422) {
                                return response.json().then(data => {
                                    const errorMsg = Object.values(data.errors).flat().join('\\n');
                                    window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Gagal pindah stok:\\n' + errorMsg, type: 'error' } }));
                                });
                            }
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data) {
                            sessionStorage.setItem('notification', JSON.stringify({ message: 'Stok berhasil dipindahkan.', type: 'success' }));
                            window.location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Terjadi kesalahan sistem.', type: 'error' } }));
                    });
                },
                init() {
                    const urlParams = new URLSearchParams(window.location.search);
                    const transferPid = urlParams.get('transfer_product_id');
                    if (transferPid) {
                        this.$nextTick(() => {
                            const product = this.allProducts.find(p => p.id == transferPid);
                            this.openTransferModal(product);
                            this.transferForm.product_id = transferPid;
                        });
                    }
                }
            };
        }
    </script>
    <div x-data="stockManagementData('{{ $tab ?? 'incoming' }}')" class="space-y-6">
        <div class="mb-8">
            <h1 class="text-3xl font-black text-gray-900 tracking-tight mb-1.5">Manajemen Stok</h1>
            <p class="text-sm text-gray-500 font-medium">Kelola stok masuk dan monitor pergerakan barang toko Anda.</p>
        </div>
        
        <!-- Tabs & Global Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 border-b border-gray-100 pb-2 relative z-10">
            <div class="flex space-x-4">
                <button type="button" 
                   @click="activeTab = 'incoming'"
                   :class="activeTab === 'incoming' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-500 hover:text-gray-700'"
                   class="pb-2 text-sm font-medium transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Stok Masuk
                </button>
                <button type="button" 
                   @click="activeTab = 'history'"
                   :class="activeTab === 'history' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-500 hover:text-gray-700'"
                   class="pb-2 text-sm font-medium transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Riwayat
                </button>
            </div>

            <!-- Global Action Buttons -->
            <div class="flex items-center gap-2">
                <button type="button" 
                        x-on:click="openManualModal()" 
                        class="px-3 py-1.5 bg-white border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 flex items-center gap-2 text-xs font-bold transition-all shadow-sm group cursor-pointer relative z-20">
                    <div class="w-5 h-5 rounded bg-indigo-50 flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                    Log Manual
                </button>
            </div>
        </div>

        <!-- Tab 1: Stok Masuk (Incoming Stock) -->
        <div x-show="activeTab === 'incoming'" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left Column: Shipment Details & Item List -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Shipment Details Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <h3 class="font-bold text-gray-800">Detail Pengiriman</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Supplier</label>
                            <select x-model="supplier_id" class="w-full rounded-lg border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Pilih Supplier</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Tanggal Penerimaan</label>
                            <input type="date" x-model="receipt_date" class="w-full rounded-lg border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Tujuan Stok</label>
                            <select x-model="locationInput" class="w-full rounded-lg border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="display">Etalase (Siap Jual)</option>
                                <option value="warehouse">Gudang</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Incoming Items List Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            <h3 class="font-bold text-gray-800">Daftar Barang Masuk</h3>
                        </div>
                        <button @click="openUploadModal()" class="text-sm text-indigo-600 font-bold hover:text-indigo-700 flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 rounded-lg transition-colors border border-indigo-100/50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            Upload Massal
                        </button>
                    </div>

                    <!-- Add Item Inline Form -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 p-3 bg-gray-50 rounded-lg border border-gray-200 items-end">
                        <div>
                            <label class="block text-[10px] font-semibold text-gray-400 uppercase mb-1">Cari & Pilih Produk</label>
                            <select x-model="selectedProduct" class="w-full rounded-lg border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Pilih Produk...</option>
                                <template x-for="product in products" :key="product.id">
                                    <option :value="product.id" x-text="product.name + ' (E: ' + product.stock + ' | G: ' + product.warehouse_stock + ')'"></option>
                                </template>
                            </select>
                        </div>
                        <div class="flex gap-2 items-end">
                            <div class="flex-1">
                                <label class="block text-[10px] font-semibold text-gray-400 uppercase mb-1">Jumlah & Unit</label>
                                <div class="flex h-[38px]">
                                    <input type="number" x-model="qtyInput" min="1" class="w-full rounded-l-lg border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 border-r-0" placeholder="Qty">
                                    <select x-model="unitInput" class="w-24 rounded-r-lg border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 font-bold bg-white">
                                        <option value="pcs">Pcs</option>
                                        <option value="box">Dus</option>
                                    </select>
                                </div>
                            </div>
                            <button type="button" @click="addItem()" class="h-[38px] px-6 bg-indigo-600 text-white rounded-lg text-sm font-bold hover:bg-indigo-700 shadow-sm transition-all flex items-center justify-center whitespace-nowrap">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Tambah
                            </button>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="text-xs text-gray-400 font-semibold uppercase border-b border-gray-100">
                                <tr>
                                    <th class="py-3 px-2">Detail Produk</th>
                                    <th class="py-3 px-2">Stok E|G</th>
                                    <th class="py-3 px-2">Jml Masuk</th>
                                    <th class="py-3 px-2">Unit</th>
                                    <th class="py-3 px-2">Harga Beli</th>
                                    <th class="py-3 px-2">Subtotal</th>
                                    <th class="py-3 px-2">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                <template x-for="(item, index) in items" :key="index">
                                    <tr>
                                        <td class="py-3 px-2">
                                            <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg overflow-hidden bg-gray-100 flex items-center justify-center text-gray-400 border border-gray-200">
                                                <template x-if="item.image">
                                                    <img :src="'/storage/' + item.image" class="w-full h-full object-cover">
                                                </template>
                                                <template x-if="!item.image">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                </template>
                                            </div>
                                                <div>
                                                    <div class="font-medium text-gray-800 text-sm" x-text="item.name"></div>
                                                    <div class="text-xs text-gray-400" x-text="item.sku"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 px-2 text-sm text-gray-600">
                                            <span x-text="item.stock"></span> | <span x-text="products.find(p => p.id == item.product_id)?.warehouse_stock || 0"></span>
                                        </td>
                                        <td class="py-3 px-2">
                                            <input type="number" x-model="item.quantity" class="w-16 rounded border-gray-200 text-sm py-1">
                                        </td>
                                        <td class="py-3 px-2">
                                            <select x-model="item.unit" class="w-20 rounded border-gray-200 text-sm py-1">
                                                <option value="pcs">Pcs</option>
                                                <option value="box">Dus</option>
                                            </select>
                                        </td>
                                        <td class="py-3 px-2 text-sm text-gray-600" x-text="'Rp ' + (item.unit_cost ? new Intl.NumberFormat('id-ID').format(item.unit_cost) : '0')"></td>
                                        <td class="py-3 px-2 font-medium text-gray-800" x-text="'Rp ' + (item.unit_cost * item.quantity * (item.unit === 'box' ? (products.find(p => p.id == item.product_id)?.box_quantity || 1) : 1)).toLocaleString('id-ID')"></td>
                                        <td class="py-3 px-2">
                                            <button @click="removeItem(index)" class="text-red-400 hover:text-red-600">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                                <tr x-show="items.length === 0">
                                    <td colspan="6" class="py-8 text-center text-gray-400 text-sm">
                                        Belum ada barang ditambahkan.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right Column: Summary & Actions -->
            <div class="space-y-6">
                <!-- Transaction Summary -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center gap-2 mb-6">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        <h3 class="font-bold text-gray-800">Ringkasan Transaksi</h3>
                    </div>

                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Total Item Unik</span>
                            <span class="font-medium text-gray-900" x-text="items.length + ' Item'"></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Total Kuantitas</span>
                            <span class="font-bold text-gray-900" x-text="totalQty + ' UNITS'"></span>
                        </div>
                        <div class="pt-3 border-t border-gray-100 flex justify-between items-center">
                            <span class="text-gray-500 text-sm">Estimasi Nilai</span>
                            <span class="text-xl font-bold text-indigo-600" x-text="'Rp ' + totalValue.toLocaleString('id-ID')"></span>
                        </div>
                    </div>

                    <form action="{{ route('stocks.store') }}" method="POST" hx-boost="false">
                        @csrf
                        <input type="hidden" name="type" value="in">
                        <input type="hidden" name="note" x-model="noteInput">
                        <input type="hidden" name="supplier_id" x-model="supplier_id">
                        <input type="hidden" name="receipt_date" x-model="receipt_date">
                        <input type="hidden" name="location" x-model="locationInput">
                        
                        <!-- Hidden inputs for items -->
                        <template x-for="(item, index) in items" :key="index">
                            <div>
                                <input type="hidden" :name="'items[' + index + '][product_id]'" :value="item.product_id">
                                <input type="hidden" :name="'items[' + index + '][quantity]'" :value="item.quantity">
                                <input type="hidden" :name="'items[' + index + '][unit]'" :value="item.unit">
                                <input type="hidden" :name="'items[' + index + '][unit_cost]'" :value="item.unit_cost">
                            </div>
                        </template>

                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Catatan</label>
                            <textarea x-model="noteInput" class="w-full rounded-lg border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500" rows="2" placeholder="Tulis catatan..."></textarea>
                        </div>

                        @if($errors->any())
                            <div class="mb-4 p-4 bg-red-50 border border-red-100 rounded-xl">
                                <div class="flex items-center gap-2 mb-2 text-red-700 font-bold text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Terjadi kesalahan:
                                </div>
                                <ul class="list-disc ml-5 text-xs text-red-600 space-y-0.5">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <button type="submit" :disabled="items.length === 0" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-md transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                            SELESAIKAN & UPDATE STOK
                        </button>
                    </form>
                    
                    <p class="text-[10px] text-gray-400 text-center mt-3">
                        Dengan mengklik selesaikan, Anda mengonfirmasi bahwa jumlah fisik stok sesuai dengan yang dimasukkan.
                    </p>
                </div>

            </div>
        </div>
        
        <!-- Tab 2: Riwayat -->
        <div x-show="activeTab === 'history'" class="space-y-6">
            <div class="mb-6 bg-white p-3 rounded-xl border border-gray-100 shadow-sm">
                <div class="flex flex-col lg:flex-row items-center gap-4">
                    <!-- Filters Form -->
                    <form method="GET" action="{{ route('stocks.index') }}" class="flex flex-grow flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
                        <input type="hidden" name="tab" value="history">
                        
                        <!-- Search -->
                        <div class="relative flex-grow w-full">
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   oninput="clearTimeout(this.delay); this.delay = setTimeout(() => this.form.submit(), 500)"
                                   placeholder="Cari produk atau SKU..." class="w-full pl-10 pr-4 py-2 text-sm rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 transition-all">
                            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        
                        <!-- Date -->
                        <div class="relative w-full sm:w-40">
                            <input type="date" name="date" value="{{ $date }}" 
                                   onchange="this.form.submit()"
                                   class="w-full pl-3 pr-3 py-2 text-sm rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 transition-all bg-gray-50/50">
                        </div>

                        <!-- Type -->
                        <div class="relative w-full sm:w-40">
                            <select name="type" onchange="this.form.submit()" class="w-full pl-3 pr-3 py-2 text-sm rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 transition-all bg-gray-50/50">
                                <option value="">Semua Tipe</option>
                                <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>Stok Masuk</option>
                                <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>Stok Keluar</option>
                                <option value="adjustment" {{ request('type') == 'adjustment' ? 'selected' : '' }}>Penyesuaian</option>
                            </select>
                        </div>
                        
                        @if(request()->hasAny(['search', 'date', 'type']))
                            <div class="flex gap-2">
                                <a href="{{ route('stocks.index', ['tab' => 'history']) }}" class="p-2.5 bg-gray-100 text-gray-500 rounded-xl hover:bg-gray-200 transition-colors" title="Reset Filter">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-50 text-xs uppercase font-medium text-gray-500">
                            <tr>
                                <th class="px-6 py-4">Tanggal</th>
                                <th class="px-6 py-4">Produk</th>
                                <th class="px-6 py-4">Tipe</th>
                                <th class="px-6 py-4">Lokasi</th>
                                <th class="px-6 py-4 text-center">Jumlah</th>
                                <th class="px-6 py-4 text-center">Satuan</th>
                                <th class="px-6 py-4 text-center">Sisa</th>
                                <th class="px-6 py-4">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($logs as $log)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">{{ $log->created_at->format('d M Y H:i') }}</td>
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $log->product->name }}</td>
                                <td class="px-6 py-4">
                                    @if($log->type == 'in')
                                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-semibold">Masuk</span>
                                    @elseif($log->type == 'out')
                                        <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-semibold">Keluar</span>
                                    @else
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs font-semibold">Adj</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider {{ $log->stock_location === 'warehouse' ? 'bg-blue-50 text-blue-600 border border-blue-100' : 'bg-green-50 text-green-600 border border-green-100' }}">
                                        {{ $log->stock_location === 'warehouse' ? 'Gudang' : 'Etalase' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center font-mono font-bold" :class="{'text-green-600': {{ $log->quantity }} > 0, 'text-red-600': {{ $log->quantity }} < 0}">
                                    {{ $log->quantity > 0 ? '+' : '' }}{{ abs($log->quantity) }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if(str_contains($log->note, 'Satuan: Dus'))
                                        <span class="px-2 py-0.5 bg-purple-50 text-purple-600 rounded text-[10px] font-bold uppercase tracking-wider">Dus</span>
                                    @else
                                        <span class="px-2 py-0.5 bg-gray-50 text-gray-500 rounded text-[10px] font-bold uppercase tracking-wider">Pcs</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center font-mono font-medium">{{ $log->balance_after }}</td>
                                <td class="px-6 py-4 text-gray-500 max-w-xs truncate" title="{{ $log->note }}">{{ $log->note ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-400 italic">Belum ada data riwayat stok sesuai filter.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-8 border-t border-gray-100 bg-white flex flex-col md:flex-row justify-between items-center gap-8">
                    @if($logs->total() > 0)
                    <p class="text-xs text-gray-400 font-medium italic order-2 md:order-1">
                        Menampilkan <span class="text-gray-700 font-bold">{{ $logs->firstItem() }}</span> sampai <span class="text-gray-700 font-bold">{{ $logs->lastItem() }}</span> dari <span class="text-gray-700 font-bold">{{ $logs->total() }}</span> riwayat stok
                    </p>
                    @endif
                        {{ $logs->appends(request()->all())->links() }}
                    </div>
                </div>
            </div>

        <!-- MANUAL LOG MODAL -->
        <div x-show="showManualModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" @click="closeManualModal()"></div>
            
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div x-show="showManualModal" 
                     x-transition:enter="ease-out duration-300" 
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-gray-100">
                    
                    <form action="{{ route('stocks.store') }}" method="POST">
                        @csrf
                        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                            <h3 class="text-lg font-bold text-gray-900">Manual Stock Log</h3>
                            <button type="button" @click="closeManualModal()" class="text-gray-400 hover:text-gray-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        
                        <div class="p-6 space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Pilih Produk</label>
                                <select name="product_id" x-model="manualForm.product_id" required class="w-full rounded-xl border-gray-300 text-sm focus:border-indigo-500">
                                    <option value="">-- Pilih Produk --</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }} (E: {{ $product->stock }} | G: {{ $product->warehouse_stock }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Tipe Log</label>
                                    <select name="type" x-model="manualForm.type" required class="w-full rounded-xl border-gray-300 text-sm focus:border-indigo-500">
                                        <option value="in">Stok Masuk (+)</option>
                                        <option value="out">Stok Keluar (-)</option>
                                        <option value="adjustment">Penyesuaian (±)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Lokasi Stok</label>
                                    <select name="location" x-model="manualForm.location" required class="w-full rounded-xl border-gray-300 text-sm focus:border-indigo-500">
                                        <option value="display">Etalase</option>
                                        <option value="warehouse">Gudang</option>
                                    </select>
                                </div>
                                <div class="col-span-2 sm:col-span-1">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Jumlah</label>
                                    <div class="flex">
                                        <input type="number" name="quantity" min="1" x-model="manualForm.quantity" required class="w-full rounded-l-xl border-gray-300 text-sm focus:border-indigo-500 border-r-0">
                                        <select name="unit" x-model="manualForm.unit" required class="w-24 rounded-r-xl border-gray-300 text-sm focus:border-indigo-500">
                                            <option value="pcs">Pcs</option>
                                            <option value="box">Dus</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Catatan</label>
                                <textarea name="note" x-model="manualForm.note" rows="2" class="w-full rounded-xl border-gray-300 text-sm focus:border-indigo-500" placeholder="Misal: Barang rusak, Retur, dll"></textarea>
                            </div>
                        </div>

                        <div class="bg-gray-50/50 px-6 py-4 flex flex-row-reverse gap-3 border-t border-gray-100">
                            <button type="submit" class="w-full sm:w-auto inline-flex justify-center rounded-xl border border-transparent px-5 py-2.5 bg-indigo-600 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 transition-all">Update Stok</button>
                            <button type="button" @click="closeManualModal()" class="mt-3 sm:mt-0 w-full sm:w-auto inline-flex justify-center rounded-xl border border-gray-300 px-5 py-2.5 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- UPLOAD MASSAL MODAL -->
        <div x-show="showUploadModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" @click="closeUploadModal()"></div>
            
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div x-show="showUploadModal" 
                     x-transition:enter="ease-out duration-300" 
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-gray-100">
                    
                    <form action="{{ route('stocks.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                            <h3 class="text-lg font-bold text-gray-900">Upload Stok Massal</h3>
                            <button type="button" @click="closeUploadModal()" class="text-gray-400 hover:text-gray-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        
                        <div class="p-6 space-y-5">
                            <div class="p-4 bg-indigo-50 border border-indigo-100 rounded-xl">
                                <div class="flex gap-3">
                                    <svg class="w-5 h-5 text-indigo-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <div class="text-xs text-indigo-700 leading-relaxed">
                                        <p class="font-bold mb-1">Panduan Upload:</p>
                                        <ul class="list-disc ml-4 space-y-1">
                                            <li>Gunakan file format **.CSV**.</li>
                                            <li>Kolom wajib: SKU/Nama, Kuantitas.</li>
                                            <li>Pastikan SKU sesuai dengan data produk.</li>
                                        </ul>
                                        <a href="{{ route('stocks.template') }}" class="inline-flex items-center gap-1 mt-3 text-indigo-600 font-bold hover:underline">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0L8 8m4-4v12"></path></svg>
                                            Download Template CSV
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-black uppercase tracking-wider text-gray-400 mb-2">Tipe Transaksi</label>
                                <select name="type" required class="w-full h-11 px-4 rounded-xl border-gray-300 text-sm font-bold focus:border-indigo-500">
                                    <option value="in">Stok Masuk (+)</option>
                                    <option value="out">Stok Keluar (-)</option>
                                    <option value="adjustment">Penyesuaian (±)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-black uppercase tracking-wider text-gray-400 mb-2">Pilih File CSV</label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-indigo-300 transition-colors bg-gray-50/50">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-10 w-10 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600">
                                            <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-bold text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500 px-1">
                                                <span>Klik untuk pilih file</span>
                                                <input id="file-upload" name="file" type="file" accept=".csv" required class="sr-only">
                                            </label>
                                        </div>
                                        <p class="text-xs text-gray-400">CSV file up to 2MB</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50/50 px-6 py-4 flex flex-row-reverse gap-3 border-t border-gray-100">
                            <button type="submit" class="w-full sm:w-auto inline-flex justify-center rounded-xl border border-transparent px-8 py-2.5 bg-indigo-600 text-sm font-bold text-white shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all hover:scale-[1.02] active:scale-[0.98]">Mulai Impor Stok</button>
                            <button type="button" @click="closeUploadModal()" class="mt-3 sm:mt-0 w-full sm:w-auto inline-flex justify-center rounded-xl border border-gray-300 px-6 py-2.5 bg-white text-sm font-bold text-gray-700 hover:bg-gray-50 transition-all active:scale-95">Batal</button>
                        </div>
                    </form>
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
                                <template x-if="!transferProductName">
                                    <select x-model="transferForm.product_id" @change="transferProductName = $event.target.options[$event.target.selectedIndex].text" 
                                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <option value="">Pilih Produk...</option>
                                        <template x-for="product in allProducts" :key="product.id">
                                            <option :value="product.id" x-text="product.name"></option>
                                        </template>
                                    </select>
                                </template>
                                <template x-if="transferProductName">
                                    <input type="text" x-model="transferProductName" readonly class="w-full bg-gray-50 border-gray-200 rounded-lg text-sm text-gray-500 font-medium">
                                </template>
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
                            <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-sm transition-colors">
                                Konfirmasi Pindah
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-admin-layout>
