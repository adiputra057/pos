<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductUnit extends Model
{
    protected $fillable = [
        'product_id',
        'unit_name',
        'conversion_factor',
        'price',
        'is_base'
    ];

    protected $casts = [
        'is_base' => 'boolean',
        'price' => 'float',
        'conversion_factor' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
