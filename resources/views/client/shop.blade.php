@extends('client.layouts.master')

@section('content')
    <div class="bg-light py-3">
        <div class="container">
            <div class="row">
                <div class="col-md-12 mb-0"><a href="{{ route('client.index') }}">Home</a> <span class="mx-2 mb-0">/</span>
                    <strong class="text-black">Shop</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="site-section">
        <div class="container">

            <div class="row mb-5">
                <div class="col-md-9 order-2">

                    <div class="row">
                        <div class="col-md-12 mb-5">
                            <div class="float-md-left mb-4">
                                <h2 class="text-black h5">Shop All</h2>
                            </div>
                            <div class="d-flex">
                                <div class="dropdown mr-1 ml-md-auto">
                                    <button type="button" class="btn btn-secondary btn-sm dropdown-toggle"
                                        id="dropdownMenuOffset" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        Latest
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuOffset">
                                        @foreach ($categories as $category)
                                            <a class="dropdown-item"
                                                href="{{ route('client.shop', ['category_id' => $category->id]) }}"
                                                {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </a>
                                        @endforeach
                                        <a class="dropdown-item" href="{{ route('client.shop') }}"
                                            {{ request('category_id') == '' ? 'selected' : '' }}>
                                            Tất cả danh mục
                                        </a>
                                    </div>
                                </div>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-secondary btn-sm dropdown-toggle"
                                        id="dropdownMenuReference" data-toggle="dropdown">Reference</button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuReference">
                                        <a class="dropdown-item" href="#">Relevance</a>
                                        <a class="dropdown-item" href="#">Name, A to Z</a>
                                        <a class="dropdown-item" href="#">Name, Z to A</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="#">Price, low to high</a>
                                        <a class="dropdown-item" href="#">Price, high to low</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-5">
                        @foreach ($products as $product)
                            <div class="col-sm-6 col-lg-4 mb-4" data-aos="fade-up">
                                <div class="block-4 text-center border position-relative">
                                    <div class="position-absolute" style="top: 10px; right: 10px; z-index: 10;">
                                        @auth
                                            @php
                                                $isFavorited = \App\Models\Favorite::where('user_id', Auth::id())->where('product_id', $product->id)->exists();
                                            @endphp
                                            <button type="button" 
                                                class="btn btn-sm btn-light rounded-circle shadow-sm favorite-btn" 
                                                data-product-id="{{ $product->id }}"
                                                title="{{ $isFavorited ? 'Bỏ yêu thích' : 'Yêu thích' }}">
                                                <i class="bi {{ $isFavorited ? 'bi-heart-fill text-danger' : 'bi-heart text-muted' }}"></i>
                                            </button>
                                        @else
                                            <a href="{{ route('login') }}" class="btn btn-sm btn-light rounded-circle shadow-sm" title="Đăng nhập để yêu thích">
                                                <i class="bi bi-heart text-muted"></i>
                                            </a>
                                        @endauth
                                    </div>
                                    <figure class="block-4-image">
                                        <a href="{{ route('client.shop-single', $product->id) }}"><img
                                                src="{{ Storage::url($product->product_image) }}" alt="Image placeholder"
                                                class="img-fluid"></a>
                                    </figure>
                                    <div class="block-4-text p-4">
                                        <h3 class="mb-2">
                                            <a
                                                href="{{ route('client.shop-single', $product->id) }}">{{ Str::limit($product->product_name, 20) }}</a>
                                        </h3>
                                        <p class="mb-2 small text-muted">{{ Str::limit($product->short_description, 30) }}</p>
                                        <p class="text-primary font-weight-bold mb-0">
                                            @php
                                                $minPrice = null;
                                                $maxPrice = null;
                                                foreach ($product->variants as $variant) {
                                                    foreach ($variant->options as $option) {
                                                        if ($minPrice === null || $option->price_modifier < $minPrice) {
                                                            $minPrice = $option->price_modifier;
                                                        }
                                                        if ($maxPrice === null || $option->price_modifier > $maxPrice) {
                                                            $maxPrice = $option->price_modifier;
                                                        }
                                                    }
                                                }
                                            @endphp

                                            @if ($minPrice !== null && $maxPrice !== null)
                                                <span class="text-dark">
                                                    {{ number_format($minPrice + $product->price, 0, ',', '.') }}đ
                                                    -
                                                    {{ number_format($maxPrice + $product->price, 0, ',', '.') }}đ
                                                </span>
                                            @else
                                                <span class="text-dark">
                                                    {{ number_format($product->price, 0, ',', '.') }} VND
                                                </span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    </div>
                    <div class="row" data-aos="fade-up">
                        <div class="col-md-12 text-center">
                            <div class="site-block-27">
                                {{ $products->links() }}
                            </div>
                        </div>
                    </div>
                <div class="col-md-3 order-1 mb-5 mb-md-0">
                    <div class="border p-4 rounded mb-4">
                        <h3 class="mb-3 h6 text-uppercase text-black d-block">Tìm kiếm</h3>
                        <form action="{{ route('client.shop') }}" method="GET">
                            <div class="input-group">
                                <input type="text" name="keyword" class="form-control border-0 bg-light" placeholder="Tên sản phẩm..." value="{{ request('keyword') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary btn-sm" type="submit"><i class="bi bi-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="border p-4 rounded mb-4">
                        <h3 class="mb-3 h6 text-uppercase text-black d-block">Danh mục</h3>
                        <ul class="list-unstyled mb-0">
                            @foreach ($categories as $category)
                                <li class="mb-1">
                                    <a href="{{ route('client.shop', ['category_id' => $category->id]) }}" class="d-flex {{ request('category_id') == $category->id ? 'text-primary font-weight-bold' : '' }}">
                                        <span>{{ $category->name }}</span>
                                    </a>
                                </li>
                            @endforeach
                            <li class="mt-2 pt-2 border-top">
                                <a href="{{ route('client.shop') }}" class="small text-muted">Xóa lọc danh mục</a>
                            </li>
                        </ul>
                    </div>

                    <div class="border p-4 rounded mb-4">
                        <h3 class="mb-3 h6 text-uppercase text-black d-block">Lọc theo giá</h3>
                        <form action="{{ route('client.shop') }}" method="GET">
                            @if(request('category_id'))
                                <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                            @endif
                            @if(request('keyword'))
                                <input type="hidden" name="keyword" value="{{ request('keyword') }}">
                            @endif
                            
                            <div class="form-group mb-2">
                                <input type="number" name="min_price" class="form-control form-control-sm" placeholder="Giá từ..." value="{{ request('min_price') }}">
                            </div>
                            <div class="form-group mb-3">
                                <input type="number" name="max_price" class="form-control form-control-sm" placeholder="Đến giá..." value="{{ request('max_price') }}">
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm btn-block">Lọc giá</button>
                        </form>
                    </div>

                    <div class="text-center">
                        <a href="{{ route('client.shop') }}" class="btn btn-outline-secondary btn-sm">Xóa tất cả lọc</a>
                    </div>
                </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="site-section site-blocks-2">
                        <div class="row justify-content-center text-center mb-5">
                            <div class="col-md-7 site-section-heading pt-4">
                                <h2>Categories</h2>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 col-md-6 col-lg-4 mb-4 mb-lg-0" data-aos="fade" data-aos-delay="">
                                <a class="block-2-item" href="#">
                                    <figure class="image">
                                        <img src="/client/images/women.jpg" alt="" class="img-fluid">
                                    </figure>
                                    <div class="text">
                                        <span class="text-uppercase">Collections</span>
                                        <h3>Women</h3>
                                    </div>
                                </a>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-4 mb-5 mb-lg-0" data-aos="fade" data-aos-delay="100">
                                <a class="block-2-item" href="#">
                                    <figure class="image">
                                        <img src="/client/images/children.jpg" alt="" class="img-fluid">
                                    </figure>
                                    <div class="text">
                                        <span class="text-uppercase">Collections</span>
                                        <h3>Children</h3>
                                    </div>
                                </a>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-4 mb-5 mb-lg-0" data-aos="fade" data-aos-delay="200">
                                <a class="block-2-item" href="#">
                                    <figure class="image">
                                        <img src="/client/images/men.jpg" alt="" class="img-fluid">
                                    </figure>
                                    <div class="text">
                                        <span class="text-uppercase">Collections</span>
                                        <h3>Men</h3>
                                    </div>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
