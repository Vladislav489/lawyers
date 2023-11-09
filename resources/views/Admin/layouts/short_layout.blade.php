<!doctype html>
<html lang="{{ str_replace('_','-',app()->getLocale())}}">
<head>
    @include('Admin.layouts.style')
    @stack('css-style')
    @include('Admin.layouts.script')
    <script>
        pageAdmin__ = new Page();
    </script>
    @stack('js-lib-component-head')
</head>
<body>
@stack('content')
@stack('js-bottom')
</body>
</html>
