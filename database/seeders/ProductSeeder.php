<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Variant;
use App\Models\VariantOption;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();

        if ($categories->isEmpty()) {
            $this->command->error('Danh mục trống, hãy chạy CategorySeeder trước!');
            return;
        }

        // Tạo thư mục products trong storage nếu chưa có
        if (!Storage::disk('public')->exists('products')) {
            Storage::disk('public')->makeDirectory('products');
        }

        // Danh sách ảnh thực tế trong public/product-images
        $images = [
            'anhaobongro.png', 'anhaothunden.png', 'anhaothungtronden.png',
            'anhaothuntayxanh.png', 'aothoitrangden.png', 'aothoitrangdo.png',
            'aothunbabytee.png', 'aothunlocalbrand.png', 'giaysneaker.png',
            'giaysneakermidnight.png', 'giaythethaonam.png', 'giaythethaonammuathu.png',
            'giaythethaonu.png'
        ];

        $productNames = [
            'Áo Thun Nam', 'Áo Khoác Hoodie', 'Quần Jean Slim Fit', 'Giày Sneaker', 
            'Áo Polo Công Sở', 'Quần Kaki Cao Cấp', 'Giày Chạy Bộ', 'Áo Sơ Mi Trắng',
            'Quần Short Thể Thao', 'Váy Nữ Thời Trang', 'Túi Xách Da', 'Thắt Lưng Nam'
        ];

        $adjectives = ['Cao Cấp', 'Thời Trang', 'Hàn Quốc', 'Chất Lượng', 'Mùa Hè', 'Modern', 'Basic', 'Dynamic'];

        $this->command->info('Đang tạo 40 sản phẩm...');

        for ($i = 1; $i <= 40; $i++) {
            $category = $categories->random();
            $baseName = $productNames[array_rand($productNames)];
            $adj = $adjectives[array_rand($adjectives)];
            $name = "$baseName $adj #$i";
            
            $imageName = $images[array_rand($images)];
            $sourcePath = public_path('product-images/' . $imageName);
            $finalPath = 'products/' . $imageName;

            // Copy ảnh vào storage nếu file tồn tại
            if (File::exists($sourcePath)) {
                Storage::disk('public')->put($finalPath, file_get_contents($sourcePath));
            }

            $product = Product::create([
                'category_id' => $category->id,
                'product_name' => $name,
                'price' => rand(200, 1500) * 1000,
                'product_image' => $finalPath,
                'short_description' => "Mô tả ngắn cho sản phẩm $name. Thiết kế hiện đại, chất liệu bền đẹp.",
                'description' => "Đây là mô tả chi tiết cho $name. Sản phẩm phù hợp với nhiều phong cách khác nhau, được sản xuất với quy trình nghiêm ngặt đảm bảo chất lượng tốt nhất đến tay khách hàng.",
                'is_active' => 1,
                'quantity' => rand(50, 200),
            ]);

            // Tạo biến thể cho mỗi sản phẩm
            $variants = [
                'Màu sắc' => ['Đen', 'Trắng', 'Xanh', 'Xám'],
                'Kích thước' => ['S', 'M', 'L', 'XL', '39', '40', '41', '42']
            ];

            // Chọn ngẫu nhiên 1 hoặc 2 nhóm biến thể
            $selectedVariantNames = array_rand($variants, rand(1, 2));
            if (!is_array($selectedVariantNames)) $selectedVariantNames = [$selectedVariantNames];

            $variantData = [];
            foreach ($selectedVariantNames as $vName) {
                $variant = Variant::create([
                    'product_id' => $product->id,
                    'name' => $vName
                ]);

                // Chọn ngẫu nhiên 2-4 option cho mỗi nhóm
                $options = (array) array_rand(array_flip($variants[$vName]), rand(2, 4));
                
                foreach ($options as $optName) {
                    VariantOption::create([
                        'variant_id' => $variant->id,
                        'option' => $optName,
                        'price_modifier' => rand(0, 5) * 10000,
                        'quantity' => rand(10, 50)
                    ]);
                    $variantData[$vName][] = $optName;
                }
            }

            // Tạo tổ hợp ProductVariants (SKU)
            $colors = $variantData['Màu sắc'] ?? [null];
            $sizes = $variantData['Kích thước'] ?? [null];

            foreach ($colors as $color) {
                foreach ($sizes as $size) {
                    if ($color === null && $size === null) continue;
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'color' => $color,
                        'size' => $size,
                        'stock' => rand(5, 20),
                    ]);
                }
            }
        }

        $this->command->info('Đã tạo xong 40 sản phẩm.');
    }
}
