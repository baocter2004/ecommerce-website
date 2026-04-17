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
            $finalPath = $cat['source']; // Fallback

            if (File::exists($sourcePath)) {
                $finalPath = Storage::putFile('categories', new \Illuminate\Http\File($sourcePath));
            }

            Category::create([
                'name' => $cat['name'],
                'category_image' => $finalPath,
                'is_active' => $cat['is_active'],
            ]);
        }
    }
}
