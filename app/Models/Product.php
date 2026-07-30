<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'supplier_id',
        'name',
        'sku',
        'barcode',
        'buying_price',
        'selling_price',
        'wholesale_price',
        'min_wholesale_qty',
        'box_price',
        'box_quantity',
        'stock',
        'min_stock',
        'image',
        'warehouse_stock',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function stockLogs()
    {
        return $this->hasMany(StockLog::class)->latest();
    }

    public function adjustStock($quantity, $type, $note = null, $userId = null, $location = 'display')
    {
        if ($location === 'warehouse') {
            $this->warehouse_stock += $quantity;
        } else {
            $this->stock += $quantity;
        }
        $this->save();

        $this->stockLogs()->create([
            'user_id' => $userId ?? auth()->id(),
            'type' => $type,
            'quantity' => $quantity,
            'stock_location' => $location,
            'balance_after' => $location === 'warehouse' ? $this->warehouse_stock : $this->stock,
            'note' => $note,
        ]);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function units()
    {
        return $this->hasMany(ProductUnit::class);
    }
}
