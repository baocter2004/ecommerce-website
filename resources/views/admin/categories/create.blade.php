@extends('admin.layouts.master')

@section('title')
    Thêm Mới Category
@endsection

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf
        <div class="mt-3 mb-3">
            <label for="name">Name</label>
            <input type="text" name="name" class="form-control form-control-sm" value="{{ old('name') }}">
        </div>
        <div class="mb-3">
            <label for="check">is_active</label>
            <input type="checkbox" name="is_active" class="form-check" value="1">
        </div>
        <div class="mt-3 mb-3">
            <button type="submit" class="btn btn-primary">Thêm Mới</button>
        </div>
    </form>
@endsection
