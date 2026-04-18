@extends('admin.layouts.master')

@section('title')
    Danh Sách Category
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4">
                    <i class="fa fa-check-circle mr-2"></i> Thao tác thành công!
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="m-0 font-weight-bold text-primary"><i class="fa fa-list mr-2"></i> Danh Sách Danh Mục</h5>
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm">
                        <i class="fa fa-plus-circle mr-1"></i> Thêm mới
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light text-dark">
                                <tr>
                                    <th class="border-0">ID</th>
                                    <th class="border-0">Ảnh</th>
                                    <th class="border-0">Tên danh mục</th>
                                    <th class="border-0">Trạng thái</th>
                                    <th class="border-0">Ngày tạo</th>
                                    <th class="border-0">Ngày cập nhật</th>
                                    <th class="border-0 text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($categories as $category)
                                    <tr>
                                        <td class="font-weight-bold">#{{ $category->id }}</td>
                                        <td>
                                            @if($category->category_image)
                                                <img src="{{ Storage::url($category->category_image) }}" 
                                                     alt="{{ $category->name }}" 
                                                     class="rounded border shadow-sm"
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <span class="text-muted small">Không có ảnh</span>
                                            @endif
                                        </td>
                                        <td>{{ $category->name }}</td>
                                        <td>
                                            @if ($category->is_active === 1)
                                                <span class="badge badge-success px-3 py-2 rounded-pill">Hoạt động</span>
                                            @else
                                                <span class="badge badge-danger px-3 py-2 rounded-pill">Khóa</span>
                                            @endif
                                        </td>
                                        <td>{{ $category->created_at->format('d/m/Y') }}</td>
                                        <td>{{ $category->updated_at->format('d/m/Y') }}</td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.categories.edit', $category->id) }}" 
                                                   class="btn btn-outline-warning btn-sm mx-1 rounded shadow-sm"
                                                   data-toggle="tooltip" title="Chỉnh sửa">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="post"
                                                      class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
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
                                        <td colspan="6" class="text-center py-4 text-muted">Không có dữ liệu nào.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($categories->hasPages())
                    <div class="card-footer bg-white border-0 py-3">
                        <div class="d-flex justify-content-center">
                            {{ $categories->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
