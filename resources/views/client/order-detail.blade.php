@extends('client.layouts.master')

@section('title', 'Chi tiết đơn hàng #' . $order->id)

@section('content')
    <div class="bg-light py-3">
        <div class="container">
            <div class="row">
                <div class="col-md-12 mb-0">
                    <a href="{{ route('client.index') }}">Trang chủ</a>
                    <span class="mx-2 mb-0">/</span>
                    <a href="{{ route('client.orders') }}">Đơn hàng của tôi</a>
                    <span class="mx-2 mb-0">/</span>
                    <strong class="text-black">Chi tiết đơn hàng #{{ $order->id }}</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="site-section">
        <div class="container">
            <div class="row mb-5">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="h3 text-black mb-0">Chi tiết đơn hàng #{{ $order->id }}</h2>
                        <a href="{{ route('client.orders') }}" class="btn btn-outline-primary btn-sm px-3">Quay lại danh sách</a>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Thông tin đơn hàng -->
                <div class="col-md-4">
                    <div class="p-4 border mb-3 rounded bg-white shadow-sm">
                        <h3 class="h5 text-black mb-3 border-bottom pb-2">Thông tin nhận hàng</h3>
                        <p class="mb-1"><strong>Người nhận:</strong> {{ $order->user->name ?? 'Khách vãng lai' }}</p>
                        <p class="mb-1"><strong>Địa chỉ:</strong> {{ $order->shipping_address }}</p>
                        <p class="mb-1"><strong>Căn hộ/Phòng:</strong> {{ $order->appartment ?? 'N/A' }}</p>
                        <p class="mb-1"><strong>Ghi chú:</strong> {{ $order->order_note ?? 'Không có ghi chú' }}</p>
                    </div>

                    <div class="p-4 border mb-3 rounded bg-white shadow-sm">
                        <h3 class="h5 text-black mb-3 border-bottom pb-2">Thanh toán</h3>
                        <p class="mb-1"><strong>Phương thức:</strong> {{ strtoupper($order->payment_method) }}</p>
                        <p class="mb-1"><strong>Mã giảm giá:</strong> {{ $order->discount_code ?? 'Không dùng' }}</p>
                        <p class="mb-0">
                            <strong>Trạng thái:</strong> 
                            @php
                                $statusText = [
                                    'pending' => 'Chờ xử lý',
                                    'processing' => 'Đang xử lý',
                                    'shipped' => 'Đang giao',
                                    'completed' => 'Đã hoàn thành',
                                    'cancelled' => 'Đã hủy',
                                ];
                                $badgeClass = [
                                    'pending' => 'secondary',
                                    'processing' => 'info',
                                    'shipped' => 'primary',
                                    'completed' => 'success',
                                    'cancelled' => 'danger',
                                ];
                                $text = $statusText[$order->order_status] ?? $order->order_status;
                                $class = $badgeClass[$order->order_status] ?? 'dark';
                            @endphp
                            <span class="badge badge-{{ $class }} text-uppercase">{{ $text }}</span>
                        </p>
                    </div>
                </div>

                <!-- Danh sách sản phẩm -->
                <div class="col-md-8">
                    <div class="site-blocks-table shadow-sm rounded overflow-hidden">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr class="bg-light">
                                    <th class="product-thumbnail">Hình ảnh</th>
                                    <th class="product-name">Sản phẩm</th>
                                    <th class="product-price">Giá</th>
                                    <th class="product-quantity">Số lượng</th>
                                    <th class="product-total">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->items as $item)
                                    <tr>
                                        <td class="product-thumbnail">
                                            @if($item->product)
                                                <img src="{{ Storage::url($item->product->product_image) }}" alt="Image" class="img-fluid" style="max-width: 60px;">
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td class="product-name text-left">
                                            <h2 class="h6 text-black mb-1">{{ $item->product->product_name ?? 'Sản phẩm đã bị xóa' }}</h2>
                                            @if($item->color || $item->size)
                                                <small class="text-primary d-block">
                                                    @if($item->size) <span>Size: {{ $item->size }}</span> @endif
                                                    @if($item->color) <span class="ml-2">Màu: {{ $item->color }}</span> @endif
                                                </small>
                                            @endif
                                        </td>
                                        <td>{{ number_format($item->price / $item->quantity, 0, ',', '.') }}đ</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td class="font-weight-bold">{{ number_format($item->price, 0, ',', '.') }}đ</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-right font-weight-bold text-black">Tổng cộng</td>
                                    <td class="font-weight-bold text-primary h5">{{ number_format($order->total_price, 0, ',', '.') }}đ</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
