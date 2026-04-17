@extends('client.layouts.master')

@section('content')
    <div class="bg-light py-3">
        <div class="container">
            <div class="row">
                <div class="col-md-12 mb-0"><a href="{{ route('client.index') }}">Trang chủ</a> <span class="mx-2 mb-0">/</span>
                    <strong class="text-black">Cửa hàng</strong>
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
                                <h2 class="text-black h5">Tất cả sản phẩm</h2>
                            </div>
                            <div class="d-flex float-md-right">
                                <div class="dropdown mr-1 ml-md-4">
                                    <button type="button" class="btn btn-secondary btn-sm dropdown-toggle" id="dropdownMenuOffset"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        @php
                                            $sort = request('sort');
                                            $labels = [
                                                'latest' => 'Mới nhất',
                                                'price_asc' => 'Giá thấp đến cao',
                                                'price_desc' => 'Giá cao đến thấp',
                                                'name_asc' => 'Tên (A -> Z)',
                                                'name_desc' => 'Tên (Z -> A)',
                                            ];
                                            $currentLabel = $labels[$sort] ?? 'Mới nhất';
                                        @endphp
                                        Sắp xếp: {{ $currentLabel }}
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuOffset">
                                        @foreach($labels as $key => $label)
                                            <a class="dropdown-item" href="{{ route('client.shop', array_merge(request()->query(), ['sort' => $key, 'page' => 1])) }}">
                                                {{ $label }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-5">
                        @forelse ($products as $product)
                            <div class="col-sm-6 col-lg-4 mb-4" data-aos="fade-up">
                                <div class="block-4 text-center border h-100">
                                    <div class="p-2 d-flex justify-content-end">
                                        @auth
                                            @php
                                                $isFavorited = \App\Models\Favorite::where('user_id', Auth::id())
                                                    ->where('product_id', $product->id)
                                                    ->exists();
                                            @endphp
                                            <button type="button" class="btn btn-sm btn-light rounded-circle shadow-sm favorite-btn"
                                                data-product-id="{{ $product->id }}" title="{{ $isFavorited ? 'Bỏ yêu thích' : 'Yêu thích' }}">
                                                <i class="bi {{ $isFavorited ? 'bi-heart-fill text-danger' : 'bi-heart text-muted' }}"></i>
                                            </button>
                                        @else
                                            <a href="{{ route('login') }}" class="btn btn-sm btn-light rounded-circle shadow-sm" title="Đăng nhập để yêu thích">
                                                <i class="bi bi-heart text-muted"></i>
                                            </a>
                                        @endauth
                                    </div>
                                    <figure class="block-4-image">
                                        <a href="{{ route('client.shop-single', $product->id) }}">
                                            <img src="{{ Storage::url($product->product_image) }}" alt="Image placeholder" class="img-fluid">
                                        </a>
                                    </figure>
                                    <div class="block-4-text p-4">
                                        <h3 class="mb-2">
                                            <a href="{{ route('client.shop-single', $product->id) }}">{{ Str::limit($product->product_name, 20) }}</a>
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
                                                    {{ number_format($product->price, 0, ',', '.') }}đ
                                                </span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5">
                                <p class="text-muted">Không tìm thấy sản phẩm nào phù hợp với yêu cầu của bạn.</p>
                                <a href="{{ route('client.shop') }}" class="btn btn-primary btn-sm">Xem tất cả sản phẩm</a>
                            </div>
                        @endforelse
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
                    <!-- Search Filter -->
                    <div class="border p-4 rounded mb-4">
                        <h3 class="mb-3 h6 text-uppercase text-black d-block">Tìm kiếm</h3>
                        <form action="{{ route('client.shop') }}" method="GET">
                            @foreach(request()->query() as $key => $value)
                                @if(!in_array($key, ['keyword', 'page']) && !is_array($value))
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endif
                            @endforeach
                            <div class="input-group">
                                <input type="text" name="keyword" class="form-control border-0 bg-light" placeholder="Tên sản phẩm..." value="{{ request('keyword') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary btn-sm" type="submit"><i class="bi bi-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Category Filter -->
                    <div class="border p-4 rounded mb-4">
                        <h3 class="mb-3 h6 text-uppercase text-black d-block">Danh mục</h3>
                        <ul class="list-unstyled mb-0">
                            @foreach ($categories as $category)
                                <li class="mb-1">
                                    <a href="{{ route('client.shop', array_merge(request()->query(), ['category_id' => $category->id, 'page' => 1])) }}" 
                                       class="d-flex {{ request('category_id') == $category->id ? 'text-primary font-weight-bold' : '' }}">
                                        <span>{{ $category->name }}</span>
                                    </a>
                                </li>
                            @endforeach
                            @if(request('category_id'))
                                <li class="mt-2 pt-2 border-top">
                                    @php
                                        $withoutCategory = request()->query();
                                        unset($withoutCategory['category_id'], $withoutCategory['page']);
                                    @endphp
                                    <a href="{{ route('client.shop', $withoutCategory) }}" class="small text-danger">
                                        <i class="bi bi-x-circle mr-1"></i> Xóa lọc danh mục
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>

                    <!-- Price Filter -->
                    <div class="border p-4 rounded mb-4">
                        <h3 class="mb-3 h6 text-uppercase text-black d-block">Lọc theo giá</h3>
                        <form action="{{ route('client.shop') }}" method="GET">
                            @foreach(request()->query() as $key => $value)
                                @if(!in_array($key, ['min_price', 'max_price', 'page']) && !is_array($value))
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endif
                            @endforeach

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

            <!-- Featured Products -->
            <div class="row mt-5 pt-5 border-top">
                <div class="col-md-12">
                    <div class="site-section site-blocks-2">
                        <div class="row justify-content-center text-center mb-5">
                            <div class="col-md-7 site-section-heading pt-4">
                                <h2>Sản phẩm nổi bật</h2>
                            </div>
                        </div>
                        <div class="row">
                            @foreach($featured_products as $f_product)
                            <div class="col-sm-6 col-md-4 col-lg-3 mb-4" data-aos="fade" data-aos-delay="">
                                <a class="block-2-item card h-100 border-0 shadow-sm" href="{{ route('client.shop-single', $f_product->id) }}">
                                    <figure class="block-4-image m-0">
                                        <img src="{{ Storage::url($f_product->product_image) }}" alt="" class="img-fluid">
                                    </figure>
                                    <div class="text text-center mt-2">
                                        <span class="text-uppercase small text-muted">{{ $f_product->category->name ?? 'Sản phẩm' }}</span>
                                        <h3 class="h6 text-black">{{ Str::limit($f_product->product_name, 20) }}</h3>
                                    </div>
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
