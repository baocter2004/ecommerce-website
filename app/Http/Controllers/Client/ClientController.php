<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index () 
    {
        return view('client.index');
    }
    public function shopSingle()
    {
        return view('client.shop-single');
    }

    public function shop()
    {
        return view('client.shop');
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
}
