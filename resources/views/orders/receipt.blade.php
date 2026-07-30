<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #{{ $order->invoice_number }}</title>

    <style>
        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact;
            margin: 0;
            padding: 0;
        }
        html, body {
            background: #fff;
            width: 100%;
            margin: 0;
            padding: 0;
        }
        .wrapper {
            width: 45mm;
            margin-left: 0;
            padding: 0 1mm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #000 !important;
            font-weight: 500;
            line-height: 1.3; /* Tightened line-height to save paper */
        }
        .header {
            text-align: center;
            margin-bottom: 4px;
            border-bottom: 1px dashed #000;
            padding: 4px 0; /* Compact header */
        }
        .store-name {
            font-size: 15px;
            font-weight: 900;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        .meta-info {
            margin-bottom: 4px;
            border-bottom: 1px dashed #000;
            padding: 2px 0;
            font-size: 10px;
        }
        .row {
            display: flex;
            width: 100%;
            margin-bottom: 1px; /* Minimal space between rows */
            align-items: baseline;
        }
        .col-left {
            flex: 1;
            text-align: left;
        }
        .col-right {
            text-align: right;
            min-width: 35%;
        }
        
        .item-block {
            margin-bottom: 4px; /* Compact items */
        }
        .item-name {
            font-weight: 500;
            text-transform: capitalize;
            font-size: 11px;
            display: block;
        }
        .item-details {
            font-size: 10px;
        }
        .divider {
            border-bottom: 1px dashed #000;
            margin: 4px 0;
        }
        .grand-total {
            font-weight: 900;
            font-size: 14px;
            border-top: 1px solid #000;
            margin-top: 2px;
            padding: 2px 0;
        }
        .footer {
            text-align: center;
            margin-top: 8px;
            font-size: 10px;
            margin-bottom: 20px; /* Reduced bottom margin */
            line-height: 1.2;
        }
        @media print {
            .no-print { display: none !important; }
            body { 
                margin: 0;
                padding: 0;
                width: 45mm;
            }
            @page {
                margin: 0;
                size: 58mm auto;
            }
        }
        .btn-print {
            display: block;
            width: 45mm;
            padding: 8px;
            background: #000;
            color: #fff;
            text-align: center;
            text-decoration: none;
            margin-top: 10px;
            font-weight: bold;
            border: none;
            cursor: pointer;
            font-size: 12px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        @if(\App\Models\Setting::get('show_logo_on_receipt', '1') == '1' && \App\Models\Setting::get('store_logo'))
            <div style="margin-bottom: 4px;">
                <img src="{{ asset('storage/' . \App\Models\Setting::get('store_logo')) }}" style="max-height: 40px; max-width: 100%; object-fit: contain;">
            </div>
        @endif
        <div class="store-name">{{ \App\Models\Setting::get('store_name', 'MiniMartPOS') }}</div>
        @if(\App\Models\Setting::get('store_address'))
            <div style="font-size: 9px;">{{ \App\Models\Setting::get('store_address') }}</div>
        @endif
        @if(\App\Models\Setting::get('store_phone'))
            <div style="font-size: 9px;">Telp: {{ \App\Models\Setting::get('store_phone') }}</div>
        @endif
        @if(\App\Models\Setting::get('receipt_header'))
            <div style="font-size: 9px; margin-top: 4px; font-style: italic;">{{ \App\Models\Setting::get('receipt_header') }}</div>
        @endif
    </div>

    <div class="meta-info">
        <div class="row">
            <div class="col-left">{{ $order->created_at->format('d/m/y H:i') }} WITA</div>
            <div class="col-right">{{ substr($order->invoice_number, -6) }}</div>
        </div>
        <div class="row" style="font-size: 9px;">
            <div class="col-left">{{ substr($order->user->name, 0, 15) }}</div>
            @if($order->customer)
                <div class="col-right">{{ substr($order->customer->name, 0, 15) }}</div>
            @endif
        </div>
    </div>

    <div class="items">
        @foreach($order->items as $item)
        <div class="item-block">
            <span class="item-name">{{ $item->product->name }}</span>
            <div class="row">
                <div class="col-left">{{ $item->quantity }}{{ $item->unit ? ' ' . ucfirst(strtolower($item->unit)) : '' }} x {{ number_format($item->price, 0, ',', '.') }}</div>
                <div class="col-right">{{ number_format($item->quantity * $item->price, 0, ',', '.') }}</div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="divider"></div>

    <div class="totals">
        <div class="row">
            <div class="col-left">Subtotal</div>
            <div class="col-right">{{ number_format($order->subtotal ?? $order->total_price, 0, ',', '.') }}</div>
        </div>
        @if($order->discount > 0)
        <div class="row">
            <div class="col-left">Disc:</div>
            <div class="col-right">-{{ number_format($order->discount, 0, ',', '.') }}</div>
        </div>
        @endif
        <div class="row grand-total">
            <div class="col-left">Total</div>
            <div class="col-right">{{ number_format($order->total_price, 0, ',', '.') }}</div>
        </div>
        <div class="row" style="margin-top: 2px;">
            <div class="col-left">{{ $order->payment_method === 'transfer' ? 'Transfer Bank' : ($order->payment_method === 'qris' ? 'QRIS' : ucfirst($order->payment_method)) }}</div>
            <div class="col-right">{{ number_format($order->payment_amount, 0, ',', '.') }}</div>
        </div>
        <div class="row">
            <div class="col-left">Kembalian</div>
            <div class="col-right">{{ number_format($order->change_amount, 0, ',', '.') }}</div>
        </div>
    </div>

    <div class="footer">
        <p>TERIMA KASIH</p>
        @if(\App\Models\Setting::get('receipt_footer'))
            @foreach(explode("\n", str_replace("\r", "", \App\Models\Setting::get('receipt_footer'))) as $line)
                <p>{{ $line }}</p>
            @endforeach
        @else
            <p>Barang yang sudah dibeli</p>
            <p>tidak dapat ditukar/dikembalikan</p>
        @endif
    </div>
</div>

    <button class="no-print btn-print" onclick="window.print()">PRINT RECEIPT</button>

    <script>
        window.addEventListener('load', function() {
            // Wait 300ms for browser to decode/render the logo image
            setTimeout(function() {
                window.print();
                if (window.self === window.top) {
                    setTimeout(function() {
                        window.close();
                    }, 500);
                }
            }, 300);
        });
    </script>
</body>
</html>
