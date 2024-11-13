<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;

class DashboardController extends Controller
{
    public function dashboard() {
       $categories = Category::withCount('products')->get();
       return view('admin.dashboard', compact('categories'));
    }
}
