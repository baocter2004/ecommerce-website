@extends('admin.layouts.master')

@section('title')
    Chi tiết đơn hàng #{{ $order->id }}
@endsection

@section('content')
    <div class="container-fluid">
        @if (session('success'))
            <div class="alert alert-success shadow-sm border-0 mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="row">
            <!-- Left Column: Order Items & Details -->
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 font-weight-bold">Sản phẩm trong đơn hàng</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="px-4">ID</th>
                                        <th>Sản phẩm</th>
                                        <th>Giá</th>
                                        <th>Số lượng</th>
                                        <th class="text-right px-4">Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->items as $item)
                                        <tr>
                                            <td class="px-4 text-muted">#{{ $item->product_id }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if ($item->product->product_image)
                                                        <img src="{{ Storage::url($item->product->product_image) }}" 
                                                             class="rounded border mr-3" style="width: 50px; height: 50px; object-fit: cover;">
                                                    @endif
                                                    <div class="d-flex flex-column">
                                                        <span class="font-weight-bold text-dark">{{ $item->product->product_name }}</span>
                                                        @if ($item->variant)
                                                            <small class="text-primary">{{ $item->variant->name }}: {{ $item->variantOption->option }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ number_format($item->price, 0, ',', '.') }} VND</td>
                                            <td>x{{ $item->quantity }}</td>
                                            <td class="text-right px-4 font-weight-bold">
                                                {{ number_format($item->price * $item->quantity, 0, ',', '.') }} VND
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-light">
                                    <tr>
                                        <td colspan="4" class="text-right font-weight-bold px-4 pt-3">TỔNG CỘNG:</td>
                                        <td class="text-right px-4 font-weight-bold text-primary h5 pt-3 mb-0">
                                            {{ number_format($order->total_price, 0, ',', '.') }} VND
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 font-weight-bold">Thông tin ghi chú</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0 text-muted italic">
                            {{ $order->order_note ?: 'Không có ghi chú nào cho đơn hàng này.' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Right Column: Status & Customer Info -->
            <div class="col-lg-4">
                <!-- Order Status Update -->
                <div class="card shadow-sm border-0 mb-4 bg-primary text-white">
                    <div class="card-body py-4">
                        <h6 class="text-white-50 text-uppercase mb-2" style="font-size: 0.75rem; letter-spacing: 1px;">TRẠNG THÁI HIỆN TẠI</h6>
                        @php
                            $statusText = [
                                'pending' => 'Chờ xử lý',
                                'processing' => 'Đang xử lý',
                                'shipping' => 'Đang giao hàng',
                                'completed' => 'Đã hoàn thành',
                                'cancelled' => 'Đã hủy',
                            ][$order->order_status] ?? $order->order_status;
                        @endphp
                        <h3 class="font-weight-bold mb-4">{{ $statusText }}</h3>
                        
                        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group mb-3 text-dark">
                                <label class="text-white-50 mb-1" style="font-size: 0.75rem;">CẬP NHẬT TRẠNG THÁI</label>
                                <select name="order_status" class="form-control border-0 shadow-none">
                                    <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                    <option value="processing" {{ $order->order_status == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                                    <option value="shipping" {{ $order->order_status == 'shipping' ? 'selected' : '' }}>Đang giao hàng</option>
                                    <option value="completed" {{ $order->order_status == 'completed' ? 'selected' : '' }}>Đã hoàn thành</option>
                                    <option value="cancelled" {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>Hủy đơn hàng</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-light btn-block font-weight-bold text-primary py-2 shadow-sm">
                                CẬP NHẬT NGAY
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Customer Information -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 font-weight-bold">Thông tin giao hàng</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="text-muted small text-uppercase mb-1 d-block" style="letter-spacing: 0.5px;">TÊN KHÁCH HÀNG</label>
                            <p class="font-weight-bold mb-0">{{ $order->user ? $order->user->name : 'Khách vãng lai' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small text-uppercase mb-1 d-block" style="letter-spacing: 0.5px;">SỐ ĐIỆN THOẠI</label>
                            <p class="font-weight-bold mb-0 text-primary">{{ $order->user ? $order->user->phone : 'N/A' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small text-uppercase mb-1 d-block" style="letter-spacing: 0.5px;">ĐỊA CHỈ GIAO HÀNG</label>
                            <p class="mb-0">{{ $order->shipping_address }}</p>
                            @if ($order->appartment)
                                <small class="text-muted">{{ $order->appartment }}</small>
                            @endif
                        </div>
                        <div class="mb-0">
                            <label class="text-muted small text-uppercase mb-1 d-block" style="letter-spacing: 0.5px;">PHƯƠNG THỨC THANH TOÁN</label>
                            <span class="badge badge-light border px-3 py-2 mt-1">{{ strtoupper($order->payment_method) }}</span>
                        </div>
                    </div>
                </div>

                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-block py-2">
                    <i class="bi bi-arrow-left mr-1"></i> Quay lại danh sách
                </a>
            </div>
        </div>
    </div>
@endsection
