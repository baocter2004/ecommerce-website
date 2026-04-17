<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Models\VariantOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    // Hiển thị giỏ hàng của người dùng
    public function index()
    {
        $cart = Auth::check()
            ? Cart::where('user_id', Auth::user()->id)->first()
            : Cart::where('session_id', session()->getId())->first();

        $cart_items = $cart 
            ? $cart->items()->with(['product', 'variant', 'variantOption'])->get() 
            : collect();

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

                // Kiểm tra tồn kho
                if ($newQuantity > $variantOption->quantity) {
                    return redirect()->back()->with('error', 'Số lượng sản phẩm trong kho không đủ. Hiện chỉ còn ' . $variantOption->quantity . ' sản phẩm.');
                }

                $totalPrice = ($product->price + $variantOption->price_modifier) * $newQuantity;

                $existingCartItem->update([
                    'quantity' => $newQuantity,
                    'price'    => $totalPrice
                ]);
            } else {
                // Kiểm tra tồn kho
                if ($quantity > $variantOption->quantity) {
                    return redirect()->back()->with('error', 'Số lượng sản phẩm trong kho không đủ. Hiện chỉ còn ' . $variantOption->quantity . ' sản phẩm.');
                }

                $totalPrice = ($product->price + $variantOption->price_modifier) * $quantity;

                // Tạo dữ liệu giỏ hàng
                $cartItemData = [
                    'product_id'        => $productId,
                    'variant_id'        => $variantOption->variant_id,
                    'variant_option_id' => $variantOption->id,
                    'quantity'          => $quantity,
                    'price'             => $totalPrice,
                ];

                $cart->items()->updateOrCreate(
                    [
                        'product_id'        => $productId,
                        'variant_option_id' => $variantOption->id,
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

    public function updateQuantity(Request $request, $cartItemId)
    {
        try {
            $data = $request->validate([
                'quantity' => ['required', 'integer', 'min:1'],
            ]);

            $cart = Auth::check()
                ? Cart::where('user_id', Auth::user()->id)->first()
                : Cart::where('session_id', session()->getId())->first();

            if (!$cart) {
                return response()->json(['message' => 'Giỏ hàng không tồn tại.'], 404);
            }

            $cartItem = $cart->items()
                ->with(['product', 'variantOption'])
                ->where('id', $cartItemId)
                ->first();

            if (!$cartItem) {
                return response()->json(['message' => 'Sản phẩm trong giỏ hàng không tồn tại.'], 404);
            }

            $requestedQty = (int) $data['quantity'];
            $variantOption = $cartItem->variantOption;

            if ($variantOption && $requestedQty > (int) $variantOption->quantity) {
                throw ValidationException::withMessages([
                    'quantity' => 'Số lượng sản phẩm trong kho không đủ. Hiện chỉ còn ' . $variantOption->quantity . ' sản phẩm.',
                ]);
            }

            $product = $cartItem->product;
            $priceModifier = $variantOption ? (float) $variantOption->price_modifier : 0;
            $unitPrice = ((float) $product->price) + $priceModifier;
            $linePrice = $unitPrice * $requestedQty;

            $cartItem->update([
                'quantity' => $requestedQty,
                'price' => $linePrice,
            ]);

            $subtotal = (float) $cart->items()->sum('price');

            return response()->json([
                'cart_item_id' => (int) $cartItem->id,
                'quantity' => (int) $cartItem->quantity,
                'line_price' => (float) $cartItem->price,
                'subtotal' => $subtotal,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Dữ liệu không hợp lệ.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Đã xảy ra lỗi khi cập nhật số lượng.',
            ], 500);
        }
    }
}
