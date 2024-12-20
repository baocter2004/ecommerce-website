<div class="site-section block-3 site-blocks-2 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 site-section-heading text-center pt-4">
                <h2>Featured Products</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="nonloop-block-3 owl-carousel">
                    @foreach ($featured_products as $product)
                        <div class="item">
                            <div class="block-4 text-center">
                                <figure class="block-4-image">
                                    <a href="{{ route('client.shop-single', $product->id) }}">
                                        <img src="{{ Storage::url($product->product_image) }}" alt="Image placeholder"
                                            class="img-fluid">
                                    </a>
                                </figure>
                                <div class="block-4-text p-4">
                                    <h3>
                                        <a
                                            href="{{ route('client.shop-single', $product->id) }}">{{ Str::limit($product->product_name, 15) }}</a>
                                    </h3>
                                    <p class="mb-0">
                                        {{ Str::limit($product->short_description, 20) }}
                                    </p>
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
                                                Giá: {{ number_format($minPrice + $product->price, 0, ',', '.') }} VND -
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
            </div>
        </div>
    </div>
</div>
