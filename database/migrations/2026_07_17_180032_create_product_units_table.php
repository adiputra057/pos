<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('unit_name');
            $table->integer('conversion_factor');
            $table->decimal('price', 15, 2);
            $table->boolean('is_base')->default(false);
            $table->timestamps();
        });

        // Copy existing products' prices and units to product_units table
        $products = DB::table('products')->get();
        foreach ($products as $product) {
            // 1. Insert base unit (pcs)
            DB::table('product_units')->insert([
                'product_id' => $product->id,
                'unit_name' => 'pcs',
                'conversion_factor' => 1,
                'price' => $product->selling_price,
                'is_base' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // 2. Insert alternative unit if box_quantity > 0 and box_price > 0
            if (isset($product->box_quantity) && $product->box_quantity > 0 && isset($product->box_price) && $product->box_price > 0) {
                DB::table('product_units')->insert([
                    'product_id' => $product->id,
                    'unit_name' => 'dus',
                    'conversion_factor' => $product->box_quantity,
                    'price' => $product->box_price,
                    'is_base' => false,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_units');
    }
};
