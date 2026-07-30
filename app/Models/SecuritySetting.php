<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SecuritySetting extends Model
{
    protected $fillable = [
        'session_timeout',
        'auto_logout_on_close',
        'require_pin_for_void',
        'require_pin_for_refund',
        'require_pin_for_delete',
        'large_transaction_threshold',
        'require_approval_large_transaction',
        'enable_activity_log',
        'max_login_attempts',
        'lockout_duration',
        'min_password_length',
        'require_password_complexity',
        'password_expiry_days',
        'allowed_ips',
        'operational_hours_start',
        'operational_hours_end'
    ];

    protected $casts = [
        'session_timeout' => 'integer',
        'auto_logout_on_close' => 'boolean',
        'require_pin_for_void' => 'boolean',
        'require_pin_for_refund' => 'boolean',
        'require_pin_for_delete' => 'boolean',
        'large_transaction_threshold' => 'decimal:2',
        'require_approval_large_transaction' => 'boolean',
        'enable_activity_log' => 'boolean',
        'max_login_attempts' => 'integer',
        'lockout_duration' => 'integer',
        'min_password_length' => 'integer',
        'require_password_complexity' => 'boolean',
        'password_expiry_days' => 'integer',
    ];

    /**
     * Get the current security settings (singleton pattern)
     */
    public static function current()
    {
        return self::first() ?? self::create([
            'session_timeout' => 30,
            'auto_logout_on_close' => false,
            'require_pin_for_void' => true,
            'require_pin_for_refund' => true,
            'require_pin_for_delete' => false,
            'large_transaction_threshold' => 1000000,
            'require_approval_large_transaction' => false,
            'enable_activity_log' => true,
            'max_login_attempts' => 5,
            'lockout_duration' => 15,
            'min_password_length' => 8,
            'require_password_complexity' => false,
            'password_expiry_days' => 0
        ]);
    }
}
