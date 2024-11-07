@extends('admin.layouts.master')

@section('title')
    Danh Sách Category
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            Thao Tác Thành Công
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

    <!-- Nút tạo mới Category -->
    <div class="mt-3 mb-3">
        <a href="{{ route('admin.categories.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Create
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover table-borderless table-striped align-middle">
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
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td>{{ $category->name }}</td>
                        <td>
                            @if ($category->is_active === 1)
                                <span class="badge bg-success">Yes!</span>
                            @else
                                <span class="badge bg-danger">No!</span>
                            @endif
                        </td>
                        <td>{{ $category->created_at->format('d/m/Y') }}</td>
                        <td>{{ $category->updated_at->format('d/m/Y') }}</td>
                        <td>
                            <!-- Chỉnh sửa -->
                            <a class="btn btn-warning" href="{{ route('admin.categories.edit', $category->id) }}"
                                data-bs-toggle="tooltip" title="Chỉnh Sửa">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <!-- Xóa -->
                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="post"
                                class="d-inline" onsubmit="return confirm('Do you want delete it?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" data-bs-toggle="tooltip" title="Xóa">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6" class="text-center">
                        {{ $categories->links() }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
@endsection

@push('scripts')
    <script>
        // Kích hoạt tooltips của Bootstrap
        var tooltipTriggerList = Array.from(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>
@endpush
