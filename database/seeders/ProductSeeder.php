<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Variant;
use App\Models\VariantOption;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        ProductVariant::truncate();
        VariantOption::truncate();
        Variant::truncate();
        Product::truncate();
        Schema::enableForeignKeyConstraints();

        if (Storage::exists('products')) {
            Storage::deleteDirectory('products');
        }
        Storage::makeDirectory('products');
        $shoeImages = [
            'product-images/giaysneaker.png',
            'product-images/giaysneakermidnight.png',
            'product-images/giaythethaonam.png',
            'product-images/giaythethaonammuathu.png',
            'product-images/giaythethaonu.png',
            'client/images/shoe_1.jpg',
            'client/images/shoe.png'
        ];

        $shirtImages = [
            'product-images/anhaobongro.png',
            'product-images/anhaothunden.png',
            'product-images/anhaothungtronden.png',
            'product-images/anhaothuntayxanh.png',
            'product-images/aothunbabytee.png',
            'product-images/aothunlocalbrand.png',
            'product-images/aothoitrangden.png',
            'product-images/aothoitrangdo.png',
            'client/images/cloth_1.jpg',
            'client/images/cloth_2.jpg',
            'client/images/cloth_3.jpg'
        ];

        $categories = [
            1 => ['name' => 'Giày dép', 'images' => $shoeImages, 'prefix' => 'Giày'],
            2 => ['name' => 'Áo thời trang', 'images' => $shirtImages, 'prefix' => 'Áo'],
            3 => ['name' => 'Quần nam/nữ', 'images' => $shirtImages, 'prefix' => 'Quần'], 
            4 => ['name' => 'Phụ kiện', 'images' => $shoeImages, 'prefix' => 'Phụ kiện'],
        ];

        for ($catId = 1; $catId <= 4; $catId++) {
            $config = $categories[$catId];
            
            for ($i = 1; $i <= 10; $i++) {
                $sourceImage = $config['images'][array_rand($config['images'])];
                $sourcePath = public_path($sourceImage);
                
                $finalImagePath = $sourceImage; 

                if (File::exists($sourcePath)) {
                    $finalImagePath = Storage::putFile('products', new \Illuminate\Http\File($sourcePath));
                }

                $price = rand(200, 1500) * 1000;
                
                $product = Product::create([
                    'product_name' => $config['prefix'] . " " . $this->getRandomName() . " " . $i,
                    'category_id' => $catId,
                    'price' => $price,
                    'product_image' => $finalImagePath,
                    'short_description' => "Mẫu " . strtolower($config['name']) . " cao cấp, thiết kế hiện đại, phù hợp xu hướng thời trang mới nhất.",
                    'description' => "Sản phẩm " . $config['name'] . " được làm từ chất liệu chọn lọc, mang lại cảm giác thoải mái và tự tin cho người mặc. Độ bền cao, dễ dàng phối đồ cho nhiều hoàn cảnh khác nhau.",
                    'is_active' => 1,
                ]);

                // Tạo biến thể cho từng sản phẩm
                if ($catId == 1) { // Giày
                    $this->createVariants($product, 'Kích thước', ['39', '40', '41', '42'], 'Màu sắc', ['Đen', 'Trắng', 'Xanh']);
                } else if ($catId == 2 || $catId == 3) { // Áo / Quần
                    $this->createVariants($product, 'Size', ['S', 'M', 'L', 'XL'], 'Màu sắc', ['Đen', 'Trắng', 'Navy']);
                } else {
                    $this->createVariants($product, 'Loại', ['Basic', 'Premium'], null, []);
                }
            }
        }
    }

    private function getRandomName()
    {
        $names = ['Retro', 'Midnight', 'Sport', 'Active', 'Classic', 'Modern', 'Luxury', 'Elegance', 'Urban', 'Street'];
        return $names[array_rand($names)];
    }

    private function createVariants($product, $v1Name, $v1Options, $v2Name = null, $v2Options = [])
    {
        $variant1 = Variant::create([
            'product_id' => $product->id,
            'name' => $v1Name
        ]);

        $sizes = [];
        foreach ($v1Options as $opt) {
            VariantOption::create([
                'variant_id' => $variant1->id,
                'option' => $opt,
                'price_modifier' => rand(0, 3) * 10000,
                'quantity' => rand(10, 100)
            ]);
            $sizes[] = $opt;
        }

        $colors = [];
        if ($v2Name) {
            $variant2 = Variant::create([
                'product_id' => $product->id,
                'name' => $v2Name
            ]);

            foreach ($v2Options as $opt) {
                VariantOption::create([
                    'variant_id' => $variant2->id,
                    'option' => $opt,
                    'price_modifier' => 0,
                    'quantity' => rand(10, 100)
                ]);
                $colors[] = $opt;
            }
        }

        if (!empty($colors) && !empty($sizes)) {
            foreach ($colors as $c) {
                foreach ($sizes as $s) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'color' => $c,
                        'size' => $s,
                        'stock' => rand(5, 50),
                    ]);
                }
            }
        } elseif (!empty($sizes)) {
            foreach ($sizes as $s) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'color' => null,
                    'size' => $s,
                    'stock' => rand(5, 50),
                ]);
            }
        }
    }
}
