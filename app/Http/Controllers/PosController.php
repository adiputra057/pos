<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PosController extends Controller
{
    protected $stockService;

    public function __construct(\App\Services\StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    public function index()
    {
        $trackStock = \App\Models\Setting::get('track_stock', '1') === '1';
        $productsQuery = \App\Models\Product::with('category', 'supplier', 'units');
        if ($trackStock) {
            $productsQuery->where('stock', '>', 0);
        }
        $products = $productsQuery->get();
        $customers = \App\Models\Customer::all();
        $categories = \App\Models\Category::all();
        
        $paymentMethods = \App\Models\PaymentSetting::where('is_active', true)
            ->orderBy('display_order')
            ->get();
            
        $quickCash = \App\Models\Setting::get('quick_cash_nominals', '5000,10000,20000,50000,100000');
        $qrisImage = \App\Models\Setting::get('qris_image', '');
        
        $recentOrders = \App\Models\Order::with('customer', 'user', 'items.product')
            ->whereDate('created_at', now()->format('Y-m-d'))
            ->latest()
            ->take(10)
            ->get();
        
        return view('pos.index', compact('customers', 'products', 'categories', 'paymentMethods', 'quickCash', 'qrisImage', 'recentOrders'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'payment_amount' => 'required|numeric',
            'payment_method' => 'required|string',
            'customer_id' => 'nullable|exists:customers,id',
            'cart' => 'required|array',
            'cart.*.id' => 'required|exists:products,id',
            'cart.*.qty' => 'required|integer|min:1',
            'cart.*.unit' => 'required|string',
        ]);

        \Illuminate\Support\Facades\DB::beginTransaction();

        try {
            $totalPrice = 0;
            $items = [];

            foreach ($request->cart as $item) {
                // Find product and load its units
                $product = \App\Models\Product::with('units')->lockForUpdate()->find($item['id']);
                
                $unitName = strtolower($item['unit']);
                
                // Find matching product unit in product_units
                $productUnit = $product->units->first(function($u) use ($unitName) {
                    return strtolower($u->unit_name) === $unitName;
                });
                
                if (!$productUnit) {
                    throw new \Exception("Satuan {$item['unit']} tidak valid untuk produk {$product->name}.");
                }
                
                $conversionFactor = (int)$productUnit->conversion_factor;
                $qtyToDeduct = $item['qty'] * $conversionFactor;
                
                // Determine price per unit
                // If it is the base unit (usually pcs) and quantity is greater than or equal to min_wholesale_qty,
                // and wholesale_price is defined, we can use the wholesale price.
                // Otherwise, use the standard price of the selected unit!
                $pricePerUnit = (float)$productUnit->price;
                if ($productUnit->is_base) {
                    $minQty = $product->min_wholesale_qty ?? 5;
                    if ($product->wholesale_price !== null && $item['qty'] >= $minQty) {
                        $pricePerUnit = (float)$product->wholesale_price;
                    }
                }

                $trackStock = \App\Models\Setting::get('track_stock', '1') == '1';

                if ($trackStock) {
                    if ($product->stock < $qtyToDeduct) {
                        throw new \Exception("Stok untuk {$product->name} tidak mencukupi.");
                    }

                    // Use StockService to log the outflow
                    $this->stockService->adjustStock(
                        product: $product, 
                        quantity: -$qtyToDeduct, 
                        type: 'out', 
                        note: 'Penjualan POS (' . ucfirst($productUnit->unit_name) . ')'
                    );
                }
                
                $totalPrice += $pricePerUnit * $item['qty'];
                $items[] = [
                    'product_id' => $product->id,
                    'qty' => $item['qty'],
                    'unit' => $productUnit->unit_name,
                    'price' => $pricePerUnit,
                ];
            }
            
            $changeAmount = $request->payment_amount - $totalPrice;

            if ($changeAmount < 0) {
                throw new \Exception("Uang pembayaran kurang.");
            }

            $order = \App\Models\Order::create([
                'user_id' => auth()->id(),
                'customer_id' => $request->customer_id,
                'invoice_number' => \App\Models\Setting::get('invoice_prefix', 'INV') . '-' . str_pad((\App\Models\Order::max('id') + 1), 6, '0', STR_PAD_LEFT),
                'total_price' => $totalPrice,
                'subtotal' => $totalPrice,
                'tax' => 0,
                'discount' => 0,
                'payment_amount' => $request->payment_amount,
                'change_amount' => $changeAmount,
                'payment_method' => $request->payment_method,
            ]);

            // Create Payment Record
            \App\Models\Payment::create([
                'order_id' => $order->id,
                'amount' => $totalPrice,
                'payment_method' => $request->payment_method,
            ]);

            foreach ($items as $item) {
                \App\Models\OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['qty'],
                    'unit' => $item['unit'],
                    'price' => $item['price'],
                ]);
            }

            \Illuminate\Support\Facades\DB::commit();

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'invoice_number' => $order->invoice_number,
                'change' => $changeAmount,
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
}
