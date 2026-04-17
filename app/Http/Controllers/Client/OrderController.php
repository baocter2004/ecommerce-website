<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateOrderRequest;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\VariantOption;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    private function getCart()
    {
        return Auth::check()
            ? Cart::where('user_id', Auth::id())->latest('updated_at')->first()
            : Cart::where('session_id', session()->getId())->latest('updated_at')->first();
    }

    public function checkout()
    {
        $cart = $this->getCart();

        if (!$cart) {
            return redirect()->route('client.cart')->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        // Eager load relationships
        $cart->load(['items.product', 'items.variant', 'items.variantOption']);

        if ($cart->items->isEmpty()) {
            return redirect()->route('client.cart')->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        $dataUser = null;

        if (Auth::check()) {
            $dataUser = Auth::user();  // Đảm bảo lấy thông tin người dùng
        }

        return view('client.checkout', compact('cart', 'dataUser'));
    }

    public function createOrder(CreateOrderRequest $request)
    {
        $cart = $this->getCart();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('client.checkout')->with('error', 'Giỏ hàng trống, không thể tạo đơn hàng.');
        }

        try {
            // Bắt đầu phiên giao dịch
            DB::beginTransaction();

            // Tính tổng giá trị đơn hàng (price đã là tổng tiền của line item)
            $totalFinalPrice = $cart->items->sum('price');

            $orderData = [
                'user_id' => Auth::check() ? Auth::user()->id : null,
                'session_id' => session()->getId(),
                'total_price' => $totalFinalPrice,
                'shipping_address' => $request->shipping_address,
                'appartment' => $request->appartment,
                'discount_code' => $request->discount_code,
                'payment_method' => $request->payment_method,
                'order_note' => $request->order_note,
                'order_status' => 'pending'
            ];

            // dd($orderData , session()->getId());

            // Tạo đơn hàng
            $order = Order::create($orderData);

            // Duyệt qua từng sản phẩm trong giỏ hàng và lưu vào bảng order_items
            foreach ($cart->items as $item) {
                $orderItemData = [
                    'order_id'         => $order->id,
                    'product_id'       => $item->product_id,
                    'quantity'         => $item->quantity,
                    'price'            => $item->price,
                    'variant_id'       => $item->variant_id,
                    'variant_option_id'=> $item->variant_option_id,
                    'color'            => $item->color,
                    'size'             => $item->size,
                ];
                OrderItem::create($orderItemData);

                // Trừ tồn kho: ưu tiên theo combo ở product_variants
                if ($item->color || $item->size) {
                    $pv = ProductVariant::query()
                        ->where('product_id', $item->product_id)
                        ->when($item->color, fn ($q) => $q->where('color', $item->color))
                        ->when($item->size, fn ($q) => $q->where('size', $item->size))
                        ->first();

                    if ($pv) {
                        $pv->decrement('stock', $item->quantity);
                    } elseif ($item->variant_option_id) {
                        // Fallback nếu vẫn còn dùng kiểu cũ
                        VariantOption::where('id', $item->variant_option_id)
                            ->decrement('quantity', $item->quantity);
                    }
                } elseif ($item->variant_option_id) {
                    VariantOption::where('id', $item->variant_option_id)
                        ->decrement('quantity', $item->quantity);
                } else {
                    // Basic product (no variants) stock lives on products.quantity
                    Product::where('id', $item->product_id)->decrement('quantity', $item->quantity);
                }
            }

            // Xóa các sản phẩm trong giỏ hàng sau khi đặt hàng
            $cart->items->each(function ($item) {
                $item->delete();
            });

            // Xóa giỏ hàng sau khi đặt hàng
            $cart->delete();

            // Cam kết giao dịch
            DB::commit();

            return redirect()->route('client.thankyou')->with('success', 'Đặt hàng thành công!');

        } catch (\Throwable $th) {
            // Rollback nếu có lỗi
            DB::rollBack();

            // Ghi log lỗi
            Log::error('Tạo đơn hàng thất bại: ' . $th->getMessage());

            // return redirect()->back()->withErrors($th->getMessage());

            // Thông báo lỗi cho người dùng
            return redirect()->route('client.checkout')->with('error', 'Có lỗi xảy ra trong quá trình tạo đơn hàng, vui lòng thử lại.');
        }
    }
}
