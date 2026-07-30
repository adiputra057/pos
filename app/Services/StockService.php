<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockLog;
use App\Services\AuditLogger;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockService
{
    public function adjustStock(Product $product, int $quantity, string $type, ?string $note = null, $date = null, string $location = 'display')
    {
        $date = $date ?: now();

        return DB::transaction(function () use ($product, $quantity, $type, $note, $date, $location) {
            // Update product stock based on location
            if ($location === 'warehouse') {
                $product->warehouse_stock += $quantity;
            } else {
                $product->stock += $quantity;
            }
            $product->save();

            // Create Log
            StockLog::create([
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'type' => $type,
                'quantity' => $quantity,
                'stock_location' => $location,
                'balance_after' => $location === 'warehouse' ? $product->warehouse_stock : $product->stock,
                'note' => $note,
                'created_at' => $date,
            ]);

            // Create Expense if type is 'in' (Restock) - Only for warehouse or direct display
            if ($type === 'in' && $quantity > 0) {
                \App\Models\Expense::create([
                    'user_id' => Auth::id(),
                    'title' => 'Pembelian Stok (' . ucfirst($location) . '): ' . $product->name,
                    'amount' => $product->buying_price * $quantity,
                    'category' => 'Pembelian Stok',
                    'date' => $date,
                    'description' => "Penambahan stok {$quantity} unit ke {$location} @ Rp " . number_format($product->buying_price, 0, ',', '.') . ". Catatan: {$note}",
                ]);
            }

            // Log Audit
            AuditLogger::log('stock_adjustment', 'Product', $product->id, [
                'type' => $type,
                'quantity' => $quantity,
                'location' => $location,
                'balance' => $location === 'warehouse' ? $product->warehouse_stock : $product->stock,
                'note' => $note
            ]);

            return $product;
        });
    }

    /**
     * Transfer stock between display and warehouse
     */
    public function transferStock(Product $product, int $quantity, string $from, string $to, ?string $note = null)
    {
        if ($quantity <= 0) return $product;
        
        return DB::transaction(function () use ($product, $quantity, $from, $to, $note) {
            // Deduct from source
            if ($from === 'warehouse') {
                if ($product->warehouse_stock < $quantity) throw new \Exception("Stok gudang tidak mencukupi!");
                $product->warehouse_stock -= $quantity;
            } else {
                if ($product->stock < $quantity) throw new \Exception("Stok etalase tidak mencukupi!");
                $product->stock -= $quantity;
            }

            // Add to destination
            if ($to === 'warehouse') {
                $product->warehouse_stock += $quantity;
            } else {
                $product->stock += $quantity;
            }
            $product->save();

            // Log deduction
            StockLog::create([
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'type' => 'out',
                'quantity' => -$quantity,
                'stock_location' => $from,
                'balance_after' => $from === 'warehouse' ? $product->warehouse_stock : $product->stock,
                'note' => "Pindah stok ke " . ucfirst($to) . ($note ? " | " . $note : ""),
            ]);

            // Log addition
            StockLog::create([
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'type' => 'in',
                'quantity' => $quantity,
                'stock_location' => $to,
                'balance_after' => $to === 'warehouse' ? $product->warehouse_stock : $product->stock,
                'note' => "Pindah stok dari " . ucfirst($from) . ($note ? " | " . $note : ""),
            ]);

            return $product;
        });
    }
}
