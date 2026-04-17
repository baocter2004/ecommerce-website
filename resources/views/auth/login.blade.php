@extends('auth.layouts.master')
@section('title')
    Shopper - Login
@endsection
@section('content')
<div class="container" style="margin-top: 80px; margin-bottom: 80px;">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-light shadow-sm" style="border-radius: 0;">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="text-black h3 text-uppercase font-weight-bold">Đăng nhập</h2>
                        <div class="bg-primary mx-auto" style="width: 50px; height: 2px;"></div>
                    </div>

                    <form action="{{ route('login') }}" method="POST">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="email" class="text-black small font-weight-bold text-uppercase">Địa chỉ Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control rounded-0 @error('email') is-invalid @enderror" 
                                   placeholder="email@example.com" value="{{ old('email') }}" id="email" name="email">
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="password" class="text-black small font-weight-bold text-uppercase">Mật khẩu <span class="text-danger">*</span></label>
                            <input type="password" class="form-control rounded-0 @error('password') is-invalid @enderror" 
                                   placeholder="••••••••" id="password" name="password">
                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group d-flex justify-content-between align-items-center mb-4">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="remember" name="remember">
                                <label class="custom-control-label small text-muted" for="remember">Ghi nhớ tôi</label>
                            </div>
                            <a href="#" class="small text-primary font-weight-bold">Quên mật khẩu?</a>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-lg btn-block rounded-0 text-uppercase font-weight-bold py-3" style="font-size: 13px; letter-spacing: 1px;">
                                Đăng nhập
                            </button>
                        </div>

                        <div class="text-center mt-4 pt-3 border-top">
                            <p class="text-muted small">Bạn chưa có tài khoản? 
                                <a href="{{ route('register') }}" class="text-primary font-weight-bold">Đăng ký tại đây</a>
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
