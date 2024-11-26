<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Http\Request;

class VariantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($product_id)
    {
        // Tìm sản phẩm theo ID
        $product = Product::findOrFail($product_id);

        // Lấy danh sách biến thể của sản phẩm đó
        $variants = $product->variants()->with('options')->paginate(5);

        // Trả về view hiển thị danh sách biến thể
        return view('admin.products.variants.index', compact('product', 'variants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($product_id)
    {

        $product = Product::findOrFail($product_id);

        return view('admin.products.variants.create', compact('product'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $product_id)
    {
        // Validate dữ liệu
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'options' => 'required|array|min:1',
            'options.*.option' => 'required|string|max:255',
            'options.*.price_modifier' => 'nullable|numeric|min:0',
        ]);

        // dd($validatedData);

        try {
            // Tìm kiếm sản phẩm theo ID
            $product = Product::findOrFail($product_id);

            // Tạo biến thể
            $variant = $product->variants()->create([
                'name' => $validatedData['name'],
            ]);

            // Tạo các tùy chọn cho biến thể
            foreach ($validatedData['options'] as $option) {
                $variant->options()->create([
                    'option' => $option['option'],
                    'price_modifier' => $option['price_modifier'] ?? 0,
                ]);
            }

            // Điều hướng sau khi lưu thành công
            return redirect()->route('admin.products.show', $product_id)->with('success', 'Variant added successfully!');
        } catch (\Exception $e) {
            // Bắt lỗi và thông báo cho người dùng
            return back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show($product_id, $variant_id)
    {
        // Kiểm tra sản phẩm trước
        $product = Product::findOrFail($product_id);

        // Tìm variant theo sản phẩm
        $variant = $product->variants()->findOrFail($variant_id);

        return view('admin.products.variants.show', compact('variant'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($product_id, $variant_id)
    {
        // Kiểm tra sản phẩm trước
        $product = Product::findOrFail($product_id);

        // Tìm variant theo sản phẩm
        $variant = $product->variants()->findOrFail($variant_id);

        return view('admin.products.variants.edit', compact('product', 'variant'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $productId, string $variantId)
    {
        // Validate dữ liệu
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'options' => 'required|array|min:1',
            'options.*.option' => 'required|string|max:255',
            'options.*.price_modifier' => 'nullable|numeric|min:0',
        ]);

        try {
            $product = Product::findOrFail($productId);
            // Tìm biến thể theo ID
            $variant = $product->variants()->findOrFail($variantId);
            // Cập nhật tên biến thể
            $variant->update([
                'name' => $validatedData['name'],
            ]);

            // Cập nhật các tùy chọn biến thể
            $variant->options()->delete();  // Xóa các tùy chọn cũ

            foreach ($validatedData['options'] as $option) {
                $variant->options()->create([
                    'option' => $option['option'],
                    'price_modifier' => $option['price_modifier'] ?? 0,
                ]);
            }

            // Điều hướng sau khi cập nhật thành công
            return redirect()->route('admin.products.show', $variant->product_id)->with('success', 'Variant updated successfully!');
        } catch (\Exception $e) {
            dd($e->getMessage());
            return back()->withErrors($e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($product_id, $variant_id)
    {
        try {
            $product = Product::findOrFail($product_id);

            $variant = $product->variants()->findOrFail($variant_id);

            $variant->options()->delete();
            $variant->delete();

            // Điều hướng sau khi xóa thành công
            return redirect()->route('admin.products.show', $product_id)->with('success', 'Variant deleted successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }
}
