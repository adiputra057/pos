<aside class="flex flex-col w-64 h-screen px-5 py-6 bg-white border-r border-gray-100"
       hx-boost="true"
       hx-target="#main-content"
       hx-select="#main-content"
       hx-swap="innerHTML scroll:false"
       x-data="{ 
           currentUrl: '{{ request()->url() }}',
           init() {
               window.addEventListener('nav-changed', (e) => {
                   this.currentUrl = window.location.origin + e.detail.url;
               });
           },
           isActive(pattern) {
               if (pattern === 'dashboard' && this.currentUrl === '{{ route('dashboard') }}') return true;
               if (pattern === 'users' && this.currentUrl.includes('/users')) return true;
               if (pattern === 'products' && this.currentUrl.includes('/products')) return true;
               if (pattern === 'categories' && this.currentUrl.includes('/categories')) return true;
               if (pattern === 'suppliers' && this.currentUrl.includes('/suppliers')) return true;
               if (pattern === 'customers' && this.currentUrl.includes('/customers')) return true;
               if (pattern === 'stocks' && this.currentUrl.includes('/stocks')) return true;
               if (pattern === 'orders' && this.currentUrl.includes('/orders')) return true;
                if (pattern === 'reports' && this.currentUrl.includes('/reports')) return true;
               if (pattern === 'pos' && this.currentUrl.includes('/pos')) return true;
               if (pattern === 'settings' && this.currentUrl.includes('/settings')) return true;
               return false;
           }
       }">
    <!-- Brand -->
    <div class="flex items-center gap-3 mb-10 px-2">
        <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
        </div>
        <div>
            <h1 class="text-xl font-black text-gray-900 tracking-tight">MiniMart<span class="text-indigo-600 uppercase">POS</span></h1>
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Master Dashboard</p>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 space-y-6 overflow-y-auto pr-2 scrollbar-hide transition-all">
        
        <!-- SECTION: UTAMA -->
        <div>
            <p class="px-4 text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Menu Utama</p>
            <div class="space-y-1">
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all duration-200"
                   :class="isActive('dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'text-gray-500 hover:bg-gray-50 hover:text-indigo-600 group'">
                    <svg class="w-5 h-5 mr-3 transition-colors" :class="isActive('dashboard') ? 'text-white' : 'text-gray-400 group-hover:text-indigo-600'" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    Dashboard
                </a>
                
                <a href="{{ route('pos.index') }}" 
                   hx-boost="false"
                   class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all duration-200"
                   :class="isActive('pos') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'text-gray-500 hover:bg-gray-50 hover:text-indigo-600 group'">
                    <svg class="w-5 h-5 mr-3 transition-colors" :class="isActive('pos') ? 'text-white' : 'text-gray-400 group-hover:text-indigo-600'" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    Kasir (POS)
                </a>
                
                <a href="{{ route('users.index') }}" 
                   class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all duration-200"
                   :class="isActive('users') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'text-gray-500 hover:bg-gray-50 hover:text-indigo-600 group'">
                    <svg class="w-5 h-5 mr-3 transition-colors" :class="isActive('users') ? 'text-white' : 'text-gray-400 group-hover:text-indigo-600'" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Manajemen Users
                </a>
            </div>
        </div>

        @if(Auth::user()->hasRole('owner') || Auth::user()->hasRole('admin'))
        <!-- SECTION: DATA MASTER -->
        <div>
            <p class="px-4 text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Data Master</p>
            <div class="space-y-1">
                <a href="{{ route('products.index') }}" 
                   class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all duration-200"
                   :class="isActive('products') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'text-gray-500 hover:bg-gray-50 hover:text-indigo-600 group'">
                    <svg class="w-5 h-5 mr-3 transition-colors" :class="isActive('products') ? 'text-white' : 'text-gray-400 group-hover:text-indigo-600'" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    Daftar Produk
                </a>

                <a href="{{ route('categories.index') }}" 
                   class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all duration-200"
                   :class="isActive('categories') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'text-gray-500 hover:bg-gray-50 hover:text-indigo-600 group'">
                    <svg class="w-5 h-5 mr-3 transition-colors" :class="isActive('categories') ? 'text-white' : 'text-gray-400 group-hover:text-indigo-600'" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                    Kategori
                </a>

                <a href="{{ route('suppliers.index') }}" 
                   class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all duration-200"
                   :class="isActive('suppliers') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'text-gray-500 hover:bg-gray-50 hover:text-indigo-600 group'">
                    <svg class="w-5 h-5 mr-3 transition-colors" :class="isActive('suppliers') ? 'text-white' : 'text-gray-400 group-hover:text-indigo-600'" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    Supplier
                </a>

                <a href="{{ route('customers.index') }}" 
                   class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all duration-200"
                   :class="isActive('customers') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'text-gray-500 hover:bg-gray-50 hover:text-indigo-600 group'">
                    <svg class="w-5 h-5 mr-3 transition-colors" :class="isActive('customers') ? 'text-white' : 'text-gray-400 group-hover:text-indigo-600'" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Pelanggan
                </a>
            </div>
        </div>

        <!-- SECTION: OPERASIONAL -->
        <div>
            <p class="px-4 text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Operasional</p>
            <div class="space-y-1">
                @if(\App\Models\Setting::get('track_stock', '1') === '1')
                <a href="{{ route('stocks.index') }}" 
                   class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all duration-200"
                   :class="isActive('stocks') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'text-gray-500 hover:bg-gray-50 hover:text-indigo-600 group'">
                    <svg class="w-5 h-5 mr-3 transition-colors" :class="isActive('stocks') ? 'text-white' : 'text-gray-400 group-hover:text-indigo-600'" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    Manajemen Stok
                </a>
                @endif
                
                <a href="{{ route('orders.index') }}" 
                   class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all duration-200"
                   :class="isActive('orders') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'text-gray-500 hover:bg-gray-50 hover:text-indigo-600 group'">
                    <svg class="w-5 h-5 mr-3 transition-colors" :class="isActive('orders') ? 'text-white' : 'text-gray-400 group-hover:text-indigo-600'" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Riwayat Penjualan
                </a>

                <a href="{{ route('reports.index') }}" 
                   class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all duration-200"
                   :class="isActive('reports') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'text-gray-500 hover:bg-gray-50 hover:text-indigo-600 group'">
                    <svg class="w-5 h-5 mr-3 transition-colors" :class="isActive('reports') ? 'text-white' : 'text-gray-400 group-hover:text-indigo-600'" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    Laporan
                </a>
            </div>
        </div>

        <!-- SECTION: ADMINISTRASI -->
        <div>
            <p class="px-4 text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Pengaturan</p>
            <div class="space-y-1">

                <a href="{{ route('settings.index') }}" 
                   class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all duration-200"
                   :class="isActive('settings') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'text-gray-500 hover:bg-gray-50 hover:text-indigo-600 group'">
                    <svg class="w-5 h-5 mr-3 transition-colors" :class="isActive('settings') ? 'text-white' : 'text-gray-400 group-hover:text-indigo-600'" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Pengaturan Umum
                </a>
            </div>
        </div>
        @endif
        
    </nav>
</aside>
