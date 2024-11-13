@extends('admin.layouts.master')

@section('title')
    Danh Sách Users
@endsection

@section('content')
    <a href="{{ route('admin.users.create') }}" class="mt-3 mb-3 btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tạo Mới
    </a>

    <div class="table-responsive">
        <table class="table table-striped table-hover table-borderless align-middle">
            <thead class="table-light">
                <caption>Danh Sách Users</caption>
                <tr>
                    <th>ID</th>
                    <th>Tên</th>
                    <th>Email</th>
                    <th>Tên Người Dùng</th>
                    <th>Ảnh</th>
                    <th>Điện Thoại</th>
                    <th>Vai Trò</th>
                    <th>Trạng Thái</th>
                    <th>Ngày Tạo</th>
                    <th>Ngày Cập Nhật</th>
                    <th>Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($trashList as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->user_name }}</td>
                        <td>
                            <img src="{{ Storage::url($user->image) }}" class="img-fluid rounded" alt="Avatar"
                                style="max-height: 50px; object-fit: cover;" />
                        </td>
                        <td>{{ $user->phone }}</td>
                        <td>
                            @if ($user->role === 'admin')
                                <span class="badge bg-primary">Admin</span>
                            @else
                                <span class="badge bg-warning">Member</span>
                            @endif
                        </td>
                        <td>
                            @if ($user->is_active)
                                <span class="badge bg-success">Yes</span>
                            @else
                                <span class="badge bg-danger">No</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('Y/m/d') }}</td>
                        <td>{{ $user->updated_at->format('Y/m/d') }}</td>
                        <td>
                            <form action="{{ route('admin.users.restore', $product->id) }}" method="post" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-warning" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="Khôi Phục">
                                    <i class="bi bi-arrow-repeat"></i>
                                </button>
                            </form>
                            
                            <form action="{{ route('admin.users.forcedestroy', $product->id) }}" method="post"
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
                    <td colspan="11" class="text-center">
                        {{ $trashList->links() }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
@endsection

@push('scripts')
    <script>
        // Kích hoạt Bootstrap tooltips
        var tooltipTriggerList = Array.from(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>
@endpush
