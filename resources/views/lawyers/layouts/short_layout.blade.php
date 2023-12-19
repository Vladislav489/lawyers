<!doctype html>
<html lang="{{ str_replace('_','-',app()->getLocale())}}">
<head>
@include('Site.Forecast.layouts.style')
@stack('css-style')
        <script type="text/javascript" src="/js/jquery/jquery-3.6.0.min.js"></script>
        <script  type="text/javascript" src="/js/main.js"></script>
@include('Site.Forecast.layouts.script')
@stack('js-main')
@stack('js-lib-component-head')

</head>
<body>
@stack('content')
@stack('js-bottom')
</body>
</html>
