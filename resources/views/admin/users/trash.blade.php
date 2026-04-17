@extends('admin.layouts.master')

@section('title')
    Danh Sách Users
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4">
                    <i class="fa fa-check-circle mr-2"></i> Thao tác thành công!
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="m-0 font-weight-bold text-danger"><i class="fa fa-trash mr-2"></i> Thùng rác: Người dùng</h5>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm rounded-pill px-3 shadow-sm">
                        <i class="fa fa-arrow-left mr-1"></i> Quay lại
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-dark">
                                <tr>
                                    <th class="border-0 pl-4">ID</th>
                                    <th class="border-0">Người dùng</th>
                                    <th class="border-0">Vai trò</th>
                                    <th class="border-0">Ngày tạo</th>
                                    <th class="border-0 text-center pr-4">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($trashList as $user)
                                    <tr>
                                        <td class="pl-4 font-weight-bold">#{{ $user->id }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ Storage::url($user->image) }}" 
                                                    class="rounded-circle mr-3 shadow-sm border" alt="Avatar"
                                                    style="width: 40px; height: 40px; object-fit: cover; filter: grayscale(100%);" />
                                                <div>
                                                    <div class="font-weight-bold text-dark">{{ $user->name }}</div>
                                                    <small class="text-muted">{{ $user->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($user->role === 'admin')
                                                <span class="badge badge-primary px-3 py-2 rounded-pill">Quản trị</span>
                                            @else
                                                <span class="badge badge-secondary px-3 py-2 rounded-pill">Thành viên</span>
                                            @endif
                                        </td>
                                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                        <td class="text-center pr-4">
                                            <div class="btn-group" role="group">
                                                <form action="{{ route('admin.users.restore', $user->id) }}" method="post" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-success btn-sm mx-1 rounded shadow-sm"
                                                            data-toggle="tooltip" title="Khôi phục">
                                                        <i class="fa fa-undo"></i>
                                                    </button>
                                                </form>
                                                
                                                <form action="{{ route('admin.users.forcedestroy', $user->id) }}" method="post"
                                                      class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm mx-1 rounded shadow-sm"
                                                            data-toggle="tooltip" title="Xóa vĩnh viễn">
                                                        <i class="fa fa-times-circle"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            Thùng rác trống.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($trashList->hasPages())
                    <div class="card-footer bg-white border-0 py-3">
                        <div class="d-flex justify-content-center">
                            {{ $trashList->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
