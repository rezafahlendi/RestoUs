<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Super Admin RestoUs
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Super Admin RestoUs',
                'email' => 'admin@restous.com',
                'phone' => '082254781995',
                'role' => 'admin',
                'password' => Hash::make('admin123'),
            ]
        );

        // 2. Akun Contoh Customer
        User::updateOrCreate(
            ['username' => 'customer'],
            [
                'name' => 'Budi Santoso',
                'email' => 'customer@restous.com',
                'phone' => '08123456789',
                'role' => 'customer',
                'password' => Hash::make('customer123'),
            ]
        );
    }
}
