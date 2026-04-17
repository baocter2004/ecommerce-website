@extends('client.layouts.master')

@section('title', 'Tài khoản của tôi - Shoppers')

@section('content')
<div class="bg-light py-3">
    <div class="container">
        <div class="row">
            <div class="col-md-12 mb-0">
                <a href="{{ route('client.index') }}">Trang chủ</a> 
                <span class="mx-2 mb-0">/</span> 
                <strong class="text-black">Tài khoản của tôi</strong>
            </div>
        </div>
    </div>
</div>

<div class="site-section">
    <div class="container">
        <div class="row">
            <!-- Sidebar / Info Card -->
            <div class="col-md-4 mb-5">
                <div class="card border-0 shadow-sm text-center p-4">
                    <div class="profile-avatar-wrapper mb-3 mx-auto" style="width: 150px; height: 150px; position: relative;">
                        <img src="{{ Auth::user()->image ? Storage::url(Auth::user()->image) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=random' }}" 
                             id="profile-preview"
                             class="rounded-circle shadow-sm border" 
                             style="width: 100%; height: 100%; object-fit: cover;" alt="Avatar">
                    </div>
                    <h4 class="font-weight-bold text-black mb-1">{{ Auth::user()->name }}</h4>
                    <p class="text-muted small mb-3">@ {{ Auth::user()->user_name }}</p>
                    <div class="badge badge-primary px-3 py-2 rounded-pill shadow-xs">
                        {{ Auth::user()->role === 'admin' ? 'Quản trị viên' : 'Thành viên' }}
                    </div>
                    <hr class="my-4">
                    <div class="text-left small">
                        <p class="mb-2"><i class="bi bi-envelope mr-2 text-primary"></i> {{ Auth::user()->email }}</p>
                        <p class="mb-2"><i class="bi bi-calendar-check mr-2 text-primary"></i> Tham gia: {{ Auth::user()->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-8">
                @if (session('success_profile'))
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                        <i class="bi bi-check-circle mr-2"></i> {{ session('success_profile') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if (session('success_password'))
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                        <i class="bi bi-shield-check mr-2"></i> {{ session('success_password') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <!-- Profile Info Form -->
                <div class="card border-0 shadow-sm mb-5">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 text-black font-weight-bold"><i class="bi bi-person-lines-fill mr-2"></i> Thông tin cá nhân</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('client.profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="name" class="text-black font-weight-bold small text-uppercase">Họ và tên <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control rounded-0 @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $user->name) }}">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="user_name" class="text-black font-weight-bold small text-uppercase">Tên đăng nhập <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control rounded-0 @error('user_name') is-invalid @enderror" 
                                           id="user_name" name="user_name" value="{{ old('user_name', $user->user_name) }}">
                                    @error('user_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group mt-3">
                                <label for="email" class="text-black font-weight-bold small text-uppercase">Địa chỉ Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control rounded-0 @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $user->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mt-3">
                                <label for="phone" class="text-black font-weight-bold small text-uppercase">Số điện thoại</label>
                                <input type="text" class="form-control rounded-0 @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mt-3 mb-4">
                                <label for="image" class="text-black font-weight-bold small text-uppercase">Ảnh đại diện mới</label>
                                <input type="file" class="form-control-file" id="image" name="image" onchange="previewImage(this)">
                                <small class="text-muted">Định dạng: JPG, PNG, GIF. Tối đa 2MB.</small>
                                @error('image')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-primary px-4 py-2 rounded-0 text-uppercase font-weight-bold">Lưu thay đổi</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Password Change Form -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 text-black font-weight-bold"><i class="bi bi-shield-lock mr-2"></i> Bảo mật & Mật khẩu</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('client.profile.password.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group mb-4">
                                <label for="current_password" class="text-black font-weight-bold small text-uppercase">Mật khẩu hiện tại <span class="text-danger">*</span></label>
                                <input type="password" class="form-control rounded-0 @error('current_password') is-invalid @enderror" 
                                       id="current_password" name="current_password">
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group mb-4">
                                    <label for="password" class="text-black font-weight-bold small text-uppercase">Mật khẩu mới <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control rounded-0 @error('password') is-invalid @enderror" 
                                           id="password" name="password">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 form-group mb-4">
                                    <label for="password_confirmation" class="text-black font-weight-bold small text-uppercase">Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control rounded-0" id="password_confirmation" name="password_confirmation">
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-outline-primary px-4 py-2 rounded-0 text-uppercase font-weight-bold">Đổi mật khẩu</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profile-preview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<style>
    .form-control:focus {
        border-color: #7971ea;
        box-shadow: none;
    }
    .card { border-radius: 8px; }
    .shadow-sm { box-shadow: 0 .125rem .25rem rgba(0,0,0,.075)!important; }
    .shadow-xs { box-shadow: 0 .1rem .2rem rgba(0,0,0,.05)!important; }
</style>
@endsection
