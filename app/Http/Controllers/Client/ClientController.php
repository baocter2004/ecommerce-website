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

        $categories = Category::get(['id', 'name', 'category_image'])->take(3);

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
        $sort = $request->input('sort', 'latest');

        $query = Product::with(['category', 'variants.options']);

        if ($category_id) {
            $query->where('category_id', $category_id);
        }

        if ($keyword) {
            $query->where('product_name', 'LIKE', "%{$keyword}%");
        }

        if ($min_price) {
            $query->where('price', '>=', $min_price);
        }

        if ($max_price) {
            $query->where('price', '<=', $max_price);
        }

        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('product_name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('product_name', 'desc');
                break;
            default:
                $query->latest('id');
                break;
        }

        $products = $query->paginate(9)->appends($request->all());
        $categories = Category::get(['id', 'name']);
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

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        return redirect()->route('client.shop', ['keyword' => $keyword]);
    }

    public function getFeaturedProduct($limit, $type = '')
    {
        $query = Product::with(['category', 'variants.options']);

        if ($type === 'favorites') {
            $query->withCount('favorites')->orderBy('favorites_count', 'desc');
        } else {
            $query->latest('id');
        }

        $result = $query->limit($limit)->get();

        if ($result->isEmpty()) {
            return Product::with(['category', 'variants.options'])->latest('id')->limit($limit)->get();
        }

        return $result;
    }
}
