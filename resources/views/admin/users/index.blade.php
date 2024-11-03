@extends('admin.layouts.master')

@section('title')
    Danh Sách Users
@endsection

@section('content')
<a href="{{route('admin.users.create')}}" class="mt-3 mb-3 btn btn-primary">Create</a>
    <div class="table-responsive">
        <table class="table table-striped table-hover table-borderless table-primary align-middle">
            <thead class="table-light">
                <caption>
                    Danh Sách Users
                </caption>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>User Name</th>
                    <th>Image</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th>Is Active</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                @foreach ($users as $user)
                    <tr class="table-primary">
                        <td scope="row">{{ $user->id }}</td>
                        <td scope="row">{{ $user->name }}</td>
                        <td scope="row">{{ $user->email }}</td>
                        <td scope="row">{{ $user->user_name }}</td>
                        <td scope="row">
                            <img src="{{ Storage::url($user->image) }}" class="img-fluid rounded-top" alt="" />
                        </td>
                        <td scope="row">{{ $user->phone }}</td>
                        <td scope="row">
                            @if ($user->role === 'admin')
                                <span class="badge bg-primary">ADMIN</span>
                            @else
                                <span class="badge bg-warning">MEMBER</span>
                            @endif
                        </td>
                        <td scope="row">
                            @if ($user->is_active === 1)
                                <span class="badge bg-primary">Yes</span>
                            @else
                                <span class="badge bg-danger">No</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('Y/m/d') }}</td>
                        <td>{{ $user->updated_at->format('Y/m/d') }}</td>
                        <td scope="row">
                            <a class="btn btn-info" href="{{ route('admin.users.show', $user) }}">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a class="btn btn-info mt-3 mb-3" href="{{ route('admin.users.edit', $user) }}">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('admin.users.destroy', $user) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Do You Want Delete it ?')" type="submit"
                                    class="btn btn-danger"> <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                {{ $users->links() }}
            </tfoot>
        </table>
    </div>
@endsection
