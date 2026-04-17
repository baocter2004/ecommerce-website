<!-- Navbar Top Section -->
<div class="site-navbar-top">
    <div class="container">
        <div class="row align-items-center">

            <!-- Search Bar (Left) -->
            <div class="col-12 col-md-4 d-flex justify-content-center align-items-center mb-3 mb-md-0">
                <form action="{{ route('client.search') }}" method="GET" class="d-flex w-100 p-2 bg-light rounded shadow-sm">
                    <input type="text" name="keyword" class="form-control border-0 rounded-start search-input" placeholder="Tìm kiếm sản phẩm..." aria-label="Tìm kiếm" value="{{ request('keyword') }}">
                    <button class="btn btn-primary rounded-end search-button" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </div>

            <!-- Logo (Center) -->
            <div class="col-12 col-md-4 text-center mb-3 mb-md-0">
                <div class="site-logo">
                    <a href="{{ route('client.index') }}" class="js-logo-clone text-dark fs-3 fw-bold">Shoppers</a>
                </div>
            </div>

            <!-- Icons (Right) -->
            <div class="col-12 col-md-4 text-center text-md-end">
                <div class="site-top-icons">
                    <ul class="d-flex justify-content-center justify-content-md-end gap-4 align-items-center mb-0">
                        <li class="nav-item dropdown list-unstyled" style="position: relative;">
                            @if (Auth::check())
                                <a class="nav-link dropdown-toggle text-dark p-0" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-expanded="false" data-display="static" style="line-height: 1;">
                                    <i class="bi bi-person h4"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-right shadow border-0" aria-labelledby="userDropdown" style="right: 0 !important; left: auto !important; min-width: 200px; margin-top: 10px; transform: none !important;">
                                    <li class="dropdown-item-text text-center border-bottom pb-2 mb-2">
                                        <small class="text-muted">Xin chào,</small><br>
                                        <strong class="text-primary">{{ Str::limit(Auth::user()->name, 16) }}</strong>
                                    </li>
                                    @if (Auth::user()->isAdmin())
                                        <li><a href="{{ route('admin.dashboard') }}" class="dropdown-item py-2"><i class="bi bi-speedometer2 mr-2"></i>Admin panel</a></li>
                                    @endif
                                    <li>
                                        <form action="{{ route('logout') }}" method="post" class="mb-0">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger py-2"><i class="bi bi-box-arrow-right mr-2"></i>Đăng xuất</button>
                                        </form>
                                    </li>
                                </ul>
                            @else
                                <a class="nav-link dropdown-toggle text-dark p-0" href="#" id="loginDropdown" role="button" data-toggle="dropdown" aria-expanded="false" data-display="static" style="line-height: 1;">
                                    <i class="bi bi-person h4"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-right shadow border-0" aria-labelledby="loginDropdown" style="right: 0 !important; left: auto !important; min-width: 160px; margin-top: 10px; transform: none !important;">
                                    <li><a href="{{ route('login') }}" class="dropdown-item py-2"><i class="bi bi-box-arrow-in-right mr-2"></i>Đăng nhập</a></li>
                                    <li><a href="{{ route('register') }}" class="dropdown-item py-2"><i class="bi bi-person-plus mr-2"></i>Đăng ký</a></li>
                                </ul>
                            @endif
                        </li>

                        <!-- Wishlist Icon -->
                        <li class="list-unstyled">
                            <a href="{{ route('client.wishlist') }}" class="position-relative text-dark">
                                <i class="bi bi-heart h4"></i>
                                @auth
                                    @php
                                        $wishlistCount = \App\Models\Favorite::where('user_id', Auth::id())->count();
                                    @endphp
                                    <span class="badge bg-danger position-absolute top-0 start-100 translate-middle rounded-pill {{ $wishlistCount > 0 ? '' : 'd-none' }}" style="font-size: 0.6rem;" id="wishlist-count">
                                        {{ $wishlistCount }}
                                    </span>
                                @endauth
                            </a>
                        </li>

                        <!-- Cart Icon -->
                        <li class="list-unstyled">
                            <a href="{{ route('client.cart') }}" class="site-cart text-dark position-relative">
                                <i class="bi bi-cart3 h4"></i>
                                @php
                                    $cart = \App\Models\Cart::where(Auth::check() ? 'user_id' : 'session_id', Auth::check() ? Auth::id() : session()->getId())->first();
                                    $cartCount = $cart ? $cart->items->sum('quantity') : 0;
                                @endphp
                                <span class="count">{{ $cartCount }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Mobile Menu Toggle -->
            <div class="col-12 d-md-none text-end">
                <a href="#" class="site-menu-toggle js-menu-toggle">
                    <span class="icon-menu"></span>
                </a>
            </div>

        </div>
    </div>
</div>
