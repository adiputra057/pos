<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditLogger
{
    public static function log($action, $model = null, $modelId = null, $details = null)
    {
        try {
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => $action,
                'model' => $model,
                'model_id' => $modelId,
                'ip_address' => request()->ip(),
                'details' => $details ? json_encode($details) : null,
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning("Failed to create audit log: " . $e->getMessage());
        }
    }
}
