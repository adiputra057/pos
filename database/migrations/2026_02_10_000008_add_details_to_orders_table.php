<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Check if columns exist before adding them to avoid errors on re-run
            if (!Schema::hasColumn('orders', 'subtotal')) {
                $table->decimal('subtotal', 15, 2)->after('invoice_number')->default(0);
            }
            if (!Schema::hasColumn('orders', 'tax')) {
                $table->decimal('tax', 15, 2)->after('subtotal')->default(0);
            }
            if (!Schema::hasColumn('orders', 'discount')) {
                $table->decimal('discount', 15, 2)->after('tax')->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['subtotal', 'tax', 'discount']);
        });
    }
};
