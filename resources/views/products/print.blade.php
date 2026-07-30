<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Produk - {{ date('d/m/Y') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none; }
            body { padding: 0; margin: 0; }
            @page { size: A4 landscape; margin: 1cm; }
        }
    </style>
</head>
<body class="bg-white text-gray-900 font-sans p-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center border-b-2 border-gray-900 pb-6 mb-8">
            <div>
                <h1 class="text-3xl font-black uppercase tracking-tighter text-gray-900">MiniMart POS</h1>
                <p class="text-gray-500 text-sm">Laporan Inventaris Produk</p>
            </div>
            <div class="text-right">
                <p class="font-bold">Tanggal: {{ date('d F Y') }}</p>
                <p class="text-xs text-gray-400">Dicetak oleh: {{ Auth::user()->name }}</p>
            </div>
        </div>

        <!-- Table -->
        <table class="w-full text-left border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-100 uppercase text-xs font-bold">
                    <th class="border border-gray-300 px-4 py-3">No</th>
                    <th class="border border-gray-300 px-4 py-3">SKU</th>
                    <th class="border border-gray-300 px-4 py-3">Nama Produk</th>
                    <th class="border border-gray-300 px-4 py-3">Kategori</th>
                    <th class="border border-gray-300 px-4 py-3">Supplier</th>
                    <th class="border border-gray-300 px-4 py-3 text-right">Harga Beli</th>
                    <th class="border border-gray-300 px-4 py-3 text-right">Harga Jual</th>
                    <th class="border border-gray-300 px-4 py-3 text-center">Etalase</th>
                    <th class="border border-gray-300 px-4 py-3 text-center">Gudang</th>
                    <th class="border border-gray-300 px-4 py-3 text-center">Total</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @foreach($products as $index => $product)
                <tr>
                    <td class="border border-gray-300 px-4 py-2 text-center">{{ $index + 1 }}</td>
                    <td class="border border-gray-300 px-4 py-2 font-mono text-xs">{{ $product->sku }}</td>
                    <td class="border border-gray-300 px-4 py-2 font-bold">{{ $product->name }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $product->category->name }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $product->supplier->name ?? '-' }}</td>
                    <td class="border border-gray-300 px-4 py-2 text-right">Rp {{ number_format($product->buying_price, 0, ',', '.') }}</td>
                    <td class="border border-gray-300 px-4 py-2 text-right font-bold">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                    <td class="border border-gray-300 px-4 py-2 text-center font-bold {{ $product->stock <= $product->min_stock ? 'text-red-600' : '' }}">
                        {{ $product->stock }} Pcs
                    </td>
                    <td class="border border-gray-300 px-4 py-2 text-center text-gray-600">
                        {{ $product->warehouse_stock }} Pcs
                        @if($product->box_quantity > 0)
                            <div class="text-[10px] text-gray-400">({{ ($product->warehouse_stock / $product->box_quantity) + 0 }} Dus)</div>
                        @endif
                    </td>
                    <td class="border border-gray-300 px-4 py-2 text-center font-bold bg-gray-50">
                        {{ $product->stock + $product->warehouse_stock }} Pcs
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Footer -->
        <div class="mt-12 flex justify-between">
            <div class="text-xs text-gray-400 italic">
                * Laporan ini dihasilkan secara otomatis oleh sistem MiniMart POS.
            </div>
            <div class="w-48 text-center">
                <p class="text-xs mb-16 italic text-gray-500">Mengetahui,</p>
                <div class="border-b border-gray-900 mx-auto w-32"></div>
                <p class="text-xs font-bold mt-1">( ............................ )</p>
            </div>
        </div>

        <!-- Float Actions -->
        <div class="fixed bottom-8 right-8 flex gap-3 no-print">
            <button onclick="window.close()" class="px-6 py-2.5 bg-gray-500 text-white rounded-xl font-bold hover:bg-gray-600 shadow-lg">
                Kembali
            </button>
            <button onclick="window.print()" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 shadow-lg">
                Cetak Sekarang
            </button>
        </div>
    </div>

    <script>
        // Auto print on load
        window.onload = function() {
            setTimeout(() => {
                // window.print();
            }, 500);
        }
    </script>
</body>
</html>
