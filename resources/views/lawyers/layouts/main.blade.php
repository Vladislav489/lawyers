<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="fragment" content="!">
    <title>Lawyers | @yield('title')</title>
    @include('lawyers.layouts.style')
    @stack('css-style')

    @include('lawyers.layouts.script')
    @stack('js-lib-component-head')

    <link rel="canonical" href="{{ request()->getScheme() . '://' . request()->httpHost() .request()->getPathInfo() }}">

</head>
<body>

    <header class="u-container main-bg">
        @include('lawyers.layouts._main-header')
    </header>

    <main class="gradient-bg">
        @yield('content')
    </main>

    <footer class="footer">
        @include('lawyers.layouts._main-footer')
    </footer>

    @stack('js-lib-component')
    @stack('component-js')
    @stack('component-load-js')

</body>
</html>
