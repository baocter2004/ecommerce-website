@extends('admin.layouts.master')

@section('title')
Variants for {{ $product->name }}
@endsection

@section('content')
    <h1>Variants for {{ $product->name }}</h1>
    <a href="{{ route('admin.products.variants.create', $product->id) }}" class="btn btn-primary mt-3 mb-3">Add Variant</a>

    @if ($variants->isEmpty())
        <p>No variants available for this product.</p>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-hover table-borderless align-middle">
                <thead class="table-light">
                    <caption>Danh Sách Products - Variants</caption>
                    <tr>
                        <th>ID</th>
                        <th>Variant Name</th>
                        <th>Options</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @foreach ($variants as $variant)
                        <tr class="table-light">
                            <td>{{ $variant->id }}</td>
                            <td>{{ $variant->name }}</td>
                            <td>
                                @foreach ($variant->options as $option)
                                    <div>
                                        <strong>Option:</strong> {{ $option->option }} <br>
                                        <strong>Price Modifier:</strong> {{ $option->price_modifier }}
                                    </div>
                                @endforeach
                            </td>
                            <td>
                                <a href="{{ route('admin.products.variants.edit', [$product->id, $variant->id]) }}"
                                    class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('admin.products.variants.destroy', [$product->id, $variant->id]) }}"
                                    method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('xóa ???')" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-center">
                            {{ $variants->links() }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endif
@endsection
