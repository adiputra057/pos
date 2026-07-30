<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan - {{ $period ?? date('d/m/Y') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none; }
            body { padding: 0; margin: 0; }
        }
    </style>
</head>
<body class="bg-white p-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-8 border-b-2 border-gray-100 pb-6">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">LAPORAN PENJUALAN</h1>
                <p class="text-gray-500 font-medium">MiniMartPOS - Smart Inventory System</p>
            </div>
            <div class="text-right">
                <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Tanggal Cetak</p>
                <p class="text-lg font-black text-indigo-600">{{ date('d F Y') }}</p>
            </div>
        </div>

        @if(isset($period))
        <div class="mb-6 inline-block bg-indigo-50 px-4 py-2 rounded-xl border border-indigo-100">
            <p class="text-sm font-bold text-indigo-700">Periode Laporan: {{ $period }}</p>
        </div>
        @endif

        <table class="w-full text-left border-collapse mb-8">
            <thead>
                <tr class="bg-gray-50 border-y border-gray-200">
                    <th class="px-4 py-3 text-xs font-black uppercase text-gray-500 tracking-wider">Invoice</th>
                    <th class="px-4 py-3 text-xs font-black uppercase text-gray-500 tracking-wider">Waktu</th>
                    <th class="px-4 py-3 text-xs font-black uppercase text-gray-500 tracking-wider">Kasir</th>
                    <th class="px-4 py-3 text-xs font-black uppercase text-gray-500 tracking-wider">Pelanggan</th>
                    <th class="px-4 py-3 text-xs font-black uppercase text-gray-500 tracking-wider">Metode</th>
                    <th class="px-4 py-3 text-xs font-black uppercase text-gray-500 tracking-wider text-right">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($orders as $order)
                <tr>
                    <td class="px-4 py-4 text-sm font-bold text-gray-900">{{ $order->invoice_number }}</td>
                    <td class="px-4 py-4 text-sm text-gray-600 font-medium">{{ $order->created_at->format('H:i') }}</td>
                    <td class="px-4 py-4 text-sm text-gray-600">{{ $order->user->name }}</td>
                    <td class="px-4 py-4 text-sm text-gray-600">{{ $order->customer->name ?? 'Umum' }}</td>
                    <td class="px-4 py-4 text-xs font-black uppercase text-gray-400 tracking-widest">{{ $order->payment_method }}</td>
                    <td class="px-4 py-4 text-sm font-black text-gray-900 text-right">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-gray-900 text-white">
                    <td colspan="5" class="px-4 py-4 text-sm font-bold text-right uppercase tracking-widest">Total Pendapatan</td>
                    <td class="px-4 py-4 text-lg font-black text-right">Rp {{ number_format($orders->sum('total_price'), 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="mt-12 pt-8 border-t border-dashed border-gray-200 grid grid-cols-2 gap-8 text-center">
            <div>
                <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-16">Disetujui Oleh,</p>
                <div class="w-32 h-px bg-gray-200 mx-auto mb-2"></div>
                <p class="text-sm font-bold text-gray-900">( Manager / Owner )</p>
            </div>
            <div>
                <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-16">Dibuat Oleh,</p>
                <div class="w-32 h-px bg-gray-200 mx-auto mb-2"></div>
                <p class="text-sm font-bold text-gray-900">( {{ auth()->user()->name }} )</p>
            </div>
        </div>

        <div class="mt-12 no-print flex justify-center gap-4">
            <button onclick="window.print()" class="px-8 py-3 bg-indigo-600 text-white rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-100">
                Cetak Laporan
            </button>
            <button onclick="window.close()" class="px-8 py-3 bg-gray-100 text-gray-600 rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-gray-200 transition-all">
                Tutup Halaman
            </button>
        </div>
    </div>

    <script>
        // Auto trigger print if needed
        // window.onload = () => window.print();
    </script>
</body>
</html>
