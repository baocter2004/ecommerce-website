@extends('auth.layouts.master')
@section('title')
    Shopper - Register
@endsection
@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh; background-color: #f8f9fa; padding: 50px 0;">
    <div class="card border-0 shadow-lg p-5" style="width: 550px; border-radius: 20px;">
        <div class="text-center mb-5">
            <h3 class="fw-bold text-dark">Tạo tài khoản mới</h3>
            <p class="text-muted small">Bắt đầu hành trình mua sắm tuyệt vời cùng Shopper</p>
        </div>

        <form action="{{ route('register') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-12 mb-4">
                    <label for="name" class="form-label small fw-bold text-secondary">Họ và Tên</label>
                    <div class="input-group border rounded-pill px-3 py-1 bg-light shadow-sm">
                        <span class="input-group-text bg-transparent border-0"><i class="bi bi-person text-muted"></i></span>
                        <input type="text" class="form-control bg-transparent border-0 @error('name') is-invalid @enderror" 
                               placeholder="Nguyễn Văn A" value="{{ old('name') }}" id="name" name="name">
                    </div>
                    @error('name')
                        <div class="text-danger small mt-1 ps-3">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12 mb-4">
                    <label for="email" class="form-label small fw-bold text-secondary">Địa chỉ Email</label>
                    <div class="input-group border rounded-pill px-3 py-1 bg-light shadow-sm">
                        <span class="input-group-text bg-transparent border-0"><i class="bi bi-envelope text-muted"></i></span>
                        <input type="email" class="form-control bg-transparent border-0 @error('email') is-invalid @enderror" 
                               placeholder="email@example.com" value="{{ old('email') }}" id="email" name="email">
                    </div>
                    @error('email')
                        <div class="text-danger small mt-1 ps-3">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12 mb-4">
                    <label for="user_name" class="form-label small fw-bold text-secondary">Tên đăng nhập</label>
                    <div class="input-group border rounded-pill px-3 py-1 bg-light shadow-sm">
                        <span class="input-group-text bg-transparent border-0"><i class="bi bi-at text-muted"></i></span>
                        <input type="text" class="form-control bg-transparent border-0 @error('user_name') is-invalid @enderror" 
                               placeholder="username123" value="{{ old('user_name') }}" id="user_name" name="user_name">
                    </div>
                    @error('user_name')
                        <div class="text-danger small mt-1 ps-3">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-4">
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

                <div class="col-md-6 mb-4">
                    <label for="password_confirmation" class="form-label small fw-bold text-secondary">Xác nhận</label>
                    <div class="input-group border rounded-pill px-3 py-1 bg-light shadow-sm">
                        <span class="input-group-text bg-transparent border-0"><i class="bi bi-shield-check text-muted"></i></span>
                        <input type="password" class="form-control bg-transparent border-0 id="password_confirmation" name="password_confirmation" placeholder="••••••••">
                    </div>
                </div>
            </div>

            <div class="form-check mb-4 px-4">
                <input class="form-check-input" type="checkbox" id="terms" required>
                <label class="form-check-label small text-muted" for="terms">
                    Tôi đồng ý với <a href="#" class="text-primary text-decoration-none">Điều khoản & Chính sách</a>
                </label>
            </div>

            <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-sm transition-all">
                ĐĂNG KÝ TÀI KHOẢN
            </button>

            <div class="text-center mt-5">
                <p class="text-muted small">Đã có tài khoản? 
                    <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">Đăng nhập ngay</a>
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
