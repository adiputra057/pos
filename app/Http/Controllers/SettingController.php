<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentSetting;
use App\Models\SecuritySetting;
use App\Models\PrinterSetting;

class SettingController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index()
    {
        $paymentMethods = PaymentSetting::orderBy('display_order')->get();
        $securitySettings = SecuritySetting::current();
        $printers = PrinterSetting::all();
        
        return view('settings.index', compact('paymentMethods', 'securitySettings', 'printers'));
    }

    /**
     * Update the settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'store_name' => 'required|string|max:255',
            'store_address' => 'nullable|string|max:500',
            'store_phone' => 'nullable|string|max:20',
            'store_email' => 'nullable|email|max:255',
            'receipt_header' => 'nullable|string|max:500',
            'receipt_footer' => 'nullable|string|max:500',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'currency' => 'nullable|string|max:10',
        ]);

        // Store settings in session or database
        // For now, we'll use session
        session(['settings' => $validated]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pengaturan berhasil disimpan!'
            ]);
        }

        return redirect()->route('settings.index')->with('success', 'Pengaturan berhasil disimpan!');
    }
}
