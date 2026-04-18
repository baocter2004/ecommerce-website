<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Storage::exists('categories')) {
            Storage::deleteDirectory('categories');
        }
        Storage::makeDirectory('categories');

        $categories = [
            ['name' => 'Giày dép', 'source' => 'categories/shoes.png', 'is_active' => true],
            ['name' => 'Áo thời trang', 'source' => 'categories/shirts.png', 'is_active' => true],
            ['name' => 'Quần nam/nữ', 'source' => 'categories/pants.png', 'is_active' => true],
            ['name' => 'Phụ kiện', 'source' => 'categories/accessories.png', 'is_active' => true],
        ];

        foreach ($categories as $cat) {
            $sourcePath = public_path($cat['source']);
            $fileName = basename($cat['source']);
            $finalPath = 'categories/' . $fileName;

            if (File::exists($sourcePath)) {
                // Sử dụng put thay vì putFile để giữ nguyên tên file
                Storage::put($finalPath, file_get_contents($sourcePath));
            }

            Category::create([
                'name' => $cat['name'],
                'category_image' => $finalPath,
                'is_active' => $cat['is_active'],
            ]);
        }
    }
}
