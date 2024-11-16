@extends('client.layouts.master')

@section('content')
    <div class="bg-light py-3">
        <div class="container">
            <div class="row">
                <div class="col-md-12 mb-0"><a href="{{ route('client.index') }}">Home</a> <span class="mx-2 mb-0">/</span>
                    <strong class="text-black">{{ $product->product_name }}</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="site-section">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <img src="{{ Storage::url($product->product_image) }}" alt="Image" class="img-fluid">
                </div>
                <div class="col-md-6">
                    <h2 class="text-black">{{ $product->product_name }}</h2>
                    <p>
                        {{ $product->short_description }}
                    </p>
                    <p>
                        <strong class="text-primary h4">
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
                                    Giá: {{ number_format($minPrice + $product->price, 0, ',', '.') }} VND -
                                    {{ number_format($maxPrice + $product->price, 0, ',', '.') }} VND
                                </span>
                            @else
                                <span class="badge bg-light text-dark">
                                    Giá: {{ number_format($product->price, 0, ',', '.') }} VND
                                </span>
                            @endif
                        </strong>
                    </p>
                    <div class="mb-1 d-flex">
                        @foreach ($product->variants as $variant)
                            @foreach ($variant->options as $option)
                                <label for="option-sm" class="d-flex mr-3 mb-3">
                                    <span class="d-inline-block mr-2" style="top:-2px; position: relative;"><input
                                            type="radio" id="option-sm" name="shop-sizes"></span> <span
                                        class="d-inline-block text-black">{{$option->option}}</span>
                                </label>
                            @endforeach
                        @endforeach
                    </div>
                    <div class="mb-5">
                        <div class="input-group mb-3" style="max-width: 120px;">
                            <div class="input-group-prepend">
                                <button class="btn btn-outline-primary js-btn-minus" type="button">&minus;</button>
                            </div>
                            <input type="text" class="form-control text-center" value="1" placeholder=""
                                aria-label="Example text with button addon" aria-describedby="button-addon1">
                            <div class="input-group-append">
                                <button class="btn btn-outline-primary js-btn-plus" type="button">&plus;</button>
                            </div>
                        </div>

                    </div>
                    <p><a href="cart.html" class="buy-now btn btn-sm btn-primary">Add To Cart</a></p>

                </div>
            </div>
        </div>
    </div>
    @include('client.layouts.components.featured-product', ['featured_products' => $featured_products])
@endsection
