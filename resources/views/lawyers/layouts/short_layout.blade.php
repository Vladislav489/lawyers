<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('lawyers.layouts.style')
    @stack('css-style')

    <script src="/js/jquery/jquery-3.6.0.min.js"></script>
    <script src="/js/main.js"></script>

    @include('lawyers.layouts.script')
    @stack('js-main')
    @stack('js-lib-component-head')
</head>
<body>

    @stack('content')
    @stack('js-bottom')

</body>
</html>
