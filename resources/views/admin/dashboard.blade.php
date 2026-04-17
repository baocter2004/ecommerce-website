@extends('admin.layouts.master')

@section('title')
    Trang Quản Trị
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Stat Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm border-0 bg-primary text-white">
                    <div class="card-body py-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 text-uppercase small mb-2">Tổng Sản Phẩm</h6>
                                <h2 class="font-weight-bold mb-0">{{ $totalProducts }}</h2>
                            </div>
                            <i class="fa fa-shopping-bag fa-3x text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 bg-success text-white">
                    <div class="card-body py-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 text-uppercase small mb-2">Đơn Hàng</h6>
                                <h2 class="font-weight-bold mb-0">{{ $totalOrders }}</h2>
                            </div>
                            <i class="fa fa-shopping-cart fa-3x text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 bg-info text-white">
                    <div class="card-body py-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 text-uppercase small mb-2">Thành Viên</h6>
                                <h2 class="font-weight-bold mb-0">{{ $totalUsers }}</h2>
                            </div>
                            <i class="fa fa-users fa-3x text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Left: Charts -->
            <div class="col-lg-7">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 font-weight-bold">Thống kê Sản phẩm theo Danh mục</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="categoryChart" height="300"></canvas>
                    </div>
                </div>
            </div>

            <!-- Right: Recent Orders -->
            <div class="col-lg-5">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 font-weight-bold">Đơn hàng mới nhất</h5>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-link">Xem tất cả</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <tbody>
                                    @foreach ($recentOrders as $order)
                                        <tr>
                                            <td class="px-3">
                                                <div class="font-weight-bold">#{{ $order->id }}</div>
                                                <small class="text-muted">{{ $order->created_at->diffForHumans() }}</small>
                                            </td>
                                            <td>
                                                <div class="small">{{ $order->user ? $order->user->name : 'Khách vãng lai' }}</div>
                                            </td>
                                            <td class="text-primary font-weight-bold small px-3">
                                                {{ number_format($order->total_price, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.7.3/dist/Chart.bundle.min.js"></script>
    <script>
        const categories = @json($categories);
        const labels = categories.map(category => category.name);
        const data = categories.map(category => category.products_count);

        var ctx = document.getElementById('categoryChart').getContext('2d');
        var categoryChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Số lượng sản phẩm',
                    data: data,
                    backgroundColor: 'rgba(78, 115, 223, 0.2)',
                    borderColor: 'rgba(78, 115, 223, 1)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    </script>
@endsection
