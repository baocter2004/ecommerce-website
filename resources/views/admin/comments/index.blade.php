@extends('admin.layouts.master')

@section('title')
    Quản lý bình luận
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4">
                    <i class="fa fa-check-circle mr-2"></i> Thao tác thành công!
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="m-0 font-weight-bold text-primary"><i class="fa fa-comments mr-2"></i> Tất cả bình luận</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-dark">
                                <tr>
                                    <th class="border-0 pl-4">ID</th>
                                    <th class="border-0">Người gửi</th>
                                    <th class="border-0">Sản phẩm</th>
                                    <th class="border-0">Đánh giá</th>
                                    <th class="border-0">Nội dung</th>
                                    <th class="border-0">Ngày gửi</th>
                                    <th class="border-0 text-center pr-4">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($comments as $comment)
                                    <tr>
                                        <td class="pl-4 font-weight-bold">#{{ $comment->id }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($comment->user->image)
                                                    <img src="{{ Storage::url($comment->user->image) }}" class="rounded-circle mr-2 shadow-sm" width="35" height="35" style="object-fit: cover;">
                                                @else
                                                    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mr-2 shadow-sm" style="width: 35px; height: 35px;">
                                                        {{ substr($comment->user->name, 0, 1) }}
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="font-weight-bold text-dark">{{ $comment->user->name }}</div>
                                                    <small class="text-muted">{{ $comment->user->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.products.show', $comment->product->id) }}" class="text-decoration-none text-primary font-weight-bold">
                                                {{ Str::limit($comment->product->product_name, 30) }}
                                            </a>
                                        </td>
                                        <td>
                                            <div class="text-warning">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <i class="fa fa-star{{ $i <= $comment->rating ? '' : '-o' }}"></i>
                                                @endfor
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-muted small border-left pl-2 py-1" style="max-width: 300px;">
                                                {{ $comment->content }}
                                            </div>
                                        </td>
                                        <td>{{ $comment->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="text-center pr-4">
                                            <form action="{{ route('admin.comments.destroy', $comment->id) }}" method="POST" 
                                                  onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3 shadow-sm">
                                                    <i class="fa fa-trashmr-1"></i> Xóa
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="fa fa-comments fa-3x mb-3"></i>
                                                <p>Chưa có bình luận nào.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($comments->hasPages())
                    <div class="card-footer bg-white border-0 py-3">
                        <div class="d-flex justify-content-center">
                            {{ $comments->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
