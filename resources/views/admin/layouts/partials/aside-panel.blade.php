<nav class="navbar navbar-expand-sm navbar-default">
    <div id="main-menu" class="main-menu collapse navbar-collapse">
        <ul class="nav navbar-nav">
            <li class="active">
                <a href="index.html"><i class="menu-icon fa fa-laptop"></i>Dashboard </a>
            </li>
            <li class="menu-title">Admin - Pannel</li>
            <li class="menu-item-has-children dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false"> <i class="menu-icon fa fa-user"></i>users</a>
                <ul class="sub-menu children dropdown-menu">
                    <li><i class="bi bi-house"></i><a href="{{ route('admin.users.index') }}">Index</a></li>
                    <li><i class="bi bi-plus-circle"></i> <a href="{{ route('admin.users.create') }}">Create</a></li>
                    <li><i class="bi bi-trash"></i> <a href="{{ route('admin.users.trash') }}">Trash</a></li>
                </ul>
            </li>
            <li class="menu-item-has-children dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false"> <i class="menu-icon fa fa-list"></i>categories</a>
                <ul class="sub-menu children dropdown-menu">
                    <li><i class="bi bi-house"></i><a href="{{ route('admin.categories.index') }}">Index</a></li>
                    <li><i class="bi bi-plus-circle"></i> <a href="{{ route('admin.categories.create') }}">Create</a>
                    </li>
                    <li><i class="bi bi-trash"></i> <a href="{{ route('admin.categories.trash') }}">Trash</a></li>
                </ul>
            </li>

            <li class="menu-item-has-children dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false"> <i class="menu-icon fa fa-cogs"></i>products</a>
                <ul class="sub-menu children dropdown-menu">
                    <li><i class="bi bi-house"></i><a href="{{ route('admin.products.index') }}">Index</a></li>
                    <li><i class="bi bi-plus-circle"></i> <a href="{{ route('admin.products.create') }}">Create</a></li>
                    <li><i class="bi bi-trash"></i> <a href="{{ route('admin.products.trash') }}">Trash</a></li>
                </ul>
            </li>
        </ul>
    </div><!-- /.navbar-collapse -->
</nav>
