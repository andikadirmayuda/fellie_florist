<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */    public function run(): void
    {
        // Create Owner
        $owner = User::create([
            'name' => 'Owner Florist',
            'email' => 'owner@florist.com',
            'password' => Hash::make('password123'),
            'status' => 'active',
        ]);
        $owner->roles()->attach(Role::where('name', 'owner')->first());

        // Create Admin
        $admin = User::create([
            'name' => 'Admin Florist',
            'email' => 'admin@florist.com',
            'password' => Hash::make('password123'),
            'status' => 'active',
        ]);
        $admin->roles()->attach(Role::where('name', 'admin')->first());        // Create Kasir
        $kasir = User::create([
            'name' => 'Kasir Florist',
            'email' => 'kasir@florist.com',
            'password' => Hash::make('password123'),
            'status' => 'active',
        ]);
        $kasir->roles()->attach(Role::where('name', 'kasir')->first());

        // Create Karyawan
        $karyawan = User::create([
            'name' => 'Karyawan Florist',
            'email' => 'karyawan@florist.com',
            'password' => Hash::make('password123'),
            'status' => 'active',
        ]);
        $karyawan->roles()->attach(Role::where('name', 'karyawan')->first());

        // Create Sample Customer
        $customer = User::create([
            'name' => 'Customer Florist',
            'email' => 'customer@florist.com',
            'password' => Hash::make('password123'),
            'status' => 'active',
        ]);
        $customer->roles()->attach(Role::where('name', 'pelanggan')->first());
    }
}
