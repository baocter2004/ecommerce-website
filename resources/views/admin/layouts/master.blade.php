<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin - @yield('title')</title>
    @include('admin.layouts.partials.css')
</head>

<body>
    <!-- Left Panel -->
    <aside id="left-panel" class="left-panel">
        @include('admin.layouts.partials.aside-panel')
    </aside>
    <!-- /#left-panel -->
    <!-- Right Panel -->
    <div id="right-panel" class="right-panel">
        <!-- Header-->
        <header id="header" class="header">

            @include('admin.layouts.partials.header.top-left-header')

            @include('admin.layouts.partials.header.top-right-header')

        </header>
        <!-- /#header -->
        <!-- Content -->
        <div class="content">
            <!-- Animated -->
            <div class="animated fadeIn">

                <h1 class="text-center mt-3 mb-3">@yield('title')</h1>

                @yield('content')

            </div>
            <!-- .animated -->
        </div>
        <!-- /.content -->
        <div class="clearfix"></div>
        <!-- Footer -->
        <footer class="site-footer">
            @include('admin.layouts.partials.footer')
        </footer>
        <!-- /.site-footer -->
    </div>
    <!-- /#right-panel -->
    @include('admin.layouts.partials.js')
</body>

</html>
