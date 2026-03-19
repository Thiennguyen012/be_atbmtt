<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'phone' => '0123456789',
            'birthday' => '1990-01-01',
            'address' => '123 Main Street',
            'password' => Hash::make('password123'),
            'status' => 1,
            'is_super_admin' => 1,
        ]);

        User::create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'phone' => '0987654321',
            'birthday' => '1995-05-15',
            'address' => '456 Test Avenue',
            'password' => Hash::make('password123'),
            'status' => 1,
            'is_super_admin' => 0,
        ]);
    }
}
