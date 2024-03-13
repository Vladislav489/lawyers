@php
    if(!isset($includeToComponent__)) $includeToComponent__ = false;
    if( $includeToComponent__  == true) $name = $clear_name;
    $includeFromHeadToDown = (isset($includeFromHeadToDown) && $includeFromHeadToDown == "true" )?true:false;
    $stackNameScript = (isset($includeFromHeadToDown) && $includeFromHeadToDown)? 'js-lib-component-head':'js-lib-component';
    $listStyle = ['1'=>'menuVDrop.css','2'=>'menuGSDrop.css','3'=>'menuVSDrop.css','4'=>'menurectangl.css'];
    if(!isset($style)) $style ='1';
    $include = (isset($include))?$include:false;
    $textComponet = (isset($admin))?"Add component Menu double click for set setting":"";
@endphp
@pushOnce('css-style')
    <link type="text/css"  rel="stylesheet" href="/js/component/menuComponent/{{$listStyle[$style]}}">
@endpushOnce
@pushOnce($stackNameScript)
<script src="/js/component/menuComponent/menu.js"></script>
@endPushOnce

@if(!$include)
    <component id="component_menu_{{$name}}" data-name="{{$name}}">
    <div id="component_{{$name}}" class="menu"><span>{{$textComponet}}</span></div>
</component>
    @if($includeToComponent__ != true)
        @if(!$includeFromHeadToDown)
            @push('component-js')
        @endif
    @endif
    <script date-id_script="{{$name}}">
        @if($includeToComponent__ != true )
            @if(!$includeFromHeadToDown)
                    $(document).ready(function() {
            @endif
        @endif
            var params_{{$name}} = {
                'name':'{{isset($name)?$name:"noName"}}',
                'autostart':{{isset($autostart)? $autostart:'false'}},
                'url':'{{isset($url)?$url:""}}',
                'template':@php echo isset($template)?str_replace("\/","/",json_encode($template)):'undefined';@endphp,
                'templateItem':@php echo isset($templateItem)?json_encode($templateItem):'undefined';@endphp,
                'data':@php echo isset($data)? $data:'null'; @endphp,
                'globalData':@php echo isset($globalData)?"'".$globalData."'":'false'; @endphp,
                'params':@php echo isset($params)?json_encode($params):"undefined"; @endphp,
                'style':'{{isset($style)?$style:'undefined'}}',
                'callBeforloadComponent':@php echo isset($callBeforloadComponent)?preg_replace('/\r|\r|/u', "", $callBeforloadComponent):"null";@endphp,
                'callAfterloadComponent':@php echo isset($callAfterloadComponent)?preg_replace('/\r|\r|/u', "", $callAfterloadComponent):"null";@endphp,
                'callAjaxSuccess':@php echo isset($callAjaxSuccess)?preg_replace('/\r|\r|/u', "", $callAjaxSuccess):"null";@endphp,
            }
            var component_{{$name}} = new Menu('#component_{{$name}}', params_{{$name}});
            page__.addNewElement(component_{{$name}}, 'component_{{$name}}')
        @if($includeToComponent__ != true )
            @if(!$includeFromHeadToDown)
                    });
            @endif
        @endif
    </script>
    @if($includeToComponent__ != true)
        @if(!$includeFromHeadToDown)
            @endpush
        @endif
    @endif
@endif
