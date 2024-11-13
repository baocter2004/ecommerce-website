@extends('admin.layouts.master')

@section('title')
    Thêm Mới Variant cho Product : {{ $product->product_name }}
@endsection

@section('content')
    <form action="{{ route('admin.products.variants.store', $product->id) }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6 mt-3 mb-3">
                <label for="name" class="form-label">Variant Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                    id="name" placeholder="Enter variant name" value="{{ old('name') }}" />
                @error('name')
                    <div class="alert alert-danger mt-2">
                        <p class="text-red">{{ $message }}</p>
                    </div>
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 mt-3 mb-3">
                <label for="variant-options" class="form-label">Variant Options</label>
                <div id="variant-options">
                    <div class="option-group">
                        <input type="text" class="form-control mb-2" name="options[0][option]"
                            placeholder="Option (e.g., Red)">
                        <input type="number" class="form-control mb-2" name="options[0][price_modifier]"
                            placeholder="Price Modifier" step="0.01">
                    </div>
                </div>
                <button type="button" id="add-option" class="btn btn-secondary mt-2">Add Option</button>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 mt-3 mb-3">
                <button type="submit" class="btn btn-primary"
                    onclick="this.disabled=true; this.form.submit();">Submit</button>
            </div>
        </div>
    </form>

    <script>
        // Khởi tạo optionIndex là 1 vì đã có sẵn 1 option group ban đầu
        let optionIndex = 1;
        document.getElementById('add-option').addEventListener('click', function () {
            let optionGroup = document.createElement('div');
            optionGroup.classList.add('option-group');
            optionGroup.innerHTML = `
                <input type="text" class="form-control mb-2" name="options[${optionIndex}][option]" placeholder="Option (e.g., Red)">
                <input type="number" class="form-control mb-2" name="options[${optionIndex}][price_modifier]" placeholder="Price Modifier" step="0.01">
            `;
            document.getElementById('variant-options').appendChild(optionGroup);
            optionIndex++;
        });
    </script>
@endsection
