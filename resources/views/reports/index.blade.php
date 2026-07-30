<x-admin-layout>
    <div x-data="{ 
        activeTab: '{{ $tab }}',
        init() {
            // Re-initialize charts when tab changes
            this.$watch('activeTab', () => {
                this.$nextTick(() => {
                    if (window.initReportCharts) window.initReportCharts();
                });
            });
        }
    }" class="space-y-6">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-black text-gray-900 tracking-tight mb-1.5">Laporan</h1>
            <p class="text-sm text-gray-500 font-medium">Analisis performa bisnis dan pengelolaan pengeluaran Anda.</p>
        </div>

        <!-- Navigation Tabs -->
        <div class="border-b border-gray-200 mb-6">
            <nav class="-mb-px flex space-x-8 overflow-x-auto" aria-label="Tabs">
                <button type="button" @click="activeTab = 'sales'" 
                    :class="activeTab === 'sales' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                    Laporan Penjualan
                </button>
                <button type="button" @click="activeTab = 'expenses'" 
                    :class="activeTab === 'expenses' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                    Laporan Pengeluaran
                </button>
            </nav>
        </div>

        <!-- Content Area -->
        <div class="min-h-[400px]">
            <div x-show="activeTab === 'sales'">
                <!-- Sales Report Filters -->
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-8 gap-4">
                    <div class="flex flex-wrap gap-3">
                        <div class="flex flex-col gap-1">
                            <span class="text-xs font-bold text-gray-400 px-1">Ekspor Terfilter</span>
                            <div class="flex gap-2">
                                <a href="{{ route('orders.export-excel', request()->all()) }}" hx-boost="false" title="Ekspor Data Terfilter ke Excel" class="flex items-center gap-2 px-4 py-2.5 bg-emerald-50 text-emerald-700 rounded-xl font-bold text-xs hover:bg-emerald-100 transition-all border border-emerald-100 shadow-sm">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    Excel
                                </a>
                                <a href="{{ route('orders.print', request()->all()) }}" hx-boost="false" target="_blank" title="Cetak PDF Data Terfilter" class="flex items-center gap-2 px-4 py-2.5 bg-indigo-50 text-indigo-700 rounded-xl font-bold text-xs hover:bg-indigo-100 transition-all border border-indigo-100 shadow-sm">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                                    PDF
                                </a>
                            </div>
                        </div>
                        
                        <div class="flex flex-col gap-1 border-l border-gray-200 pl-3">
                            <span class="text-xs font-bold text-gray-400 px-1">Laporan 1 Tahun</span>
                            <div class="flex gap-2">
                                <a href="{{ route('reports.monthly.export-excel') }}" hx-boost="false" title="Ekspor Analisis Laba/Rugi 12 Bulan" class="flex items-center gap-2 px-4 py-2.5 bg-gray-50 text-gray-700 rounded-xl font-bold text-xs hover:bg-gray-100 transition-all border border-gray-100 shadow-sm">
                                    Excel
                                </a>
                                <a href="{{ route('reports.monthly.print') }}" hx-boost="false" target="_blank" title="Cetak Analisis Laba/Rugi 12 Bulan" class="flex items-center gap-2 px-4 py-2.5 bg-gray-50 text-gray-700 rounded-xl font-bold text-xs hover:bg-gray-100 transition-all border border-gray-100 shadow-sm">
                                    PDF
                                </a>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('reports.index', ['tab' => 'sales']) }}" method="GET" class="flex items-center gap-3 bg-white p-2 rounded-2xl border border-gray-100 shadow-sm">
                        <input type="hidden" name="tab" value="sales">
                        <div class="flex items-center gap-2 px-3">
                            <span class="text-xs font-bold text-gray-400">Periode</span>
                            <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" onchange="this.form.submit()" class="bg-transparent border-none p-0 text-sm font-bold text-gray-700 focus:ring-0 cursor-pointer">
                            <span class="text-gray-300">-</span>
                            <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" onchange="this.form.submit()" class="bg-transparent border-none p-0 text-sm font-bold text-gray-700 focus:ring-0 cursor-pointer">
                        </div>
                        @if(request('start_date') || request('end_date'))
                            <a href="{{ route('reports.index', ['tab' => 'sales']) }}" class="p-1 px-2 text-[10px] font-bold text-red-500 hover:text-red-700 bg-red-50 rounded-lg transition-colors">
                                RESET
                            </a>
                        @endif
                    </form>
                </div>

                <!-- Metrics and Charts -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
                    <!-- Revenue -->
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                        <div class="flex justify-between items-start mb-4">
                            <div class="p-3 bg-indigo-50 rounded-xl">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        </div>
                        <p class="text-sm font-medium text-gray-500">Total Pendapatan</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                    </div>

                    <!-- Gross Profit -->
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                        <div class="flex justify-between items-start mb-4">
                            <div class="p-3 bg-emerald-50 rounded-xl">
                                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                            </div>
                        </div>
                        <p class="text-sm font-medium text-gray-500">Pendapatan Bersih</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1">Rp {{ number_format($grossProfit, 0, ',', '.') }}</h3>
                    </div>

                    <!-- Net Margin -->
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                        <div class="flex justify-between items-start mb-4">
                            <div class="p-3 bg-orange-50 rounded-xl">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                            </div>
                            <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded-lg text-xs font-semibold">T: 35%</span>
                        </div>
                        <p class="text-sm font-medium text-gray-500">Margin Bersih</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($netMargin, 1) }}%</h3>
                    </div>

                    <!-- Transactions -->
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                        <div class="flex justify-between items-start mb-4">
                            <div class="p-3 bg-purple-50 rounded-xl">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            </div>
                        </div>
                        <p class="text-sm font-medium text-gray-500">Total Transaksi</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($transactionCount) }}</h3>
                    </div>

                    <!-- Inventory Valuation -->
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                        <div class="flex justify-between items-start mb-4">
                            <div class="p-3 bg-rose-50 rounded-xl">
                                <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            </div>
                        </div>
                        <p class="text-sm font-medium text-gray-500">Nilai Aset Stok</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1">Rp {{ number_format($totalInventoryValue, 0, ',', '.') }}</h3>
                        <p class="text-[10px] text-gray-400 font-bold uppercase mt-1">Total: {{ number_format($totalInventoryQty) }} Unit</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                    <!-- Revenue TrendChart -->
                    <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                        <h3 class="text-lg font-bold text-gray-900 mb-6 uppercase tracking-tight">Tren Pendapatan</h3>
                        <div class="h-80 w-full relative">
                            <canvas id="revenueTrendChart"></canvas>
                        </div>
                    </div>

                    <!-- Category Dist. -->
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                        <h3 class="text-lg font-bold text-gray-900 mb-6 uppercase tracking-tight">Per Kategori</h3>
                        <div class="h-64 w-full relative mb-4">
                            <canvas id="categoryChart"></canvas>
                        </div>
                        <div class="space-y-3">
                            @foreach($categoryLabels as $index => $label)
                            <div class="flex justify-between items-center text-sm">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ ['#4F46E5', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899', '#3B82F6'][$index % 7] }}"></span>
                                    <span class="text-gray-600 font-medium">{{ $label }}</span>
                                </div>
                                <span class="font-bold text-gray-900">{{ number_format($categoryData[$index]) }} item</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm mb-8">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-gray-900">Analisis Laba/Rugi Bulanan</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 text-[10px] text-gray-400 uppercase font-black tracking-widest">
                                <tr>
                                    <th class="px-6 py-4">Bulan</th>
                                    <th class="px-6 py-4 text-right">Pendapatan</th>
                                    <th class="px-6 py-4 text-right">COGS</th>
                                    <th class="px-6 py-4 text-right">Pengeluaran</th>
                                    <th class="px-6 py-4 text-right text-emerald-600">Laba Bersih</th>
                                    <th class="px-6 py-4 text-right">Margin</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($monthlyData as $data)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 font-bold text-gray-900">{{ $data['month'] }}</td>
                                    <td class="px-6 py-4 text-right font-medium text-gray-600">Rp {{ number_format($data['revenue'], 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-right font-medium text-gray-400">Rp {{ number_format($data['cogs'], 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-right font-medium text-gray-400">Rp {{ number_format($data['expenses'], 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-right font-black {{ $data['status'] == 'profit' ? 'text-emerald-600' : 'text-rose-600' }}">
                                        Rp {{ number_format($data['net_profit'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span class="px-2 py-1 rounded-lg text-[10px] font-black {{ $data['status'] == 'profit' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                            {{ number_format($data['margin'], 1) }}%
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div x-show="activeTab === 'expenses'" x-cloak>
                <!-- Expense Report Tab -->
                <div class="space-y-6">
                    <!-- Filters & Stats -->
                    {{-- Moved below --}}

                    <!-- Metrics -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <!-- Total -->
                        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                            <div class="flex justify-between items-start mb-4">
                                <div class="p-3 bg-rose-50 rounded-xl">
                                    <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm1-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                </div>
                            </div>
                            <p class="text-sm font-medium text-gray-500">Total Pengeluaran</p>
                            <h3 class="text-2xl font-bold text-gray-900 mt-1">Rp {{ number_format($totalExpense, 0, ',', '.') }}</h3>
                        </div>

                        <!-- Average -->
                        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                            <div class="flex justify-between items-start mb-4">
                                <div class="p-3 bg-orange-50 rounded-xl">
                                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                </div>
                            </div>
                            <p class="text-sm font-medium text-gray-500">Rata-rata / Transaksi</p>
                            <h3 class="text-2xl font-bold text-gray-900 mt-1">Rp {{ number_format($averageExpense, 0, ',', '.') }}</h3>
                        </div>

                        <!-- Categories -->
                        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                            <div class="flex justify-between items-start mb-4">
                                <div class="p-3 bg-purple-50 rounded-xl">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                </div>
                            </div>
                            <p class="text-sm font-medium text-gray-500">Jumlah Kategori</p>
                            <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $categoryCount }}</h3>
                        </div>

                        <!-- Transactions -->
                        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                            <div class="flex justify-between items-start mb-4">
                                <div class="p-3 bg-indigo-50 rounded-xl">
                                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                </div>
                            </div>
                            <p class="text-sm font-medium text-gray-500">Total Pencatatan</p>
                            <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $expenseCount }}</h3>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Chart -->
                        <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                            <h3 class="text-lg font-bold text-gray-900 mb-6 uppercase tracking-tight">Tren Pengeluaran</h3>
                            <div class="h-80 relative">
                                <canvas id="expenseTrendChart"></canvas>
                            </div>
                        </div>

                        <!-- Categories -->
                        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                            <h3 class="text-lg font-bold text-gray-900 mb-6 uppercase tracking-tight">Alokasi Biaya</h3>
                            <div class="space-y-6">
                                @forelse($byCategory as $cat => $data)
                                <div class="group">
                                    <div class="flex justify-between items-end mb-2">
                                        <div class="flex flex-col">
                                            <span class="text-[10px] font-black text-gray-900 uppercase tracking-tighter">{{ $cat }}</span>
                                            <span class="text-[10px] text-gray-400 font-bold">{{ $data['count'] }} Transaksi</span>
                                        </div>
                                        <span class="text-xs font-black text-indigo-600">Rp {{ number_format($data['total'], 0, ',', '.') }}</span>
                                    </div>
                                    <div class="w-full bg-gray-50 h-2 rounded-full overflow-hidden">
                                        <div class="bg-indigo-500 h-full rounded-full transition-all duration-1000" style="width: {{ $totalExpense > 0 ? ($data['total'] / $totalExpense) * 100 : 0 }}%"></div>
                                    </div>
                                </div>
                                @empty
                                <div class="text-center py-12">
                                    <div class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-3 text-gray-200">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Belum ada data</p>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm mb-8">
                        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4 border-b border-gray-50 pb-6">
                            <div class="flex items-center gap-3 w-full md:w-auto">
                                <h3 class="text-lg font-bold text-gray-900 border-r border-gray-100 pr-4 mr-2 hidden md:block">Detail Pengeluaran</h3>
                                <button onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'add-expense' }))" class="flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100 whitespace-nowrap">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                                    CATAT
                                </button>
                            </div>

                            <form action="{{ route('reports.index', ['tab' => 'expenses']) }}" method="GET" class="flex flex-col md:flex-row items-center gap-3 w-full md:w-auto">
                                <input type="hidden" name="tab" value="expenses">
                                
                                <!-- Search Field -->
                                <div class="relative w-full md:w-64">
                                    <input type="text" name="search" value="{{ request('search') }}" 
                                           oninput="clearTimeout(this.delay); this.delay = setTimeout(() => this.form.submit(), 500)"
                                           placeholder="Cari transaksi..." 
                                           class="w-full pl-10 pr-4 py-2.5 text-sm rounded-2xl border-gray-100 focus:border-indigo-500 focus:ring-indigo-500 transition-all bg-gray-50/50 font-medium">
                                    <svg class="w-5 h-5 text-gray-300 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>

                                <div class="flex items-center gap-2 px-4 py-2 bg-gray-50/50 rounded-2xl border border-gray-100 w-full md:w-auto">
                                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest mr-1">Kalender</span>
                                    <input type="date" name="start_date" value="{{ $dateFrom }}" onchange="this.form.submit()" class="bg-transparent border-none p-0 text-xs font-bold text-gray-700 focus:ring-0 cursor-pointer">
                                    <span class="text-gray-300 mx-1">-</span>
                                    <input type="date" name="end_date" value="{{ $dateTo }}" onchange="this.form.submit()" class="bg-transparent border-none p-0 text-xs font-bold text-gray-700 focus:ring-0 cursor-pointer">
                                    @if(request('start_date') || request('end_date') || request('search'))
                                        <a href="{{ route('reports.index', ['tab' => 'expenses']) }}" class="ml-2 p-1 px-2 text-[9px] font-black text-rose-600 hover:text-rose-700 bg-rose-50 rounded-lg transition-colors uppercase">
                                            Clear
                                        </a>
                                    @endif
                                </div>
                            </form>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-gray-50 text-[10px] text-gray-400 uppercase font-black tracking-widest">
                                    <tr>
                                        <th class="px-6 py-4">Waktu</th>
                                        <th class="px-6 py-4">Judul & Keterangan</th>
                                        <th class="px-6 py-4">Kategori</th>
                                        <th class="px-6 py-4 text-right">Nominal</th>
                                        <th class="px-6 py-4">Personel</th>
                                        <th class="px-6 py-4 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @forelse($expenses as $expense)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 font-bold text-gray-900 whitespace-nowrap">{{ $expense->date->format('d M Y') }}</td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm font-bold text-gray-800">{{ $expense->title }}</p>
                                            <p class="text-xs text-gray-500 line-clamp-1">{{ $expense->description }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2.5 py-1 bg-gray-50 text-gray-500 rounded-lg text-[9px] font-black uppercase tracking-tight border border-gray-100">
                                                {{ $expense->category }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right font-black text-rose-600">
                                            Rp {{ number_format($expense->amount, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <div class="w-6 h-6 rounded-full bg-indigo-100 flex items-center justify-center text-[10px] font-bold text-indigo-700">
                                                    {{ strtoupper(substr($expense->user->name ?? 'A', 0, 1)) }}
                                                </div>
                                                <span class="text-xs text-gray-600 font-medium whitespace-nowrap">{{ $expense->user->name ?? '-' }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex justify-center items-center gap-2">
                                                <form id="delete-expense-{{ $expense->id }}" action="{{ route('expenses.destroy', $expense) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" 
                                                            class="p-2 text-gray-400 hover:text-red-600 transition-colors" 
                                                            title="Hapus"
                                                            @click="$dispatch('confirm', { 
                                                                title: 'Hapus Catatan?', 
                                                                message: 'Data pengeluaran \'{{ $expense->title }}\' senilai Rp {{ number_format($expense->amount, 0, ',', '.') }} akan dihapus secara permanen.', 
                                                                formId: 'delete-expense-{{ $expense->id }}' 
                                                            })">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-16 text-center text-gray-400 font-medium text-sm">
                                            Data pengeluaran masih kosong untuk periode ini.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        @if($expenses->hasPages())
                        <div class="mt-6">
                            {{ $expenses->links() }}
                        </div>
                        @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function initReportCharts() {
            // Sales Trend Chart
            const trendCanvas = document.getElementById('revenueTrendChart');
            if (trendCanvas) {
                if (trendCanvas._chartInstance) trendCanvas._chartInstance.destroy();
                trendCanvas._chartInstance = new Chart(trendCanvas.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($trendLabels) !!},
                        datasets: [{
                            label: 'Pendapatan',
                            data: {!! json_encode($trendData) !!},
                            borderColor: '#4F46E5',
                            backgroundColor: 'rgba(79, 70, 229, 0.1)',
                            fill: true,
                            tension: 0.4,
                            borderWidth: 4,
                            pointRadius: 4,
                            pointHoverRadius: 8,
                            pointBackgroundColor: '#4F46E5',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { grid: { color: '#F3F4F6', drawBorder: false }, ticks: { font: { weight: 'bold', size: 10 } } },
                            x: { grid: { display: false }, ticks: { font: { weight: 'bold', size: 10 } } }
                        }
                    }
                });
            }

            // Category Distribution
            const categoryCanvas = document.getElementById('categoryChart');
            if (categoryCanvas) {
                if (categoryCanvas._chartInstance) categoryCanvas._chartInstance.destroy();
                categoryCanvas._chartInstance = new Chart(categoryCanvas.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode($categoryLabels) !!},
                        datasets: [{
                            data: {!! json_encode($categoryData) !!},
                            backgroundColor: ['#4F46E5', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899', '#3B82F6'],
                            borderWidth: 0,
                            hoverOffset: 15
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        cutout: '75%'
                    }
                });
            }

            // Expense Trend Chart
            const expCanvas = document.getElementById('expenseTrendChart');
            if (expCanvas) {
                if (expCanvas._chartInstance) expCanvas._chartInstance.destroy();
                expCanvas._chartInstance = new Chart(expCanvas.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($byDate->keys()) !!},
                        datasets: [{
                            label: 'Pengeluaran',
                            data: {!! json_encode($byDate->values()) !!},
                            backgroundColor: '#4F46E5',
                            borderRadius: 8,
                            barThickness: 24,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { grid: { color: '#F3F4F6', drawBorder: false }, ticks: { font: { weight: 'bold', size: 10 } } },
                            x: { grid: { display: false }, ticks: { font: { weight: 'bold', size: 10 } } }
                        }
                    }
                });
            }
        }

        // Register for HTMX navigation (called by global layout dispatcher)
        window.__initPageCharts = initReportCharts;
        // Also run immediately on first load
        document.addEventListener('DOMContentLoaded', initReportCharts);
        document.body.addEventListener('htmx:afterSwap', function(evt) {
            if (evt.detail.target && evt.detail.target.id === 'main-content') {
                initReportCharts();
            }
        });
    </script>

    <!-- Modal Add Expense -->
    <x-modal name="add-expense" title="Catat Pengeluaran Baru" maxWidth="lg">
        <form action="{{ route('expenses.store') }}" method="POST" class="p-8">
            @csrf
            <!-- Judul Pengeluaran -->
            <div class="mb-6">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Judul Pengeluaran</label>
                <input type="text" name="title" class="w-full rounded-xl border-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 font-bold text-sm" placeholder="Contoh: Bayar Listrik, Pembelian Bahan" required>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Tanggal Transaksi</label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full rounded-xl border-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 font-bold text-sm" required>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Pilih Kategori</label>
                    <select name="category" class="w-full rounded-xl border-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 font-bold text-sm uppercase" required>
                        <option value="Umum">Umum</option>
                        <option value="Operasional">Operasional</option>
                        <option value="Gaji Karyawan">Gaji Karyawan</option>
                        <option value="Sewa Tempat">Sewa Tempat</option>
                        <option value="Utilitas">Utilitas (Listrik/Air/Wifi)</option>
                        <option value="Bahan Baku">Bahan Baku</option>
                        <option value="Pemeliharaan">Pemeliharaan</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
            </div>
            <div class="mb-6" x-data="{ 
                displayAmount: '', 
                rawAmount: '',
                format(val) {
                    if (val === null || val === undefined || val === '') return '';
                    let str = val.toString();
                    if (str.includes('.') && (str.match(/\./g) || []).length === 1 && /^\d+\.\d{1,2}$/.test(str)) {
                        str = Math.floor(parseFloat(str)).toString();
                    }
                    str = str.replace(/\D/g, '');
                    return str.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                }
            }">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Nominal Pengeluaran</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-black text-sm">Rp</span>
                    <input type="text" 
                           x-model="displayAmount" 
                           @input="rawAmount = displayAmount.replace(/\D/g, ''); displayAmount = format(rawAmount)"
                           class="w-full pl-12 rounded-xl border-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 font-black text-lg" 
                           placeholder="10.000" 
                           required>
                    <input type="hidden" name="amount" :value="rawAmount">
                </div>
            </div>
            <div class="mb-8">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Detail Keterangan <span class="text-[8px] opacity-50">(Opsional)</span></label>
                <textarea name="description" rows="3" class="w-full rounded-xl border-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm font-medium" placeholder="Tuliskan alasan pengeluaran..."></textarea>
            </div>

            <div class="flex gap-4">
                <button type="button" @click="show = false" class="flex-1 px-6 py-3.5 bg-gray-50 text-gray-500 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-gray-100 transition-all">
                    Batalkan
                </button>
                <button type="submit" class="flex-[2] px-6 py-3.5 bg-indigo-600 text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition-all">
                    Simpan Catatan
                </button>
            </div>
        </form>
    </x-modal>
</x-admin-layout>
