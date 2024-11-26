<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Models\VariantOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Hiển thị giỏ hàng của người dùng
    public function index()
    {
        // Lấy giỏ hàng theo user hoặc session
        $cart = Auth::check()
            ? Cart::where('user_id', Auth::user()->id)->first()
            : Cart::where('session_id', session()->getId())->first();

        // Lấy các sản phẩm trong giỏ hàng
        $cart_items = $cart ? $cart->items : [];

        return view('client.cart', compact('cart_items'));
    }

    // Thêm sản phẩm vào giỏ hàng
    public function addProduct(Request $request, $productId)
    {
        try {
            // Tìm sản phẩm
            $product = Product::findOrFail($productId);
            $variantOption = VariantOption::findOrFail($request->input('option_id'));
            $quantity = $request->input('quantity');

            // Lấy giỏ hàng hiện tại hoặc tạo mới
            $cart = Cart::firstOrCreate([
                'user_id' => Auth::check() ? Auth::user()->id : null,
                'session_id' => session()->getId()
            ]);

            // Kiểm tra sản phẩm với biến thể đã tồn tại trong giỏ hàng chưa
            $existingCartItem = $cart->items->where('product_id', $productId)
                ->where('variant_option_id', $variantOption->id)
                ->first();

            if ($existingCartItem) {
                $newQuantity = $existingCartItem->quantity + $quantity;

                // Xử lý biến thể sản phẩm nếu có
                $variantOption = null;

                if ($request->input('option_id')) {
                    $variantOption = VariantOption::findOrFail($request->input('option_id'));
                }

                if ($variantOption) {
                    $totalPrice = ($product->price + $variantOption->price_modifier) * $newQuantity;
                } else {
                    $totalPrice = $product->price * $newQuantity;
                }


                if ($newQuantity > $variantOption->quantity) {
                    return redirect()->back()->with('error', 'Số lượng sản phẩm trong kho không đủ. Hiện chỉ còn ' . $variantOption->quantity . ' sản phẩm.');
                }

                $existingCartItem->update([
                    'quantity' => $newQuantity,
                    'price' => $totalPrice
                ]);
            } else {

                if ($quantity > $variantOption->quantity) {
                    return redirect()->back()->with('error', 'Số lượng sản phẩm trong kho không đủ. Hiện chỉ còn ' . $variantOption->quantity . ' sản phẩm.');
                }

                // Xử lý biến thể sản phẩm nếu có
                $variantOption = null;
                if ($request->input('option_id')) {
                    $variantOption = VariantOption::findOrFail($request->input('option_id'));
                }

                if ($variantOption) {
                    $totalPrice = ($product->price + $variantOption->price_modifier) * $quantity;
                } else {
                    $totalPrice = $product->price * $quantity;
                }

                // Tạo dữ liệu giỏ hàng
                $cartItemData = [
                    'product_id' => $productId,
                    'variant_id' => $variantOption ? $variantOption->variant_id : null,
                    'variant_option_id' => $variantOption ? $variantOption->id : null,
                    'quantity' => $request->input('quantity', 1),
                    'price' => $totalPrice,
                ];

                // Thêm hoặc cập nhật sản phẩm trong giỏ hàng

                $cart->items()->updateOrCreate(
                    [
                        'product_id' => $productId,
                        'variant_option_id' => $variantOption ? $variantOption->id : null,
                    ],
                    $cartItemData
                );
            }
            return redirect()->route('client.cart')->with('success', 'Sản phẩm đã được thêm vào giỏ hàng');
        } catch (\Throwable $th) {
            return back()->withErrors(['error' => $th->getMessage()]);
        }
    }

    // Xóa sản phẩm khỏi giỏ hàng
    public function removeProduct($cartItemId)
    {
        try {
            $cart = Auth::check()
                ? Cart::where('user_id', Auth::user()->id)->first()
                : Cart::where('session_id', session()->getId())->first();

            if ($cart) {
                $cartItem = $cart->items()->where('id', $cartItemId)->first();
                if ($cartItem) {
                    $cartItem->delete();
                }
            }

            return redirect()->route('client.cart')->with('success', 'Sản phẩm đã được xóa khỏi giỏ hàng');
        } catch (\Throwable $th) {
            return redirect()->route('client.cart')->with('error', 'Đã xảy ra lỗi: ' . $th->getMessage());
        }
    }
}
