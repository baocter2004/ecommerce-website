<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\View;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'variants.options'])
            ->latest('id')
            ->limit(5)->get();

        $categories = Category::get(['id', 'name']);

        $featured_products = $this->getFeaturedProduct(5);

        return view('client.index', compact('products', 'featured_products', 'categories'));
    }
    public function shopSingle(string $id)
    {
        $product = Product::findOrFail($id);

        if ($product) {
            View::create(['product_id' => $product->id]);
        }

        $featured_products = $this->getFeaturedProduct(10);

        return view('client.shop-single', compact('product', 'featured_products'));
    }

    public function shop(Request $request)
    {
        $category_id = $request->input('category_id');;

        $query = Product::with(['category', 'variants.options']);

        // Thêm điều kiện lọc theo danh mục
        if ($category_id) {
            $query->where('category_id', $category_id);
        }

        $products = $query->latest('id')
            ->latest('id')
            ->paginate(6);

        $categories = Category::get(['id', 'name']);

        $featured_products = $this->getFeaturedProduct(5);

        return view('client.shop', compact('products', 'featured_products', 'categories'));
    }

    public function cart()
    {
        return view('client.cart');
    }

    public function checkout()
    {
        return view('client.checkout');
    }

    public function contact()
    {
        return view('client.contact');
    }

    public function about()
    {
        return view('client.about');
    }

    public function thankyou()
    {
        return view('client.thankyou');
    }

    public function search(Request $request) {}

    public function getFeaturedProduct($limit, $type = '')
    {
        $query = Product::query();
        if ($type === 'views') {
            $query->withCount('views')->orderBy('views_count', 'desc');
        } elseif ($type === 'sales') {
            $query->withCount('sales')->orderBy('sales_count', 'desc');
        } else {
            $query->withCount('views')->orderBy('views_count', 'desc');
        }

        return $query->take($limit)->get();
    }

    public function getCategoryProduct($categoryId)
    {
        $products = Product::with(['category', 'variants.options'])
            ->where('category_id', $categoryId)
            ->latest('id')
            ->paginate(6);

        return $products;
    }
}
