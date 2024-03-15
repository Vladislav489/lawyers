<!doctype html>
<html lang="{{ str_replace('_','-',app()->getLocale())}}">
<head>
    <title>Fotrcast</title>
    <meta name="csrf-token" content="{{csrf_token()}}">
    @include('Admin.layouts.style')
    @stack('css-style')
    @include('Admin.layouts.script')
    <!--js-lib-component-head-start-->
    @pushOnce('js-lib-component-head')
    <script src="/js/component/menuComponent/menu.js"></script>
        <script src="/js/component/parentComponent.js"></script>
    @endPushOnce

    @stack('js-lib-component-head')
    <!--js-lib-component-head-end-->
</head>
<body>
    @include('Admin.layouts.header')
    <div style="display: flex;height:94vh">
        <div class="left-bar" style="">
            @include('Admin.layouts.admin_menu')
        </div>
        <div class="admin_conten" style="flex:85%;padding-left:10px;padding-right:10px">@stack('content')</div>
    </div>
</body>
<!--js-lib-component-start-->
@stack('js-lib-component')
<!--js-lib-component-end-->

@stack('component-js')
</html>
