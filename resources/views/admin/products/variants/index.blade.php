@extends('admin.layouts.master')

@section('title')
Variants for {{ $product->name }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="m-0 font-weight-bold text-primary">
                        <i class="fa fa-tags mr-2"></i> Biến thể: {{ $product->product_name }}
                    </h5>
                    <a href="{{ route('admin.products.variants.create', $product->id) }}" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm">
                        <i class="fa fa-plus-circle mr-1"></i> Thêm mới
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-dark">
                                <tr>
                                    <th class="border-0 pl-4">ID</th>
                                    <th class="border-0">Tên nhóm</th>
                                    <th class="border-0">Các tùy chọn</th>
                                    <th class="border-0 text-center pr-4">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($variants as $variant)
                                    <tr>
                                        <td class="pl-4 font-weight-bold">#{{ $variant->id }}</td>
                                        <td class="font-weight-bold text-dark">{{ $variant->name }}</td>
                                        <td>
                                            <div class="d-flex flex-wrap">
                                                @foreach ($variant->options as $option)
                                                    <div class="badge badge-light border m-1 p-2 shadow-xs text-left" style="min-width: 100px;">
                                                        <div class="text-dark font-weight-bold">{{ $option->option }}</div>
                                                        <div class="small text-primary">+{{ number_format($option->price_modifier, 0, ',', '.') }} đ</div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="text-center pr-4">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.products.variants.edit', [$product->id, $variant->id]) }}" 
                                                   class="btn btn-outline-warning btn-sm mx-1 rounded shadow-sm"
                                                   data-toggle="tooltip" title="Chỉnh sửa">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                                <form action="{{ route('admin.products.variants.destroy', [$product->id, $variant->id]) }}" 
                                                      method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
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
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            Chưa có biến thể nào cho sản phẩm này.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($variants->hasPages())
                    <div class="card-footer bg-white border-0 py-3">
                        <div class="d-flex justify-content-center">
                            {{ $variants->links() }}
                        </div>
                    </div>
                @endif
            </div>
            <div class="mt-3">
                <a href="{{ route('admin.products.index') }}" class="btn btn-link text-muted">
                    <i class="fa fa-arrow-left mr-1"></i> Quay lại danh sách sản phẩm
                </a>
            </div>
        </div>
    </div>
@endsection
