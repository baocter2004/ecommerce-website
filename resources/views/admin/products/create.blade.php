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
                                @error('product_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="price" class="form-label">Giá bán (VND)</label>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror"
                                        name="price" id="price" placeholder="Nhập giá sản phẩm"
                                        value="{{ old('price') }}" />
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="quantity" class="form-label">Tồn kho (dùng cho sản phẩm không có biến thể)</label>
                                <input type="number" class="form-control @error('quantity') is-invalid @enderror"
                                    name="quantity" id="quantity" min="0" placeholder="Nhập số lượng tồn"
                                    value="{{ old('quantity', 0) }}" />
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="short_description" class="form-label">Mô tả ngắn</label>
                                <textarea name="short_description" id="short_description"
                                    class="form-control @error('short_description') is-invalid @enderror" rows="2"
                                    placeholder="Nhập mô tả ngắn">{{ old('short_description') }}</textarea>
                                @error('short_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-0">
                                <label for="description" class="form-label">Mô tả chi tiết</label>
                                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"
                                    rows="5" placeholder="Nhập mô tả chi tiết">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Variations Section -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-bottom-0 py-3 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 font-weight-bold">Biến thể sản phẩm</h5>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="add-option">
                                <i class="fa fa-plus mr-1"></i> Thêm lựa chọn
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="variant_name" class="form-label">Tên nhóm biến thể (VD: Size, Màu sắc)</label>
                                <input type="text" class="form-control @error('variant_name') is-invalid @enderror" 
                                    name="variant_name" id="variant_name"
                                    placeholder="Ví dụ: Size" value="{{ old('variant_name') }}">
                                @error('variant_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div id="variant-options-container">
                                @if (old('variant_options'))
                                    @foreach (old('variant_options') as $index => $option)
                                        <div class="row mb-2 variant-option-row">
                                            <div class="col-md-6">
                                                <label class="small text-muted">Giá trị (VD: L, XL, Đỏ...)</label>
                                                <input type="text" name="variant_options[{{ $index }}][option]" 
                                                    class="form-control @error("variant_options.$index.option") is-invalid @enderror" 
                                                    placeholder="Giá trị" value="{{ $option['option'] }}">
                                                @error("variant_options.$index.option")
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-4">
                                                <label class="small text-muted">Phụ phí (VND)</label>
                                                <input type="number" name="variant_options[{{ $index }}][price_modifier]" 
                                                    class="form-control @error("variant_options.$index.price_modifier") is-invalid @enderror" 
                                                    value="{{ $option['price_modifier'] ?? 0 }}">
                                                @error("variant_options.$index.price_modifier")
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-2">
                                                <label class="small text-muted">Số lượng</label>
                                                <input type="number" name="variant_options[{{ $index }}][quantity]"
                                                    class="form-control @error("variant_options.$index.quantity") is-invalid @enderror"
                                                    min="0" value="{{ $option['quantity'] ?? 0 }}">
                                                @error("variant_options.$index.quantity")
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-2 d-flex align-items-end">
                                                <button type="button" class="btn btn-outline-danger w-100 remove-option" 
                                                    {{ count(old('variant_options')) == 1 ? 'disabled' : '' }}>
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="row mb-2 variant-option-row">
                                        <div class="col-md-6">
                                            <label class="small text-muted">Giá trị (VD: L, XL, Đỏ...)</label>
                                            <input type="text" name="variant_options[0][option]" class="form-control" placeholder="Giá trị">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="small text-muted">Phụ phí (VND)</label>
                                            <input type="number" name="variant_options[0][price_modifier]" class="form-control" value="0">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="small text-muted">Số lượng</label>
                                            <input type="number" name="variant_options[0][quantity]" class="form-control" min="0" value="0">
                                        </div>
                                        <div class="col-md-2 d-flex align-items-end">
                                            <button type="button" class="btn btn-outline-danger w-100 remove-option" disabled>
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </div>
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
                            <div class="mb-3">
                                @include('admin.components.image-upload', [
                                    'name' => 'product_image',
                                    'id'   => 'product_image'
                                ])
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-bottom-0 py-3">
                            <h5 class="mb-0 font-weight-bold">Trạng thái</h5>
                        </div>
                        <div class="card-body">
                                <div class="custom-control custom-switch custom-switch-lg">
                                    <input type="checkbox" class="custom-control-input" name="is_active" id="is_active"
                                        value="1" checked>
                                    <label class="custom-control-label ml-2" for="is_active">Kích hoạt bán hàng</label>
                                </div>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary w-100 py-2 font-weight-bold">
                                <i class="fa fa-check-circle mr-1"></i> LƯU SẢN PHẨM
                            </button>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-link w-100 mt-2 text-muted text-decoration-none">
                                Quay lại danh sách
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>        // Dynamic Variations logic
        let optionIndex = document.querySelectorAll('.variant-option-row').length;
        document.getElementById('add-option').addEventListener('click', function() {
            const container = document.getElementById('variant-options-container');
            const newRow = document.createElement('div');
            newRow.className = 'row mb-2 variant-option-row';
            newRow.innerHTML = `
                <div class="col-md-6">
                    <input type="text" name="variant_options[${optionIndex}][option]" class="form-control" placeholder="Giá trị">
                </div>
                <div class="col-md-3">
                    <input type="number" name="variant_options[${optionIndex}][price_modifier]" class="form-control" value="0">
                </div>
                <div class="col-md-3">
                    <input type="number" name="variant_options[${optionIndex}][quantity]" class="form-control" min="0" value="0">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-outline-danger w-100 remove-option">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            `;
            container.appendChild(newRow);
            optionIndex++;

            // Enable first remove button if multiple rows exist
            toggleRemoveButtons();
        });

        document.getElementById('variant-options-container').addEventListener('click', function(e) {
            if (e.target.closest('.remove-option')) {
                const rows = document.querySelectorAll('.variant-option-row');
                if (rows.length > 1) {
                    e.target.closest('.variant-option-row').remove();
                    toggleRemoveButtons();
                }
            }
        });

        function toggleRemoveButtons() {
            const rows = document.querySelectorAll('.variant-option-row');
            rows.forEach(row => {
                const btn = row.querySelector('.remove-option');
                if (rows.length === 1) {
                    btn.setAttribute('disabled', 'disabled');
                } else {
                    btn.removeAttribute('disabled');
                }
            });
        }
    </script>

    <style>
        .custom-switch-lg .form-check-input {
            width: 3rem;
            height: 1.5rem;
            cursor: pointer;
        }
        .form-label {
            font-weight: 500;
            color: #495057;
        }
        .card-header {
            border-bottom: 1px solid #f0f0f0 !important;
        }
    </style>
@endsection
