<!--head-start-->
    <!--style-start-->
    @stack('css-style')
    <!--style-end-->
    <!--js-lib-component-head-start-->
    @stack('js-lib-component-head')
    <!--js-lib-component-head-end-->
<!--head-end-->
<!--body-start-->
<!--body-page-start-->
@stack('content')
<!--body-page-end-->

<!--js-lib-component-start-->
@stack('js-lib-component')
<!--js-lib-component-end-->

<!--js-code-component-start-->
@stack('component-js')
<!--js-code-component-end-->

<!--js-code-component-load-start-->
@stack('component-load-js')
<!--js-code-component-load-end-->
<!--body-end-->

