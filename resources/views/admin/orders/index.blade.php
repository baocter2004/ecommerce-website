@extends('admin.layouts.master')

@section('title')
    Quản lý Đơn hàng
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 font-weight-bold">Danh sách đơn hàng</h5>
                <form action="{{ route('admin.orders.index') }}" method="GET" class="d-flex">
                    <select name="status" class="form-control form-control-sm mr-2" style="width: 150px;">
                        <option value="">Tất cả trạng thái</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                        <option value="shipping" {{ request('status') == 'shipping' ? 'selected' : '' }}>Đang giao hàng</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Đã hoàn thành</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary">Lọc</button>
                </form>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4">ID</th>
                                <th>Khách hàng</th>
                                <th>Tổng tiền</th>
                                <th>Phương thức</th>
                                <th>Trạng thái</th>
                                <th>Ngày đặt</th>
                                <th class="text-right px-4">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $order)
                                <tr>
                                    <td class="px-4 fw-bold">#{{ $order->id }}</td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="font-weight-bold">{{ $order->user ? $order->user->name : 'Khách vãng lai' }}</span>
                                            <small class="text-muted">{{ $order->user ? $order->user->email : '' }}</small>
                                        </div>
                                    </td>
                                    <td class="text-primary font-weight-bold">
                                        {{ number_format($order->total_price, 0, ',', '.') }} VND
                                    </td>
                                    <td>
                                        <span class="badge badge-light border">{{ strtoupper($order->payment_method) }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = [
                                                'pending' => 'badge-warning text-dark',
                                                'processing' => 'badge-info',
                                                'shipping' => 'badge-primary',
                                                'completed' => 'badge-success',
                                                'cancelled' => 'badge-danger',
                                            ][$order->order_status] ?? 'badge-secondary';
                                            
                                            $statusText = [
                                                'pending' => 'Chờ xử lý',
                                                'processing' => 'Đang xử lý',
                                                'shipping' => 'Đang giao hàng',
                                                'completed' => 'Hoàn thành',
                                                'cancelled' => 'Đã hủy',
                                            ][$order->order_status] ?? $order->order_status;
                                        @endphp
                                        <span class="badge {{ $statusClass }} px-3 py-2 text-uppercase" style="font-size: 0.7rem;">
                                            {{ $statusText }}
                                        </span>
                                    </td>
                                    <td class="small">{{ $order->created_at->format('H:i d/m/Y') }}</td>
                                    <td class="text-right px-4">
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                            Chi tiết
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox mb-2 d-block" style="font-size: 2rem;"></i>
                                        Chưa có đơn hàng nào
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($orders->hasPages())
                <div class="card-footer bg-white border-0 py-3">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
