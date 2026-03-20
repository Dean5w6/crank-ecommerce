<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    { 
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@crank.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
 
        for ($i = 1; $i <= 9; $i++) {
            User::create([
                'name' => "Customer $i",
                'email' => "customer$i@example.com",
                'password' => Hash::make('password'),
                'role' => 'customer',
                'email_verified_at' => now(),
                'is_active' => true,
            ]);
        }
    }
}
