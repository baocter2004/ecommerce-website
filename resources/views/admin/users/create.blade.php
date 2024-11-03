@extends('admin.layouts.master')

@section('title')
    Thêm Mới User
@endsection

@section('content')
    <form action="{{ route('admin.users.store') }}" method="post" enctype="multipart/form-data">

    </form>
@endsection
