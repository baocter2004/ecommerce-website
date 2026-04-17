@extends('admin.layouts.master')

@section('title')
    Danh Sách Sản Phẩm
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4">
                    <i class="fa fa-check-circle mr-2"></i> Thao tác thành công!
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif

            <!-- Search Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body py-3">
                    <form action="{{ route('admin.products.search') }}" method="GET" class="row align-items-end">
                        <div class="col-md-4 mb-2 mb-md-0">
                            <label class="small font-weight-bold text-muted">Từ khóa tìm kiếm</label>
                            <input type="text" class="form-control form-control-sm shadow-sm" name="search_products" 
                                placeholder="Tên sản phẩm hoặc danh mục..." value="{{ request('search_products') }}">
                        </div>
                        <div class="col-md-3 mb-2 mb-md-0">
                            <label class="small font-weight-bold text-muted">Loại tìm kiếm</label>
                            <select name="search_type" class="form-control form-control-sm shadow-sm">
                                <option value="product" {{ request('search_type') == 'product' ? 'selected' : '' }}>Tìm theo tên sản phẩm</option>
                                <option value="category" {{ request('search_type') == 'category' ? 'selected' : '' }}>Tìm theo danh mục</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-dark btn-sm btn-block shadow-sm">
                                <i class="fa fa-search mr-1"></i> Tìm kiếm
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Product Table Card -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="m-0 font-weight-bold text-primary"><i class="fa fa-shopping-bag mr-2"></i> Danh Sách Sản Phẩm</h5>
                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm">
                        <i class="fa fa-plus-circle mr-1"></i> Thêm mới
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-dark">
                                <tr>
                                    <th class="border-0 pl-4">ID</th>
                                    <th class="border-0">Sản phẩm</th>
                                    <th class="border-0">Danh mục</th>
                                    <th class="border-0">Giá bán</th>
                                    <th class="border-0">Biến thể</th>
                                    <th class="border-0">Trạng thái</th>
                                    <th class="border-0 text-center pr-4">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($products as $product)
                                    <tr>
                                        <td class="pl-4 font-weight-bold">#{{ $product->id }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ Storage::url($product->product_image) }}" 
                                                    class="rounded mr-3 border shadow-sm" alt="Ảnh"
                                                    style="width: 50px; height: 50px; object-fit: cover;" />
                                                <div style="max-width: 200px;">
                                                    <div class="font-weight-bold text-dark text-truncate">{{ $product->product_name }}</div>
                                                    <small class="text-muted text-truncate d-block">{{ $product->short_description }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge badge-light border px-2 py-1">{{ $product->category->name }}</span></td>
                                        <td class="text-primary font-weight-bold">{{ number_format($product->price, 0, ',', '.') }} đ</td>
                                        <td>
                                            @foreach ($product->variants as $variant)
                                                <div class="mb-1">
                                                    <small class="text-muted d-block">{{ $variant->name }}:</small>
                                                    <div class="d-flex flex-wrap">
                                                        @foreach ($variant->options as $option)
                                                            <span class="badge badge-info mr-1 mb-1 p-1 small shadow-xs" 
                                                                  data-toggle="tooltip" title="+{{ number_format($option->price_modifier, 0, ',', '.') }} đ">
                                                                {{ $option->option }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </td>
                                        <td>
                                            @if ($product->is_active === 1)
                                                <span class="badge badge-success px-3 py-2 rounded-pill">Đang bán</span>
                                            @else
                                                <span class="badge badge-secondary px-3 py-2 rounded-pill">Ngừng bán</span>
                                            @endif
                                        </td>
                                        <td class="text-center pr-4">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.products.show', $product) }}" 
                                                   class="btn btn-outline-info btn-sm mx-1 rounded shadow-sm"
                                                   data-toggle="tooltip" title="Xem chi tiết">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.products.edit', $product) }}" 
                                                   class="btn btn-outline-warning btn-sm mx-1 rounded shadow-sm"
                                                   data-toggle="tooltip" title="Chỉnh sửa">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                                <a href="{{ route('admin.products.variants.index', $product->id) }}"
                                                   class="btn btn-outline-primary btn-sm mx-1 rounded shadow-sm"
                                                   data-toggle="tooltip" title="Biến thể">
                                                    <i class="fa fa-tags"></i>
                                                </a>
                                                <form action="{{ route('admin.products.destroy', $product) }}" method="post" class="d-inline"
                                                      onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm mx-1 rounded shadow-sm"
                                                            data-toggle="tooltip" title="Xóa">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="fa fa-search fa-3x mb-3"></i>
                                                <p>Không tìm thấy sản phẩm nào.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($products->hasPages())
                    <div class="card-footer bg-white border-0 py-3">
                        <div class="d-flex justify-content-center">
                            {{ $products->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
