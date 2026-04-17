@extends('admin.layouts.master')

@section('title')
    Chi tiết Product : {{ $product->name }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 mb-3">
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-sm rounded-pill px-3 shadow-sm text-white">
                <i class="fa fa-arrow-left mr-1"></i> Quay lại danh sách
            </a>
            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-warning btn-sm rounded-pill px-3 shadow-sm">
                <i class="fa fa-pencil mr-1"></i> Chỉnh sửa sản phẩm
            </a>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="m-0 font-weight-bold text-primary"><i class="fa fa-image mr-2"></i> Hình ảnh sản phẩm</h5>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3 border rounded p-2 bg-light">
                        <img src="{{ Storage::url($product->product_image) }}" 
                             class="img-fluid rounded shadow-sm" alt="Product Image"
                             style="max-height: 350px; width: 100%; object-fit: contain;">
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="m-0 font-weight-bold text-primary"><i class="fa fa-info-circle mr-2"></i> Thông tin trạng thái</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="small text-muted d-block">Trạng thái kinh doanh</label>
                        @if ($product->is_active === 1)
                            <span class="badge badge-success px-4 py-2 rounded-pill shadow-xs"><i class="fa fa-check-circle mr-1"></i> Đang bán</span>
                        @else
                            <span class="badge badge-secondary px-4 py-2 rounded-pill shadow-xs"><i class="fa fa-times-circle mr-1"></i> Ngừng bán</span>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label class="small text-muted d-block">Ngày tạo</label>
                        <span class="text-dark font-weight-bold">{{ $product->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="mb-0">
                        <label class="small text-muted d-block">Cập nhật lần cuối</label>
                        <span class="text-dark font-weight-bold">{{ $product->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="m-0 font-weight-bold text-primary"><i class="fa fa-file-text-o mr-2"></i> Chi tiết sản phẩm</h5>
                    <span class="badge badge-primary px-3 py-2 rounded-pill">{{ $product->category->name }}</span>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h3 class="font-weight-bold text-dark">{{ $product->product_name }}</h3>
                            <h4 class="text-primary font-weight-bold">{{ number_format($product->price, 0, ',', '.') }} đ</h4>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="font-weight-bold text-dark border-bottom pb-2">Mô tả ngắn</h6>
                        <p class="text-muted italic">{{ $product->short_description ?: 'Không có mô tả ngắn' }}</p>
                    </div>

                    <div class="mb-4">
                        <h6 class="font-weight-bold text-dark border-bottom pb-2">Nội dung chi tiết</h6>
                        <div class="text-dark">
                            {!! nl2br(e($product->description)) !!}
                        </div>
                    </div>

                    <div>
                        <h6 class="font-weight-bold text-dark border-bottom pb-2"><i class="fa fa-tags mr-1"></i> Biến thể sản phẩm</h6>
                        @forelse ($product->variants as $variant)
                            <div class="mb-3 p-3 bg-light rounded border-left border-primary" style="border-left-width: 4px !important;">
                                <div class="font-weight-bold text-primary mb-2">{{ $variant->name }}</div>
                                <div class="d-flex flex-wrap">
                                    @foreach ($variant->options as $option)
                                        <div class="badge badge-white border m-1 p-2 shadow-xs text-left" style="min-width: 100px;">
                                            <div class="text-dark font-weight-bold">{{ $option->option }}</div>
                                            <div class="small text-success">+{{ number_format($option->price_modifier, 0, ',', '.') }} đ</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <p class="text-muted italic">Sản phẩm này không có biến thể.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
