<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Laba/Rugi Bulanan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            padding: 20px;
            font-size: 12px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #333;
            padding-bottom: 15px;
        }
        
        .header h1 {
            font-size: 24px;
            color: #333;
            margin-bottom: 5px;
        }
        
        .header p {
            color: #666;
            font-size: 14px;
        }
        
        .info {
            margin-bottom: 20px;
        }
        
        .info p {
            margin: 5px 0;
            color: #555;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        thead {
            background-color: #f3f4f6;
        }
        
        th {
            padding: 12px 8px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #ddd;
            font-size: 11px;
            text-transform: uppercase;
        }
        
        td {
            padding: 10px 8px;
            border: 1px solid #ddd;
        }
        
        tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }
        
        tbody tr:hover {
            background-color: #f3f4f6;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .profit {
            color: #059669;
            font-weight: bold;
        }
        
        .loss {
            color: #dc2626;
            font-weight: bold;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
        }
        
        .status-profit {
            background-color: #d1fae5;
            color: #059669;
        }
        
        .status-loss {
            background-color: #fee2e2;
            color: #dc2626;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 10px;
        }
        
        .summary {
            margin-top: 30px;
            padding: 15px;
            background-color: #f9fafb;
            border-left: 4px solid #4f46e5;
        }
        
        .summary h3 {
            margin-bottom: 10px;
            color: #333;
        }
        
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
        
        .summary-item {
            padding: 10px;
            background-color: white;
            border-radius: 4px;
        }
        
        .summary-label {
            font-size: 10px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .summary-value {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }
        
        @media print {
            body {
                padding: 10px;
            }
            
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN LABA/RUGI BULANAN</h1>
        <p>Periode: 12 Bulan Terakhir</p>
        <p>Dicetak pada: {{ date('d F Y, H:i') }} WIB</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Bulan</th>
                <th class="text-right">Pendapatan</th>
                <th class="text-right">COGS</th>
                <th class="text-right">Pengeluaran</th>
                <th class="text-right">Total Biaya</th>
                <th class="text-right">Laba Bersih</th>
                <th class="text-right">Margin</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalRevenue = 0;
                $totalCogs = 0;
                $totalExpenses = 0;
                $totalNetProfit = 0;
                $profitMonths = 0;
            @endphp
            
            @foreach($monthlyData as $data)
                @php
                    $totalRevenue += $data['revenue'];
                    $totalCogs += $data['cogs'];
                    $totalExpenses += $data['expenses'];
                    $totalNetProfit += $data['net_profit'];
                    if ($data['status'] == 'profit') $profitMonths++;
                @endphp
                <tr>
                    <td><strong>{{ $data['month'] }}</strong></td>
                    <td class="text-right">Rp {{ number_format($data['revenue'], 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($data['cogs'], 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($data['expenses'], 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($data['total_expenses'], 0, ',', '.') }}</td>
                    <td class="text-right {{ $data['status'] == 'profit' ? 'profit' : 'loss' }}">
                        Rp {{ number_format($data['net_profit'], 0, ',', '.') }}
                    </td>
                    <td class="text-right {{ $data['status'] == 'profit' ? 'profit' : 'loss' }}">
                        {{ number_format($data['margin'], 1) }}%
                    </td>
                    <td class="text-center">
                        <span class="status-badge {{ $data['status'] == 'profit' ? 'status-profit' : 'status-loss' }}">
                            {{ $data['status'] == 'profit' ? 'UNTUNG' : 'RUGI' }}
                        </span>
                    </td>
                </tr>
            @endforeach
            
            <tr style="background-color: #e5e7eb; font-weight: bold;">
                <td>TOTAL</td>
                <td class="text-right">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($totalCogs, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($totalCogs + $totalExpenses, 0, ',', '.') }}</td>
                <td class="text-right {{ $totalNetProfit >= 0 ? 'profit' : 'loss' }}">
                    Rp {{ number_format($totalNetProfit, 0, ',', '.') }}
                </td>
                <td class="text-right {{ $totalNetProfit >= 0 ? 'profit' : 'loss' }}">
                    {{ $totalRevenue > 0 ? number_format(($totalNetProfit / $totalRevenue) * 100, 1) : 0 }}%
                </td>
                <td class="text-center">-</td>
            </tr>
        </tbody>
    </table>

    <div class="summary">
        <h3>Ringkasan</h3>
        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-label">Total Pendapatan</div>
                <div class="summary-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Total Laba Bersih</div>
                <div class="summary-value {{ $totalNetProfit >= 0 ? 'profit' : 'loss' }}">
                    Rp {{ number_format($totalNetProfit, 0, ',', '.') }}
                </div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Bulan Untung</div>
                <div class="summary-value profit">{{ $profitMonths }} dari 12 bulan</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Rata-rata Margin</div>
                <div class="summary-value">
                    {{ $totalRevenue > 0 ? number_format(($totalNetProfit / $totalRevenue) * 100, 1) : 0 }}%
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Laporan ini digenerate secara otomatis oleh Sistem POS Kasir</p>
        <p>© {{ date('Y') }} - Semua hak dilindungi</p>
    </div>

    <script>
        // Auto print when page loads
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
