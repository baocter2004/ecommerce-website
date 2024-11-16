@extends('client.layouts.master')

@section('content')
    <div class="site-blocks-cover" style="background-image: url(/client/images/hero_1.jpg);" data-aos="fade">
        <div class="container">
            <div class="row align-items-start align-items-md-center justify-content-end">
                <div class="col-md-5 text-center text-md-left pt-5 pt-md-0">
                    <h1 class="mb-2">Finding Your Perfect Moda</h1>
                    <div class="intro-text text-center text-md-left">
                        <p class="mb-4">Khám phá bộ sưu tập thời trang mới nhất. Chúng tôi mang đến những bộ quần áo chất
                            lượng với thiết kế hiện đại, phù hợp với mọi phong cách và mùa.</p>
                        <p>
                            <a href="{{ route('client.shop') }}" class="btn btn-sm btn-primary">Mua Sắm Ngay</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="site-section site-section-sm site-blocks-1">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-lg-4 d-lg-flex mb-4 mb-lg-0 pl-4" data-aos="fade-up" data-aos-delay="">
                    <div class="icon mr-4 align-self-start">
                        <span class="icon-truck"></span>
                    </div>
                    <div class="text">
                        <h2 class="text-uppercase">Miễn Phí Vận Chuyển</h2>
                        <p>Chúng tôi cung cấp dịch vụ vận chuyển miễn phí cho tất cả các đơn hàng. Không cần lo lắng về phí
                            vận chuyển khi mua sắm tại cửa hàng của chúng tôi.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 d-lg-flex mb-4 mb-lg-0 pl-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="icon mr-4 align-self-start">
                        <span class="icon-refresh2"></span>
                    </div>
                    <div class="text">
                        <h2 class="text-uppercase">Miễn Phí Đổi Trả</h2>
                        <p>Mua sắm an tâm với chính sách đổi trả miễn phí trong 30 ngày. Chúng tôi luôn cam kết mang lại sự
                            hài lòng cho khách hàng.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 d-lg-flex mb-4 mb-lg-0 pl-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="icon mr-4 align-self-start">
                        <span class="icon-help"></span>
                    </div>
                    <div class="text">
                        <h2 class="text-uppercase">Hỗ Trợ Khách Hàng</h2>
                        <p>Đội ngũ chăm sóc khách hàng của chúng tôi luôn sẵn sàng hỗ trợ bạn bất kỳ lúc nào. Liên hệ với
                            chúng tôi để giải đáp mọi thắc mắc về sản phẩm và dịch vụ.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="site-section site-blocks-2">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-md-6 col-lg-4 mb-4 mb-lg-0" data-aos="fade" data-aos-delay="">
                    <a class="block-2-item" href="#">
                        <figure class="image">
                            <img src="/client/images/women.jpg" alt="" class="img-fluid">
                        </figure>
                        <div class="text">
                            <span class="text-uppercase">Collections</span>
                            <h3>Women</h3>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-4 mb-5 mb-lg-0" data-aos="fade" data-aos-delay="100">
                    <a class="block-2-item" href="#">
                        <figure class="image">
                            <img src="/client/images/children.jpg" alt="" class="img-fluid">
                        </figure>
                        <div class="text">
                            <span class="text-uppercase">Collections</span>
                            <h3>Children</h3>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-4 mb-5 mb-lg-0" data-aos="fade" data-aos-delay="200">
                    <a class="block-2-item" href="#">
                        <figure class="image">
                            <img src="/client/images/men.jpg" alt="" class="img-fluid">
                        </figure>
                        <div class="text">
                            <span class="text-uppercase">Collections</span>
                            <h3>Men</h3>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    @include('client.layouts.components.new-products', ['products' => $products]);

    @include('client.layouts.components.featured-product', ['featured_products' => $featured_products]);

    <div class="site-section block-8">
        <div class="container">
            <div class="row justify-content-center  mb-5">
                <div class="col-md-7 site-section-heading text-center pt-4">
                    <h2>Big Sale!</h2>
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-md-12 col-lg-7 mb-5">
                    <a href="#"><img src="/client/images/blog_1.jpg" alt="Image placeholder"
                            class="img-fluid rounded"></a>
                </div>
                <div class="col-md-12 col-lg-5 text-center pl-md-5">
                    <h2><a href="#">50% less in all items</a></h2>
                    <p class="post-meta mb-4">By <a href="#">Carl Smith</a> <span
                            class="block-8-sep">&bullet;</span>
                        September
                        3, 2018</p>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quisquam iste dolor accusantium
                        facere corporis
                        ipsum animi deleniti fugiat. Ex, veniam?</p>
                    <p><a href="#" class="btn btn-primary btn-sm">Shop Now</a></p>
                </div>
            </div>
        </div>
    </div>
@endsection
