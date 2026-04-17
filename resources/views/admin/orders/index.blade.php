@extends('admin.layouts.master')

@section('title')
    Quản lý Đơn hàng
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="m-0 font-weight-bold text-primary"><i class="fa fa-shopping-cart mr-2"></i> Danh Sách Đơn Hàng</h5>
                    
                    <form action="{{ route('admin.orders.index') }}" method="GET" class="form-inline">
                        <select name="status" class="form-control form-control-sm mr-2 shadow-sm" style="width: 160px; height: 32px;">
                            <option value="">Tất cả trạng thái</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                            <option value="shipping" {{ request('status') == 'shipping' ? 'selected' : '' }}>Đang giao hàng</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Đã hoàn thành</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                        </select>
                        <button type="submit" class="btn btn-dark btn-sm shadow-sm px-3 rounded-pill" style="height: 32px;">
                            <i class="fa fa-filter mr-1"></i> Lọc
                        </button>
                    </form>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-dark">
                                <tr>
                                    <th class="border-0 pl-4">ID</th>
                                    <th class="border-0">Khách hàng</th>
                                    <th class="border-0">Tổng tiền</th>
                                    <th class="border-0">Phương thức</th>
                                    <th class="border-0">Trạng thái</th>
                                    <th class="border-0">Ngày đặt</th>
                                    <th class="border-0 text-center pr-4">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($orders as $order)
                                    <tr>
                                        <td class="pl-4 font-weight-bold text-dark">#{{ $order->id }}</td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="font-weight-bold text-dark">{{ $order->user ? $order->user->name : 'Khách vãng lai' }}</span>
                                                <small class="text-muted">{{ $order->user ? $order->user->email : ($order->email ?? 'N/A') }}</small>
                                            </div>
                                        </td>
                                        <td class="text-primary font-weight-bold">
                                            {{ number_format($order->total_price, 0, ',', '.') }} đ
                                        </td>
                                        <td>
                                            <span class="badge badge-light border px-2 py-1 text-uppercase">{{ $order->payment_method }}</span>
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
                                            <span class="badge {{ $statusClass }} px-3 py-2 rounded-pill shadow-xs" style="font-size: 0.75rem;">
                                                {{ $statusText }}
                                            </span>
                                        </td>
                                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="text-center pr-4">
                                            <a href="{{ route('admin.orders.show', $order->id) }}" 
                                               class="btn btn-outline-primary btn-sm rounded-pill px-3 shadow-sm"
                                               data-toggle="tooltip" title="Xem chi tiết">
                                                <i class="fa fa-eye mr-1"></i> Chi tiết
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="fa fa-inbox fa-3x mb-3"></i>
                                                <p>Chưa có đơn hàng nào.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($orders->hasPages())
                    <div class="card-footer bg-white border-0 py-3">
                        <div class="d-flex justify-content-center">
                            {{ $orders->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
