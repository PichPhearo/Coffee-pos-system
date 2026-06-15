<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@pos.com',
            'password' => Hash::make('123456'),
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Cashier',
            'email' => 'cashier@pos.com',
            'password' => Hash::make('123456'),
            'role' => 'cashier'
        ]);

        User::create([
            'name' => 'Barista',
            'email' => 'barista@pos.com',
            'password' => Hash::make('123456'),
            'role' => 'barista'
        ]);
    }
}