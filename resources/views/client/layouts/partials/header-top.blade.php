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
                    <ul class="d-flex justify-content-center justify-content-md-end align-items-center mb-0">
                        <li class="nav-item dropdown list-unstyled mx-2" style="position: relative;">
                            @if (Auth::check())
                                <a class="text-dark p-0 d-inline-block" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-expanded="false" data-display="static" style="line-height: 1;">
                                    <i class="bi bi-person h4 mb-0"></i>
                                </a>
                                <div class="dropdown-menu shadow border-0" aria-labelledby="userDropdown" style="left: 50% !important; right: auto !important; transform: translateX(-50%) !important; min-width: 200px; margin-top: 10px;">
                                    <div class="p-3 text-center border-bottom mb-2">
                                        <small class="text-muted">Xin chào,</small><br>
                                        <strong class="text-primary">{{ Str::limit(Auth::user()->name, 20) }}</strong>
                                    </div>
                                    <a href="{{ route('client.profile.edit') }}" class="dropdown-item py-2"><i class="bi bi-person-badge mr-2"></i>Tài khoản của tôi</a>
                                    @if (Auth::user()->isAdmin())
                                        <a href="{{ route('admin.dashboard') }}" class="dropdown-item py-2"><i class="bi bi-speedometer2 mr-2"></i>Admin panel</a>
                                    @endif
                                    <form action="{{ route('logout') }}" method="post" class="mb-0">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger py-2 w-100 text-left"><i class="bi bi-box-arrow-right mr-2"></i>Đăng xuất</button>
                                    </form>
                                </div>
                            @else
                                <a class="text-dark p-0 d-inline-block" href="#" id="loginDropdown" role="button" data-toggle="dropdown" aria-expanded="false" data-display="static" style="line-height: 1;">
                                    <i class="bi bi-person h4 mb-0"></i>
                                </a>
                                <div class="dropdown-menu shadow border-0" aria-labelledby="loginDropdown" style="left: 50% !important; right: auto !important; transform: translateX(-50%) !important; min-width: 160px; margin-top: 10px;">
                                    <a href="{{ route('login') }}" class="dropdown-item py-2"><i class="bi bi-box-arrow-in-right mr-2"></i>Đăng nhập</a>
                                    <a href="{{ route('register') }}" class="dropdown-item py-2"><i class="bi bi-person-plus mr-2"></i>Đăng ký</a>
                                </div>
                            @endif
                        </li>

                        <!-- Wishlist Icon -->
                        <li class="list-unstyled mx-2">
                            <a href="{{ route('client.wishlist') }}" class="site-cart position-relative text-dark d-inline-block" style="line-height: 1;">
                                <i class="bi bi-heart h4 mb-0"></i>
                                @auth
                                    @php
                                        $wishlistCount = \App\Models\Favorite::where('user_id', Auth::id())->count();
                                    @endphp
                                    <span class="count {{ $wishlistCount > 0 ? '' : 'd-none' }}" id="wishlist-count">{{ $wishlistCount }}</span>
                                @endauth
                            </a>
                        </li>

                        <!-- Cart Icon -->
                        <li class="list-unstyled mx-2">
                            <a href="{{ route('client.cart') }}" class="site-cart text-dark position-relative d-inline-block" style="line-height: 1;">
                                <i class="bi bi-cart3 h4 mb-0"></i>
                                @php
                                    $cart = \App\Models\Cart::where(Auth::check() ? 'user_id' : 'session_id', Auth::check() ? Auth::id() : session()->getId())
                                        ->latest('updated_at')
                                        ->first();
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
