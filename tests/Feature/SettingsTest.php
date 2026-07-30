<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\PaymentSetting;
use App\Models\SecuritySetting;
use App\Models\PrinterSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create admin user
        $this->user = User::factory()->create();
        $role = Role::create(['name' => 'owner', 'description' => 'Owner']);
        $this->user->roles()->attach($role);
    }

    public function test_settings_page_can_be_rendered()
    {
        $response = $this->actingAs($this->user)->get(route('settings.index'));

        $response->assertStatus(200);
        $response->assertSee('Pengaturan');
        $response->assertSee('Informasi Toko');
    }

    public function test_general_settings_can_be_updated()
    {
        $response = $this->actingAs($this->user)->post(route('settings.update.general'), [
            'store_name' => 'New Store Name',
            'store_phone' => '08123456789',
            'store_address' => 'New Address',
            'store_email' => 'store@example.com',
            'currency' => 'IDR',
            'tax_rate' => 10,
        ]);

        $response->assertSessionHas('success');
        $this->assertEquals('New Store Name', session('settings.store_name'));
    }

    public function test_payment_settings_can_be_updated()
    {
        $paymentMethod = PaymentSetting::create(['method_name' => 'cash', 'is_active' => 0]);

        $response = $this->actingAs($this->user)->post(route('settings.update.payment'), [
            'methods' => [
                [
                    'id' => $paymentMethod->id,
                    'is_active' => 1,
                    'admin_fee' => 1000,
                    'minimum_amount' => 50000,
                ]
            ]
        ]);

        $response->assertStatus(200);
        
        $paymentMethod->refresh();
        $this->assertTrue((bool)$paymentMethod->is_active);
        $this->assertEquals(1000, $paymentMethod->admin_fee);
    }

    public function test_security_settings_can_be_updated()
    {
        SecuritySetting::create(['session_timeout' => 60]);

        $response = $this->actingAs($this->user)->post(route('settings.update.security'), [
            'session_timeout' => 120,
            'auto_logout_on_close' => 1,
            'require_pin_for_void' => 1,
        ]);

        $response->assertStatus(200);
        
        $settings = SecuritySetting::current();
        $this->assertEquals(120, $settings->session_timeout);
        $this->assertTrue((bool)$settings->auto_logout_on_close);
    }

    public function test_printer_can_be_added()
    {
        $response = $this->actingAs($this->user)->post(route('settings.printers.store'), [
            'printer_name' => 'Test Printer',
            'printer_type' => 'receipt',
            'connection_type' => 'usb',
            'paper_size' => '58mm',
            'print_copies' => 1,
            'is_default' => 1,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('printer_settings', ['printer_name' => 'Test Printer']);
    }

    public function test_activity_logs_page_can_be_rendered()
    {
        $response = $this->actingAs($this->user)->get(route('settings.activity-logs'));

        $response->assertStatus(200);
        $response->assertSee('Activity Logs');
    }
}
