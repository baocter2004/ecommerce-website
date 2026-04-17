<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductVariant;
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
            ? Cart::where('user_id', Auth::id())->latest('updated_at')->first()
            : Cart::where('session_id', session()->getId())->latest('updated_at')->first();

        $cart_items = $cart 
            ? $cart->items()->with(['product', 'product.variants', 'variant', 'variantOption'])->get() 
            : collect();

        return view('client.cart', compact('cart_items'));
    }

    // Thêm sản phẩm vào giỏ hàng
    public function addProduct(Request $request, $productId)
    {
        try {
            // Tìm sản phẩm
            $product = Product::findOrFail($productId);
            $quantity = (int) $request->input('quantity', 1);
            if ($quantity < 1) {
                $quantity = 1;
            }

            $optionIdsByVariant = $request->input('option_ids', []);
            if (!is_array($optionIdsByVariant)) {
                $optionIdsByVariant = [];
            }

            $variants = $product->variants()->with('options')->get();
            $isDefaultBasicSelection = false;
            if ($variants->count() === 1) {
                $onlyVariantId = $variants->first()->id;
                $isDefaultBasicSelection = (($optionIdsByVariant[$onlyVariantId] ?? null) === '__basic__');
            }
            if ($variants->isNotEmpty()) {
                // Ensure each variant has a selected option
                foreach ($variants as $variant) {
                    if (empty($optionIdsByVariant[$variant->id])) {
                        throw ValidationException::withMessages([
                            'option_ids' => 'Vui lòng chọn đầy đủ biến thể cho sản phẩm.',
                        ]);
                    }
                }
            }

            $selectedOptionIds = collect($optionIdsByVariant)
                ->values()
                ->filter(fn ($v) => is_numeric($v) && (int) $v > 0)
                ->map(fn ($v) => (int) $v)
                ->all();
            $selectedOptions = VariantOption::query()
                ->whereIn('id', $selectedOptionIds)
                ->with('variant')
                ->get()
                ->keyBy('id');

            // Map selected options to color/size (best-effort)
            $selectedColor = null;
            $selectedSize = null;
            foreach ($optionIdsByVariant as $variantId => $optionId) {
                $opt = $selectedOptions->get((int) $optionId);
                if (!$opt || !$opt->variant) {
                    continue;
                }

                $variantName = mb_strtolower((string) $opt->variant->name);
                if (str_contains($variantName, 'màu') || str_contains($variantName, 'mau') || str_contains($variantName, 'color')) {
                    $selectedColor = $opt->option;
                } elseif (str_contains($variantName, 'size') || str_contains($variantName, 'kích') || str_contains($variantName, 'kich')) {
                    $selectedSize = $opt->option;
                } else {
                    // Fallback: if only one slot is available, fill it
                    if ($selectedSize === null) {
                        $selectedSize = $opt->option;
                    } elseif ($selectedColor === null) {
                        $selectedColor = $opt->option;
                    }
                }
            }

            // Lấy giỏ hàng hiện tại hoặc tạo mới
            $cart = Cart::firstOrCreate([
                'user_id' => Auth::check() ? Auth::user()->id : null,
                'session_id' => session()->getId()
            ]);

            // Kiểm tra sản phẩm với combo (color/size) đã tồn tại trong giỏ hàng chưa
            $existingCartItem = $cart->items()
                ->where('product_id', $productId)
                ->where('color', $selectedColor)
                ->where('size', $selectedSize)
                ->first();

            if ($existingCartItem) {
                $newQuantity = $existingCartItem->quantity + $quantity;

                // Kiểm tra tồn kho theo combo nếu có
                $maxStock = $variants->isEmpty() || $isDefaultBasicSelection
                    ? (int) ($product->quantity ?? 0)
                    : $this->resolveMaxStock($product->id, $selectedColor, $selectedSize, $selectedOptions);
                if ($maxStock !== null && $newQuantity > $maxStock) {
                    return redirect()->back()->with('error', 'Số lượng sản phẩm trong kho không đủ. Hiện chỉ còn ' . $maxStock . ' sản phẩm.');
                }

                $priceModifierSum = $selectedOptions->sum('price_modifier');
                $totalPrice = ((float) $product->price + (float) $priceModifierSum) * $newQuantity;

                $existingCartItem->update([
                    'quantity' => $newQuantity,
                    'price'    => $totalPrice
                ]);
            } else {
                $maxStock = $variants->isEmpty() || $isDefaultBasicSelection
                    ? (int) ($product->quantity ?? 0)
                    : $this->resolveMaxStock($product->id, $selectedColor, $selectedSize, $selectedOptions);
                if ($maxStock !== null && $quantity > $maxStock) {
                    return redirect()->back()->with('error', 'Số lượng sản phẩm trong kho không đủ. Hiện chỉ còn ' . $maxStock . ' sản phẩm.');
                }

                $priceModifierSum = $selectedOptions->sum('price_modifier');
                $totalPrice = ((float) $product->price + (float) $priceModifierSum) * $quantity;

                // Tạo dữ liệu giỏ hàng
                $cartItemData = [
                    'product_id'        => $productId,
                    'variant_id'        => null,
                    'variant_option_id' => null,
                    'color'             => $selectedColor,
                    'size'              => $selectedSize,
                    'quantity'          => $quantity,
                    'price'             => $totalPrice,
                ];

                $cart->items()->updateOrCreate(
                    [
                        'product_id' => $productId,
                        'color'      => $selectedColor,
                        'size'       => $selectedSize,
                    ],
                    $cartItemData
                );
            }
            return redirect()->route('client.cart')->with('success', 'Sản phẩm đã được thêm vào giỏ hàng');
        } catch (\Throwable $th) {
            return back()->withErrors(['error' => $th->getMessage()]);
        }
    }

    private function resolveMaxStock(int $productId, ?string $color, ?string $size, $selectedOptions): ?int
    {
        // Prefer combo stock from product_variants table if it exists.
        $pvQuery = ProductVariant::query()->where('product_id', $productId);
        if ($pvQuery->exists() && ($color !== null || $size !== null)) {
            $pv = ProductVariant::query()
                ->where('product_id', $productId)
                ->when($color !== null, fn ($q) => $q->where('color', $color))
                ->when($size !== null, fn ($q) => $q->where('size', $size))
                ->first();

            if ($pv) {
                return (int) $pv->stock;
            }
        }

        // Fallback: minimum stock among selected variant options (best-effort).
        if ($selectedOptions && method_exists($selectedOptions, 'min')) {
            $min = $selectedOptions->min('quantity');
            return $min !== null ? (int) $min : null;
        }

        return null;
    }

    // Xóa sản phẩm khỏi giỏ hàng
    public function removeProduct($cartItemId)
    {
        try {
            $cart = Auth::check()
                ? Cart::where('user_id', Auth::id())->latest('updated_at')->first()
                : Cart::where('session_id', session()->getId())->latest('updated_at')->first();

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
                ? Cart::where('user_id', Auth::id())->latest('updated_at')->first()
                : Cart::where('session_id', session()->getId())->latest('updated_at')->first();

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

            // Stock check: prefer combo stock (product_variants) when color/size exists
            $maxStock = null;
            if ($cartItem->color || $cartItem->size) {
                $pv = ProductVariant::query()
                    ->where('product_id', $cartItem->product_id)
                    ->when($cartItem->color, fn ($q) => $q->where('color', $cartItem->color))
                    ->when($cartItem->size, fn ($q) => $q->where('size', $cartItem->size))
                    ->first();
                if ($pv) {
                    $maxStock = (int) $pv->stock;
                }
            } elseif ($variantOption) {
                $maxStock = (int) $variantOption->quantity;
            } else {
                // Basic product (no variants)
                $maxStock = $cartItem->product ? (int) ($cartItem->product->quantity ?? 0) : null;
            }

            if ($maxStock !== null && $requestedQty > $maxStock) {
                throw ValidationException::withMessages([
                    'quantity' => 'Số lượng sản phẩm trong kho không đủ. Hiện chỉ còn ' . $maxStock . ' sản phẩm.',
                ]);
            }

            // Keep pricing consistent by using stored unit price
            $currentQty = max(1, (int) $cartItem->quantity);
            $unitPrice = ((float) $cartItem->price) / $currentQty;
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
