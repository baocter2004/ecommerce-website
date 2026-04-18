@extends('client.layouts.master')

@section('title', 'Đơn hàng của tôi')

@section('content')
    <div class="bg-light py-3">
        <div class="container">
            <div class="row">
                <div class="col-md-12 mb-0">
                    <a href="{{ route('client.index') }}">Trang chủ</a>
                    <span class="mx-2 mb-0">/</span>
                    <strong class="text-black">Đơn hàng của tôi</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="site-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="h3 mb-4 text-black">Danh sách đơn hàng</h2>
                </div>
            </div>

            <div class="row mb-5">
                <div class="col-md-12">
                    <div class="site-blocks-table shadow-sm rounded overflow-hidden">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr class="bg-light">
                                    <th>Mã đơn hàng</th>
                                    <th>Ngày đặt</th>
                                    <th>Sản phẩm</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($orders as $order)
                                    <tr>
                                        <td class="font-weight-bold">#{{ $order->id }}</td>
                                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="text-left">
                                            <ul class="list-unstyled mb-0">
                                                @foreach ($order->items as $item)
                                                    <li>
                                                        <small>
                                                            - {{ $item->product->product_name ?? 'Sản phẩm không tồn tại' }} 
                                                            x{{ $item->quantity }}
                                                            @if($item->color || $item->size)
                                                                ({{ $item->color }} {{ $item->size ? '- ' . $item->size : '' }})
                                                            @endif
                                                        </small>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td class="font-weight-bold text-primary">
                                            {{ number_format($order->total_price, 0, ',', '.') }}đ
                                        </td>
                                        <td>
                                            @php
                                                $statusBadge = [
                                                    'pending' => 'secondary',
                                                    'processing' => 'info',
                                                    'shipped' => 'primary',
                                                    'completed' => 'success',
                                                    'cancelled' => 'danger',
                                                ];
                                                $statusText = [
                                                    'pending' => 'Chờ xử lý',
                                                    'processing' => 'Đang xử lý',
                                                    'shipped' => 'Đang giao',
                                                    'completed' => 'Đã hoàn thành',
                                                    'cancelled' => 'Đã hủy',
                                                ];
                                                $badgeClass = $statusBadge[$order->order_status] ?? 'dark';
                                                $text = $statusText[$order->order_status] ?? $order->order_status;
                                            @endphp
                                            <span class="badge badge-{{ $badgeClass }} px-3 py-2 text-uppercase" style="font-size: 0.75rem;">
                                                {{ $text }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('client.orders.show', $order->id) }}" class="btn btn-primary btn-sm px-3 rounded">Chi tiết</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <p class="text-muted">Bạn chưa có đơn hàng nào.</p>
                                            <a href="{{ route('client.shop') }}" class="btn btn-primary btn-sm px-4 py-2">Mua sắm ngay</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="site-block-27">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
