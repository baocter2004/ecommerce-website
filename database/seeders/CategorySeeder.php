<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Giày dép', 'category_image' => 'categories/shoes.jpg', 'is_active' => true],
            ['name' => 'Áo thời trang', 'category_image' => 'categories/shirts.jpg', 'is_active' => true],
            ['name' => 'Quần nam/nữ', 'category_image' => 'categories/pants.jpg', 'is_active' => true],
            ['name' => 'Phụ kiện', 'category_image' => 'categories/accessories.jpg', 'is_active' => true],
        ];

        foreach ($categories as $cat) {
            \App\Models\Category::create($cat);
        }
    }
}
