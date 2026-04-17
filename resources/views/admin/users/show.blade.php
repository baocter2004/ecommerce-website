@extends('admin.layouts.master')

@section('title')
    Xem Thông Tin User
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 mb-3">
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm rounded-pill px-3 shadow-sm text-white">
                <i class="fa fa-arrow-left mr-1"></i> Quay lại danh sách
            </a>
            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-sm rounded-pill px-3 shadow-sm">
                <i class="fa fa-pencil mr-1"></i> Chỉnh sửa thành viên
            </a>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4 text-center">
                <div class="card-body py-5">
                    <img src="{{ Storage::url($user->image) }}" 
                         class="rounded-circle shadow border mb-3" alt="Avatar"
                         style="width: 150px; height: 150px; object-fit: cover;">
                    <h4 class="font-weight-bold text-dark mb-1">{{ $user->name }}</h4>
                    <p class="text-muted small">@ {{ $user->user_name }}</p>
                    
                    <div class="mt-4">
                        @if ($user->role === 'admin')
                            <span class="badge badge-primary px-4 py-2 rounded-pill shadow-xs">Quản trị viên</span>
                        @else
                            <span class="badge badge-secondary px-4 py-2 rounded-pill shadow-xs">Thành viên</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="m-0 font-weight-bold text-primary"><i class="fa fa-id-card-o mr-2"></i> Thông tin chi tiết</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-3 text-muted">Họ và Tên:</div>
                        <div class="col-sm-9 font-weight-bold">{{ $user->name }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3 text-muted">Địa chỉ Email:</div>
                        <div class="col-sm-9 font-weight-bold">{{ $user->email }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3 text-muted">Số điện thoại:</div>
                        <div class="col-sm-9 font-weight-bold">{{ $user->phone ?: 'Chưa cập nhật' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3 text-muted">Trạng thái tài khoản:</div>
                        <div class="col-sm-9">
                            @if ($user->is_active)
                                <span class="badge badge-success px-3 py-1 rounded-pill"><i class="fa fa-check mr-1"></i> Đang hoạt động</span>
                            @else
                                <span class="badge badge-danger px-3 py-1 rounded-pill"><i class="fa fa-times mr-1"></i> Đang bị khóa</span>
                            @endif
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <label class="small text-muted d-block">Ngày tham gia</label>
                            <span class="text-dark">{{ $user->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="col-sm-4">
                            <label class="small text-muted d-block">Cập nhật lần cuối</label>
                            <span class="text-dark">{{ $user->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
