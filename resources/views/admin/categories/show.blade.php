@extends('admin.layouts.master')

@section('title')
    Danh Sách Category
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="mt-3 mb-3">
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">Create</a>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-hover table-borderless table-primary align-middle">
            <thead class="table-light">
                <caption>
                    Danh Sách Category
                </caption>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Is Active</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                @foreach ($categories as $category)
                    <tr class="table-primary">
                        <td scope="row">{{ $category->id }}</td>
                        <td scope="row">{{ $category->name }}</td>
                        <td scope="row">
                            @if ($category->is_active === 1)
                                <span class="badge bg-primary">Yes!</span>
                            @else
                                <span class="badge bg-danger">No!</span>
                            @endif
                        </td>
                        <td scope="row">{{ $category->created_at->format('d/m/Y') }}</td>
                        <td scope="row">{{ $category->updated_at->format('d/m/Y') }}</td>
                        <td scope="row">
                            <a class="btn btn-warning" href="{{ route('admin.categories.edit', $category->id) }}"><i
                                    class="bi bi-pencil-square"></i></a>
                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Do you want delete it ?')"
                                    class="mt-2 btn btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                {{ $categories->links() }}
            </tfoot>
        </table>
    </div>
@endsection
