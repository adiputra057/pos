<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrinterSetting extends Model
{
    protected $fillable = [
        'printer_name',
        'printer_type',
        'is_default',
        'paper_size',
        'auto_print',
        'print_copies',
        'connection_type',
        'ip_address',
        'port',
        'is_active'
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'auto_print' => 'boolean',
        'print_copies' => 'integer',
        'port' => 'integer',
        'is_active' => 'boolean'
    ];

    /**
     * Get the default printer
     */
    public static function getDefault()
    {
        return self::where('is_default', true)->where('is_active', true)->first();
    }

    /**
     * Get printers by type
     */
    public static function getByType($type)
    {
        return self::where('printer_type', $type)->where('is_active', true)->get();
    }

    /**
     * Set this printer as default
     */
    public function setAsDefault()
    {
        // Remove default from all printers of the same type
        self::where('printer_type', $this->printer_type)->update(['is_default' => false]);
        
        // Set this printer as default
        $this->is_default = true;
        $this->save();
    }
}
