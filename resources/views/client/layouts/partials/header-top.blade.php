<!-- Navbar Top Section -->
<div class="site-navbar-top">
    <div class="container">
        <div class="row align-items-center">

            <!-- Search Bar (Left) -->
            <div class="col-12 col-md-4 d-flex justify-content-center align-items-center mb-3 mb-md-0">
                <form action="{{ route('client.search') }}" method="GET" class="d-flex w-100 p-2 bg-light rounded shadow-sm">
                    <input type="text" name="searchKey" class="form-control border-0 rounded-start search-input" placeholder="Tìm kiếm sản phẩm..." aria-label="Tìm kiếm">
                    <button class="btn btn-secondary rounded-end search-button" type="submit">
                        <span class="icon icon-search2"></span>
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
                    <ul class="d-flex justify-content-center justify-content-md-end align-items-center">
                        <!-- User Icon -->
                        <li class="me-3 nav-item dropdown">
                            @if (Auth::check())
                                <a class="nav-link dropdown-toggle text-dark" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="icon icon-person"></span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <li class="text-center">
                                        <strong>XIN CHÀO, {{ Str::limit(Auth::user()->name, 16) }}</strong>
                                    </li>
                                    <li><a href="#" class="dropdown-item">profile</a></li>
                                    @if (Auth::user()->isAdmin())
                                        <li><a href="#" class="dropdown-item">Admin panel</a></li>
                                    @endif
                                    <li>
                                        <form action="{{ route('logout') }}" method="post" class="mb-0">
                                            @csrf
                                            <button type="submit" class="dropdown-item">Đăng xuất</button>
                                        </form>
                                    </li>
                                </ul>
                            @else
                                <a class="nav-link dropdown-toggle text-dark" href="#" id="loginDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="icon icon-person"></span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="loginDropdown">
                                    <li><a href="{{ route('login') }}" class="dropdown-item">Đăng nhập</a></li>
                                    <li><a href="{{ route('register') }}" class="dropdown-item">Đăng ký</a></li>
                                </ul>
                            @endif
                        </li>

                        <!-- Wishlist Icon -->
                        <li class="me-3">
                            <a href="{{ route('client.wishlist') }}" class="position-relative">
                                <span class="icon icon-heart-o"></span>
                                @auth
                                    @php
                                        $wishlistCount = \App\Models\Favorite::where('user_id', Auth::id())->count();
                                    @endphp
                                    @if($wishlistCount > 0)
                                        <span class="badge bg-danger position-absolute top-0 start-100 translate-middle rounded-pill" style="font-size: 0.6rem;" id="wishlist-count">
                                            {{ $wishlistCount }}
                                        </span>
                                    @endif
                                @endauth
                            </a>
                        </li>

                        <!-- Cart Icon -->
                        <li>
                            <a href="{{ route('client.cart') }}" class="site-cart">
                                <span class="icon icon-shopping_cart"></span>
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
