@extends('admin.layouts.master')

@section('title')
    Danh Sách Products
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

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom-0">
                    <h5 class="m-0 font-weight-bold text-danger"><i class="fa fa-trash mr-2"></i> Thùng rác: Sản phẩm</h5>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-sm rounded-pill px-3 shadow-sm">
                        <i class="fa fa-arrow-left mr-1"></i> Quay lại
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
                                    <th class="border-0 text-center pr-4">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($trashList as $product)
                                    <tr>
                                        <td class="pl-4 font-weight-bold">#{{ $product->id }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ Storage::url($product->product_image) }}" 
                                                    class="rounded mr-3 border grayscale shadow-sm" alt="Ảnh"
                                                    style="width: 50px; height: 50px; object-fit: cover; filter: grayscale(100%);" />
                                                <div style="max-width: 300px;">
                                                    <div class="font-weight-bold text-dark text-truncate">{{ $product->product_name }}</div>
                                                    <small class="text-muted d-block text-truncate">{{ $product->short_description }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge badge-light border">{{ $product->category->name }}</span></td>
                                        <td class="text-muted font-weight-bold">{{ number_format($product->price, 0, ',', '.') }} đ</td>
                                        <td class="text-center pr-4">
                                            <div class="btn-group" role="group">
                                                <form action="{{ route('admin.products.restore', $product->id) }}" method="post" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-success btn-sm mx-1 rounded shadow-sm"
                                                            data-toggle="tooltip" title="Khôi phục">
                                                        <i class="fa fa-undo"></i>
                                                    </button>
                                                </form>
                                                
                                                <form action="{{ route('admin.products.forcedestroy', $product->id) }}" method="post"
                                                      class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn? Thao tác này không thể khôi phục.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm mx-1 rounded shadow-sm"
                                                            data-toggle="tooltip" title="Xóa vĩnh viễn">
                                                        <i class="fa fa-times-circle"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            Thùng rác trống.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($trashList->hasPages())
                    <div class="card-footer bg-white border-0 py-3">
                        <div class="d-flex justify-content-center">
                            {{ $trashList->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
