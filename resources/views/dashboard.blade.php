<x-admin-layout>
    <div class="mb-8">
        <h1 class="text-3xl font-black text-gray-900 tracking-tight mb-1.5">Ringkasan Bisnis</h1>
        <p class="text-sm text-gray-500 font-medium">Pantau performa toko Anda hari ini.</p>
    </div>
    <!-- Stats Grid -->

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Revenue Card -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-indigo-50 rounded-xl">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium {{ $revenueGrowth >= 0 ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
                    {{ $revenueGrowth >= 0 ? '+' : '' }}{{ number_format($revenueGrowth, 1) }}%
                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $revenueGrowth >= 0 ? 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6' : 'M13 17h8m0 0V9m0 8l-8-8-4 4-6-6' }}"></path></svg>
                </span>
            </div>
            <p class="text-sm font-medium text-gray-500 mb-1">Pendapatan Hari Ini</p>
            <h3 class="text-3xl font-bold text-gray-900">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</h3>
            <p class="text-xs text-gray-400 mt-2">Dibandingkan kemarin</p>
        </div>

        <!-- Profit Card -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-emerald-50 rounded-xl">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-emerald-50 text-emerald-700">
                    +8.2%
                </span>
            </div>
            <p class="text-sm font-medium text-gray-500 mb-1">Keuntungan Hari Ini</p>
            <h3 class="text-3xl font-bold text-gray-900">Rp {{ number_format($todayProfit, 0, ',', '.') }}</h3>
            <p class="text-xs text-gray-400 mt-2">Margin bersih hari ini</p>
        </div>

        <!-- Low Stock Alerts -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-orange-50 rounded-xl">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-orange-50 text-orange-700">
                    PERLU TINDAKAN
                </span>
            </div>
            <p class="text-sm font-medium text-gray-500 mb-1">Peringatan Stok Rendah</p>
            @if($lowStockItems > 0)
                <h3 class="text-3xl font-bold text-gray-900">{{ str_pad($lowStockItems, 2, '0', STR_PAD_LEFT) }} Barang</h3>
            @else
                <h3 class="text-3xl font-bold text-gray-900">-</h3>
            @endif
            <p class="text-xs text-gray-400 mt-2">Disarankan segera restock</p>
        </div>

        <!-- Inventory Valuation -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-purple-50 rounded-xl">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <div class="flex flex-col items-end">
                    <span class="text-[10px] font-black text-purple-600 uppercase tracking-widest">Total Aset</span>
                </div>
            </div>
            <p class="text-sm font-medium text-gray-500 mb-1">Nilai Persediaan</p>
            <h3 class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalInventoryValue, 0, ',', '.') }}</h3>
            <p class="text-xs text-gray-400 mt-2">Etalase + Gudang</p>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Chart Section -->
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-gray-900 text-lg">Tren Pendapatan Bulan Ini ({{ date('F Y') }})</h3>
            </div>
            <div class="h-80 w-full relative">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Top Selling Products -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-gray-900 text-lg">Produk Terlaris</h3>
                <a href="{{ route('products.index') }}" class="text-sm text-indigo-600 font-medium hover:underline">Lihat Semua</a>
            </div>
            <div class="space-y-6">
                @foreach($topProducts as $product)
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-gray-100 flex-shrink-0 overflow-hidden">
                        @if($product->image)
                            <img src="{{ Storage::url($product->image) }}" class="w-full h-full object-cover">
                        @else
                             <div class="flex items-center justify-center w-full h-full text-gray-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-bold text-gray-900 truncate">{{ $product->name }}</h4>
                        <p class="text-xs text-gray-500">{{ $product->category->name ?? 'Item' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-gray-900">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</p>
                        <p class="text-xs text-emerald-600 font-medium">{{ $product->total_sold }} Terjual</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-50 flex justify-between items-center">
            <h3 class="font-bold text-gray-900 text-lg">Aktivitas Toko Terbaru</h3>
            <a href="{{ route('orders.index') }}" class="text-sm text-indigo-600 font-medium hover:underline flex items-center">
                Lihat Semua Aktivitas
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">ID Transaksi</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Pelanggan</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Item</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($recentTransactions as $order)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm font-medium text-gray-500">#{{ $order->invoice_number }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <span class="text-sm font-bold text-gray-900">{{ $order->customer->name ?? 'Pelanggan Umum' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $order->items->count() }} Item</td>
                        <td class="px-6 py-4 text-sm font-bold text-gray-900">Rp {{ number_format($order->total_price, 2, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800">
                                LUNAS
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 text-right">{{ $order->created_at->diffForHumans() }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function initRevenueChart() {
            const chartCanvas = document.getElementById('revenueChart');
            if (!chartCanvas) return;
            
            // Destroy existing chart if any (to prevent duplicates on reload)
            if (window.revenueChartInstance) {
                window.revenueChartInstance.destroy();
            }

            const ctx = chartCanvas.getContext('2d');
            window.revenueChartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($chartLabels),
                    datasets: [{
                        label: 'Pendapatan',
                        data: @json($chartData),
                        backgroundColor: '#4F46E5',
                        borderRadius: 6,
                        maxBarThickness: 32,
                        categoryPercentage: 0.8,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1F2937',
                            padding: 12,
                            titleFont: { size: 13 },
                            bodyFont: { size: 13 },
                            callbacks: {
                                label: function(context) {
                                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { borderDash: [2, 2], drawBorder: false },
                            ticks: { display: false }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 12 }, color: '#9CA3AF' }
                        }
                    }
                }
            });
        }

        // Register for HTMX navigation (called by global layout dispatcher)
        window.__initPageCharts = initRevenueChart;
        // Also run immediately on first load
        document.addEventListener('DOMContentLoaded', initRevenueChart);
        document.body.addEventListener('htmx:afterSwap', function(evt) {
            if (evt.detail.target && evt.detail.target.id === 'main-content') {
                initRevenueChart();
            }
        });
    </script>
</x-admin-layout>
