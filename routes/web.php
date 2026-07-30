<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});



Route::get('/fix-permissions', function () {
    $user = \Illuminate\Support\Facades\Auth::user();
    if (!$user) return 'Not logged in';
    
    $role = \App\Models\Role::firstOrCreate(['name' => 'owner'], ['description' => 'Owner']);
    
    if (!$user->hasRole('owner')) {
        $user->roles()->attach($role);
        return "Role 'owner' assigned to {$user->name}. <a href='/stocks'>Go to Stocks</a>";
    }
    
    return "User already has 'owner' role. <a href='/stocks'>Go to Stocks</a>";
})->middleware('auth');

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StockController; // Added this use statement

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'role:owner,admin'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Role based routes for Owner and Admin only
    Route::middleware('role:owner,admin')->group(function () {
        Route::resource('users', \App\Http\Controllers\UserController::class);
        Route::get('/stocks/template', [StockController::class, 'downloadTemplate'])->name('stocks.template');
        Route::post('/stocks/import', [StockController::class, 'import'])->name('stocks.import');
        Route::post('/stocks/transfer', [StockController::class, 'transfer'])->name('stocks.transfer');
        Route::resource('stocks', StockController::class)->only(['index', 'create', 'store']);
        Route::resource('expenses', \App\Http\Controllers\ExpenseController::class);
        Route::get('/expenses-report', [\App\Http\Controllers\ExpenseController::class, 'report'])->name('expenses.report');
        Route::get('/expenses-report/export', [\App\Http\Controllers\ExpenseController::class, 'exportExcel'])->name('expenses.report.export');
        // Activity Logs
        Route::get('/settings/activity-logs', [\App\Http\Controllers\SettingsController::class, 'activityLogs'])->name('settings.activity-logs');

        Route::get('/settings/{tab?}', [\App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings/general', [\App\Http\Controllers\SettingsController::class, 'updateGeneralSettings'])->name('settings.update.general');
        Route::post('/settings/payment', [\App\Http\Controllers\SettingsController::class, 'updatePaymentSettings'])->name('settings.update.payment');
        Route::post('/settings/security', [\App\Http\Controllers\SettingsController::class, 'updateSecuritySettings'])->name('settings.update.security');
        Route::post('/settings/system', [\App\Http\Controllers\SettingsController::class, 'updateSystemSettings'])->name('settings.update.system');
        Route::post('/settings/system/cleanup', [\App\Http\Controllers\SettingsController::class, 'cleanupLogs'])->name('settings.system.cleanup');
        Route::get('/settings/backup/download', [\App\Http\Controllers\SettingsController::class, 'downloadBackup'])->name('settings.backup.download');
        
        // Printer Settings
        Route::post('/settings/printers', [\App\Http\Controllers\SettingsController::class, 'storePrinter'])->name('settings.printers.store');
        Route::patch('/settings/printers/{printer}', [\App\Http\Controllers\SettingsController::class, 'updatePrinterSettings'])->name('settings.printers.update');
        Route::delete('/settings/printers/{printer}', [\App\Http\Controllers\SettingsController::class, 'destroyPrinter'])->name('settings.printers.destroy');
        
        // Products, Suppliers, etc involve stock management - restricted
        Route::get('/products/next-sku', [\App\Http\Controllers\ProductController::class, 'nextSku'])->name('products.next-sku');
        Route::get('/products/export-excel', [\App\Http\Controllers\ProductController::class, 'exportExcel'])->name('products.export-excel');
        Route::get('/products/print', [\App\Http\Controllers\ProductController::class, 'printPDF'])->name('products.print');
        Route::resource('products', \App\Http\Controllers\ProductController::class);
        Route::resource('suppliers', \App\Http\Controllers\SupplierController::class);
        Route::resource('categories', \App\Http\Controllers\CategoryController::class);

        Route::get('/reports/monthly/export-excel', [\App\Http\Controllers\ReportController::class, 'exportMonthlyExcel'])->name('reports.monthly.export-excel');
        Route::get('/reports/monthly/print', [\App\Http\Controllers\ReportController::class, 'printMonthlyPDF'])->name('reports.monthly.print');
        Route::get('/reports/{tab?}', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    });

    // POS and Orders - accessible by all roles (Owner, Admin, Cashier)
    Route::get('/pos', [\App\Http\Controllers\PosController::class, 'index'])->name('pos.index');
    Route::post('/pos', [\App\Http\Controllers\PosController::class, 'store'])->name('pos.store');
    
    // Customers - accessible by all roles (Owner, Admin, Cashier)
    Route::resource('customers', \App\Http\Controllers\CustomerController::class);
    
    Route::get('/orders/export-excel', [\App\Http\Controllers\OrderController::class, 'exportExcel'])->name('orders.export-excel');
    Route::get('/orders/print', [\App\Http\Controllers\OrderController::class, 'printPDF'])->name('orders.print');
    Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [\App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');
});

require __DIR__.'/auth.php';
