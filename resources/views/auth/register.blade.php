@extends('auth.layouts.master')
@section('title')
    Shopper - Register
@endsection
@section('content')
<div class="container" style="margin-top: 60px; margin-bottom: 60px;">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card border-light shadow-sm" style="border-radius: 0;">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="text-black h3 text-uppercase font-weight-bold">Tạo tài khoản</h2>
                        <div class="bg-primary mx-auto" style="width: 50px; height: 2px;"></div>
                    </div>

                    <form action="{{ route('register') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label for="name" class="text-black small font-weight-bold text-uppercase">Họ và Tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control rounded-0 @error('name') is-invalid @enderror" 
                                       placeholder="Nguyễn Văn A" value="{{ old('name') }}" id="name" name="name">
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group mb-3">
                                <label for="user_name" class="text-black small font-weight-bold text-uppercase">Tên đăng nhập <span class="text-danger">*</span></label>
                                <input type="text" class="form-control rounded-0 @error('user_name') is-invalid @enderror" 
                                       placeholder="nguyenvana123" value="{{ old('user_name') }}" id="user_name" name="user_name">
                                @error('user_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-12 form-group mb-3">
                                <label for="email" class="text-black small font-weight-bold text-uppercase">Địa chỉ Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control rounded-0 @error('email') is-invalid @enderror" 
                                       placeholder="email@example.com" value="{{ old('email') }}" id="email" name="email">
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group mb-4">
                                <label for="password" class="text-black small font-weight-bold text-uppercase">Mật khẩu <span class="text-danger">*</span></label>
                                <input type="password" class="form-control rounded-0 @error('password') is-invalid @enderror" 
                                       placeholder="••••••••" id="password" name="password">
                                @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group mb-4">
                                <label for="password_confirmation" class="text-black small font-weight-bold text-uppercase">Xác nhận <span class="text-danger">*</span></label>
                                <input type="password" class="form-control rounded-0" placeholder="••••••••" id="password_confirmation" name="password_confirmation">
                            </div>
                        </div>

                        <div class="form-group mt-2">
                            <button type="submit" class="btn btn-primary btn-lg btn-block rounded-0 text-uppercase font-weight-bold py-3" style="font-size: 13px; letter-spacing: 1px;">
                                Đăng ký tài khoản
                            </button>
                        </div>

                        <div class="text-center mt-4 pt-3 border-top">
                            <p class="text-muted small">Bạn đã có tài khoản? 
                                <a href="{{ route('login') }}" class="text-primary font-weight-bold">Đăng nhập ngay</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    body { background-color: #f4f7f6; }
    .form-control {
        border: 1px solid #e1e1e1;
        padding: 12px 15px;
    }
    .form-control:focus {
        border-color: #007bff;
        box-shadow: none;
        background-color: #fff;
    }
    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }
    .btn-primary:hover {
        background-color: #0069d9;
        border-color: #0062cc;
    }
    .text-primary {
        color: #007bff !important;
    }
    .bg-primary {
        background-color: #007bff !important;
    }
</style>
@endsection
