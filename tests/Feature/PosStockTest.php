<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PosStockTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $category;
    protected $supplier;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $role = Role::create(['name' => 'owner', 'description' => 'Owner']);
        $this->user->roles()->attach($role);

        $this->category = Category::create([
            'name' => 'Test Category',
            'description' => 'Test Category Description'
        ]);

        $this->supplier = Supplier::create([
            'name' => 'Test Supplier',
            'phone' => '0812345678'
        ]);
    }

    public function test_pos_filters_out_of_stock_products_when_track_stock_is_enabled()
    {
        // 1. Enable track_stock
        Setting::set('track_stock', '1');

        // 2. Create an in-stock product and an out-of-stock product
        $inStockProduct = Product::create([
            'category_id' => $this->category->id,
            'supplier_id' => $this->supplier->id,
            'name' => 'In Stock Product',
            'sku' => 'IN-001',
            'stock' => 10,
            'buying_price' => 4000,
            'selling_price' => 5000,
        ]);

        $outOfStockProduct = Product::create([
            'category_id' => $this->category->id,
            'supplier_id' => $this->supplier->id,
            'name' => 'Out Of Stock Product',
            'sku' => 'OUT-001',
            'stock' => 0,
            'buying_price' => 8000,
            'selling_price' => 10000,
        ]);

        // 3. Request POS index
        $response = $this->actingAs($this->user)->get(route('pos.index'));

        // 4. Assert POS returns in-stock product but NOT the out-of-stock product
        $response->assertStatus(200);
        $products = $response->viewData('products');
        
        $this->assertTrue($products->contains('id', $inStockProduct->id));
        $this->assertFalse($products->contains('id', $outOfStockProduct->id));
    }

    public function test_pos_shows_all_products_when_track_stock_is_disabled()
    {
        // 1. Disable track_stock
        Setting::set('track_stock', '0');

        // 2. Create an in-stock product and an out-of-stock product
        $inStockProduct = Product::create([
            'category_id' => $this->category->id,
            'supplier_id' => $this->supplier->id,
            'name' => 'In Stock Product',
            'sku' => 'IN-001',
            'stock' => 10,
            'buying_price' => 4000,
            'selling_price' => 5000,
        ]);

        $outOfStockProduct = Product::create([
            'category_id' => $this->category->id,
            'supplier_id' => $this->supplier->id,
            'name' => 'Out Of Stock Product',
            'sku' => 'OUT-001',
            'stock' => 0,
            'buying_price' => 8000,
            'selling_price' => 10000,
        ]);

        // 3. Request POS index
        $response = $this->actingAs($this->user)->get(route('pos.index'));

        // 4. Assert POS returns BOTH products
        $response->assertStatus(200);
        $products = $response->viewData('products');
        
        $this->assertTrue($products->contains('id', $inStockProduct->id));
        $this->assertTrue($products->contains('id', $outOfStockProduct->id));
    }
}
