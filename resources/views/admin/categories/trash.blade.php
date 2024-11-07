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

    <div class="mt-3 mb-3">
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tạo Mới
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover table-borderless align-middle">
            <thead class="table-light">
                <caption>
                    Danh Sách Category
                </caption>
                <tr>
                    <th>ID</th>
                    <th>Tên</th>
                    <th>Trạng Thái</th>
                    <th>Ngày Tạo</th>
                    <th>Ngày Cập Nhật</th>
                    <th>Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($trashList as $category)
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td>{{ $category->name }}</td>
                        <td>
                            @if ($category->is_active === 1)
                                <span class="badge bg-primary">Có</span>
                            @else
                                <span class="badge bg-secondary">Không</span>
                            @endif
                        </td>
                        <td>{{ $category->created_at->format('d/m/Y') }}</td>
                        <td>{{ $category->updated_at->format('d/m/Y') }}</td>
                        <td>
                            <form action="{{ route('admin.categories.restore', $category->id) }}" method="post" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-warning" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="Khôi Phục">
                                    <i class="bi bi-arrow-repeat"></i>
                                </button>
                            </form>
                            
                            <form action="{{ route('admin.categories.forcedestroy', $category->id) }}" method="post"
                                class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa không?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="Xóa Vĩnh Viễn">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6">
                        {{ $trashList->links() }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
@endsection

@push('scripts')
    <script>
        // Initialize Bootstrap Tooltips
        var tooltipTriggerList = Array.from(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
@endpush
