<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin Account
        User::create([
            'name' => 'Administrator',
            'user_name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password123'),
            'role' => User::ROLE_ADMIN,
            'is_active' => 1,
        ]);

        // Test Customer
        User::create([
            'name' => 'Nguyễn Văn Khách',
            'user_name' => 'khachhang',
            'email' => 'khachhang@gmail.com',
            'password' => bcrypt('password123'),
            'role' => User::ROLE_MEMBER,
            'is_active' => 1,
        ]);

        // Random Users
        User::factory(5)->create();
    }
}
