<nav class="site-navbar-top bg-light shadow-sm fixed-top">
    <div class="container">
        <div class="row align-items-center">

            <div class="col-12 col-md-4 order-2 order-md-1 site-search-icon text-left position-relative">
                <form action="" class="site-block-top-search">
                    <div class="input-group">
                        <button class="btn btn-secondary" type="submit">
                            <span class="icon icon-search2"></span>
                        </button>
                        <input type="text" class="form-control" placeholder="Tìm kiếm sản phẩm..." aria-label="Search">
                    </div>
                </form>
            </div>

            <div class="col-12 mb-3 mb-md-0 col-md-4 order-1 order-md-2 text-center">
                <div class="site-logo">
                    <a href="{{ route('client.index') }}" class="js-logo-clone text-dark fs-3 fw-bold">Shoppers</a>
                </div>
            </div>

            <div class="col-12 col-md-4 order-3 text-end">
                <div class="site-top-icons">
                    <ul class="list-unstyled d-flex justify-content-end align-items-center mb-0">
                        <li class="me-3">
                            <a href="#" aria-label="Yêu thích" class="text-dark">
                                <span class="icon icon-heart-o"></span>
                            </a>
                        </li>

                        <li class="me-3 position-relative">
                            <a href="cart.html" class="text-dark" aria-label="Giỏ hàng">
                                <span class="icon icon-shopping_cart"></span>
                                <span
                                    class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle">2</span>
                            </a>
                        </li>

                        <li class="me-3 nav-item dropdown">
                            @if (Auth::check())
                                <a class="nav-link dropdown-toggle text-dark" href="#" id="userDropdown"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="icon icon-person"></span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <li class="text-center">
                                        <strong>XIN CHÀO, {{ Str::limit(Auth::user()->name, 16) }}</strong>
                                    </li>
                                    <li><a href="#" class="dropdown-item">Trang cá nhân</a></li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="post" class="mb-0">
                                            @csrf
                                            <button type="submit" class="dropdown-item">Đăng xuất</button>
                                        </form>
                                    </li>
                                </ul>
                            @else
                                <a class="nav-link dropdown-toggle text-dark" href="#" id="loginDropdown"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="icon icon-person"></span> Đăng nhập/Đăng ký
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="loginDropdown">
                                    <li><a href="{{ route('login') }}" class="dropdown-item">Đăng nhập</a></li>
                                    <li><a href="{{ route('register') }}" class="dropdown-item">Đăng ký</a></li>
                                </ul>
                            @endif
                        </li>

                        <li class="d-inline-block d-md-none">
                            <a href="#" class="site-menu-toggle js-menu-toggle">
                                <span class="icon icon-menu"></span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</nav>
<style>
    body {
        padding-top: 70px;
        /* Duy trì khoảng cách cho nội dung phía dưới thanh điều hướng cố định */
    }

    .site-navbar-top {
        transition: background-color 0.3s;
        /* Hiệu ứng chuyển màu nền */
    }
</style>
