@extends('admin.layouts.master')

@section('title')
    Thêm Mới User
@endsection

@section('content')
    <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6 mt-3 mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name"
                    placeholder="Enter name" value="{{ old('name') }}" />
                @error('name')
                    <div class="alert alert-danger mt-2">
                        <p class="text-red">{{ $message }}</p>
                    </div>
                @enderror
            </div>
            <div class="col-md-6 mt-3 mb-3">
                <label for="user_name" class="form-label">User Name</label>
                <input type="text" class="form-control  @error('user_name') is-invalid @enderror" name="user_name"
                    id="user_name" placeholder="Enter username" value="{{ old('user_name') }}">
                @error('user_name')
                    <div class="alert alert-danger mt-2">
                        <p class="text-red">{{ $message }}</p>
                    </div>
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mt-3 mb-3">
                <label for="email" class="form-label">email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                    id="email" placeholder="Enter User email" value="{{ old('email') }}" />
                @error('email')
                    <div class="alert alert-danger mt-2">
                        <p class="text-red">{{ $message }}</p>
                    </div>
                @enderror
            </div>

            <div class="col-md-6 mt-3 mb-3">
                <label for="role" class="form-label">Role</label>
                <select name="role" id="role" class="form-control">
                    <option value="">-- Select Role --</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role }}" {{ old('role') }}>
                            {{ $role }}
                        </option>
                    @endforeach
                </select>
                @error('role')
                    <div class="alert alert-danger mt-2">
                        <p class="text-red">{{ $message }}</p>
                    </div>
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 mt-3 mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password"
                    id="password" placeholder="Enter User password" value="{{ old('password') }}" />
                @error('password')
                    <div class="alert alert-danger mt-2">
                        <p class="text-red">{{ $message }}</p>
                    </div>
                @enderror
            </div>

            <div class="col-md-12 mt-3 mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                    name="password_confirmation" id="password_confirmation" placeholder="Enter User password Again"
                    value="{{ old('password_confirmation') }}" />
                @error('password_confirmation')
                    <div class="alert alert-danger mt-2">
                        <p class="text-red">{{ $message }}</p>
                    </div>
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mt-3 mb-3">
                <label for="phone" class="form-label">phone</label>
                <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone"
                    id="phone" placeholder="Enter User phone" value="{{ old('phone') }}" />
                @error('phone')
                    <div class="alert alert-danger mt-2">
                        <p class="text-red">{{ $message }}</p>
                    </div>
                @enderror
            </div>

            <div class="col-md-6 mt-3 mb-3">
                <label for="image" class="form-label">User Image</label>
                <input type="file" class="form-control @error('image') is-invalid @enderror" name="image"
                    id="image" />
                @error('image')
                    <div class="alert alert-danger mt-2">
                        <p class="text-red">{{ $message }}</p>
                    </div>
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mt-3 mb-3">
                <label for="is_active" class="form-label">Is Active</label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                        {{ old('is_active') ? 'checked' : '' }} />
                    <label class="form-check-label" for="is_active">Activate Product</label>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 mt-3 mb-3">
                <button type="submit" class="btn btn-primary" onclick="this.disabled=true; this.form.submit();">Submit</button>
            </div>
        </div>
    </form>
@endsection
