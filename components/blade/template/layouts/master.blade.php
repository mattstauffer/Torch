<html>
    <head>
        <title>App Name - @yield('title')</title>
    </head>
    <body>
        @section('sidebar')
            Это - главный сайдбар.
        @show

        <div class="container">
            @yield('content')
        </div>
    </body>
</html>
