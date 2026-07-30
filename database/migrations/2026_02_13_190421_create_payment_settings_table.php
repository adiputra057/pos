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
        Schema::create('payment_settings', function (Blueprint $table) {
            $table->id();
            $table->string('method_name', 50); // 'cash', 'transfer', 'qris', 'debit', 'credit'
            $table->boolean('is_active')->default(true);
            $table->decimal('admin_fee', 10, 2)->default(0);
            $table->decimal('minimum_amount', 15, 2)->default(0);
            $table->string('icon', 100)->nullable();
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });

        // Insert default payment methods
        DB::table('payment_settings')->insert([
            ['method_name' => 'cash', 'is_active' => true, 'admin_fee' => 0, 'minimum_amount' => 0, 'icon' => 'cash', 'display_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['method_name' => 'transfer', 'is_active' => true, 'admin_fee' => 0, 'minimum_amount' => 0, 'icon' => 'bank', 'display_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['method_name' => 'qris', 'is_active' => true, 'admin_fee' => 0, 'minimum_amount' => 0, 'icon' => 'qr-code', 'display_order' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['method_name' => 'debit', 'is_active' => false, 'admin_fee' => 0, 'minimum_amount' => 0, 'icon' => 'credit-card', 'display_order' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['method_name' => 'credit', 'is_active' => false, 'admin_fee' => 0, 'minimum_amount' => 0, 'icon' => 'credit-card', 'display_order' => 5, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_settings');
    }
};
