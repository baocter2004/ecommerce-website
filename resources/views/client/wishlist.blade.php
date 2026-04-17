@extends('client.layouts.master')

@section('title')
    Danh sách yêu thích
@endsection

@section('content')
    <div class="bg-light py-3">
        <div class="container">
            <div class="row">
                <div class="col-md-12 mb-0">
                    <a href="{{ route('client.index') }}">Trang chủ</a> 
                    <span class="mx-2 mb-0">/</span> 
                    <strong class="text-black">Danh sách yêu thích</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="site-section">
        <div class="container">
            <div class="row mb-5">
                <div class="col-md-12">
                    <div class="site-blocks-table">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="product-thumbnail">Hình ảnh</th>
                                    <th class="product-name">Sản phẩm</th>
                                    <th class="product-price">Giá cơ bản</th>
                                    <th class="product-add-to-cart">Thao tác</th>
                                    <th class="product-remove">Xóa</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($favorites as $favorite)
                                    @php
                                        $firstVariant = null;
                                        if($favorite->product->variants->count() > 0) {
                                            $firstVariant = $favorite->product->variants->first()->options->first();
                                        }
                                    @endphp
                                    <tr>
                                        <td class="product-thumbnail">
                                            <a href="{{ route('client.shop-single', $favorite->product->id) }}">
                                                <img src="{{ Storage::url($favorite->product->product_image) }}" alt="Image" class="img-fluid" style="max-width: 100px;">
                                            </a>
                                        </td>
                                        <td class="product-name">
                                            <h2 class="h5 text-black">{{ $favorite->product->product_name }}</h2>
                                        </td>
                                        <td>{{ number_format($favorite->product->price, 0, ',', '.') }}đ</td>
                                        <td>
                                            @if($firstVariant)
                                                <form action="{{ route('client.cart.add', $favorite->product->id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="option_id" value="{{ $firstVariant->id }}">
                                                    <input type="hidden" name="quantity" value="1">
                                                    <button type="submit" class="btn btn-primary btn-sm">Thêm vào giỏ</button>
                                                </form>
                                            @else
                                                <a href="{{ route('client.shop-single', $favorite->product->id) }}" class="btn btn-primary btn-sm">Chọn loại</a>
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('client.wishlist.remove', $favorite->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-primary btn-sm" title="Xóa">X</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <p class="text-muted">Danh sách yêu thích của bạn đang trống.</p>
                                            <a href="{{ route('client.shop') }}" class="btn btn-primary btn-sm">Tiếp tục mua sắm</a>
                                        </td>
                                    </tr>
                                @endforelse
                                
                            </tbody>
                        </table>
                    </div>

                    <div class="row" data-aos="fade-up">
                        <div class="col-md-12 text-center">
                            <div class="site-block-27">
                                {{ $favorites->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
