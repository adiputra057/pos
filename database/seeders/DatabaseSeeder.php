<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Roles and Users
        $this->call(RoleSeeder::class);

        // 2. Create Dummy Data if needed (Categories, Products, etc - kept from before)

        // Create or get Categories
        $food = \App\Models\Category::firstOrCreate(
            ['name' => 'Food'],
            ['description' => 'Meals and Snacks']
        );

        $drink = \App\Models\Category::firstOrCreate(
            ['name' => 'Drinks'],
            ['description' => 'Beverages']
        );

        // Create or get Supplier
        $supplier = \App\Models\Supplier::firstOrCreate(
            ['name' => 'General Supplier'],
            ['phone' => '08123456789']
        );

        // Create or update Products by SKU to avoid duplicate key errors
        \App\Models\Product::updateOrCreate(
            ['sku' => 'F001'],
            [
                'category_id' => $food->id,
                'supplier_id' => $supplier->id,
                'name' => 'Nasi Goreng',
                'buying_price' => 10000,
                'selling_price' => 15000,
                'stock' => 50,
            ]
        );

        \App\Models\Product::updateOrCreate(
            ['sku' => 'D001'],
            [
                'category_id' => $drink->id,
                'supplier_id' => $supplier->id,
                'name' => 'Es Teh Manis',
                'buying_price' => 2000,
                'selling_price' => 5000,
                'stock' => 100,
            ]
        );
    }
}
