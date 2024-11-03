@extends('admin.layouts.master')

@section('title')
    Chi tiết Product : {{ $product->name }}
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
    <div class="table-responsive">
        <table class="table table-striped table-hover table-borderless table-primary align-middle">
            <thead class="table-light">
                <caption>
                    Chi Tiết Product
                </caption>
                <tr>
                    <th>Trường</th>
                    <th>Dữ Liệu</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                @foreach ($product->toArray() as $key => $value)
                    <tr class="table-primary">
                        <td scope="row">{{ ucfirst($key) }}</td>
                        <td>
                            @if ($key === 'product_image')
                                @if ($value)
                                    <img src="{{ Storage::url($value) }}" width="200px" alt="Product Image">
                                @else
                                    <span>Không có ảnh</span>
                                @endif
                            @elseif ($key === 'is_active')
                                <span class="badge {{ $value ? 'bg-primary' : 'bg-danger' }}">
                                    {{ $value ? 'Yes' : 'No' }}
                                </span>
                            @else
                                {{ is_array($value) ? implode(', ', $value) : $value }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
