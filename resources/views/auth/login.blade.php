@extends('auth.layouts.master')
@section('title')
    Shopper - Login
@endsection
@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh; background-color: #f8f9fa;">
    <div class="card border-0 shadow-lg p-5" style="width: 450px; border-radius: 20px;">
        <div class="text-center mb-5">
            <h3 class="fw-bold text-dark">Chào mừng trở lại!</h3>
            <p class="text-muted small">Đăng nhập để tiếp tục trải nghiệm cùng Shopper</p>
        </div>

        <form action="{{ route('login') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="email" class="form-label small fw-bold text-secondary">Địa chỉ Email</label>
                <div class="input-group border rounded-pill px-3 py-1 bg-light shadow-sm">
                    <span class="input-group-text bg-transparent border-0"><i class="bi bi-envelope text-muted"></i></span>
                    <input type="email" class="form-control bg-transparent border-0 @error('email') is-invalid @enderror" 
                           placeholder="name@example.com" value="{{ old('email') }}" id="email" name="email">
                </div>
                @error('email')
                    <div class="text-danger small mt-1 ps-3">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="form-label small fw-bold text-secondary">Mật khẩu</label>
                <div class="input-group border rounded-pill px-3 py-1 bg-light shadow-sm">
                    <span class="input-group-text bg-transparent border-0"><i class="bi bi-lock text-muted"></i></span>
                    <input type="password" class="form-control bg-transparent border-0 @error('password') is-invalid @enderror" 
                           placeholder="••••••••" id="password" name="password">
                </div>
                @error('password')
                    <div class="text-danger small mt-1 ps-3">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4 px-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember" name="remember">
                    <label class="form-check-label small text-muted" for="remember">Ghi nhớ tôi</label>
                </div>
                <a href="#" class="small text-primary text-decoration-none">Quên mật khẩu?</a>
            </div>

            <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-sm transition-all">
                ĐĂNG NHẬP
            </button>

            <div class="text-center mt-5">
                <p class="text-muted small">Chưa có tài khoản? 
                    <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none">Đăng ký ngay</a>
                </p>
            </div>
        </form>
    </div>
</div>

<style>
    .transition-all { transition: all 0.3s ease; }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(0, 123, 255, 0.4) !important; }
    .input-group:focus-within { border-color: #0d6efd !important; background-color: #fff !important; }
    input:focus { outline: none !important; box-shadow: none !important; }
</style>
@endsection
