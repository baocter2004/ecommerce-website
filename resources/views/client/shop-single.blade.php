@extends('client.layouts.master')

@section('content')
    <div class="bg-light py-3">
        <div class="container">
            <div class="row">
                <div class="col-md-12 mb-0">
                    <a href="{{ route('client.index') }}">Home</a>
                    <span class="mx-2 mb-0">/</span>
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
                    <p>{{ $product->short_description }}</p>
                    <p>
                        <strong class="text-primary h4">
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

                        @if ($product->variants)
                            <strong class="text-primary h4">
                                <span class="badge bg-light text-dark">
                                    Giá: <span
                                        id="product-price">{{ number_format($minPrice + $product->price, 0, ',', '.') }}</span>
                                    VND
                                </span>
                            </strong>
                        @endif

                    </p>
                    <form action="{{ route('client.cart.add', $product->id) }}" method="POST">
                        @csrf
                        <div class="mb-1 d-flex">
                            @foreach ($product->variants as $variant)
                                <div class="row variant-group mb-3">
                                    <strong>{{ $variant->name }}:</strong>
                                    @foreach ($variant->options as $option)
                                        <label for="option-{{ $option->id }}" class="d-flex mr-3 mb-3">
                                            <input type="radio" id="option-{{ $option->id }}" name="option_id"
                                                value="{{ $option->id }}" class="mr-2"
                                                data-price="{{ $option->price_modifier }}">
                                            <span class="d-inline-block text-black">{{ $option->option }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                        <div class="mb-5">
                            <div class="input-group mb-3" style="max-width: 120px;">
                                <input type="text" class="form-control text-center" id="quantity" name="quantity"
                                    value="1" placeholder="1" min="1" aria-label="Quantity"
                                    aria-describedby="button-addon1">
                            </div>
                        </div>

                        <button type="submit" class="buy-now btn btn-sm btn-primary">Add To Cart</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('client.layouts.components.featured-product', ['featured_products' => $featured_products])
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var basePrice = parseFloat('{{ $product->price }}');
            var priceDisplay = document.getElementById('product-price');
            var quantityInput = document.getElementById('quantity');
            var quantityNumber = parseInt(quantityInput.value, 10);
            var variantOptions = document.querySelectorAll('input[name="option_id"]');

            quantityInput.addEventListener('change', function() {
                quantityNumber = parseInt(quantityInput.value, 10);
                if (isNaN(quantityNumber) || quantityNumber < 1) {
                    quantityNumber = 1;
                }
                updatePrice();
            });

            variantOptions.forEach(function(option) {
                option.addEventListener('change', function() {
                    updatePrice();
                });
            });

            function updatePrice() {
                // Lấy giá trị price_modifier từ tùy chọn được chọn
                var selectedOption = document.querySelector('input[name="option_id"]:checked');
                var priceModifier = selectedOption ? parseFloat(selectedOption.getAttribute('data-price')) : 0;

                // Tính giá mới dựa trên giá cơ bản, priceModifier và số lượng
                var newPrice = (basePrice + priceModifier) * quantityNumber;

                // Cập nhật giá hiển thị
                priceDisplay.textContent = newPrice.toLocaleString('vi-VN');

                console.log(selectedOption, priceModifier, newPrice)
            }

            updatePrice();
        });
    </script>
@endsection
