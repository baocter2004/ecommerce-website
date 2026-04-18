<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Variant;
use App\Models\VariantOption;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();

        if ($categories->isEmpty()) {
            return;
        }

        $products = [
            [
                'name' => 'Áo Thun Nam Cotton',
                'price' => 250000,
                'image' => 'products/anhaothunden.png',
                'variants' => [
                    'Màu sắc' => ['Đen', 'Trắng', 'Xám'],
                    'Kích thước' => ['S', 'M', 'L', 'XL']
                ]
            ],
            [
                'name' => 'Giày Sneaker Thời Trang',
                'price' => 750000,
                'image' => 'products/giaysneaker.png',
                'variants' => [
                    'Màu sắc' => ['Trắng', 'Đen'],
                    'Kích thước' => ['39', '40', '41', '42']
                ]
            ],
            [
                'name' => 'Quần Jean Slim Fit',
                'price' => 450000,
                'image' => 'products/pants.png',
                'variants' => [
                    'Màu sắc' => ['Xanh Đậm', 'Xanh Nhạt'],
                    'Kích thước' => ['29', '30', '31', '32']
                ]
            ]
        ];

        foreach ($products as $pData) {
            $product = Product::create([
                'category_id' => $categories->random()->id,
                'product_name' => $pData['name'],
                'slug' => Str::slug($pData['name']) . '-' . Str::random(5),
                'price' => $pData['price'],
                'product_image' => $pData['image'],
                'description' => 'Mô tả cho ' . $pData['name'] . '. Chất liệu cao cấp, form dáng chuẩn.',
                'is_active' => 1,
                'quantity' => 100, // Tổng kho mặc định
            ]);

            $variantData = [];
            foreach ($pData['variants'] as $vName => $options) {
                $variant = Variant::create([
                    'product_id' => $product->id,
                    'name' => $vName
                ]);

                foreach ($options as $optName) {
                    $opt = VariantOption::create([
                        'variant_id' => $variant->id,
                        'option' => $optName,
                        'price_modifier' => 0,
                        'quantity' => 50
                    ]);
                    $variantData[$vName][] = $optName;
                }
            }

            // Tạo tổ hợp product_variants (SKU)
            // Giả sử vName là 'Màu sắc' và 'Kích thước'
            $colors = $variantData['Màu sắc'] ?? [null];
            $sizes = $variantData['Kích thước'] ?? [null];

            foreach ($colors as $color) {
                foreach ($sizes as $size) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'color' => $color,
                        'size' => $size,
                        'stock' => rand(10, 30),
                    ]);
                }
            }
        }
    }
}
