@extends('admin.layouts.master')

@section('title')
    Danh Sách Sản Phẩm
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

    <form action="{{ route('admin.products.search') }}" method="GET" class="mt-3 mb-3 row">
        <div class="col-4">
            <input type="text" class="form-control" name="search_products" placeholder="Tìm kiếm sản phẩm hoặc danh mục">
        </div>

        <div class="col-4">
            <select name="search_type" class="form-control">
                <option value="product">Tìm theo tên sản phẩm</option>
                <option value="category">Tìm theo danh mục</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Tìm kiếm</button>
    </form>


    <div class="table-responsive">
        <table class="table table-striped table-hover table-borderless table-sm align-middle text-center">
            <thead class="thead-dark">
                <caption>Danh Sách Products</caption>
                <tr>
                    <th>ID</th>
                    <th>Tên Sản Phẩm</th>
                    <th>Danh Mục</th>
                    <th>Giá</th>
                    <th>Variant</th>
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
                            @foreach ($product->variants as $variant)
                                @if ($variant->name === 'Size')
                                    <span class="badge bg-secondary">{{ $variant->name }}:</span>
                                    @foreach ($variant->options as $option)
                                        <span class="badge bg-light text-dark mt-2">
                                            {{ $option->option }} -
                                            {{ number_format($option->price_modifier, 0, ',', '.') }} VND
                                        </span>
                                    @endforeach
                                @endif
                            @endforeach
                        </td>
                        <td>
                            <img src="{{ Storage::url($product->product_image) }}" class="img-thumbnail" alt="Chưa có Ảnh"
                                style="width: 300px; height: auto;" />
                        </td>
                        <td>
                            {{ Str::limit($product->description, 50) }}
                            @if (strlen($product->description) > 50)
                                <a href="{{ route('admin.products.show', $product->id) }}" class="text-primary">Xem
                                    Thêm</a>
                            @endif
                        </td>
                        <td>
                            {{ Str::limit($product->short_description, 20) }}
                            @if (strlen($product->short_description) > 20)
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
                            <a href="{{ route('admin.products.show', $product) }}" class="btn btn-info mt-2"
                                data-bs-toggle="tooltip" title="Xem Chi Tiết">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning mt-2"
                                data-bs-toggle="tooltip" title="Chỉnh Sửa">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <a href="{{ route('admin.products.variants.index', $product->id) }}"
                                class="btn btn-primary mt-2" data-bs-toggle="tooltip" title="Xem Biến Thể">
                                <i class="bi bi-box"></i>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="post" class="d-inline"
                                onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger mt-2 mb-2" data-bs-toggle="tooltip"
                                    title="Xóa">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="12" class="text-center">
                        <div class="d-flex justify-content-center mt-3 mb-2">
                            {{ $products->links() }}
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
        @if ($products->isEmpty())
            <p class="text-center" style="color: red">Không tìm thấy sản phẩm nào với từ khóa
                "{{ request()->input('search_products') }}".</p>
        @endif
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
