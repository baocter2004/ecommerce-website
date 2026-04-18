@extends('admin.layouts.master')
@section('title')
    Thêm Mới Sản Phẩm
@endsection

@section('content')
    <div class="container-fluid">
        @if ($errors->any())
            <div class="alert alert-danger shadow-sm border-0">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- Left Column: Basic Info -->
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-bottom-0 py-3">
                            <h5 class="mb-0 font-weight-bold">Thông tin cơ bản</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="product_name" class="form-label">Tên sản phẩm</label>
                                <input type="text" class="form-control @error('product_name') is-invalid @enderror"
                                    name="product_name" id="product_name" placeholder="Nhập tên sản phẩm"
                                    value="{{ old('product_name') }}" />
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="category_id" class="form-label">Danh mục</label>
                                    <select name="category_id" id="category_id"
                                        class="form-control @error('category_id') is-invalid @enderror">
                                        <option value="">Chọn danh mục</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category['id'] }}"
                                                {{ old('category_id') == $category['id'] ? 'selected' : '' }}>
                                                {{ $category['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="price" class="form-label">Giá bán (VND)</label>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror"
                                        name="price" id="price" placeholder="Nhập giá sản phẩm"
                                        value="{{ old('price') }}" />
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="quantity" class="form-label">Tồn kho (dùng cho sản phẩm không có biến thể)</label>
                                <input type="number" class="form-control" name="quantity" id="quantity" min="0" value="{{ old('quantity', 0) }}" />
                            </div>

                            <div class="mb-3">
                                <label for="short_description" class="form-label">Mô tả ngắn</label>
                                <textarea name="short_description" id="short_description" class="form-control" rows="2" placeholder="Nhập mô tả ngắn">{{ old('short_description') }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Mô tả chi tiết</label>
                                <textarea name="description" id="description" class="form-control" rows="5" placeholder="Nhập mô tả chi tiết">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Variations Section -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-bottom-0 py-3 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 font-weight-bold">Biến thể sản phẩm</h5>
                            <button type="button" class="btn btn-sm btn-primary" id="add-variant-group">
                                <i class="fa fa-plus mr-1"></i> Thêm nhóm biến thể (VD: Màu, Size)
                            </button>
                        </div>
                        <div class="card-body" id="variant-groups-container">
                            <!-- Các nhóm biến thể sẽ được thêm ở đây -->
                            @if(old('variants'))
                                @foreach(old('variants') as $vIndex => $vGroup)
                                    <div class="variant-group border rounded p-3 mb-3 bg-light" data-index="{{ $vIndex }}">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div class="flex-grow-1 mr-3">
                                                <input type="text" name="variants[{{ $vIndex }}][name]" class="form-control font-weight-bold" placeholder="Tên nhóm (VD: Màu sắc)" value="{{ $vGroup['name'] }}">
                                            </div>
                                            <button type="button" class="btn btn-outline-danger btn-sm remove-group">Xóa nhóm</button>
                                        </div>
                                        <div class="options-container">
                                            @foreach($vGroup['options'] as $oIndex => $option)
                                                <div class="row mb-2 align-items-center option-row">
                                                    <div class="col-md-5">
                                                        <input type="text" name="variants[{{ $vIndex }}][options][{{ $oIndex }}][option]" class="form-control form-control-sm" placeholder="Giá trị (VD: Đỏ)" value="{{ $option['option'] }}">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="number" name="variants[{{ $vIndex }}][options][{{ $oIndex }}][price_modifier]" class="form-control form-control-sm" placeholder="Phụ phí" value="{{ $option['price_modifier'] ?? 0 }}">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="number" name="variants[{{ $vIndex }}][options][{{ $oIndex }}][quantity]" class="form-control form-control-sm" placeholder="Kho" value="{{ $option['quantity'] ?? 0 }}">
                                                    </div>
                                                    <div class="col-md-1 text-right">
                                                        <button type="button" class="btn btn-link text-danger p-0 remove-option"><i class="fa fa-times"></i></button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-secondary add-option-btn mt-2">
                                            <i class="fa fa-plus-circle"></i> Thêm giá trị
                                        </button>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Column: Images & Status -->
                <div class="col-lg-4">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-bottom-0 py-3">
                            <h5 class="mb-0 font-weight-bold">Ảnh sản phẩm</h5>
                        </div>
                        <div class="card-body text-center">
                            @include('admin.components.image-upload', ['name' => 'product_image', 'id' => 'product_image'])
                        </div>
                    </div>

                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-bottom-0 py-3">
                            <h5 class="mb-0 font-weight-bold">Trạng thái</h5>
                        </div>
                        <div class="card-body">
                            <div class="custom-control custom-switch custom-switch-lg">
                                <input type="checkbox" class="custom-control-input" name="is_active" id="is_active" value="1" checked>
                                <label class="custom-control-label ml-2" for="is_active">Kích hoạt bán hàng</label>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary w-100 py-2 font-weight-bold">
                                <i class="fa fa-check-circle mr-1"></i> LƯU SẢN PHẨM
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        let groupIndex = {{ old('variants') ? count(old('variants')) : 0 }};

        document.getElementById('add-variant-group').addEventListener('click', function() {
            const container = document.getElementById('variant-groups-container');
            const groupHtml = `
                <div class="variant-group border rounded p-3 mb-3 bg-light" data-index="${groupIndex}">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="flex-grow-1 mr-3">
                            <input type="text" name="variants[${groupIndex}][name]" class="form-control font-weight-bold" placeholder="Tên nhóm (VD: Màu sắc)">
                        </div>
                        <button type="button" class="btn btn-outline-danger btn-sm remove-group">Xóa nhóm</button>
                    </div>
                    <div class="options-container">
                        <div class="row mb-2 align-items-center option-row">
                            <div class="col-md-5">
                                <input type="text" name="variants[${groupIndex}][options][0][option]" class="form-control form-control-sm" placeholder="Giá trị (VD: Đỏ)">
                            </div>
                            <div class="col-md-3">
                                <input type="number" name="variants[${groupIndex}][options][0][price_modifier]" class="form-control form-control-sm" placeholder="Phụ phí" value="0">
                            </div>
                            <div class="col-md-3">
                                <input type="number" name="variants[${groupIndex}][options][0][quantity]" class="form-control form-control-sm" placeholder="Kho" value="0">
                            </div>
                            <div class="col-md-1 text-right">
                                <button type="button" class="btn btn-link text-danger p-0 remove-option"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-secondary add-option-btn mt-2">
                        <i class="fa fa-plus-circle"></i> Thêm giá trị
                    </button>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', groupHtml);
            groupIndex++;
        });

        document.getElementById('variant-groups-container').addEventListener('click', function(e) {
            // Xóa nhóm
            if (e.target.classList.contains('remove-group')) {
                e.target.closest('.variant-group').remove();
            }

            // Thêm option vào nhóm
            if (e.target.closest('.add-option-btn')) {
                const group = e.target.closest('.variant-group');
                const gIdx = group.dataset.index;
                const container = group.querySelector('.options-container');
                const oIdx = container.querySelectorAll('.option-row').length;

                const optionHtml = `
                    <div class="row mb-2 align-items-center option-row">
                        <div class="col-md-5">
                            <input type="text" name="variants[${gIdx}][options][${oIdx}][option]" class="form-control form-control-sm" placeholder="Giá trị">
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="variants[${gIdx}][options][${oIdx}][price_modifier]" class="form-control form-control-sm" value="0">
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="variants[${gIdx}][options][${oIdx}][quantity]" class="form-control form-control-sm" value="0">
                        </div>
                        <div class="col-md-1 text-right">
                            <button type="button" class="btn btn-link text-danger p-0 remove-option"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', optionHtml);
            }

            // Xóa option
            if (e.target.closest('.remove-option')) {
                const container = e.target.closest('.options-container');
                if (container.querySelectorAll('.option-row').length > 1) {
                    e.target.closest('.option-row').remove();
                }
            }
        });
    </script>

    <style>
        .form-label { font-weight: 500; color: #495057; }
        .variant-group { transition: all 0.2s; border-left: 4px solid #007bff !important; }
        .variant-group:hover { border-left-color: #0056b3 !important; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
    </style>
@endsection
