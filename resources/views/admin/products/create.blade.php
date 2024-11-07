@extends('admin.layouts.master')
@section('title')
    Thêm Mới Sản Phẩm
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

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6 mt-3 mb-3">
                <label for="product_name" class="form-label">Product Name</label>
                <input type="text" class="form-control @error('product_name') is-invalid @enderror" name="product_name"
                    id="product_name" placeholder="Enter product name" value="{{ old('product_name') }}" />
                @error('product_name')
                    <div class="alert alert-danger mt-2">
                        <p class="text-red">{{ $message }}</p>
                    </div>
                @enderror
            </div>
            <div class="col-md-6 mt-3 mb-3">
                <label for="category_id" class="form-label">Category</label>
                <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror">
                    <option value="">Select Category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category['id'] }}">
                            {{ $category['name'] }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="alert alert-danger mt-2">
                        <p class="text-red">{{ $message }}</p>
                    </div>
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mt-3 mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="text" class="form-control @error('price') is-invalid @enderror" name="price"
                    id="price" placeholder="Enter product price" value="{{ old('price') }}" />
                @error('price')
                    <div class="alert alert-danger mt-2">
                        <p class="text-red">{{ $message }}</p>
                    </div>
                @enderror
            </div>

            <div class="col-md-6 mt-3 mb-3">
                <label for="product_image" class="form-label">Product Image</label>
                <input type="file" class="form-control @error('product_image') is-invalid @enderror" name="product_image"
                    id="product_image" />
                @error('product_image')
                    <div class="alert alert-danger mt-2">
                        <p class="text-red">{{ $message }}</p>
                    </div>
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mt-3 mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"
                    rows="5" placeholder="Enter product description">{{ old('description') }}</textarea>
                @error('description')
                    <div class="alert alert-danger mt-2">
                        <p class="text-red">{{ $message }}</p>
                    </div>
                @enderror
            </div>

            <div class="col-md-6 mt-3 mb-3">
                <label for="short_description" class="form-label">Short Description</label>
                <textarea name="short_description" id="short_description"
                    class="form-control @error('short_description') is-invalid @enderror" rows="5"
                    placeholder="Enter short description">{{ old('short_description') }}</textarea>
                @error('short_description')
                    <div class="alert alert-danger mt-2">
                        <p class="text-red">{{ $message }}</p>
                    </div>
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mt-3 mb-3">
                <label for="is_active" class="form-label">Is Active</label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" />
                    <label class="form-check-label" for="is_active">Activate Product</label>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 mt-3 mb-3">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
@endsection
