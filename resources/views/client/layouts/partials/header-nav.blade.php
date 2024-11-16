<nav class="site-navigation text-right text-md-center" role="navigation">
    <div class="container">
        <ul class="site-menu js-clone-nav d-none d-md-block">
            <li class="has-children active">
                <a href="{{ route('client.index') }}">Home</a>
            </li>
            <li><a href="{{ route('client.shop') }}">Shop</a></li>
            <li class="has-children">
                <a href="{{ route('client.about') }}">About</a>
            </li>
            <li><a href="{{ route('client.contact') }}">Contact</a></li>
        </ul>
    </div>
</nav>
