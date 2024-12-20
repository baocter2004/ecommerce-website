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
                                <div class="block-4 text-center border">
                                    <figure class="block-4-image">
                                        <a href="{{ route('client.shop-single', $product->id) }}"><img
                                                src="{{ Storage::url($product->product_image) }}" alt="Image placeholder"
                                                class="img-fluid"></a>
                                    </figure>
                                    <div class="block-4-text p-4">
                                        <h3>
                                            <a
                                                href="{{ route('client.shop-single', $product->id) }}">{{ Str::limit($product->product_name, 15) }}</a>
                                        </h3>
                                        <p class="mb-0">{{ Str::limit($product->short_description, 25) }}</p>
                                        <p class="text-primary font-weight-bold">
                                            @php
                                                // Khởi tạo các biến giá trị tối thiểu và tối đa từ giá biến thể
                                                $minPrice = null;
                                                $maxPrice = null;

                                                // Duyệt qua các variant và tìm giá nhỏ nhất và lớn nhất từ options
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
                                                <span class="badge bg-light text-dark">
                                                    Giá: {{ number_format($minPrice + $product->price, 0, ',', '.') }} VND
                                                    -
                                                    {{ number_format($maxPrice + $product->price, 0, ',', '.') }} VND
                                                </span>
                                            @else
                                                <span class="badge bg-light text-dark">
                                                    Giá: {{ number_format($product->price, 0, ',', '.') }} VND
                                                </span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="row" data-aos="fade-up">
                        <div class="col-md-12 text-center">
                            <div class="site-block-27">
                                {{ $products->links() }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 order-1 mb-5 mb-md-0">
                    <div class="border p-4 rounded mb-4">
                        <h3 class="mb-3 h6 text-uppercase text-black d-block">Categories</h3>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-1"><a href="#" class="d-flex"><span>Men</span> <span
                                        class="text-black ml-auto">(2,220)</span></a></li>
                            <li class="mb-1"><a href="#" class="d-flex"><span>Women</span> <span
                                        class="text-black ml-auto">(2,550)</span></a></li>
                            <li class="mb-1"><a href="#" class="d-flex"><span>Children</span> <span
                                        class="text-black ml-auto">(2,124)</span></a></li>
                        </ul>
                    </div>
                    <div class="border p-4 rounded mb-4">
                        <div class="mb-4">
                            <h3 class="mb-3 h6 text-uppercase text-black d-block">Filter by Price</h3>
                            <div id="slider-range" class="border-primary"></div>
                            <input type="text" name="text" id="amount" class="form-control border-0 pl-0 bg-white"
                                disabled="" />
                        </div>

                        <div class="mb-4">
                            <h3 class="mb-3 h6 text-uppercase text-black d-block">Size</h3>
                            <label for="s_sm" class="d-flex">
                                <input type="checkbox" id="s_sm" class="mr-2 mt-1"> <span class="text-black">Small
                                    (2,319)</span>
                            </label>
                            <label for="s_md" class="d-flex">
                                <input type="checkbox" id="s_md" class="mr-2 mt-1"> <span class="text-black">Medium
                                    (1,282)</span>
                            </label>
                            <label for="s_lg" class="d-flex">
                                <input type="checkbox" id="s_lg" class="mr-2 mt-1"> <span class="text-black">Large
                                    (1,392)</span>
                            </label>
                        </div>

                        <div class="mb-4">
                            <h3 class="mb-3 h6 text-uppercase text-black d-block">Color</h3>
                            <a href="#" class="d-flex color-item align-items-center">
                                <span class="bg-danger color d-inline-block rounded-circle mr-2"></span> <span
                                    class="text-black">Red (2,429)</span>
                            </a>
                            <a href="#" class="d-flex color-item align-items-center">
                                <span class="bg-success color d-inline-block rounded-circle mr-2"></span> <span
                                    class="text-black">Green (2,298)</span>
                            </a>
                            <a href="#" class="d-flex color-item align-items-center">
                                <span class="bg-info color d-inline-block rounded-circle mr-2"></span> <span
                                    class="text-black">Blue (1,075)</span>
                            </a>
                            <a href="#" class="d-flex color-item align-items-center">
                                <span class="bg-primary color d-inline-block rounded-circle mr-2"></span> <span
                                    class="text-black">Purple (1,075)</span>
                            </a>
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
