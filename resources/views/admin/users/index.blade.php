@extends('admin.layouts.master')

@section('title')
    Danh Sách Users
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4">
                    <i class="fa fa-check-circle mr-2"></i> Thao tác thành công!
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="m-0 font-weight-bold text-primary"><i class="fa fa-users mr-2"></i> Danh Sách Người Dùng</h5>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm">
                        <i class="fa fa-plus-circle mr-1"></i> Thêm mới
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light text-dark">
                                <tr>
                                    <th class="border-0">ID</th>
                                    <th class="border-0">Người dùng</th>
                                    <th class="border-0">Tài khoản</th>
                                    <th class="border-0">Vai trò</th>
                                    <th class="border-0">Trạng thái</th>
                                    <th class="border-0">Ngày tạo</th>
                                    <th class="border-0 text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ Storage::url($user->image) }}" 
                                                    class="rounded-circle mr-3 shadow-sm border" alt="Avatar"
                                                    style="width: 40px; height: 40px; object-fit: cover;" />
                                                <div>
                                                    <div class="font-weight-bold text-dark">{{ $user->name }}</div>
                                                    <small class="text-muted">{{ $user->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $user->user_name }}</td>
                                        <td>
                                            @if ($user->role === 'admin')
                                                <span class="badge badge-primary px-3 py-2 rounded-pill">Quản trị</span>
                                            @else
                                                <span class="badge badge-secondary px-3 py-2 rounded-pill">Thành viên</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($user->is_active)
                                                <span class="badge badge-success px-3 py-2 rounded-pill"><i class="fa fa-check mr-1"></i> Bật</span>
                                            @else
                                                <span class="badge badge-danger px-3 py-2 rounded-pill"><i class="fa fa-times mr-1"></i> Tắt</span>
                                            @endif
                                        </td>
                                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.users.show', $user->id) }}" 
                                                   class="btn btn-outline-info btn-sm mx-1 rounded shadow-sm"
                                                   data-toggle="tooltip" title="Xem chi tiết">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.users.edit', $user->id) }}" 
                                                   class="btn btn-outline-warning btn-sm mx-1 rounded shadow-sm"
                                                   data-toggle="tooltip" title="Chỉnh sửa">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="post" 
                                                      class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm mx-1 rounded shadow-sm"
                                                            data-toggle="tooltip" title="Xóa">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">Không có người dùng nào.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($users->hasPages())
                    <div class="card-footer bg-white border-0 py-3">
                        <div class="d-flex justify-content-center">
                            {{ $users->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
