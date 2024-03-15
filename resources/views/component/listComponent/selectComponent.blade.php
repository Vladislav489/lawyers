@php
    if(!isset($includeToComponent__))$includeToComponent__ = false;
    if( $includeToComponent__  == true) $name = $clear_name;
    $includeFromHeadToDown = (isset($includeFromHeadToDown) && $includeFromHeadToDown == "true" )?true:false;
    $stackNameScript = (isset($includeFromHeadToDown) && $includeFromHeadToDown)? 'js-lib-component-head':'js-lib-component';
    $include =(isset($include))?$include:false;
    $textComponet = (isset($admin))?"Add component Select double click for set setting":"";
@endphp
@pushOnce('css-style')
    <link type="text/css"  rel="stylesheet" href="/js/component/selectComponent/select.css">
@endpushOnce
@pushOnce($stackNameScript)
{{--    <script src="/js/component/parentComponent.js"></script>--}}
    <script src="/js/component/selectComponent/select.js"></script>
@endPushOnce
@if(!$include)
    @php
        if(strpos($name,'view_component_') !== false)$name = uniqid(str_replace('view_component_','',$name));
    @endphp
<component id="component_selectComponent_{{$name}}" data-name="{{$name}}">
    <div id="component_{{$name}}" class="select">{{$textComponet}}</div>
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
                'autostart':{{isset($autostart)?$autostart:'false'}},
                'url':'{{isset($url)?$url:'undefined'}}',
                'default_title': "{{isset($default_title)?$default_title:'Не выбрано'}}",
                'clear_name':'{{isset($clear_name)? $clear_name:(isset($name)? $name:'noName')}}',
                'data':@php echo isset($data)?$data:'null'; @endphp,
                'globalData':@php echo isset($globalData)?"'".$globalData."'":'false'; @endphp,
                'name':'{{isset($name)?$name:'noName'}}',
                'template':@php echo isset($template)?str_replace("\/","/",json_encode($template)):'undefined';@endphp,
                'params':@php echo isset($urlparams)?json_encode($urlparams):'undefined'; @endphp,
                'change':@php echo isset($change)?preg_replace('/\r|\r|/u', "", $change):'undefined';@endphp,
                'focus':@php echo isset($focus)?preg_replace('/\r|\r|/u', "", $focus):"null";@endphp,
                'select':@php echo isset($select)?preg_replace('/\r|\r|/u', "", $select):"null";@endphp,
                'callBeforloadComponent':@php echo isset($callBeforloadComponent)?preg_replace('/\r|\r|/u', "", $callBeforloadComponent):"null";@endphp,
                'callAfterloadComponent':@php echo isset($callAfterloadComponent)?preg_replace('/\r|\r|/u', "", $callAfterloadComponent):"null";@endphp,
                'callAjaxSuccess':@php echo isset($callAjaxSuccess)?preg_replace('/\r|\r|/u', "", $callAjaxSuccess):"null";@endphp,
            }
            var component_{{$name}} = new selectComponent('#component_{{$name}}', params_{{$name}})
            component_{{$name}}.startWidget()
            page__.addNewElement(component_{{$name}}, 'component_{{$name}}')
            @if($includeToComponent__ != true )
                @if(!$includeFromHeadToDown)
                    });
                @endif
            @endif
    </script>
    @if($includeToComponent__ != true )
        @if(!$includeFromHeadToDown)
            @endpush
        @endif
    @endif
@endif
