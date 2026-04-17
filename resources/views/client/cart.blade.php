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
            @if(session('success'))
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
                                    <tr>
                                        <td class="product-thumbnail">
                                            <img src="{{ Storage::url($cart_item->product->product_image) }}" alt="Image"
                                                class="img-fluid" style="max-width: 80px;">
                                        </td>
                                        <td class="product-name text-left pl-4">
                                            <h2 class="h5 text-black mb-1">{{ $cart_item->product->product_name }}</h2>
                                            @if($cart_item->variantOption)
                                                <small class="text-primary d-block">
                                                    {{ $cart_item->variant->name }}: {{ $cart_item->variantOption->option }}
                                                </small>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $unitPrice = $cart_item->price / $cart_item->quantity;
                                            @endphp
                                            {{ number_format($unitPrice, 0, ',', '.') }}đ
                                        </td>
                                        <td>
                                            <div class="input-group mb-0 mx-auto" style="max-width: 120px;">
                                                <div class="input-group-prepend">
                                                    <button class="btn btn-outline-primary btn-sm js-btn-minus" type="button">&minus;</button>
                                                </div>
                                                <input type="text" class="form-control text-center form-control-sm"
                                                    value="{{ $cart_item->quantity }}" readonly>
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-primary btn-sm js-btn-plus" type="button">&plus;</button>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="font-weight-bold text-dark">
                                            {{ number_format($cart_item->price, 0, ',', '.') }}đ
                                        </td>
                                        <td>
                                            <form action="{{ route('client.cart.remove', $cart_item->id) }}" method="POST" class="d-inline">
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
                                            <a href="{{ route('client.shop') }}" class="btn btn-primary btn-sm">Tiếp tục mua sắm</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @if($cart_items->count() > 0)
                <div class="row">
                    <div class="col-md-6">
                        <div class="row mb-5">
                            <div class="col-md-6">
                                <a href="{{ route('client.shop') }}" class="btn btn-outline-primary btn-sm btn-block">TIẾP TỤC MUA SẮM</a>
                            </div>
                        </div>
                        <div class="card border-0 shadow-sm bg-light">
                            <div class="card-body">
                                <label class="text-black h4 mb-3" for="coupon">Mã giảm giá</label>
                                <p>Nhập mã giảm giá nếu bạn có.</p>
                                <div class="row">
                                    <div class="col-md-8 mb-3 mb-md-0">
                                        <input type="text" class="form-control py-3" id="coupon" placeholder="Mã giảm giá">
                                    </div>
                                    <div class="col-md-4">
                                        <button class="btn btn-primary btn-sm btn-block py-3">ÁP DỤNG</button>
                                    </div>
                                </div>
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
                                        <strong class="text-black">{{ number_format($cartSubtotal, 0, ',', '.') }}đ</strong>
                                    </div>
                                </div>
                                <div class="row mb-5">
                                    <div class="col-md-6">
                                        <span class="text-black h5 font-weight-bold">Tổng cộng</span>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <strong class="text-primary h5 font-weight-bold">{{ number_format($cartSubtotal, 0, ',', '.') }}đ</strong>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <a href="{{ route('client.checkout') }}" class="btn btn-primary btn-lg py-3 btn-block font-weight-bold">
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
@endsection
