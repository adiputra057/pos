<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    const UPDATED_AT = null; // Only use created_at

    protected $fillable = [
        'user_id',
        'action',
        'module',
        'description',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime'
    ];

    /**
     * Get the user that performed the action
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Log an activity
     */
    public static function log($action, $module = null, $description = null, $oldValues = null, $newValues = null, $userId = null)
    {
        $securitySettings = SecuritySetting::current();
        
        if (!$securitySettings->enable_activity_log) {
            return null;
        }

        return self::create([
            'user_id' => $userId ?? auth()->id(),
            'action' => $action,
            'module' => $module,
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }

    /**
     * Get logs for a specific user
     */
    public static function forUser($userId)
    {
        return self::where('user_id', $userId)->orderBy('created_at', 'desc')->get();
    }

    /**
     * Get logs for a specific module
     */
    public static function forModule($module)
    {
        return self::where('module', $module)->orderBy('created_at', 'desc')->get();
    }
}
