<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $favorites = Favorite::with('product')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('client.wishlist', compact('favorites'));
    }

    public function toggle(Product $product)
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => 'unauthorized',
                'message' => 'Bạn cần đăng nhập để sử dụng tính năng này.'
            ], 401);
        }

        $userId = Auth::id();
        $favorite = Favorite::where('user_id', $userId)
            ->where('product_id', $product->id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            $status = 'removed';
            $message = 'Đã xóa khỏi danh sách yêu thích!';
        } else {
            Favorite::create([
                'user_id' => $userId,
                'product_id' => $product->id
            ]);
            $status = 'added';
            $message = 'Đã thêm vào danh sách yêu thích!';
        }

        $count = Favorite::where('user_id', $userId)->count();

        return response()->json([
            'status' => $status,
            'message' => $message,
            'count' => $count
        ]);
    }

    public function remove(Favorite $favorite)
    {
        if ($favorite->user_id !== Auth::id()) {
            abort(403);
        }

        $favorite->delete();

        return back()->with('success', 'Đã xóa sản phẩm khỏi danh sách yêu thích!');
    }
}
