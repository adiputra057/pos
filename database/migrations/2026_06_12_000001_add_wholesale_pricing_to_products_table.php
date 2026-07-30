<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('wholesale_price', 15, 2)->nullable()->after('selling_price')
                  ->comment('Harga per-pcs saat beli dalam jumlah grosir');
            $table->integer('min_wholesale_qty')->nullable()->default(5)->after('wholesale_price')
                  ->comment('Minimum qty untuk mendapatkan harga grosir');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['wholesale_price', 'min_wholesale_qty']);
        });
    }
};
