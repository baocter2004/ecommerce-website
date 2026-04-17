@extends('client.layouts.master')

@section('content')
    <div class="bg-light py-3">
        <div class="container">
            <div class="row">
                <div class="col-md-12 mb-0">
                    <a href="{{ route('client.index') }}">Trang chủ</a>
                    <span class="mx-2 mb-0">/</span>
                    <strong class="text-black">Giỏ hàng</strong>
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

            <div class="row mb-5">
                <div class="col-md-12">
                    <div class="site-blocks-table shadow-sm rounded overflow-hidden">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr class="bg-light">
                                    <th class="product-thumbnail">Hình ảnh</th>
                                    <th class="product-name">Sản phẩm</th>
                                    <th class="product-price">Giá (Đơn vị)</th>
                                    <th class="product-quantity">Số lượng</th>
                                    <th class="product-total">Thành tiền</th>
                                    <th class="product-remove">Xóa</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($cart_items as $cart_item)
                                    <tr data-cart-item-id="{{ $cart_item->id }}">
                                        <td class="product-thumbnail">
                                            <img src="{{ Storage::url($cart_item->product->product_image) }}" alt="Image"
                                                class="img-fluid" style="max-width: 80px;">
                                        </td>
                                        <td class="product-name text-left pl-4">
                                            <h2 class="h5 text-black mb-1">{{ $cart_item->product->product_name }}</h2>
                                            @if ($cart_item->variantOption)
                                                <small class="text-primary d-block">
                                                    {{ $cart_item->variant->name }}: {{ $cart_item->variantOption->option }}
                                                </small>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $unitPrice = $cart_item->price / $cart_item->quantity;
                                            @endphp
                                            <span class="js-unit-price"
                                                data-unit-price="{{ $unitPrice }}">{{ number_format($unitPrice, 0, ',', '.') }}đ</span>
                                        </td>
                                        <td>
                                            <div class="input-group mb-0 mx-auto" style="max-width: 120px;">
                                                <div class="input-group-prepend">
                                                    <button class="btn btn-outline-primary btn-sm js-btn-minus"
                                                        type="button">&minus;</button>
                                                </div>
                                                <input type="text"
                                                    class="form-control text-center form-control-sm js-qty-input"
                                                    value="{{ $cart_item->quantity }}" readonly
                                                    data-cart-item-id="{{ $cart_item->id }}">
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-primary btn-sm js-btn-plus"
                                                        type="button">&plus;</button>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="font-weight-bold text-dark js-line-total"
                                            data-line-total="{{ $cart_item->price }}">
                                            {{ number_format($cart_item->price, 0, ',', '.') }}đ
                                        </td>
                                        <td>
                                            <form action="{{ route('client.cart.remove', $cart_item->id) }}" method="POST"
                                                class="d-inline js-remove-form" data-cart-item-id="{{ $cart_item->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">
                                                    <span class="icon icon-trash"></span>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <p class="text-muted">Giỏ hàng của bạn đang trống.</p>
                                            <a href="{{ route('client.shop') }}" class="btn btn-primary btn-sm">Tiếp tục
                                                mua sắm</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @if ($cart_items->count() > 0)
                <div class="row">
                    <div class="col-md-6">
                        <div class="row mb-5">
                            <div class="col-md-6">
                                <a href="{{ route('client.shop') }}" class="btn btn-outline-primary btn-sm btn-block">TIẾP
                                    TỤC MUA SẮM</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 pl-md-5">
                        <div class="row justify-content-end">
                            <div class="col-md-7">
                                <div class="row">
                                    <div class="col-md-12 text-right border-bottom mb-4">
                                        <h3 class="text-black h4 text-uppercase font-weight-bold">Tổng giỏ hàng</h3>
                                    </div>
                                </div>
                                @php
                                    $cartSubtotal = $cart_items->sum('price');
                                @endphp
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <span class="text-black">Tạm tính</span>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <strong class="text-black" id="js-cart-subtotal"
                                            data-subtotal="{{ $cartSubtotal }}">{{ number_format($cartSubtotal, 0, ',', '.') }}đ</strong>
                                    </div>
                                </div>
                                <div class="row mb-5">
                                    <div class="col-md-6">
                                        <span class="text-black h5 font-weight-bold">Tổng cộng</span>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <strong class="text-primary h5 font-weight-bold" id="js-cart-total"
                                            data-total="{{ $cartSubtotal }}">{{ number_format($cartSubtotal, 0, ',', '.') }}đ</strong>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <a href="{{ route('client.checkout') }}"
                                            class="btn btn-primary btn-lg py-3 btn-block font-weight-bold">
                                            TIẾN HÀNH THANH TOÁN
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if ($cart_items->count() > 0)
        @push('scripts')
            <script>
                (function() {
                    const STORAGE_KEY = 'cart_quantities_v1';
                    const csrfToken = @json(csrf_token());
                    const updateUrlTemplate = @json(route('client.cart.update', ['cartItemId' => '__CART_ITEM_ID__']));

                    const formatVnd = (amount) => {
                        const n = Number(amount || 0);
                        return new Intl.NumberFormat('vi-VN').format(Math.round(n)) + 'đ';
                    };

                    const readStore = () => {
                        try {
                            const raw = localStorage.getItem(STORAGE_KEY);
                            return raw ? JSON.parse(raw) : {};
                        } catch (e) {
                            return {};
                        }
                    };

                    const writeStore = (data) => {
                        try {
                            localStorage.setItem(STORAGE_KEY, JSON.stringify(data || {}));
                        } catch (e) {}
                    };

                    const setStoredQty = (cartItemId, qty) => {
                        const store = readStore();
                        store[String(cartItemId)] = qty;
                        writeStore(store);
                    };

                    const removeStoredQty = (cartItemId) => {
                        const store = readStore();
                        delete store[String(cartItemId)];
                        writeStore(store);
                    };

                    const setRowQty = (row, qty) => {
                        const input = row.querySelector('.js-qty-input');
                        if (input) input.value = String(qty);
                    };

                    const setRowLineTotal = (row, lineTotal) => {
                        const cell = row.querySelector('.js-line-total');
                        if (cell) {
                            cell.dataset.lineTotal = String(lineTotal);
                            cell.textContent = formatVnd(lineTotal);
                        }
                    };

                    const setSummary = (subtotal) => {
                        const subtotalEl = document.getElementById('js-cart-subtotal');
                        const totalEl = document.getElementById('js-cart-total');
                        if (subtotalEl) {
                            subtotalEl.dataset.subtotal = String(subtotal);
                            subtotalEl.textContent = formatVnd(subtotal);
                        }
                        if (totalEl) {
                            totalEl.dataset.total = String(subtotal);
                            totalEl.textContent = formatVnd(subtotal);
                        }
                    };

                    const patchQuantity = async (cartItemId, quantity) => {
                        const url = updateUrlTemplate.replace('__CART_ITEM_ID__', encodeURIComponent(cartItemId));
                        const res = await fetch(url, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({
                                quantity
                            })
                        });

                        const json = await res.json().catch(() => ({}));
                        if (!res.ok) {
                            const msg = (json && (json.message || (json.errors && json.errors.quantity && json.errors
                                .quantity[0]))) || 'Không thể cập nhật số lượng.';
                            throw new Error(msg);
                        }
                        return json;
                    };

                    const onAdjust = async (row, delta) => {
                        const cartItemId = row.getAttribute('data-cart-item-id');
                        const input = row.querySelector('.js-qty-input');
                        if (!cartItemId || !input) return;

                        const current = Number(input.value || 1);
                        const next = Math.max(1, current + delta);
                        if (next === current) return;

                        // Optimistic UI
                        setRowQty(row, next);
                        setStoredQty(cartItemId, next);

                        try {
                            const data = await patchQuantity(cartItemId, next);
                            setRowQty(row, data.quantity);
                            setRowLineTotal(row, data.line_price);
                            setSummary(data.subtotal);
                            setStoredQty(cartItemId, data.quantity);
                        } catch (e) {
                            // rollback
                            setRowQty(row, current);
                            setStoredQty(cartItemId, current);
                            alert(e.message || 'Không thể cập nhật số lượng.');
                        }
                    };

                    // Bind +/- buttons
                    document.querySelectorAll('tr[data-cart-item-id]').forEach((row) => {
                        const plus = row.querySelector('.js-btn-plus');
                        const minus = row.querySelector('.js-btn-minus');
                        if (plus) plus.addEventListener('click', () => onAdjust(row, +1));
                        if (minus) minus.addEventListener('click', () => onAdjust(row, -1));
                    });

                    // Remove localStorage entry on delete
                    document.querySelectorAll('form.js-remove-form').forEach((form) => {
                        form.addEventListener('submit', () => {
                            const cartItemId = form.getAttribute('data-cart-item-id');
                            if (cartItemId) removeStoredQty(cartItemId);
                        });
                    });

                    // Restore quantities from localStorage (and sync to server)
                    const stored = readStore();
                    const rows = Array.from(document.querySelectorAll('tr[data-cart-item-id]'));

                    (async () => {
                        for (const row of rows) {
                            const cartItemId = row.getAttribute('data-cart-item-id');
                            const input = row.querySelector('.js-qty-input');
                            if (!cartItemId || !input) continue;

                            const saved = Number(stored[String(cartItemId)]);
                            if (!saved || saved < 1) continue;

                            const current = Number(input.value || 1);
                            if (saved === current) continue;

                            // Set UI first for immediate feel
                            setRowQty(row, saved);

                            try {
                                const data = await patchQuantity(cartItemId, saved);
                                setRowQty(row, data.quantity);
                                setRowLineTotal(row, data.line_price);
                                setSummary(data.subtotal);
                                setStoredQty(cartItemId, data.quantity);
                            } catch (e) {
                                // If sync fails, keep server value and overwrite localStorage
                                setRowQty(row, current);
                                setStoredQty(cartItemId, current);
                            }
                        }
                    })();
                })();
            </script>
        @endpush
    @endif
@endsection
