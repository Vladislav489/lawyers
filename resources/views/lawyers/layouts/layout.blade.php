<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="fragment" content="!">

    @include('lawyers.layouts.style')
    @stack('css-style')

    <script src="/js/jquery/jquery-3.6.0.min.js"></script>
    <script src="/js/vue/vue.min.js"></script>
    <script src="/js/main.js"></script>

    @stack('js-lib-component-head')
    @include('lawyers.layouts.script')

    <link rel="canonical" href="{{ request()->getScheme() . '://' . request()->httpHost() .request()->getPathInfo() }}">
</head>
<body>

    <header class="header">
        @include('lawyers.layouts.header')
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="footer">
        @include('lawyers.layouts.footer')
    </footer>

    @stack('js-lib-component')
    @stack('component-js')
    @stack('component-load-js')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
