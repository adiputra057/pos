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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Cashier ID
            $table->string('invoice_number')->unique();
            $table->decimal('total_price', 10, 2);
            $table->decimal('payment_amount', 10, 2);
            $table->decimal('change_amount', 10, 2);
            $table->string('payment_method')->default('cash'); // cash, card, qris
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
