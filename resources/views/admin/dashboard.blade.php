@extends('admin.layouts.master')

@section('title')
    Dashboard
@endsection

@section('content')
    <div class="container">
        <h2 class="mt-4">Thống kê Sản phẩm theo Danh mục</h2>
        <!-- Biểu đồ -->
        <div class="card mt-4">
            <div class="card-header">Số lượng sản phẩm theo danh mục</div>
            <div class="card-body">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.7.3/dist/Chart.bundle.min.js"></script>
    <script>
        const categories = @json($categories);
        console.log(categories);
        const labels = categories.map(category => category.name);
        const data = categories.map(category => category.products_count);

        // Tạo biểu đồ
        var ctx = document.getElementById('categoryChart').getContext('2d');
        var categoryChart = new Chart(ctx, {
            type: 'bar', // Loại biểu đồ là cột
            data: {
                labels: labels,
                datasets: [{
                    label: 'Số lượng sản phẩm', // Nhãn cho biểu đồ
                    data: data, // Dữ liệu sản phẩm theo danh mục
                    backgroundColor: 'rgba(54, 162, 235, 0.2)', // Màu nền của các cột
                    borderColor: 'rgba(54, 162, 235, 1)', // Màu viền của các cột
                    borderWidth: 1 // Độ dày viền của các cột
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true // Đảm bảo trục y bắt đầu từ 0
                        }
                    }]
                }
            }
        });
    </script>
@endsection
