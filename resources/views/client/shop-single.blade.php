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
            @if (session('success'))
                <div class="alert alert-success border-0 mb-4 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger border-0 mb-4 shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-warning border-0 mb-4 shadow-sm">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                <div class="col-md-6 mb-4 mb-md-0">
                    <img src="{{ Storage::url($product->product_image) }}" alt="Image"
                        class="img-fluid rounded shadow-sm">
                </div>
                <div class="col-md-6">
                    <h2 class="text-black font-weight-bold mb-3">{{ $product->product_name }}</h2>
                    <h3 class="text-muted mb-4">Danh Mục : {{ $product->category->name }}</h3>
                    <p class="text-muted mb-4">{{ $product->short_description }}</p>

                    @php
                        $hasVariants = $product->variants->isNotEmpty();
                        $isBasicProduct = $product->variants->isEmpty();
                        $basicStock = (int) ($product->quantity ?? 0);
                        $allowDefaultBasic = $hasVariants && $product->variants->count() === 1 && $basicStock > 0;
                    @endphp

                    <div class="mb-4">
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

                        <h4 class="text-primary font-weight-bold mb-0">
                            <span id="product-price">
                                {{ number_format(($minPrice ?? 0) + $product->price, 0, ',', '.') }}
                            </span> VND
                        </h4>
                        @if ($minPrice !== null && $minPrice !== $maxPrice)
                            <small class="text-muted">Giá dao động:
                                {{ number_format($minPrice + $product->price, 0, ',', '.') }}đ -
                                {{ number_format($maxPrice + $product->price, 0, ',', '.') }}đ</small>
                        @endif

                        @if ($isBasicProduct || $allowDefaultBasic)
                            <div class="mt-2">
                                @if ($basicStock > 0)
                                    <small class="text-success font-weight-bold">
                                        {{ $isBasicProduct ? 'Còn' : 'Mặc định còn' }} {{ $basicStock }} sản phẩm
                                    </small>
                                @else
                                    <small class="text-danger font-weight-bold">Hết hàng</small>
                                @endif
                            </div>
                        @endif

                        <div id="combo-stock-display" class="mt-2" style="display: none;">
                            <small class="text-success font-weight-bold">
                                Còn lại: <span id="combo-stock-value">0</span> sản phẩm
                            </small>
                        </div>
                        <div id="combo-out-of-stock" class="mt-2" style="display: none;">
                            <small class="text-danger font-weight-bold">Loại này hiện đã hết hàng</small>
                        </div>
                    </div>

                    <form action="{{ route('client.cart.add', $product->id) }}" method="POST" id="add-to-cart-form">
                        @csrf
                        <div class="mb-4">
                            @foreach ($product->variants as $variant)
                                <div class="variant-group mb-3 text-black">
                                    <h6 class="font-weight-bold mb-2">{{ $variant->name }}:</h6>
                                    <div class="d-flex flex-wrap">
                                        @if ($allowDefaultBasic)
                                            <div class="custom-control custom-radio mr-3 mb-2">
                                                <input type="radio" id="option-{{ $variant->id }}-basic"
                                                    name="option_ids[{{ $variant->id }}]"
                                                    value="__basic__" class="custom-control-input"
                                                    data-price="0" checked>
                                                <label class="custom-control-label" for="option-{{ $variant->id }}-basic">
                                                    Mặc định
                                                </label>
                                            </div>
                                        @endif
                                        @foreach ($variant->options as $index => $option)
                                            <div class="custom-control custom-radio mr-3 mb-2">
                                                <input type="radio" id="option-{{ $variant->id }}-{{ $option->id }}"
                                                    name="option_ids[{{ $variant->id }}]"
                                                    value="{{ $option->id }}" class="custom-control-input"
                                                    data-price="{{ $option->price_modifier }}"
                                                    {{ !$allowDefaultBasic && $index === 0 ? 'checked' : '' }}>
                                                <label class="custom-control-label"
                                                    for="option-{{ $variant->id }}-{{ $option->id }}">
                                                    {{ $option->option }}
                                                    @if ($option->price_modifier > 0)
                                                        (+{{ number_format($option->price_modifier, 0, ',', '.') }}đ)
                                                    @elseif($option->price_modifier < 0)
                                                        (-{{ number_format(abs($option->price_modifier), 0, ',', '.') }}đ)
                                                    @endif
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="row align-items-center mb-5">
                            <div class="col-auto">
                                <div class="input-group" style="max-width: 140px;">
                                    <div class="input-group-prepend">
                                        <button class="btn btn-outline-primary js-detail-btn-minus" type="button">&minus;</button>
                                    </div>
                                    <input type="text" class="form-control text-center" id="quantity" name="quantity"
                                        value="1" min="1" aria-label="Quantity">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-primary js-detail-btn-plus" type="button">&plus;</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                @if (Auth::user())
                                    <button type="submit"
                                        class="btn btn-primary btn-block py-2 font-weight-bold"
                                        {{ $isBasicProduct && $basicStock <= 0 ? 'disabled' : '' }}>
                                        <span class="icon icon-shopping_cart mr-2"></span> THÊM VÀO GIỎ
                                    </button>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-block py-2 font-weight-bold">
                                        <span class="icon icon-shopping_cart mr-2"></span> ĐĂNG NHẬP ĐỂ MUA HÀNG
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>

                    <hr>

                    <div class="d-flex align-items-center mt-4">
                        @auth
                            @php
                                $isFavorited = \App\Models\Favorite::where('user_id', Auth::id())
                                    ->where('product_id', $product->id)
                                    ->exists();
                            @endphp
                            <button type="button" class="btn btn-outline-danger btn-sm px-4 rounded-pill favorite-btn"
                                data-product-id="{{ $product->id }}">
                                <i class="bi {{ $isFavorited ? 'bi-heart-fill text-danger' : 'bi-heart' }} mr-2"></i>
                                <span class="favorite-text">{{ $isFavorited ? 'ĐÃ YÊU THÍCH' : 'THÊM VÀO YÊU THÍCH' }}</span>
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm px-4 rounded-pill">
                                <i class="bi bi-heart mr-2"></i> ĐĂNG NHẬP ĐỂ YÊU THÍCH
                            </a>
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Reviews Section -->
            <div class="row mt-5 pt-5 border-top">
                <div class="col-md-12">
                    <h3 class="text-black h4 mb-4 font-weight-bold">Bình luận & Đánh giá
                        ({{ $product->comments->count() }})</h3>

                    <div class="row">
                        <!-- List Reviews -->
                        <div class="col-md-7">
                            @forelse ($product->comments->sortByDesc('created_at') as $comment)
                                <div class="review-item mb-4 pb-4 border-bottom">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="text-black font-weight-bold mb-0">{{ $comment->user->name }}</h6>
                                        <div class="text-warning small">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i
                                                    class="bi {{ $i <= $comment->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="text-muted small mb-2">{{ $comment->created_at->format('d/m/Y H:i') }}</p>
                                    <p class="mb-0 text-dark">{{ $comment->content }}</p>
                                </div>
                            @empty
                                <div class="alert alert-light border text-center py-4">
                                    Chưa có bình luận nào cho sản phẩm này. Hãy là người đầu tiên!
                                </div>
                            @endforelse
                        </div>

                        <!-- Comment Form -->
                        <div class="col-md-5">
                            <div class="card border-0 shadow-sm bg-light">
                                <div class="card-body p-4">
                                    <h5 class="text-black mb-4 font-weight-bold">Viết đánh giá của bạn</h5>
                                    @auth
                                        <form action="{{ route('client.comments.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">

                                            <div class="form-group mb-3">
                                                <label class="text-black small font-weight-bold">Đánh giá của bạn</label>
                                                <div class="star-rating h4 mb-0">
                                                    <div class="d-flex flex-row-reverse justify-content-start">
                                                        <input type="radio" id="star5" name="rating" value="5"
                                                            class="d-none" required /><label for="star5"
                                                            class="px-1 pointer"><i class="bi bi-star"></i></label>
                                                        <input type="radio" id="star4" name="rating" value="4"
                                                            class="d-none" /><label for="star4" class="px-1 pointer"><i
                                                                class="bi bi-star"></i></label>
                                                        <input type="radio" id="star3" name="rating" value="3"
                                                            class="d-none" /><label for="star3" class="px-1 pointer"><i
                                                                class="bi bi-star"></i></label>
                                                        <input type="radio" id="star2" name="rating" value="2"
                                                            class="d-none" /><label for="star2" class="px-1 pointer"><i
                                                                class="bi bi-star"></i></label>
                                                        <input type="radio" id="star1" name="rating" value="1"
                                                            class="d-none" /><label for="star1" class="px-1 pointer"><i
                                                                class="bi bi-star"></i></label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group mb-4">
                                                <label for="content" class="text-black font-weight-bold small mb-2">Lời
                                                    nhắn</label>
                                                <textarea name="content" id="content" rows="4" class="form-control border-0"
                                                    placeholder="Chia sẻ cảm nhận của bạn về sản phẩm..." required></textarea>
                                            </div>

                                            <button type="submit"
                                                class="btn btn-primary btn-block py-2 font-weight-bold shadow-sm">GỬI BÌNH
                                                LUẬN</button>
                                        </form>
                                    @else
                                        <div class="text-center py-3">
                                            <p class="text-muted mb-3">Bạn cần đăng nhập để gửi bình luận.</p>
                                            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm px-4">ĐĂNG
                                                NHẬP NGAY</a>
                                        </div>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .star-rating label {
            cursor: pointer;
            color: #ccc;
            transition: color 0.2s;
        }

        .star-rating label:hover,
        .star-rating label:hover~label,
        .star-rating input:checked~label {
            color: #ffc107;
        }

        .star-rating input:checked~label i::before {
            content: "\f586";
        }

        /* star-fill */
        .pointer {
            cursor: pointer;
        }
    </style>

    @include('client.layouts.components.featured-product', ['featured_products' => $featured_products])

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var basePrice = parseFloat('{{ $product->price }}');
            var priceDisplay = document.getElementById('product-price');
            var quantityInput = document.getElementById('quantity');
            var variantOptions = document.querySelectorAll('input[name^="option_ids["]');
            var isBasicProduct = {{ $isBasicProduct ? 'true' : 'false' }};
            var allowDefaultBasic = {{ $allowDefaultBasic ? 'true' : 'false' }};
            var basicMaxStock = parseInt('{{ (int) ($product->quantity ?? 0) }}', 10);
            
            // Dữ liệu combo stock
            var comboStockData = @json($product->productVariants);
            var addToCartBtn = document.querySelector('button[type="submit"]');

            function getSelectedCombo() {
                var selected = {};
                var allSelected = true;
                
                document.querySelectorAll('.variant-group').forEach(function(group) {
                    var name = group.querySelector('h6').textContent.replace(':', '').trim().toLowerCase();
                    var checked = group.querySelector('input:checked');
                    
                    if (checked) {
                        if (checked.value === '__basic__') return;
                        
                        // Lấy text hiển thị của label
                        var label = document.querySelector(`label[for="${checked.id}"]`).textContent.split('(')[0].trim();
                        
                        if (name.includes('màu') || name.includes('color')) {
                            selected.color = label;
                        } else if (name.includes('size') || name.includes('kích')) {
                            selected.size = label;
                        }
                    } else {
                        allSelected = false;
                    }
                });
                
                return allSelected ? selected : null;
            }

            function updateStockDisplay() {
                if (isBasicProduct || isDefaultBasicSelected()) {
                    document.getElementById('combo-stock-display').style.display = 'none';
                    document.getElementById('combo-out-of-stock').style.display = 'none';
                    if (addToCartBtn) addToCartBtn.disabled = (basicMaxStock <= 0);
                    return;
                }

                var selected = getSelectedCombo();
                if (!selected || (Object.keys(selected).length === 0)) {
                    document.getElementById('combo-stock-display').style.display = 'none';
                    document.getElementById('combo-out-of-stock').style.display = 'none';
                    return;
                }

                // Tìm combo trong mảng JSON
                var combo = comboStockData.find(function(item) {
                    var match = true;
                    if (selected.color && item.color !== selected.color) match = false;
                    if (selected.size && item.size !== selected.size) match = false;
                    return match;
                });

                if (combo) {
                    if (combo.stock > 0) {
                        document.getElementById('combo-stock-display').style.display = 'block';
                        document.getElementById('combo-out-of-stock').style.display = 'none';
                        document.getElementById('combo-stock-value').textContent = combo.stock;
                        if (addToCartBtn) addToCartBtn.disabled = false;
                        
                        // Giới hạn số lượng nhập không vượt quá kho combo
                        var currentQty = parseInt(quantityInput.value);
                        if (currentQty > combo.stock) {
                            quantityInput.value = combo.stock;
                            updatePriceDisplay();
                        }
                    } else {
                        document.getElementById('combo-stock-display').style.display = 'none';
                        document.getElementById('combo-out-of-stock').style.display = 'block';
                        if (addToCartBtn) addToCartBtn.disabled = true;
                    }
                } else {
                    // Không tìm thấy combo này (có thể do dữ liệu chưa đồng bộ)
                    document.getElementById('combo-stock-display').style.display = 'none';
                    document.getElementById('combo-out-of-stock').style.display = 'none';
                }
            }

            function isDefaultBasicSelected() {
                if (!allowDefaultBasic) return false;
                return Boolean(document.querySelector('input[name^="option_ids["][value="__basic__"]:checked'));
            }

            function updatePriceDisplay() {
                var quantityNumber = parseInt(quantityInput.value, 10);
                if (isNaN(quantityNumber) || quantityNumber < 1) quantityNumber = 1;
                
                // Logic giới hạn số lượng
                var maxStock = basicMaxStock;
                var selected = getSelectedCombo();
                if (selected && !isDefaultBasicSelected()) {
                    var combo = comboStockData.find(function(item) {
                        var match = true;
                        if (selected.color && item.color !== selected.color) match = false;
                        if (selected.size && item.size !== selected.size) match = false;
                        return match;
                    });
                    if (combo) maxStock = combo.stock;
                }

                if (!isNaN(maxStock) && maxStock >= 0) {
                    quantityNumber = Math.min(quantityNumber, Math.max(1, maxStock));
                }
                quantityInput.value = String(quantityNumber);

                var priceModifier = 0;
                document.querySelectorAll('input[name^="option_ids["]:checked').forEach(function(opt) {
                    var mod = parseFloat(opt.getAttribute('data-price'));
                    if (!isNaN(mod)) priceModifier += mod;
                });

                var total = (basePrice + priceModifier) * quantityNumber;
                priceDisplay.textContent = total.toLocaleString('vi-VN');
            }

            quantityInput.addEventListener('change', function() {
                updatePriceDisplay();
                updateStockDisplay();
            });

            variantOptions.forEach(opt => opt.addEventListener('change', function() {
                updatePriceDisplay();
                updateStockDisplay();
            }));

            // Plus/Minus buttons logic
            document.querySelector('.js-detail-btn-minus').addEventListener('click', function() {
                var val = parseInt(quantityInput.value);
                if (val > 1) {
                    quantityInput.value = val - 1;
                    updatePriceDisplay();
                    updateStockDisplay();
                }
            });
            document.querySelector('.js-detail-btn-plus').addEventListener('click', function() {
                var val = parseInt(quantityInput.value);
                var next = val + 1;
                
                var maxStock = basicMaxStock;
                var selected = getSelectedCombo();
                if (selected && !isDefaultBasicSelected()) {
                    var combo = comboStockData.find(item => {
                        var match = true;
                        if (selected.color && item.color !== selected.color) match = false;
                        if (selected.size && item.size !== selected.size) match = false;
                        return match;
                    });
                    if (combo) maxStock = combo.stock;
                }

                if (!isNaN(maxStock) && maxStock >= 0) {
                    next = Math.min(next, maxStock);
                }
                quantityInput.value = next;
                updatePriceDisplay();
                updateStockDisplay();
            });

            updatePriceDisplay();
            updateStockDisplay();
        });
    </script>
@endsection
