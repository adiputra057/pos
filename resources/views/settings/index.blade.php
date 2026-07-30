<x-admin-layout>

    <div x-data="settingsManagement('{{ $tab ?? 'general' }}')" class="space-y-6">
        
        <!-- Header -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight mb-1.5">Pengaturan</h1>
                <p class="text-sm text-gray-500 font-medium">Kelola konfigurasi sistem dan preferensi aplikasi.</p>
            </div>
            <div>
                <a href="{{ route('settings.activity-logs') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Activity Logs
                </a>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8 overflow-x-auto" aria-label="Tabs">
                <button @click="activeTab = 'general'" 
                    :class="activeTab === 'general' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                    Umum
                </button>
                <button @click="activeTab = 'payment'" 
                    :class="activeTab === 'payment' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                    Pembayaran
                </button>
                <button @click="activeTab = 'security'" 
                    :class="activeTab === 'security' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                    Keamanan
                </button>
                <button @click="activeTab = 'system'" 
                    :class="activeTab === 'system' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                    Sistem
                </button>
            </nav>
        </div>

        <!-- Content Area -->
        <div class="min-h-[400px]">
            
            <!-- General Settings -->
            <div x-show="activeTab === 'general'" class="space-y-6">
                <!-- Store Info -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Toko</h3>
                    <form action="{{ route('settings.update.general') }}" method="POST" enctype="multipart/form-data" class="space-y-4" @submit.prevent="submitForm($event)" hx-boost="false">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Logo Upload -->
                            <div class="md:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 mb-3 text-center md:text-left">Logo Toko</label>
                                <div class="flex flex-col items-center space-y-3">
                                    <div class="relative group">
                                        <div class="w-32 h-32 rounded-2xl border-2 border-dashed border-gray-200 flex items-center justify-center overflow-hidden bg-gray-50 group-hover:bg-gray-100 transition-colors duration-200">
                                            @if($settings['store_logo'])
                                                <img id="logo-preview" src="{{ asset('storage/' . $settings['store_logo']) }}" class="w-full h-full object-contain">
                                            @else
                                                <div id="logo-placeholder" class="text-gray-400 flex flex-col items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    <span class="text-[10px] font-bold uppercase tracking-widest leading-tight">Belum Ada Logo</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <input type="file" name="store_logo" id="store_logo" class="hidden" accept="image/*" 
                                           onchange="const file = this.files[0]; if(file) { const reader = new FileReader(); reader.onload = (e) => { const img = document.getElementById('logo-preview') || document.createElement('img'); img.id = 'logo-preview'; img.src = e.target.result; img.className = 'w-full h-full object-contain'; const holder = document.getElementById('logo-placeholder'); if(holder) holder.remove(); document.querySelector('.group .w-32').innerHTML = ''; document.querySelector('.group .w-32').appendChild(img); }; reader.readAsDataURL(file); }">
                                    <label for="store_logo" class="cursor-pointer inline-flex items-center px-3 py-1.5 bg-white border border-gray-300 rounded-lg text-xs font-bold text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition-colors">
                                        Pilih Gambar
                                    </label>
                                </div>
                            </div>

                            <!-- Right Side Fields -->
                            <div class="md:col-span-2 space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Toko</label>
                                        <input type="text" name="store_name" value="{{ $settings['store_name'] }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                                        <input type="text" name="store_phone" value="{{ $settings['store_phone'] }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Toko</label>
                                    <textarea name="store_address" rows="2" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ $settings['store_address'] }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <input type="email" name="store_email" value="{{ $settings['store_email'] }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>
                        
                        <div class="pt-6 border-t border-gray-100 mt-6">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                <!-- Left Column -->
                                <div class="space-y-8">
                                    <!-- Section 1: Branding & Struk -->
                                    <div>
                                        <h4 class="text-md font-bold text-gray-900 mb-4 flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                            </svg>
                                            Personalasi Struk & Sosial
                                        </h4>
                                        <div class="space-y-4">
                                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                                                <span class="text-sm font-medium text-gray-700">Tampilkan Logo di Struk</span>
                                                <input type="hidden" name="show_logo_on_receipt" value="0">
                                                <button type="button" 
                                                    @click="$el.nextElementSibling.value = $el.nextElementSibling.value == '1' ? '0' : '1'; $el.classList.toggle('bg-indigo-600'); $el.classList.toggle('bg-gray-200'); $el.firstElementChild.classList.toggle('translate-x-5'); $el.firstElementChild.classList.toggle('translate-x-0')"
                                                    class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none {{ $settings['show_logo_on_receipt'] ? 'bg-indigo-600' : 'bg-gray-200' }}">
                                                    <span class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200 {{ $settings['show_logo_on_receipt'] ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                                </button>
                                                <input type="hidden" name="show_logo_on_receipt" value="{{ $settings['show_logo_on_receipt'] }}">
                                            </div>
                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">WhatsApp</label>
                                                    <input type="text" name="whatsapp_number" value="{{ $settings['whatsapp_number'] }}" placeholder="628..." class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Instagram</label>
                                                    <input type="text" name="instagram_username" value="{{ $settings['instagram_username'] }}" placeholder="@toko..." class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Ukuran Kertas Default</label>
                                                <select name="default_paper_size" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                                    <option value="58mm" {{ $settings['default_paper_size'] == '58mm' ? 'selected' : '' }}>58mm (Kecil)</option>
                                                    <option value="80mm" {{ $settings['default_paper_size'] == '80mm' ? 'selected' : '' }}>80mm (Besar)</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Section 3: Regional & Tampilan -->
                                    <div>
                                        <h4 class="text-md font-bold text-gray-900 mb-4 flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 002 2h.293m1.414-1.414l.707-.707A2 2 0 0118.828 10h1.117M15 19.5l1.5-1.5a2 2 0 012.828 0l1.5 1.5M3 12a9 9 0 1118 0 9 9 0 01-18 0z" />
                                            </svg>
                                            Regional & Mata Uang
                                        </h4>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div class="col-span-2">
                                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Zona Waktu</label>
                                                <select name="store_timezone" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                                    <option value="Asia/Jakarta" {{ $settings['store_timezone'] == 'Asia/Jakarta' ? 'selected' : '' }}>WIB (Jakarta/Sumatera)</option>
                                                    <option value="Asia/Makassar" {{ $settings['store_timezone'] == 'Asia/Makassar' ? 'selected' : '' }}>WITA (Kalimantan/Bali/Sulawesi)</option>
                                                    <option value="Asia/Jayapura" {{ $settings['store_timezone'] == 'Asia/Jayapura' ? 'selected' : '' }}>WIT (Papua/Maluku)</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Mata Uang</label>
                                                <select name="currency" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                                    <option value="IDR" {{ $settings['currency'] == 'IDR' ? 'selected' : '' }}>IDR (Rupiah)</option>
                                                    <option value="USD" {{ $settings['currency'] == 'USD' ? 'selected' : '' }}>USD (Dollar)</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Desimal (0-4)</label>
                                                <select name="currency_precision" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                                    @for($i = 0; $i <= 4; $i++)
                                                        <option value="{{ $i }}" {{ $settings['currency_precision'] == $i ? 'selected' : '' }}>{{ $i }} Digit</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Column -->
                                <div class="space-y-8">
                                    <!-- Section 2: Operasional & Penomoran -->
                                    <div>
                                        <h4 class="text-md font-bold text-gray-900 mb-4 flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            </svg>
                                            Operasional & Penomoran
                                        </h4>
                                        <div class="space-y-4">
                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Prefix Invoice</label>
                                                    <input type="text" name="invoice_prefix" value="{{ $settings['invoice_prefix'] }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Ambang Stok Rendah</label>
                                                    <input type="number" name="low_stock_threshold" value="{{ $settings['low_stock_threshold'] }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                                </div>
                                            </div>
                                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                                                <span class="text-sm font-medium text-gray-700">Suara Scan Barcode</span>
                                                <input type="hidden" name="enable_scan_sound" value="0">
                                                <button type="button" 
                                                    @click="$el.nextElementSibling.value = $el.nextElementSibling.value == '1' ? '0' : '1'; $el.classList.toggle('bg-indigo-600'); $el.classList.toggle('bg-gray-200'); $el.firstElementChild.classList.toggle('translate-x-5'); $el.firstElementChild.classList.toggle('translate-x-0')"
                                                    class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none {{ $settings['enable_scan_sound'] ? 'bg-indigo-600' : 'bg-gray-200' }}">
                                                    <span class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200 {{ $settings['enable_scan_sound'] ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                                </button>
                                                <input type="hidden" name="enable_scan_sound" value="{{ $settings['enable_scan_sound'] }}">
                                            </div>

                                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                                                <div>
                                                    <span class="text-sm font-medium text-gray-700 block">Lacak & Kurangi Stok</span>
                                                    <span class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider block mt-0.5">Kelola stok produk secara otomatis saat transaksi</span>
                                                </div>
                                                <input type="hidden" name="track_stock" value="0">
                                                <button type="button" 
                                                    @click="$el.nextElementSibling.value = $el.nextElementSibling.value == '1' ? '0' : '1'; $el.classList.toggle('bg-indigo-600'); $el.classList.toggle('bg-gray-200'); $el.firstElementChild.classList.toggle('translate-x-5'); $el.firstElementChild.classList.toggle('translate-x-0')"
                                                    class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none {{ $settings['track_stock'] ? 'bg-indigo-600' : 'bg-gray-200' }}">
                                                    <span class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200 {{ $settings['track_stock'] ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                                </button>
                                                <input type="hidden" name="track_stock" value="{{ $settings['track_stock'] }}">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Section 4: Biaya & Pajak -->
                                    <div>
                                        <h4 class="text-md font-bold text-gray-900 mb-4 flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Biaya & Pajak
                                        </h4>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Pajak (VAT) %</label>
                                                <input type="number" name="tax_rate" value="{{ $settings['tax_rate'] }}" step="0.01" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Biaya Layanan %</label>
                                                <input type="number" name="service_charge" value="{{ $settings['service_charge'] }}" step="0.01" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                            </div>
                                            <div class="col-span-2">
                                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Diskon Default (%)</label>
                                                <input type="number" name="default_discount" value="{{ $settings['default_discount'] }}" step="0.01" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-6 border-t border-gray-100">
                            <button type="submit" class="inline-flex items-center px-6 py-2 bg-indigo-600 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md shadow-indigo-200">
                                Simpan Semua Pengaturan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Payment Settings -->
            <div x-show="activeTab === 'payment'" class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <form action="{{ route('settings.update.payment') }}" method="POST" @submit.prevent="submitForm($event)" hx-boost="false" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="space-y-8">
                            <!-- Section 1: QRIS & Digital Payment -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div>
                                    <h3 class="text-md font-bold text-gray-900 mb-4 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                        </svg>
                                        QRIS & Pembayaran Digital
                                    </h3>
                                    <div class="space-y-4">
                                        <div class="flex items-start space-x-4">
                                            <div class="flex-shrink-0">
                                                <div class="h-32 w-32 border-2 border-dashed border-gray-300 rounded-xl overflow-hidden flex items-center justify-center bg-gray-50 relative group">
                                                    @if($settings['qris_image'])
                                                        <img src="{{ asset('storage/' . $settings['qris_image']) }}" class="h-full w-full object-contain">
                                                    @else
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2-2v12a2 2 0 002 2z" />
                                                        </svg>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="flex-grow">
                                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Upload QRIS Statis</label>
                                                <input type="file" name="qris_image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                                <p class="mt-2 text-[10px] text-gray-400 italic">Format: JPG, PNG. Rekomendasi 500x500px.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h3 class="text-md font-bold text-gray-900 mb-4 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        Tombol Cepat Pembayaran
                                    </h3>
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nominal Tunai Cepat (Pisahkan koma)</label>
                                            <input type="text" name="quick_cash_nominals" value="{{ $settings['quick_cash_nominals'] }}" placeholder="5000,10000,20000..." class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                            <p class="mt-1 text-[10px] text-gray-400">Nominal ini akan muncul sebagai tombol pilihan saat pembayaran tunai.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 2: Daftar Metode Pembayaran -->
                            <div>
                                <h3 class="text-md font-bold text-gray-900 mb-4 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                    Metode Pembayaran & Urutan
                                </h3>
                                <div class="space-y-3">
                                    @foreach($paymentMethods as $index => $method)
                                    <div class="flex items-center justify-between p-4 bg-white border border-gray-100 rounded-2xl hover:border-indigo-200 transition-all shadow-sm">
                                        <input type="hidden" name="methods[{{ $index }}][id]" value="{{ $method->id }}">
                                        <div class="flex items-center space-x-4">
                                            <div class="h-10 w-10 flex items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 font-bold border border-indigo-100">
                                                {{ substr($method->method_name, 0, 1) }}
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-gray-900">{{ $method->display_name }}</h4>
                                                <span class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">ID: {{ $method->method_name }}</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-6">
                                            <div class="text-center">
                                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Fee (Rp)</label>
                                                <input type="number" name="methods[{{ $index }}][admin_fee]" value="{{ $method->admin_fee }}" class="w-20 rounded-lg border-gray-200 text-xs text-center focus:ring-indigo-500 focus:border-indigo-500">
                                            </div>
                                            <div class="text-center">
                                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Min (Rp)</label>
                                                <input type="number" name="methods[{{ $index }}][minimum_amount]" value="{{ $method->minimum_amount }}" class="w-20 rounded-lg border-gray-200 text-xs text-center focus:ring-indigo-500 focus:border-indigo-500">
                                            </div>
                                            <div class="text-center">
                                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Urutan</label>
                                                <input type="number" name="methods[{{ $index }}][display_order]" value="{{ $method->display_order }}" class="w-16 rounded-lg border-gray-200 text-xs text-center focus:ring-indigo-500 focus:border-indigo-500">
                                            </div>
                                            <div class="flex flex-col items-center">
                                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Aktif</label>
                                                <input type="hidden" name="methods[{{ $index }}][is_active]" value="0">
                                                <button type="button" 
                                                    @click="$el.nextElementSibling.value = $el.nextElementSibling.value == '1' ? '0' : '1'; $el.classList.toggle('bg-emerald-500'); $el.classList.toggle('bg-gray-200'); $el.firstElementChild.classList.toggle('translate-x-5'); $el.firstElementChild.classList.toggle('translate-x-0')"
                                                    class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none {{ $method->is_active ? 'bg-emerald-500' : 'bg-gray-200' }}">
                                                    <span class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200 {{ $method->is_active ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                                </button>
                                                <input type="hidden" name="methods[{{ $index }}][is_active]" value="{{ $method->is_active ? '1' : '0' }}">
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-8 border-t border-gray-50">
                            <button type="submit" class="inline-flex items-center px-8 py-3 bg-indigo-600 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 transition shadow-lg shadow-indigo-100">
                                Simpan Pengaturan Pembayaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Security Settings -->
            <div x-show="activeTab === 'security'" class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Keamanan Sistem</h3>
                    <form action="{{ route('settings.update.security') }}" method="POST" class="space-y-6" @submit.prevent="submitForm($event)" hx-boost="false">
                        @csrf
                        
                        <div class="space-y-8">
                            <!-- Section 1: Sesi & Login Protection -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div>
                                    <h4 class="text-md font-bold text-gray-900 mb-4 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                        Sesi & Proteksi Login
                                    </h4>
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Session Timeout (Menit)</label>
                                            <input type="number" name="session_timeout" value="{{ $securitySettings->session_timeout ?? 120 }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                        </div>
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                                            <span class="text-sm font-medium text-gray-700">Auto Logout (Tutup Browser)</span>
                                            <input type="hidden" name="auto_logout_on_close" value="0">
                                            <button type="button" 
                                                @click="$el.nextElementSibling.value = $el.nextElementSibling.value == '1' ? '0' : '1'; $el.classList.toggle('bg-indigo-600'); $el.classList.toggle('bg-gray-200'); $el.firstElementChild.classList.toggle('translate-x-5'); $el.firstElementChild.classList.toggle('translate-x-0')"
                                                class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none {{ ($securitySettings->auto_logout_on_close ?? false) ? 'bg-indigo-600' : 'bg-gray-200' }}">
                                                <span class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200 {{ ($securitySettings->auto_logout_on_close ?? false) ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                            </button>
                                            <input type="hidden" name="auto_logout_on_close" value="{{ ($securitySettings->auto_logout_on_close ?? false) ? '1' : '0' }}">
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Batas Salah Password</label>
                                                <input type="number" name="max_login_attempts" value="{{ $securitySettings->max_login_attempts ?? 5 }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Durasi Blokir (Menit)</label>
                                                <input type="number" name="lockout_duration" value="{{ $securitySettings->lockout_duration ?? 15 }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h4 class="text-md font-bold text-gray-900 mb-4 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                        </svg>
                                        Kebijakan Password
                                    </h4>
                                    <div class="space-y-4">
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Panjang Minimal</label>
                                                <input type="number" name="min_password_length" value="{{ $securitySettings->min_password_length ?? 8 }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Ganti Berkala (Hari)</label>
                                                <input type="number" name="password_expiry_days" value="{{ $securitySettings->password_expiry_days ?? 0 }}" placeholder="0 = Off" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                                            <span class="text-sm font-medium text-gray-700">Wajib Kombinasi Simbol</span>
                                            <input type="hidden" name="require_password_complexity" value="0">
                                            <button type="button" 
                                                @click="$el.nextElementSibling.value = $el.nextElementSibling.value == '1' ? '0' : '1'; $el.classList.toggle('bg-indigo-600'); $el.classList.toggle('bg-gray-200'); $el.firstElementChild.classList.toggle('translate-x-5'); $el.firstElementChild.classList.toggle('translate-x-0')"
                                                class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none {{ ($securitySettings->require_password_complexity ?? false) ? 'bg-indigo-600' : 'bg-gray-200' }}">
                                                <span class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200 {{ ($securitySettings->require_password_complexity ?? false) ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                            </button>
                                            <input type="hidden" name="require_password_complexity" value="{{ ($securitySettings->require_password_complexity ?? false) ? '1' : '0' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 2: Otorisasi & Access Control -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div>
                                    <h4 class="text-md font-bold text-gray-900 mb-4 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                        Otorisasi Operasional
                                    </h4>
                                    <div class="space-y-3">
                                        <div class="flex items-center justify-between p-2">
                                            <span class="text-sm font-medium text-gray-700">PIN Supervisor untuk Void</span>
                                            <input type="hidden" name="require_pin_for_void" value="0">
                                            <button type="button" 
                                                @click="$el.nextElementSibling.value = $el.nextElementSibling.value == '1' ? '0' : '1'; $el.classList.toggle('bg-indigo-600'); $el.classList.toggle('bg-gray-200'); $el.firstElementChild.classList.toggle('translate-x-5'); $el.firstElementChild.classList.toggle('translate-x-0')"
                                                class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none {{ ($securitySettings->require_pin_for_void ?? false) ? 'bg-indigo-600' : 'bg-gray-200' }}">
                                                <span class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200 {{ ($securitySettings->require_pin_for_void ?? false) ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                            </button>
                                            <input type="hidden" name="require_pin_for_void" value="{{ ($securitySettings->require_pin_for_void ?? false) ? '1' : '0' }}">
                                        </div>
                                        <div class="flex items-center justify-between p-2">
                                            <span class="text-sm font-medium text-gray-700">PIN Supervisor untuk Refund</span>
                                            <input type="hidden" name="require_pin_for_refund" value="0">
                                            <button type="button" 
                                                @click="$el.nextElementSibling.value = $el.nextElementSibling.value == '1' ? '0' : '1'; $el.classList.toggle('bg-indigo-600'); $el.classList.toggle('bg-gray-200'); $el.firstElementChild.classList.toggle('translate-x-5'); $el.firstElementChild.classList.toggle('translate-x-0')"
                                                class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none {{ ($securitySettings->require_pin_for_refund ?? false) ? 'bg-indigo-600' : 'bg-gray-200' }}">
                                                <span class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200 {{ ($securitySettings->require_pin_for_refund ?? false) ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                            </button>
                                            <input type="hidden" name="require_pin_for_refund" value="{{ ($securitySettings->require_pin_for_refund ?? false) ? '1' : '0' }}">
                                        </div>
                                        <div class="flex items-center justify-between p-2">
                                            <span class="text-sm font-medium text-gray-700">Aktifkan Activity Log</span>
                                            <input type="hidden" name="enable_activity_log" value="0">
                                            <button type="button" 
                                                @click="$el.nextElementSibling.value = $el.nextElementSibling.value == '1' ? '0' : '1'; $el.classList.toggle('bg-indigo-600'); $el.classList.toggle('bg-gray-200'); $el.firstElementChild.classList.toggle('translate-x-5'); $el.firstElementChild.classList.toggle('translate-x-0')"
                                                class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none {{ ($securitySettings->enable_activity_log ?? true) ? 'bg-indigo-600' : 'bg-gray-200' }}">
                                                <span class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200 {{ ($securitySettings->enable_activity_log ?? true) ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                            </button>
                                            <input type="hidden" name="enable_activity_log" value="{{ ($securitySettings->enable_activity_log ?? true) ? '1' : '0' }}">
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h4 class="text-md font-bold text-gray-900 mb-4 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                        </svg>
                                        Pembatasan Akses
                                    </h4>
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Whitelist IP (Pisahkan koma)</label>
                                            <input type="text" name="allowed_ips" value="{{ $securitySettings->allowed_ips }}" placeholder="127.0.0.1, 192.168.1.x" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Mulai Operasional</label>
                                                <input type="time" name="operational_hours_start" value="{{ $securitySettings->operational_hours_start }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Selesai Operasional</label>
                                                <input type="time" name="operational_hours_end" value="{{ $securitySettings->operational_hours_end }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-6 border-t border-gray-100">
                            <button type="submit" class="inline-flex items-center px-6 py-2 bg-indigo-600 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md shadow-indigo-200">
                                Simpan Keamanan
                            </button>
                        </div>
                    </form>
                </div>
            </div>



            <!-- System Settings -->
            <div x-show="activeTab === 'system'" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Backup Database -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col h-full">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-green-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Cadangkan Database</h3>
                                <p class="text-xs text-gray-500">Unduh seluruh data aplikasi ke dalam format SQL.</p>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-4 mb-6 flex-grow">
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-sm font-medium text-gray-600">Backup Terakhir</span>
                                <span class="text-sm font-bold text-gray-900">{{ $lastBackup }}</span>
                            </div>
                            <div class="p-3 bg-yellow-50 border border-yellow-100 rounded-lg">
                                <p class="text-[10px] text-yellow-800 leading-relaxed italic">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 inline mr-1 -mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                    Disarankan melakukan cadangan secara rutin setiap minggu atau sebelum melakukan update sistem besar.
                                </p>
                            </div>
                        </div>

                        <a href="{{ route('settings.backup.download') }}" hx-boost="false" download class="w-full inline-flex items-center justify-center px-4 py-3 bg-green-600 border border-transparent rounded-xl font-bold text-sm text-white uppercase tracking-widest hover:bg-green-700 transition-all shadow-lg shadow-green-100">
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Unduh Database (.sql)
                        </a>
                    </div>

                    <!-- Log Maintenance -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col h-full">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Pembersihan Otomatis</h3>
                                <p class="text-xs text-gray-500">Kelola berapa lama log aktivitas disimpan.</p>
                            </div>
                        </div>

                        <form action="{{ route('settings.update.system') }}" method="POST" class="space-y-4 mb-6" @submit.prevent="submitForm($event)">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Simpan Log Selama (Hari)</label>
                                <div class="flex gap-3">
                                    <input type="number" name="log_retention_days" value="{{ $settings['log_retention_days'] }}" min="1" max="365" class="flex-1 rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <button type="submit" class="px-6 py-2 bg-indigo-600 rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition-all">
                                        Simpan
                                    </button>
                                </div>
                                <p class="mt-2 text-[10px] text-gray-400 font-medium">Log yang lebih tua dari {{ $settings['log_retention_days'] }} hari akan dibersihkan secara berkala.</p>
                            </div>
                        </form>

                        <div class="pt-6 border-t border-gray-100 mt-auto">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <p class="text-sm font-bold text-gray-900">{{ number_format($totalLogs) }} Entri</p>
                                    <p class="text-[10px] text-gray-500 font-medium uppercase tracking-tight">Total Log Saat Ini</p>
                                </div>
                                <form action="{{ route('settings.system.cleanup') }}" method="POST" @submit.prevent="submitForm($event)">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-white border border-rose-200 rounded-lg text-xs font-bold text-rose-600 uppercase tracking-widest hover:bg-rose-50 transition-colors">
                                        Bersihkan Sekarang
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
