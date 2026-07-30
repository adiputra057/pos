<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentSetting extends Model
{
    protected $fillable = [
        'method_name',
        'is_active',
        'admin_fee',
        'minimum_amount',
        'icon',
        'display_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'admin_fee' => 'decimal:2',
        'minimum_amount' => 'decimal:2',
        'display_order' => 'integer'
    ];

    /**
     * Get active payment methods ordered by display_order
     */
    public static function getActiveMethods()
    {
        return self::where('is_active', true)
            ->orderBy('display_order')
            ->get();
    }

    /**
     * Get method display name
     */
    public function getDisplayNameAttribute()
    {
        if ($this->method_name === 'transfer') {
            return 'Transfer Bank';
        }
        if ($this->method_name === 'qris') {
            return 'QRIS';
        }
        return ucfirst(str_replace('_', ' ', $this->method_name));
    }
}
