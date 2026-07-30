<x-admin-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
                <div class="flex items-center gap-2 mb-1.5">
                    <a href="{{ route('settings.index') }}" class="text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <h1 class="text-3xl font-black text-gray-900 tracking-tight">Activity Logs</h1>
                </div>
                <p class="text-sm text-gray-500 font-medium ml-8">Pantau aktivitas pengguna dan perubahan sistem.</p>
            </div>
            <div>
                 <form action="{{ route('settings.activity-logs') }}" method="GET" class="flex flex-wrap items-center gap-2">
                     <a href="{{ route('settings.activity-logs') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Reset Filter
                    </a>
                 </form>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
            <form action="{{ route('settings.activity-logs') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                
                <!-- Users -->
                <div class="md:col-span-1">
                    <label for="user_id" class="block text-xs font-medium text-gray-700 mb-1">User</label>
                    <select name="user_id" id="user_id" onchange="this.form.submit()" class="w-full rounded-lg border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua User</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Module -->
                <div class="md:col-span-1">
                    <label for="module" class="block text-xs font-medium text-gray-700 mb-1">Modul</label>
                    <select name="module" id="module" onchange="this.form.submit()" class="w-full rounded-lg border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Modul</option>
                        <option value="auth" {{ request('module') == 'auth' ? 'selected' : '' }}>Auth (Login/Logout)</option>
                        <option value="pos" {{ request('module') == 'pos' ? 'selected' : '' }}>POS (Penjualan)</option>
                        <option value="products" {{ request('module') == 'products' ? 'selected' : '' }}>Produk</option>
                        <option value="stocks" {{ request('module') == 'stocks' ? 'selected' : '' }}>Stok</option>
                        <option value="settings" {{ request('module') == 'settings' ? 'selected' : '' }}>Pengaturan</option>
                         <option value="orders" {{ request('module') == 'orders' ? 'selected' : '' }}>Order & Report</option>
                    </select>
                </div>
                
                 <!-- Action -->
                <div class="md:col-span-1">
                    <label for="action" class="block text-xs font-medium text-gray-700 mb-1">Action</label>
                    <input type="text" name="action" id="action" value="{{ request('action') }}" 
                           oninput="clearTimeout(window.filterTimer); window.filterTimer = setTimeout(() => this.form.submit(), 500)" 
                           placeholder="Contoh: create, update" class="w-full rounded-lg border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Date Range -->
                <div class="md:col-span-1">
                    <label for="date_from" class="block text-xs font-medium text-gray-700 mb-1">Dari Tanggal</label>
                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" onchange="this.form.submit()" class="w-full rounded-lg border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                
                <div class="md:col-span-1 flex items-end">
                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Logs Table -->
        <div class="space-y-2">
            <div class="flex items-center justify-between px-2">
                <div class="flex items-center gap-3">
                    <p class="text-xs text-gray-500 font-medium tracking-tight">
                        Menampilkan <span class="font-bold text-gray-900">{{ $logs->firstItem() ?? 0 }}</span> - <span class="font-bold text-gray-900">{{ $logs->lastItem() ?? 0 }}</span> dari <span class="font-bold text-gray-900">{{ $logs->total() }}</span> data aktivitas
                    </p>
                    @if(request()->filled('page'))
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-widest bg-indigo-50 text-indigo-700 border border-indigo-100">
                            Halaman {{ $logs->currentPage() }}
                        </span>
                    @endif
                </div>
                
                @if($logs->hasPages())
                    <div class="text-[10px] font-medium text-gray-400 uppercase tracking-widest">
                        Total {{ $logs->lastPage() }} Halaman
                    </div>
                @endif
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aktivitas</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Modul</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Detail</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($logs as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $log->created_at->format('d M Y H:i:s') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs mr-3">
                                        {{ substr($log->user->name ?? '?', 0, 1) }}
                                    </div>
                                    <div class="text-sm font-medium text-gray-900">{{ $log->user->name ?? 'Unknown' }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $actionColors = [
                                        'create' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                        'update' => 'bg-amber-50 text-amber-700 border-amber-100',
                                        'delete' => 'bg-rose-50 text-rose-700 border-rose-100',
                                        'login' => 'bg-sky-50 text-sky-700 border-sky-100',
                                        'logout' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
                                    ];
                                    $actionClass = $actionColors[strtolower($log->action)] ?? 'bg-gray-50 text-gray-700 border-gray-100';
                                @endphp
                                <div class="flex flex-col gap-1">
                                    <span class="inline-flex items-center w-fit px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-widest border {{ $actionClass }}">
                                        {{ str_replace('_', ' ', $log->action) }}
                                    </span>
                                    <div class="text-xs text-gray-500 font-medium">{{ $log->description }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-0.5 inline-flex text-[9px] leading-5 font-black rounded-md bg-gray-100 text-gray-500 uppercase tracking-widest border border-gray-200">
                                    {{ $log->module }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $log->ip_address ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-data="{ open: false }">
                                @if($log->old_values || $log->new_values)
                                    <button @click="open = !open" type="button" class="text-indigo-600 hover:text-indigo-900 text-xs font-semibold">
                                        Lihat Data
                                    </button>
                                    
                                    <!-- Modal/Popover for details -->
                                    <div x-show="open" @click.away="open = false" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                                        Detail Perubahan
                                                    </h3>
                                                    <div class="mt-4 overflow-hidden border border-gray-100 rounded-2xl shadow-sm">
                                                        @php
                                                            $oldValues = (array) ($log->old_values ?? []);
                                                            $newValues = (array) ($log->new_values ?? []);
                                                            
                                                            $exclude = ['updated_at', 'created_at', 'id', 'password', 'remember_token'];
                                                            $allKeys = array_unique(array_merge(array_keys($oldValues), array_keys($newValues)));
                                                            $allKeys = array_filter($allKeys, fn($key) => !in_array($key, $exclude));

                                                            // Human-friendly mapping for common fields
                                                            $fieldLabels = [
                                                                'session_timeout' => 'Durasi Sesi (Menit)',
                                                                'auto_logout_on_close' => 'Auto Logout (Browser Tutup)',
                                                                'require_pin_for_void' => 'PIN Supervisor (Void)',
                                                                'require_pin_for_refund' => 'PIN Supervisor (Refund)',
                                                                'enable_activity_log' => 'Status Activity Log',
                                                                'store_name' => 'Nama Toko',
                                                                'store_phone' => 'No. Telepon Toko',
                                                                'store_address' => 'Alamat Toko',
                                                                'currency' => 'Mata Uang',
                                                                'tax_rate' => 'Tarif Pajak (%)',
                                                                'receipt_header' => 'Header Struk',
                                                                'receipt_footer' => 'Footer Struk',
                                                                'is_active' => 'Status Aktif',
                                                                'method_name' => 'Nama Metode',
                                                                'display_name' => 'Nama Tampilan',
                                                                'admin_fee' => 'Biaya Admin',
                                                                'minimum_amount' => 'Minimal Transaksi',
                                                                'name' => 'Nama',
                                                                'email' => 'Email',
                                                                'role' => 'Peran/Role',
                                                                'stock' => 'Stok',
                                                                'price' => 'Harga',
                                                                'selling_price' => 'Harga Jual',
                                                                'purchase_price' => 'Harga Beli',
                                                                'sku' => 'Kode/SKU',
                                                                'barcode' => 'Barcode',
                                                            ];
                                                        @endphp
                                                        <table class="min-w-full divide-y divide-gray-100">
                                                            <thead class="bg-gray-50/80">
                                                                <tr>
                                                                    <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Field / Informasi</th>
                                                                    <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Data Lama</th>
                                                                    <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Data Baru</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="bg-white divide-y divide-gray-50">
                                                                @foreach($allKeys as $key)
                                                                    @php
                                                                        $old = $oldValues[$key] ?? null;
                                                                        $new = $newValues[$key] ?? null;
                                                                        $isChanged = json_encode($old) !== json_encode($new);
                                                                        
                                                                        $formatValue = function($val) use ($key) {
                                                                            if (is_null($val)) return '<span class="text-gray-300 italic text-[10px]">kosong</span>';
                                                                            if ($val === true || $val === '1' || $val === 1) 
                                                                                return '<span class="px-2 py-0.5 rounded-full bg-blue-50 text-blue-600 text-[10px] font-black uppercase tracking-tighter">Aktif / Ya</span>';
                                                                            if ($val === false || $val === '0' || $val === 0) 
                                                                                return '<span class="px-2 py-0.5 rounded-full bg-gray-50 text-gray-400 text-[10px] font-black uppercase tracking-tighter">Nonaktif / Tidak</span>';
                                                                            if (is_numeric($val) && (str_contains($key, 'price') || str_contains($key, 'fee')))
                                                                                return '<span class="font-mono text-gray-600">Rp ' . number_format($val, 0, ',', '.') . '</span>';
                                                                            if (is_array($val) || is_object($val)) 
                                                                                return '<pre class="text-[9px] bg-gray-50 p-2 rounded-lg text-gray-500 overflow-x-auto border border-gray-100">' . json_encode($val, JSON_PRETTY_PRINT) . '</pre>';
                                                                            return '<span class="text-gray-700 font-medium">' . e($val) . '</span>';
                                                                        };
                                                                    @endphp
                                                                    <tr class="{{ $isChanged ? 'bg-indigo-50/30' : '' }} group transition-all duration-200">
                                                                        <td class="px-5 py-4 whitespace-nowrap">
                                                                            <span class="text-[11px] font-black text-gray-900 group-hover:text-indigo-600 transition-colors">
                                                                                {{ $fieldLabels[$key] ?? str_replace('_', ' ', ucfirst($key)) }}
                                                                            </span>
                                                                        </td>
                                                                        <td class="px-5 py-4">
                                                                            <div class="line-through decoration-red-200 decoration-1">
                                                                                {!! $formatValue($old) !!}
                                                                            </div>
                                                                        </td>
                                                                        <td class="px-5 py-4">
                                                                            <div class="{{ $isChanged ? 'font-bold' : '' }}">
                                                                                {!! $formatValue($new) !!}
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="bg-gray-50 px-6 py-4 flex justify-end">
                                                    <button @click="open = false" type="button" class="px-6 py-2.5 bg-white border border-gray-200 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-600 hover:bg-gray-50 hover:text-gray-900 shadow-sm transition-all">
                                                        Tutup Detail
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                Tidak ada data aktivitas yang ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>
