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
                                <i class="bi bi-plus-lg"></i> Thêm lựa chọn
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="variant_name" class="form-label">Tên nhóm biến thể (VD: Size, Màu sắc)</label>
                                <input type="text" class="form-control" name="variant_name" id="variant_name"
                                    placeholder="Ví dụ: Size" value="{{ old('variant_name') }}">
                            </div>

                            <div id="variant-options-container">
                                <div class="row mb-2 variant-option-row">
                                    <div class="col-md-6">
                                        <label class="small text-muted">Giá trị (VD: L, XL, Đỏ...)</label>
                                        <input type="text" name="variant_options[0][option]" class="form-control" placeholder="Giá trị">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="small text-muted">Phụ phí (VND)</label>
                                        <input type="number" name="variant_options[0][price_modifier]" class="form-control" value="0">
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="button" class="btn btn-outline-danger w-100 remove-option" disabled>
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
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
                                <div class="image-preview-container border rounded mb-3 d-flex align-items-center justify-content-center bg-light"
                                    style="height: 250px; overflow: hidden;">
                                    <img id="image-preview" src="#" alt="Preview" class="img-fluid d-none" style="max-height: 100%;">
                                    <div id="preview-placeholder">
                                        <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                        <p class="text-muted small">Chưa chọn ảnh</p>
                                    </div>
                                </div>
                                <input type="file" class="form-control @error('product_image') is-invalid @enderror"
                                    name="product_image" id="product_image" accept="image/*" onchange="previewImage(this)"/>
                                @error('product_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                                <i class="bi bi-check-circle"></i> LƯU SẢN PHẨM
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

    <script>
        // Image Preview logic
        function previewImage(input) {
            const preview = document.getElementById('image-preview');
            const placeholder = document.getElementById('preview-placeholder');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                    placeholder.classList.add('d-none');
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.src = '#';
                preview.classList.add('d-none');
                placeholder.classList.remove('d-none');
            }
        }

        // Dynamic Variations logic
        let optionIndex = 1;
        document.getElementById('add-option').addEventListener('click', function() {
            const container = document.getElementById('variant-options-container');
            const newRow = document.createElement('div');
            newRow.className = 'row mb-2 variant-option-row';
            newRow.innerHTML = `
                <div class="col-md-6">
                    <input type="text" name="variant_options[${optionIndex}][option]" class="form-control" placeholder="Giá trị">
                </div>
                <div class="col-md-4">
                    <input type="number" name="variant_options[${optionIndex}][price_modifier]" class="form-control" value="0">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-outline-danger w-100 remove-option">
                        <i class="bi bi-trash"></i>
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
