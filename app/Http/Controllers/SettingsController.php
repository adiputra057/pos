<?php

namespace App\Http\Controllers;

use App\Models\PaymentSetting;
use App\Models\SecuritySetting;
use App\Models\PrinterSetting;
use App\Models\Setting;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SettingsController extends Controller
{
    /**
     * Display the settings page
     */
    public function index($tab = 'general')
    {
        $paymentMethods = PaymentSetting::orderBy('display_order')->get();
        $securitySettings = SecuritySetting::current();
        $printers = PrinterSetting::all();
        
        // Get general settings from database
        $settings = [
            'store_name' => Setting::get('store_name', 'MiniMartPOS'),
            'store_phone' => Setting::get('store_phone', ''),
            'store_address' => Setting::get('store_address', ''),
            'store_email' => Setting::get('store_email', ''),
            'store_logo' => Setting::get('store_logo', ''),
            'receipt_header' => Setting::get('receipt_header', ''),
            'receipt_footer' => Setting::get('receipt_footer', ''),
            'currency' => Setting::get('currency', 'IDR'),
            'currency_precision' => Setting::get('currency_precision', '0'),
            'store_timezone' => Setting::get('store_timezone', 'Asia/Jakarta'),
            'tax_rate' => Setting::get('tax_rate', '0'),
            'log_retention_days' => Setting::get('log_retention_days', '30'),
            'show_logo_on_receipt' => Setting::get('show_logo_on_receipt', '1'),
            'whatsapp_number' => Setting::get('whatsapp_number', ''),
            'instagram_username' => Setting::get('instagram_username', ''),
            'default_paper_size' => Setting::get('default_paper_size', '58mm'),
            'invoice_prefix' => Setting::get('invoice_prefix', 'TRX'),
            'low_stock_threshold' => Setting::get('low_stock_threshold', '10'),
            'service_charge' => Setting::get('service_charge', '0'),
            'default_discount' => Setting::get('default_discount', '0'),
            'enable_scan_sound' => Setting::get('enable_scan_sound', '1'),
            'track_stock' => Setting::get('track_stock', '1'),
            'qris_image' => Setting::get('qris_image', ''),
            'quick_cash_nominals' => Setting::get('quick_cash_nominals', '5000,10000,20000,50000,100000'),
        ];
        
        $totalLogs = ActivityLog::count();
        $lastBackup = Setting::get('last_backup_at', 'Belum pernah');
        
        return view('settings.index', compact('paymentMethods', 'securitySettings', 'printers', 'tab', 'settings', 'totalLogs', 'lastBackup'));
    }

    /**
     * Update general settings
     */
    public function updateGeneralSettings(Request $request)
    {
        $validated = $request->validate([
            'store_name' => 'required|string|max:255',
            'store_phone' => 'nullable|string|max:20',
            'store_address' => 'nullable|string|max:500',
            'store_email' => 'nullable|email|max:255',
            'store_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'receipt_header' => 'nullable|string|max:500',
            'receipt_footer' => 'nullable|string|max:500',
            'show_logo_on_receipt' => 'nullable|boolean',
            'whatsapp_number' => 'nullable|string|max:20',
            'instagram_username' => 'nullable|string|max:50',
            'default_paper_size' => 'nullable|string|in:58mm,80mm',
            'invoice_prefix' => 'nullable|string|max:10',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'service_charge' => 'nullable|numeric|min:0|max:100',
            'default_discount' => 'nullable|numeric|min:0|max:100',
            'enable_scan_sound' => 'nullable|boolean',
            'track_stock' => 'nullable|boolean',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'currency' => 'nullable|string|max:10',
            'currency_precision' => 'nullable|integer|min:0|max:4',
            'store_timezone' => 'nullable|string|max:50',
        ]);

        // Handle Logo Upload
        if ($request->hasFile('store_logo')) {
            // Delete old logo if exists
            $oldLogo = Setting::get('store_logo');
            if ($oldLogo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($oldLogo);
            }
            $validated['store_logo'] = $request->file('store_logo')->store('store', 'public');
        }

        // Get old values for logging
        $oldValues = [];
        foreach ($validated as $key => $value) {
            if ($key !== 'store_logo' || $request->hasFile('store_logo')) {
                 $oldValues[$key] = Setting::get($key);
            }
        }
        
        // Save to database
        foreach ($validated as $key => $value) {
            if ($key === 'store_logo' && !$request->hasFile('store_logo')) continue;
            Setting::set($key, $value);
        }

        ActivityLog::log('update_general_settings', 'settings', 'Updated general store settings', $oldValues, $validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pengaturan umum berhasil diperbarui'
            ]);
        }

        return back()->with('success', 'Pengaturan umum berhasil diperbarui');
    }

    /**
     * Update payment settings
     */
    public function updatePaymentSettings(Request $request)
    {
        $validated = $request->validate([
            'methods' => 'required|array',
            'methods.*.id' => 'required|exists:payment_settings,id',
            'methods.*.is_active' => 'boolean',
            'methods.*.admin_fee' => 'numeric|min:0',
            'methods.*.minimum_amount' => 'numeric|min:0',
            'methods.*.display_order' => 'integer|min:0',
            'quick_cash_nominals' => 'nullable|string',
            'qris_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        foreach ($validated['methods'] as $methodData) {
            $method = PaymentSetting::find($methodData['id']);
            $method->update([
                'is_active' => $methodData['is_active'] ?? false,
                'admin_fee' => $methodData['admin_fee'] ?? 0,
                'minimum_amount' => $methodData['minimum_amount'] ?? 0,
                'display_order' => $methodData['display_order'] ?? 0,
            ]);
        }

        if ($request->hasFile('qris_image')) {
            $path = $request->file('qris_image')->store('settings', 'public');
            Setting::set('qris_image', $path);
        }

        if (isset($validated['quick_cash_nominals'])) {
            Setting::set('quick_cash_nominals', $validated['quick_cash_nominals']);
        }

        ActivityLog::log('update_payment_settings', 'settings', 'Updated payment settings');

        return response()->json([
            'success' => true,
            'message' => 'Pengaturan pembayaran berhasil diperbarui'
        ]);
    }

    /**
     * Update security settings
     */
    public function updateSecuritySettings(Request $request)
    {
        $validated = $request->validate([
            'session_timeout' => 'required|integer|min:5|max:1440',
            'auto_logout_on_close' => 'boolean',
            'require_pin_for_void' => 'boolean',
            'require_pin_for_refund' => 'boolean',
            'require_pin_for_delete' => 'boolean',
            'large_transaction_threshold' => 'numeric|min:0',
            'require_approval_large_transaction' => 'boolean',
            'enable_activity_log' => 'boolean',
            'max_login_attempts' => 'nullable|integer|min:1|max:20',
            'lockout_duration' => 'nullable|integer|min:1|max:1440',
            'min_password_length' => 'nullable|integer|min:4|max:32',
            'require_password_complexity' => 'boolean',
            'password_expiry_days' => 'nullable|integer|min:0|max:365',
            'allowed_ips' => 'nullable|string',
            'operational_hours_start' => 'nullable',
            'operational_hours_end' => 'nullable',
        ]);

        $securitySettings = SecuritySetting::current();
        $oldValues = $securitySettings->toArray();
        $securitySettings->update($validated);

        ActivityLog::log('update_security_settings', 'settings', 'Updated security settings', $oldValues, $validated);

        return response()->json([
            'success' => true,
            'message' => 'Pengaturan keamanan berhasil diperbarui'
        ]);
    }

    /**
     * Update printer settings
     */
    public function updatePrinterSettings(Request $request, PrinterSetting $printer)
    {
        $validated = $request->validate([
            'printer_name' => 'required|string|max:100',
            'printer_type' => 'required|in:receipt,kitchen,bar',
            'is_default' => 'boolean',
            'paper_size' => 'required|in:58mm,80mm',
            'auto_print' => 'boolean',
            'print_copies' => 'required|integer|min:1|max:5',
            'connection_type' => 'required|in:usb,network,bluetooth',
            'ip_address' => 'nullable|ip',
            'port' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $oldValues = $printer->toArray();
        $printer->update($validated);

        if ($validated['is_default'] ?? false) {
            $printer->setAsDefault();
        }

        ActivityLog::log('update_printer_settings', 'settings', "Updated printer: {$printer->printer_name}", $oldValues, $validated);

        return back()->with('success', 'Pengaturan printer berhasil diperbarui');
    }

    /**
     * Create new printer
     */
    public function storePrinter(Request $request)
    {
        $validated = $request->validate([
            'printer_name' => 'required|string|max:100',
            'printer_type' => 'required|in:receipt,kitchen,bar',
            'is_default' => 'boolean',
            'paper_size' => 'required|in:58mm,80mm',
            'auto_print' => 'boolean',
            'print_copies' => 'required|integer|min:1|max:5',
            'connection_type' => 'required|in:usb,network,bluetooth',
            'ip_address' => 'nullable|ip',
            'port' => 'nullable|integer',
        ]);

        $printer = PrinterSetting::create($validated);

        if ($validated['is_default'] ?? false) {
            $printer->setAsDefault();
        }

        ActivityLog::log('create_printer', 'settings', "Created new printer: {$printer->printer_name}");

        return response()->json(['message' => 'Printer berhasil ditambahkan', 'printer' => $printer]);
    }

    /**
     * Delete printer
     */
    public function destroyPrinter(PrinterSetting $printer)
    {
        $printerName = $printer->printer_name;
        $printer->delete();

        ActivityLog::log('delete_printer', 'settings', "Deleted printer: {$printerName}");

        return response()->json(['message' => 'Printer berhasil dihapus']);
    }

    /**
     * Get activity logs
     */
    public function activityLogs(Request $request)
    {
        $query = ActivityLog::with('user')->orderBy('created_at', 'desc');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(15)->withQueryString();
        $users = \App\Models\User::all();

        return view('settings.activity-logs', compact('logs', 'users'));
    }

    /**
     * Update system maintenance settings
     */
    public function updateSystemSettings(Request $request)
    {
        $validated = $request->validate([
            'log_retention_days' => 'required|integer|min:1|max:365',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        ActivityLog::log('update_system_settings', 'settings', "Updated system maintenance settings: retention {$validated['log_retention_days']} days");

        return back()->with('success', 'Pengaturan sistem berhasil diperbarui');
    }

    /**
     * Manually cleanup activity logs
     */
    public function cleanupLogs(Request $request)
    {
        $days = Setting::get('log_retention_days', 30);
        $date = now()->subDays($days);
        
        $count = ActivityLog::where('created_at', '<', $date)->count();
        ActivityLog::where('created_at', '<', $date)->delete();

        ActivityLog::log('cleanup_logs', 'settings', "Manually cleaned up {$count} activity logs older than {$days} days");

        return back()->with('success', "Berhasil menghapus {$count} log aktivitas lama.");
    }

    /**
     * Download database backup
     */
    public function downloadBackup()
    {
        $tables = DB::select('SHOW TABLES');
        $dbName = config('database.connections.mysql.database');
        $property = "Tables_in_{$dbName}";
        
        $sql = "-- Database Backup: " . $dbName . "\n";
        $sql .= "-- Generated at: " . date('Y-m-d H:i:s') . "\n\n";
        $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

        foreach ($tables as $table) {
            $tableName = $table->$property;
            
            // Structure
            $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`")[0];
            $sql .= "-- Structure for table `{$tableName}`\n";
            $sql .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
            $sql .= $createTable->{'Create Table'} . ";\n\n";
            
            // Data
            $rows = DB::table($tableName)->get();
            if ($rows->count() > 0) {
                $sql .= "-- Data for table `{$tableName}`\n";
                foreach ($rows as $row) {
                    $item = (array)$row;
                    $keys = array_keys($item);
                    $values = array_values($item);
                    
                    $escapedValues = array_map(function($value) {
                        if (is_null($value)) return "NULL";
                        return "'" . addslashes($value) . "'";
                    }, $values);
                    
                    $sql .= "INSERT INTO `{$tableName}` (`" . implode("`, `", $keys) . "`) VALUES (" . implode(", ", $escapedValues) . ");\n";
                }
                $sql .= "\n";
            }
        }
        
        $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";

        Setting::set('last_backup_at', date('Y-m-d H:i:s'));
        
        $filename = "backup-" . $dbName . "-" . date('Y-m-d-His') . ".sql";
        
        return response($sql)
            ->withHeaders([
                'Content-Type' => 'application/x-sql',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ]);
    }
}
