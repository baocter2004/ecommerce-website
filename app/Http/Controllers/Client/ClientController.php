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

        $categories = Category::get(['id', 'name','category_image'])->take(3);

        // Lấy sản phẩm yêu thích nhất (Top Favorited)
        $featured_products = $this->getFeaturedProduct(8, 'favorites');

        return view('client.index', compact('products', 'featured_products', 'categories'));
    }
    public function shopSingle(string $id)
    {
        $product = Product::with(['category', 'variants.options', 'comments.user'])->findOrFail($id);

        if ($product) {
            View::create(['product_id' => $product->id]);
        }

        $featured_products = $this->getFeaturedProduct(10, 'favorites');

        return view('client.shop-single', compact('product', 'featured_products'));
    }

    public function shop(Request $request)
    {
        $category_id = $request->input('category_id');
        $keyword = $request->input('keyword');
        $min_price = $request->input('min_price');
        $max_price = $request->input('max_price');

        $query = Product::with(['category', 'variants.options']);

        // Lọc theo danh mục
        if ($category_id) {
            $query->where('category_id', $category_id);
        }

        // Tìm kiếm theo tên
        if ($keyword) {
            $query->where('product_name', 'LIKE', "%{$keyword}%");
        }

        // Lọc theo khoảng giá
        if ($min_price) {
            $query->where('price', '>=', $min_price);
        }
        if ($max_price) {
            $query->where('price', '<=', $max_price);
        }

        $products = $query->latest('id')->paginate(9);

        $categories = Category::get(['id', 'name']);
        
        // Lấy sản phẩm yêu thích nhất (Top Favorited)
        $featured_products = $this->getFeaturedProduct(5, 'favorites');

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
        
        if ($type === 'favorites') {
            // Lấy sản phẩm được nhiều người "Tim" nhất
            $query->withCount('favorites')->orderBy('favorites_count', 'desc');
        } elseif ($type === 'views') {
            $query->withCount('views')->orderBy('views_count', 'desc');
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
