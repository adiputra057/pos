<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Roles
        $roles = [
            'owner' => 'Pemilik Toko - Akses Penuh',
            'admin' => 'Administrator - Kelola Stok & Produk',
            'cashier' => 'Kasir - Transaksi Penjualan',
        ];

        foreach ($roles as $name => $desc) {
            Role::firstOrCreate(['name' => $name], ['description' => $desc]);
        }

        // 2. Create Default User (Owner)
        $owner = User::firstOrCreate(
            ['email' => 'owner@minimart.com'],
            [
                'name' => 'Owner MiniMart',
                'password' => Hash::make('password'),
            ]
        );
        $owner->assignRole('owner');

        // 3. Create Default User (Admin)
        $admin = User::firstOrCreate(
            ['email' => 'admin@minimart.com'],
            [
                'name' => 'Admin Gudang',
                'password' => Hash::make('password'),
            ]
        );
        $admin->assignRole('admin');

        // 4. Create Default User (Cashier)
        $cashier = User::firstOrCreate(
            ['email' => 'kasir@minimart.com'],
            [
                'name' => 'Kasir 1',
                'password' => Hash::make('password'),
            ]
        );
        $cashier->assignRole('cashier');
    }
}
