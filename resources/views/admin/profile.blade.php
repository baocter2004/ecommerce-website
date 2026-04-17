@extends('admin.layouts.master')

@section('title')
    Hồ Sơ Cá Nhân
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <strong>Thông tin cá nhân</strong>
                </div>
                <div class="card-body card-block">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Họ và Tên</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name"
                                placeholder="Nhập họ tên" value="{{ old('name', $user->name) }}" />
                            @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="user_name" class="form-label">Tên đăng nhập</label>
                            <input type="text" class="form-control @error('user_name') is-invalid @enderror" name="user_name"
                                id="user_name" placeholder="Nhập tên đăng nhập" value="{{ old('user_name', $user->user_name) }}">
                            @error('user_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email"
                                placeholder="Nhập email" value="{{ old('email', $user->email) }}" />
                            @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Số điện thoại</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" id="phone"
                                placeholder="Nhập số điện thoại" value="{{ old('phone', $user->phone) }}" />
                            @error('phone')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Ảnh đại diện</label>
                            @include('admin.components.image-upload', [
                                'name' => 'image',
                                'id'   => 'image',
                                'preview' => $user->image,
                                'height' => '200px'
                            ])
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Vai trò</label>
                            <input type="text" class="form-control" value="{{ $user->role }}" readonly disabled>
                            <small class="text-muted">Bạn không thể thay đổi vai trò của chính mình.</small>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12 text-right">
                            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
