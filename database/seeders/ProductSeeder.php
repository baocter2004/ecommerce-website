<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'product_name' => 'Giày Sneaker Retro Nam',
                'category_id' => 1,
                'price' => 850000,
                'product_image' => 'products/item-1.jpg',
                'short_description' => 'Mẫu giày sneaker mang phong cách retro cổ điển, cực kỳ dễ phối đồ.',
                'description' => 'Giày Sneaker Retro Nam với chất liệu da lộn cao cấp, đế cao su chống trượt bền bỉ. Phù hợp cho cả đi làm và đi chơi.',
                'variants' => [
                    [
                        'name' => 'Kích thước',
                        'options' => [
                            ['option' => '39', 'price_modifier' => 0, 'quantity' => 10],
                            ['option' => '40', 'price_modifier' => 0, 'quantity' => 15],
                            ['option' => '41', 'price_modifier' => 50000, 'quantity' => 5],
                        ]
                    ],
                    [
                        'name' => 'Màu sắc',
                        'options' => [
                            ['option' => 'Trắng Trơn', 'price_modifier' => 0, 'quantity' => 20],
                            ['option' => 'Xám Bụi', 'price_modifier' => 20000, 'quantity' => 10],
                        ]
                    ]
                ]
            ],
            [
                'product_name' => 'Áo Thun Cotton Basic',
                'category_id' => 2,
                'price' => 250000,
                'product_image' => 'products/item-2.jpg',
                'short_description' => 'Áo thun 100% cotton co giãn 4 chiều, thấm hút mồ hôi cực tốt.',
                'description' => 'Áo thun Basic dệt từ sợi cotton tinh khiết, bề mặt vải mịn mượt, không bai nhão sau nhiều lần giặt.',
                'variants' => [
                    [
                        'name' => 'Size',
                        'options' => [
                            ['option' => 'M', 'price_modifier' => 0, 'quantity' => 50],
                            ['option' => 'L', 'price_modifier' => 0, 'quantity' => 40],
                            ['option' => 'XL', 'price_modifier' => 20000, 'quantity' => 20],
                        ]
                    ],
                    [
                        'name' => 'Màu sắc',
                        'options' => [
                            ['option' => 'Đen', 'price_modifier' => 0, 'quantity' => 100],
                            ['option' => 'Trắng', 'price_modifier' => 0, 'quantity' => 80],
                            ['option' => 'Xanh Navy', 'price_modifier' => 0, 'quantity' => 60],
                        ]
                    ]
                ]
            ],
            [
                'product_name' => 'Quần Jean Slim Fit',
                'category_id' => 3,
                'price' => 450000,
                'product_image' => 'products/item-3.jpg',
                'short_description' => 'Dáng Slim Fit tôn dáng, chất denim dày dặn có co giãn nhẹ.',
                'description' => 'Quần Jean Slim Fit thiết kế hiện đại, đường may chắc chắn. Sản phẩm đã qua xử lý wash màu cao cấp không phai.',
                'variants' => [
                    [
                        'name' => 'Vòng bụng',
                        'options' => [
                            ['option' => '29', 'price_modifier' => 0, 'quantity' => 12],
                            ['option' => '30', 'price_modifier' => 0, 'quantity' => 18],
                            ['option' => '31', 'price_modifier' => 0, 'quantity' => 15],
                            ['option' => '32', 'price_modifier' => 0, 'quantity' => 10],
                        ]
                    ],
                    [
                        'name' => 'Màu sắc',
                        'options' => [
                            ['option' => 'Xanh Đậm', 'price_modifier' => 0, 'quantity' => 30],
                            ['option' => 'Đen Khói', 'price_modifier' => 30000, 'quantity' => 25],
                        ]
                    ]
                ]
            ],
            [
                'product_name' => 'Đồng Hồ Nam Sang Trọng',
                'category_id' => 4,
                'price' => 1200000,
                'product_image' => 'products/item-4.jpg',
                'short_description' => 'Mặt kính sapphire chống trầy, thiết kế tinh xảo từng chi tiết.',
                'description' => 'Đồng hồ phong cách doanh nhân với dây da thật, bộ máy quartz Nhật Bản chính xác tuyệt đối.',
                'variants' => [
                    [
                        'name' => 'Màu dây',
                        'options' => [
                            ['option' => 'Da Nâu', 'price_modifier' => 0, 'quantity' => 5],
                            ['option' => 'Da Đen', 'price_modifier' => 0, 'quantity' => 8],
                        ]
                    ]
                ]
            ],
            [
                'product_name' => 'Kính Râm Aviator',
                'category_id' => 4,
                'price' => 350000,
                'product_image' => 'products/item-5.jpg',
                'short_description' => 'Kính râm dáng phi công classic, bảo vệ mắt khỏi tia UV.',
                'description' => 'Mắt kính Aviator thời thượng với gọng kim loại siêu nhẹ, tròng kính phân cực chống lóa hiệu quả.',
                'variants' => [
                    [
                        'name' => 'Màu gọng',
                        'options' => [
                            ['option' => 'Vàng Gold', 'price_modifier' => 50000, 'quantity' => 10],
                            ['option' => 'Bạc Silver', 'price_modifier' => 0, 'quantity' => 15],
                        ]
                    ]
                ]
            ]
        ];

        foreach ($products as $pData) {
            $variantsData = $pData['variants'];
            unset($pData['variants']);

            $product = \App\Models\Product::create($pData);

            $colors = [];
            $sizes = [];

            foreach ($variantsData as $vData) {
                $options = $vData['options'];
                $variantName = $vData['name'];
                unset($vData['options']);
                $vData['product_id'] = $product->id;

                $variant = \App\Models\Variant::create($vData);

                foreach ($options as $oData) {
                    $oData['variant_id'] = $variant->id;
                    \App\Models\VariantOption::create($oData);

                    if ($variantName === 'Màu sắc' || $variantName === 'Màu dây' || $variantName === 'Màu gọng') {
                        $colors[] = $oData['option'];
                    } elseif ($variantName === 'Kích thước' || $variantName === 'Size' || $variantName === 'Vòng bụng') {
                        $sizes[] = $oData['option'];
                    }
                }
            }

            // Create Product Variants (Combinations)
            if (empty($colors) && empty($sizes)) {
                // No variants, maybe create a default one or skip
            } elseif (empty($colors)) {
                foreach ($sizes as $size) {
                    \App\Models\ProductVariant::create([
                        'product_id' => $product->id,
                        'color' => null,
                        'size' => $size,
                        'stock' => rand(10, 50),
                    ]);
                }
            } elseif (empty($sizes)) {
                foreach ($colors as $color) {
                    \App\Models\ProductVariant::create([
                        'product_id' => $product->id,
                        'color' => $color,
                        'size' => null,
                        'stock' => rand(10, 50),
                    ]);
                }
            } else {
                foreach ($colors as $color) {
                    foreach ($sizes as $size) {
                        \App\Models\ProductVariant::create([
                            'product_id' => $product->id,
                            'color' => $color,
                            'size' => $size,
                            'stock' => rand(5, 30),
                        ]);
                    }
                }
            }
        }
    }
}
