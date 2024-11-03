@extends('admin.layouts.master')

@section('title')
    Chỉnh Sửa Category : {{$category->name}}
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
    @if (session()->has('success'))
        <div class="alert alert-primary">
            <h1>Thao Tác Thành Công</h1>
        </div>
    @endif
    <form action="{{ route('admin.categories.update',$category->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mt-3 mb-3">
            <label for="name">Name</label>
            <input type="text" name="name" class="form-control form-control-sm" value="{{$category->name}}">
        </div>
        <div class="mb-3 row">
            <label for="check">is_active</label>
            <input type="checkbox" name="is_active" class="form-check" value="1">
        </div>
        <div class="mt-3 mb-3">
            <button type="submit" class="btn btn-primary">Chỉnh Sửa</button>
        </div>
    </form>
@endsection
