<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use App\Models\Comment;
use App\Models\Favorite;
use App\Models\Review;
use Illuminate\Database\Seeder;

class EngagementSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', User::ROLE_MEMBER)->get();
        $products = Product::all();

        if ($users->isEmpty() || $products->isEmpty()) {
            return;
        }

        foreach ($products as $product) {
            $commentUsers = $users->random(rand(1, 3));
            
            foreach ($commentUsers as $user) {
                Comment::create([
                    'product_id' => $product->id,
                    'user_id' => $user->id,
                    'rating' => rand(4, 5),
                    'content' => $this->getRandomComment(),
                ]);

                // Also create a review (sometimes)
                if (rand(0, 1)) {
                    Review::create([
                        'product_id' => $product->id,
                        'rating' => rand(4, 5),
                        'review' => $this->getRandomComment(),
                    ]);
                }
            }
        }

        foreach ($products as $product) {
            $favUsers = $users->random(rand(0, min(5, $users->count())));
            
            foreach ($favUsers as $user) {
                Favorite::create([
                    'product_id' => $product->id,
                    'user_id' => $user->id,
                ]);
            }
        }
    }

    private function getRandomComment()
    {
        $comments = [
            'Sản phẩm tuyệt vời, chất lượng xứng đáng với giá tiền!',
            'Giao hàng nhanh, đóng gói cẩn thận. Rất hài lòng.',
            'Chất vải đẹp, mặc rất thoải mái. Sẽ ủng hộ shop dài dài.',
            'Giày đi êm chân, đúng size. Tư vấn nhiệt tình.',
            'Màu sắc bên ngoài đẹp hơn trong ảnh. Ưng ý lắm!',
            'Sản phẩm hoàn thiện tốt, tinh xảo. Đáng mua.',
        ];

        return $comments[array_rand($comments)];
    }
}
