@php
    if(!isset($includeToComponent__)) $includeToComponent__ = false;
    if( $includeToComponent__  == true)$name = $clear_name;
    $includeFromHeadToDown = (isset($includeFromHeadToDown) && $includeFromHeadToDown = "true" )?true:false;
    $stackNameScript = (isset($includeFromHeadToDown) && $includeFromHeadToDown)? 'js-lib-component-head':'js-lib-component';
    $include =(isset($include))?$include:false;
    $textComponet = (isset($admin))?"Add component Text Info double click for set setting":"Loading";
@endphp
@pushOnce('css-style')
@endpushOnce
@pushOnce($stackNameScript)
    <script src="/js/component/infoComponent/textInfo.js"></script>
@endPushOnce
@if(!$include)
    @php
        if(strpos($name,'view_component_') !== false) $name = uniqid(str_replace('view_component_','',$name));
    @endphp
<component id="component_textInfo_{{$name}}" data-name="{{$name}}">
    <div id="component_{{$name}}" class="text_title">{{$textComponet}}</div>
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
                'autostart':'{{isset($autostart)?$autostart:'false'}}',
                'url':'{{isset($url)?$url:'undefined'}}',
                'name':'{{isset($name)?$name:'NoName'}}',
                'data':@php echo isset($data)?$data:'null'; @endphp,
                'globalData':@php echo isset($globalData)?"'".$globalData."'":'false'; @endphp,
                'template':@php echo isset($template)?str_replace("\/","/",json_encode($template)):'undefined';@endphp,
                'params':@php echo isset($urlparams)?json_encode($urlparams):'undefined';@endphp
            };
            var obj_{{$name}}  = new textInfo('#component_{{$name}}', params_{{$name}})
            page__.addNewElement(obj_{{$name}}, 'component_{{$name}}')
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
