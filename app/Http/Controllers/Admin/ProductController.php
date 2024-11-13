<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('category','variants.options')->latest('id')->paginate(5);
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
            $data = $request->except('product_image');

            if ($request->hasFile('product_image')) {
                $data['product_image'] = Storage::put('products', $request->file('product_image'));
            }

            Product::query()->create($data);

            return redirect()
                ->route('admin.products.index')
                ->with('success', true);
        } catch (\Throwable $th) {
            // return back()->withErrors($th->getMessage());
            return back()->with('success', false);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load(['category:name,id','variants.options:variant_id,price_modifier,option']);

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
            $data = $request->except('product_image');

            $data['is_active'] = isset($data['is_active']) ?  $data['is_active'] : 0;

            if ($request->hasFile('product_image')) {
                $data['product_image'] = Storage::put('/products',  $request->file('product_image'));
            }

            $oldImage = $product->product_image;

            $product->update($data);

            if (
                $request->hasFile('product_image')
                && !empty($oldImage)
                && Storage::exists($oldImage)
            ) {
                Storage::delete($oldImage);
            }

            return redirect()
                ->route('admin.products.edit', $product)
                ->with('success', true);
        } catch (\Throwable $th) {
            // return back()->withErrors($th->getMessage());
            return back()->with('success', false);
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
}
