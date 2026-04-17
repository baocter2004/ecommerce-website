@extends('client.layouts.master')

@section('content')
    <div class="bg-light py-3">
        <div class="container">
            <div class="row">
                <div class="col-md-12 mb-0">
                    <a href="{{ route('client.index') }}">Home</a>
                    <span class="mx-2 mb-0">/</span>
                    <a href="{{ route('client.cart') }}">Cart</a>
                    <span class="mx-2 mb-0">/</span>
                    <strong class="text-black">Checkout</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="site-section">
        <div class="container">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('client.checkout') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-7 mb-5 mb-md-0">
                        <h2 class="h3 mb-3 text-black font-weight-bold">Thông tin thanh toán</h2>
                        <div class="p-4 p-lg-5 border shadow-sm rounded bg-white">
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="name" class="text-black font-weight-bold">Họ và tên <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ old('name', $dataUser ? $dataUser->name : '') }}"
                                        placeholder="Nhập họ tên đầy đủ">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="shipping_address" class="text-black font-weight-bold">Địa chỉ giao hàng
                                        <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="shipping_address" name="shipping_address"
                                        placeholder="Số nhà, tên đường, phường/xã..." value="{{ old('shipping_address') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="appartment" class="text-black font-weight-bold">Căn hộ / Tòa Nhà(Tùy
                                    chọn)</label>
                                <input type="text" class="form-control" name="appartment" id="appartment"
                                    placeholder="Tòa nhà, số tầng, vv." value="{{ old('appartment') }}">
                            </div>


                            <div class="form-group">
                                <label for="order_note" class="text-black font-weight-bold">Ghi chú đơn hàng</label>
                                <textarea name="order_note" id="order_note" cols="30" rows="5" class="form-control"
                                    placeholder="Lời nhắn cho cửa hàng hoặc người giao hàng...">{{ old('order_note') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <h2 class="h3 mb-3 text-black font-weight-bold">Đơn hàng của bạn</h2>
                        <div class="p-4 p-lg-5 border shadow-sm rounded bg-white">
                            <div class="site-block-order-table mb-4">
                                <div class="d-flex justify-content-between border-bottom pb-2 mb-3">
                                    <span class="font-weight-bold text-black">Sản phẩm</span>
                                    <span class="font-weight-bold text-black">Thành tiền</span>
                                </div>
                                @foreach ($cart->items as $item)
                                    <div class="d-flex justify-content-between mb-3 border-bottom pb-3">
                                        <div class="pr-3">
                                            <span class="text-black">{{ $item->product->product_name }}</span>
                                            <strong class="mx-1">x</strong> {{ $item->quantity }}
                                            @if ($item->color || $item->size)
                                                <small class="text-primary d-block mt-1">
                                                    @if ($item->size)
                                                        <span class="mr-2">Size: {{ $item->size }}</span>
                                                    @endif
                                                    @if ($item->color)
                                                        <span>Màu: {{ $item->color }}</span>
                                                    @endif
                                                </small>
                                            @elseif ($item->variantOption)
                                                <small class="text-primary d-block mt-1">
                                                    {{ optional($item->variant)->name }}: {{ $item->variantOption->option }}
                                                </small>
                                            @endif
                                        </div>
                                        <span class="text-black font-weight-bold">
                                            {{ number_format($item->price, 0, ',', '.') }}đ
                                        </span>
                                    </div>
                                @endforeach

                                @php
                                    $orderTotal = $cart->items->sum('price');
                                @endphp

                                <div class="d-flex justify-content-between mb-3">
                                    <span class="text-black font-weight-bold">Tạm tính</span>
                                    <span class="text-black">{{ number_format($orderTotal, 0, ',', '.') }}đ</span>
                                </div>
                                <div class="d-flex justify-content-between mb-4">
                                    <strong class="text-black h5 font-weight-bold">Tổng cộng</strong>
                                    <strong
                                        class="text-primary h5 font-weight-bold">{{ number_format($orderTotal, 0, ',', '.') }}đ</strong>
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <label for="payment_method" class="text-black font-weight-bold mb-2">Phương thức thanh
                                    toán</label>
                                <select class="form-control custom-select" id="payment_method" name="payment_method">
                                    <option value="cash_payment">Thanh toán khi nhận hàng (COD)</option>
                                </select>
                            </div>

                            <div class="form-group mt-5">
                                <button type="submit" class="btn btn-primary btn-lg btn-block font-weight-bold py-3">
                                    ĐẶT HÀNG NGAY
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
