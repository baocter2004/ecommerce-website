@extends('admin.layouts.master')

@section('title')
    Danh Sách Products
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

    @if (session()->has('success'))
        <div class="alert alert-primary">
            <h1>Thao Tác Thành Công</h1>
        </div>
    @endif
    <a href="{{route('admin.products.create')}}" class="mt-3 mb-3 btn btn-primary">Create</a>
    <div class="table-responsive">
        <table class="table table-striped table-hover table-borderless table-primary align-middle">
            <thead class="table-light">
                <caption>
                    Danh Sách Products
                </caption>
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Product Image</th>
                    <th>Description</th>
                    <th>Short Description</th>
                    <th>Is Active</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                @foreach ($products as $product)
                    <tr class="table-primary">
                        <td scope="row">{{ $product->id }}</td>
                        <td>{{ $product->product_name }}</td>
                        <td>{{ $product->category->name }}</td>
                        <td>{{ $product->price }}</td>
                        <td>
                            <img src="{{ Storage::url($product->product_image) }}" class="img-fluid rounded-top"
                                alt="Chưa có Ảnh" />
                        </td>
                        <td>
                            {{ Str::limit($product->description, 50) }}
                            @if (strlen($product->description) > 50)
                                <a href="{{ route('admin.products.show', $product->id) }}">Xem Thêm</a>
                            @endif
                        </td>
                        <td>
                            {{ Str::limit($product->short_description, 50) }}
                            @if (strlen($product->short_description) > 50)
                                <a href="{{ route('admin.products.show', $product->id) }}">Xem Thêm</a>
                            @endif
                        </td>

                        <td>
                            @if ($product->is_active === 1)
                                <span class="badge bg-primary">Yes</span>
                            @else
                                <span class="badge bg-danger">No</span>
                            @endif
                        </td>
                        <td>{{ $product->created_at->format('Y/m/d') }}</td>
                        <td>{{ $product->updated_at->format('Y/m/d') }}</td>
                        <td>
                            <a class="btn btn-info" href="{{ route('admin.products.show', $product) }}">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a class="btn btn-info mt-3 mb-3" href="{{ route('admin.products.edit', $product) }}">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Do You Want Delete it ?')" type="submit"
                                    class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                {{ $products->links() }}
            </tfoot>
        </table>
    </div>
@endsection
