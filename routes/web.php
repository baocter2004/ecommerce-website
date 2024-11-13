<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VariantController;
use App\Http\Controllers\Auth\AuthenController;
use App\Http\Controllers\Client\ClientController;

use Illuminate\Support\Facades\Route;
use Monolog\Handler\RotatingFileHandler;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


// auth
Route::controller(AuthenController::class)
    ->group(function () {
        Route::get('register',  'showFormRegister')->name('register');
        Route::post('register',  'handleRegister');

        Route::get('login',  'showFormLogin')->name('login');
        Route::post('login',  'handleLogin');

        Route::post('logout',  'logout')->name('logout');
    });

// admin
Route::prefix('admin')
    ->middleware(['auth', 'isadmin'])
    ->name('admin.')
    ->group(function () {

        Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

        // Đường dẫn của CRUD users 
        Route::prefix('users')
            ->name('users.')
            ->controller(UserController::class)
            ->group(function () {
                Route::get('/trash', 'trash')->name('trash');

                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('/{user}', 'show')->name('show');
                Route::get('/{user}/edit', 'edit')->name('edit');
                Route::put('/{user}', 'update')->name('update');
                Route::delete('/{user}', 'destroy')->name('destroy');

                Route::delete('{user}/forcedestroy', 'forceDestroy')->name('forcedestroy');
                Route::post('{user}/restore', 'restore')->name('restore');
            });

        // Đường dẫn của CRUD categories

        Route::prefix('categories')
            ->name('categories.')
            ->controller(CategoryController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('/{category}/edit', 'edit')->name('edit');
                Route::put('/{category}', 'update')->name('update');
                Route::delete('/{category}', 'destroy')->name('destroy');

                Route::get('/trash', 'trash')->name('trash');
                Route::delete('{category}/forcedestroy', 'forceDestroy')->name('forcedestroy');
                Route::post('{category}/restore', 'restore')->name('restore');
            });


        //Đường Dẫn của CRUD products
        Route::prefix('products')
            ->name('products.')
            ->controller(ProductController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('/{product}/show', 'show')->name('show');
                Route::get('/{product}/edit', 'edit')->name('edit');
                Route::put('/{product}', 'update')->name('update');
                Route::delete('/{product}', 'destroy')->name('destroy');

                Route::get('/trash', 'trash')->name('trash');
                Route::delete('{product}/forcedestroy', 'forceDestroy')->name('forcedestroy');
                Route::post('{product}/restore', 'restore')->name('restore');
            });

        Route::prefix('products/{product}/variants')
            ->name('products.variants.')
            ->controller(VariantController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('/{variant}/edit', 'edit')->name('edit');
                Route::put('/{variant}', 'update')->name('update');
                Route::delete('/{variant}', 'destroy')->name('destroy');
            });
    });


// client

Route::name('client.')
    ->group(function () {
        Route::get('/', [ClientController::class, 'index'])->name('index');
        Route::get('/shop-single', [ClientController::class, 'shopSingle'])->name('shopSingle');
        Route::get('/shop', [ClientController::class, 'shop'])->name('shop');
        Route::get('/cart', [ClientController::class, 'cart'])->name('cart');
        Route::get('/checkout', [ClientController::class, 'checkout'])->name('checkout');
        Route::get('/contact', [ClientController::class, 'contact'])->name('contact');
        Route::get('/about', [ClientController::class, 'about'])->name('about');
        Route::get('/thankyou', [ClientController::class, 'thankyou'])->name('thankyou');
    });
