@extends('admin.layouts.master')

@section('title')
    Danh Sách Products
@endsection

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session()->has('success'))
        <div class="alert alert-success">
            <h1>Thao Tác Thành Công</h1>
        </div>
    @endif

    <!-- Tạo mới sản phẩm -->
    <a href="{{ route('admin.products.create') }}" class="mt-3 mb-3 btn btn-success">
        <i class="bi bi-plus-circle"></i> Tạo Mới
    </a>

    <div class="table-responsive">
        <table class="table table-striped table-hover table-borderless align-middle">
            <thead class="table-light">
                <caption>Danh Sách Products</caption>
                <tr>
                    <th>ID</th>
                    <th>Tên Sản Phẩm</th>
                    <th>Danh Mục</th>
                    <th>Giá</th>
                    <th>Ảnh Sản Phẩm</th>
                    <th>Mô Tả</th>
                    <th>Mô Tả Ngắn</th>
                    <th>Trạng Thái</th>
                    <th>Ngày Tạo</th>
                    <th>Ngày Cập Nhật</th>
                    <th>Thao Tác</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                @foreach ($products as $product)
                    <tr class="table-light">
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->product_name }}</td>
                        <td>{{ $product->category->name }}</td>
                        <td>{{ number_format($product->price, 0, ',', '.') }} VND</td>
                        <td>
                            <img src="{{ Storage::url($product->product_image) }}" class="img-fluid rounded"
                                alt="Chưa có Ảnh" style="max-height: 80px; object-fit: cover;" />
                        </td>
                        <td>
                            {{ Str::limit($product->description, 50) }}
                            @if (strlen($product->description) > 50)
                                <a href="{{ route('admin.products.show', $product->id) }}" class="text-primary">Xem Thêm</a>
                            @endif
                        </td>
                        <td>
                            {{ Str::limit($product->short_description, 50) }}
                            @if (strlen($product->short_description) > 50)
                                <a href="{{ route('admin.products.show', $product->id) }}" class="text-primary">Xem
                                    Thêm</a>
                            @endif
                        </td>
                        <td>
                            @if ($product->is_active === 1)
                                <span class="badge bg-success">Yes</span>
                            @else
                                <span class="badge bg-danger">No</span>
                            @endif
                        </td>
                        <td>{{ $product->created_at->format('Y/m/d') }}</td>
                        <td>{{ $product->updated_at->format('Y/m/d') }}</td>
                        <td>
                            <!-- Xem -->
                            <a href="{{ route('admin.products.show', $product) }}" class="btn btn-info"
                                data-bs-toggle="tooltip" title="Xem Chi Tiết">
                                <i class="bi bi-eye"></i>
                            </a>
                            <!-- Chỉnh sửa -->
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning"
                                data-bs-toggle="tooltip" title="Chỉnh Sửa">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <!-- Xóa -->
                            <form action="{{ route('admin.products.destroy', $product) }}" method="post" class="d-inline"
                                onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" data-bs-toggle="tooltip" title="Xóa">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="11" class="text-center">
                        {{ $products->links() }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
@endsection

@push('scripts')
    <script>
        // Kích hoạt Bootstrap tooltips
        var tooltipTriggerList = Array.from(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>
@endpush
