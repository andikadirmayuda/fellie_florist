<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {        // Create Owner
        User::create([
            'name' => 'Owner Florist',
            'email' => 'owner@florist.com',
            'password' => Hash::make('password123'),
            'role' => 'owner',
            'status' => 'active',
        ]);        // Create Admin
        User::create([
            'name' => 'Admin Florist',
            'email' => 'admin@florist.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'status' => 'active',
        ]);        // Create Kasir
        User::create([
            'name' => 'Kasir Florist',
            'email' => 'kasir@florist.com',
            'password' => Hash::make('password123'),
            'role' => 'kasir',
            'status' => 'active',
        ]);        // Create Karyawan
        User::create([
            'name' => 'Karyawan Florist',
            'email' => 'karyawan@florist.com',
            'password' => Hash::make('password123'),
            'role' => 'karyawan',
            'status' => 'active',
        ]);        // Create Sample Customer
        User::create([
            'name' => 'Customer Florist',
            'email' => 'customer@florist.com',
            'password' => Hash::make('password123'),
            'role' => 'pelanggan',
            'status' => 'active',
        ]);
    }
}
