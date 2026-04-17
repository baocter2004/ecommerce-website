<nav class="navbar navbar-expand-sm navbar-default">
    <div id="main-menu" class="main-menu collapse navbar-collapse">
        <ul class="nav navbar-nav">
            <li class="active">
                <a href="{{ route('admin.dashboard') }}"><i class="menu-icon fa fa-laptop"></i> Dashboard </a>
            </li>
            <li class="menu-title">Management</li>
            <li class="menu-item-has-children dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false"> <i class="menu-icon fa fa-users"></i> Users</a>
                <ul class="sub-menu children dropdown-menu">
                    <li><i class="bi bi-list-ul"></i><a href="{{ route('admin.users.index') }}">List Users</a></li>
                    <li><i class="bi bi-person-plus"></i><a href="{{ route('admin.users.create') }}">Add User</a></li>
                    <li><i class="bi bi-trash"></i><a href="{{ route('admin.users.trash') }}">Trash</a></li>
                </ul>
            </li>
            <li class="menu-item-has-children dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false"> <i class="menu-icon fa fa-list"></i> Categories</a>
                <ul class="sub-menu children dropdown-menu">
                    <li><i class="bi bi-list-ul"></i><a href="{{ route('admin.categories.index') }}">List Categories</a></li>
                    <li><i class="bi bi-plus-circle"></i><a href="{{ route('admin.categories.create') }}">Add Category</a></li>
                    <li><i class="bi bi-trash"></i><a href="{{ route('admin.categories.trash') }}">Trash</a></li>
                </ul>
            </li>

            <li class="menu-item-has-children dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="menu-icon fa fa-shopping-bag"></i> Products
                </a>
                <ul class="sub-menu children dropdown-menu">
                    <li><i class="bi bi-list-ul"></i><a href="{{ route('admin.products.index') }}">List Products</a></li>
                    <li><i class="bi bi-plus-circle"></i><a href="{{ route('admin.products.create') }}">Add Product</a></li>
                    <li><i class="bi bi-trash"></i><a href="{{ route('admin.products.trash') }}">Trash</a></li>
                </ul>
            </li>
            <li>
                <a href="{{ route('admin.orders.index') }}">
                    <i class="menu-icon fa fa-shopping-cart"></i> Orders
                </a>
            </li>
        </ul>
    </div><!-- /.navbar-collapse -->
</nav>