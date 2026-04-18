<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('category', 'variants.options')->latest('id')->paginate(5);
        // dd($products);
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::get(['id', 'name'])->toArray();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->except(['product_image', 'variants']);
            $data['quantity'] = $data['quantity'] ?? 0;
            $data['is_active'] = $request->has('is_active') ? 1 : 0;

            if ($request->hasFile('product_image')) {
                $data['product_image'] = Storage::put('products', $request->file('product_image'));
            }

            $product = Product::query()->create($data);

            // Xử lý nhiều biến thể
            if ($request->has('variants')) {
                $variantMatrix = [];
                foreach ($request->variants as $vData) {
                    if (empty($vData['name']) || empty($vData['options'])) continue;

                    $variant = $product->variants()->create([
                        'name' => $vData['name']
                    ]);

                    $optionNames = [];
                    foreach ($vData['options'] as $opt) {
                        if (empty($opt['option'])) continue;
                        $variant->options()->create([
                            'option' => $opt['option'],
                            'price_modifier' => $opt['price_modifier'] ?? 0,
                            'quantity' => $opt['quantity'] ?? 0,
                        ]);
                        $optionNames[] = $opt['option'];
                    }
                    
                    if (!empty($optionNames)) {
                        $variantMatrix[$vData['name']] = $optionNames;
                    }
                }

                // Tự động tạo tổ hợp (ProductVariant)
                $this->generateProductVariants($product, $variantMatrix);
            }

            DB::commit();

            return redirect()
                ->route('admin.products.index')
                ->with('success', true);
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('success', false)->withErrors($th->getMessage());
        }
    }

    private function generateProductVariants($product, $matrix)
    {
        $colors = [];
        $sizes = [];

        foreach ($matrix as $name => $options) {
            $lowerName = mb_strtolower($name);
            if (str_contains($lowerName, 'màu') || str_contains($lowerName, 'color')) {
                $colors = $options;
            } elseif (str_contains($lowerName, 'size') || str_contains($lowerName, 'kích')) {
                $sizes = $options;
            }
        }

        if (empty($colors)) $colors = [null];
        if (empty($sizes)) $sizes = [null];

        foreach ($colors as $color) {
            foreach ($sizes as $size) {
                if ($color === null && $size === null) continue;
                
                $product->productVariants()->create([
                    'color' => $color,
                    'size' => $size,
                    'stock' => 10, // Mặc định
                ]);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load([
            'category:id,name',
            'variants:id,product_id,name',
            'variants.options:id,variant_id,option,price_modifier,quantity',
            'productVariants:id,product_id,color,size,stock',
        ]);

        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::get(['id', 'name']);
        return view('admin.products.edit', ['product' => $product, 'categories' => $categories]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        try {
            DB::beginTransaction();

            $data = $request->except(['product_image', 'variant_name', 'variant_options']);

            $data['is_active'] = isset($data['is_active']) ?  $data['is_active'] : 0;

            if ($request->hasFile('product_image')) {
                $data['product_image'] = Storage::put('/products',  $request->file('product_image'));
            }

            $oldImage = $product->product_image;

            $product->update($data);

            if ($request->hasFile('product_image') && !empty($oldImage) && Storage::exists($oldImage)) {
                Storage::delete($oldImage);
            }

            // Sync Variants (Simple approach: replace if new variants are provided)
            if ($request->filled('variant_name')) {
                $hasValidOptions = false;
                foreach ($request->variant_options as $option) {
                    if (!empty($option['option'])) {
                        $hasValidOptions = true;
                        break;
                    }
                }

                if ($hasValidOptions) {
                    // Delete old variants and their options
                    foreach ($product->variants as $v) {
                        $v->options()->delete();
                        $v->delete();
                    }

                    $variant = $product->variants()->create([
                        'name' => $request->variant_name
                    ]);

                    foreach ($request->variant_options as $option) {
                        if (!empty($option['option'])) {
                            $variant->options()->create([
                                'option' => $option['option'],
                                'price_modifier' => $option['price_modifier'] ?? 0,
                                'quantity' => $option['quantity'] ?? 0,
                            ]);
                        }
                    }
                }
            }

            DB::commit();

            return redirect()
                ->route('admin.products.edit', $product)
                ->with('success', true);
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('success', false)->withErrors($th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            $product->delete();

            // if (Storage::exists($product->product_image)) {
            //     Storage::delete($product->product_image);
            // }

            return redirect()
                ->route('admin.products.index')
                ->with('success', true);
        } catch (\Throwable $th) {
            //throw $th;
            return back()->with('success', false);
        }
    }

    public function trash()
    {
        $trashList = Product::onlyTrashed()->latest('id')->paginate(5);

        return view('admin.products.trash', compact('trashList'));
    }

    public function forceDestroy($id)
    {
        try {
            $product = Product::onlyTrashed()->findOrFail($id);
            $product->forceDelete();
            if (Storage::exists($product->product_image)) {
                Storage::delete($product->product_image);
            }
            return redirect()
                ->route('admin.products.trash')
                ->with('success', true);
        } catch (\Throwable $th) {
            return back()->with('success', false);
        }
    }

    public function restore($id)
    {
        try {
            $product = Product::onlyTrashed()->findOrFail($id);

            // dd($product);
            $product->restore();
            return redirect()
                ->route('admin.products.index')
                ->with('success', true);
        } catch (\Throwable $th) {
            return back()->with('success', false);
        }
    }

    public function search(Request $request)
    {
        // Lấy từ khóa tìm kiếm từ input
        $searchKey = $request->input('search_products');
        // lựa chọn kiểu tìm kiếm (danh mục - mô tả sp , tên sp)
        $searchType = $request->input('search_type');
        if (!empty($searchKey)) {
            if ($searchType === 'category') {
                $products = Product::with('category:id,name')
                    ->whereHas('category', function ($search) use ($searchKey) {
                        $search->where('name', 'LIKE', "%{$searchKey}%");
                    })
                    ->latest('id')
                    ->paginate(5);
            } else {
                $products = Product::where('product_name', 'LIKE', "%{$searchKey}%")
                    ->orWhere('description', 'LIKE', "%{$searchKey}%")
                    ->latest('id')
                    ->paginate(5);
            }
            // dd($products);
        } else {
            $products = Product::latest('id')->paginate(5);
        }

        return view('admin.products.index', compact('products'));
    }
}
