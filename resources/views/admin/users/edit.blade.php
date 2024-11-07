@extends('admin.layouts.master')

@section('title')
    Cập Nhật User
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            Thao Tác Thành Công
        </div>
    @endif

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="container">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name"
                        placeholder="Enter name" value="{{ old('name', $user->name) }}" />
                    @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="user_name" class="form-label">User Name</label>
                    <input type="text" class="form-control @error('user_name') is-invalid @enderror" name="user_name"
                        id="user_name" placeholder="Enter username" value="{{ old('user_name', $user->user_name) }}">
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
                        placeholder="Enter User email" value="{{ old('email', $user->email) }}" />
                    @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="role" class="form-label">Role</label>
                    <select name="role" id="role" class="form-select">
                        <option value="">-- Select Role --</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role }}" {{ old('role', $user->role) == $role ? 'selected' : '' }}>
                                {{ $role }}
                            </option>
                        @endforeach
                    </select>
                    @error('role')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" id="phone"
                        placeholder="Enter User phone" value="{{ old('phone', $user->phone) }}" />
                    @error('phone')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="image" class="form-label">User Image</label>
                    <input type="file" class="form-control @error('image') is-invalid @enderror" name="image" id="image" />
                    <img src="{{ Storage::url($user->image) }}" class="img-fluid rounded-top mt-2" alt="User Image" />
                    @error('image')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="is_active" class="form-label">Is Active</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                            {{ old('is_active', $user->is_active) ? 'checked' : '' }} />
                        <label class="form-check-label" for="is_active">Activate User</label>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary w-100" onclick="this.disabled=true; this.form.submit();">Submit</button>
                </div>
            </div>
        </div>
    </form>
@endsection
