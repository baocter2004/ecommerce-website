<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để bình luận.');
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating'     => 'required|integer|min:1|max:5',
            'content'    => 'required|string|max:1000',
        ]);

        // Kiểm tra user đã bình luận sản phẩm này chưa
        $alreadyCommented = Comment::where('user_id', Auth::id())
            ->where('product_id', $request->input('product_id'))
            ->exists();

        if ($alreadyCommented) {
            return back()->with('error', 'Bạn đã bình luận sản phẩm này rồi. Mỗi sản phẩm chỉ được bình luận một lần.');
        }

        Comment::create([
            'product_id' => $request->input('product_id'),
            'user_id'    => Auth::id(),
            'rating'     => $request->input('rating'),
            'content'    => $request->input('content'),
        ]);

        return back()->with('success', 'Bình luận của bạn đã được gửi thành công.');
    }
}
