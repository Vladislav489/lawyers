<!doctype html>
<html lang="{{ str_replace('_','-',app()->getLocale())}}">
<!--head-start-->
<head>
    <!--meta-start-->

    <meta name="csrf-token" content="{{csrf_token()}}">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="fragment" content="!">
    <!--meta-end-->
    <!--style-start-->
    @include('lawyers.layouts.style')
    <link rel="canonical" href="@php  echo  request()->getScheme()."://".request()->httpHost().request()->getPathInfo()@endphp">
    @stack('css-style')
    <script type="text/javascript" src="/js/jquery/jquery-3.6.0.min.js"></script>
    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>-->
    <script  type="text/javascript" src="/js/vue/vue.min.js"></script>
    <script  type="text/javascript" src="/js/main.js"></script>
    <!--style-end-->
    <!--js-lib-component-head-start-->
    @stack('js-lib-component-head')
    <!--js-lib-component-head-end-->
    <!--js-lib-start-->
    @include('lawyers.layouts.script')
    <!--js-lib-end-->

</head>
<!--head-end-->
<!--body-start-->
<body>
    <!--head-page-start-->
         <header class="header">@include('lawyers.layouts.header')</header>
    <!--head-page-end-->
    <!--breadcrumbs-start-->
    <!--breadcrumbs-end-->
    <!--body-page-start-->
    <main>
        @yield('content')
    </main>
    <!--body-page-end-->
    <!--footer-page-start-->
        <footer class="footer">@include('lawyers.layouts.footer')</footer>
    <!--footer-page-end-->
    <!--js-lib-component-start-->
    @stack('js-lib-component')
    <!--js-lib-component-end-->

    <!--js-code-component-start-->
    @stack('component-js')
    <!--js-code-component-end-->

    <!--js-code-component-load-start-->
    @stack('component-load-js')
    <!--js-code-component-load-end-->
</body>
<!--body-end-->
</html>
