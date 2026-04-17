<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Storage::exists('users')) {
            Storage::deleteDirectory('users');
        }
        Storage::makeDirectory('users');

        $avatarSourcePath = public_path('admin-assets/images/avatar');
        $avatars = File::exists($avatarSourcePath) ? File::files($avatarSourcePath) : [];

        $getRandomAvatar = function() use ($avatars) {
            if (empty($avatars)) return null;
            $sourceFile = $avatars[array_rand($avatars)];
            return Storage::putFile('users', new \Illuminate\Http\File($sourceFile->getPathname()));
        };

        User::create([
            'name' => 'Administrator',
            'user_name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password123'),
            'role' => User::ROLE_ADMIN,
            'is_active' => 1,
            'image' => $getRandomAvatar(),
        ]);

        User::create([
            'name' => 'Nguyễn Văn Khách',
            'user_name' => 'khachhang',
            'email' => 'khachhang@gmail.com',
            'password' => bcrypt('password123'),
            'role' => User::ROLE_MEMBER,
            'is_active' => 1,
            'image' => $getRandomAvatar(),
        ]);

        for ($i = 0; $i < 5; $i++) {
            User::factory()->create([
                'image' => $getRandomAvatar(),
            ]);
        }
    }
}
