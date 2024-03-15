@php
    if(!isset($includeToComponent__)) $includeToComponent__ = false;
    if( $includeToComponent__  == true) $name = $clear_name;
    $includeFromHeadToDown = (isset($includeFromHeadToDown) && $includeFromHeadToDown == "true" )?true:false;
    $stackNameScript = (isset($includeFromHeadToDown) && $includeFromHeadToDown)? 'js-lib-component-head':'js-lib-component';
    if(!isset($style)) $style ='1';
    $include =(isset($include))?$include:false;
    $textComponet = (isset($admin))?"Add component Menu double click for set setting":"";
@endphp
@pushOnce('js-lib-component')
<script src="/js/component/breadcrumbsComponent/breadcrumbsSimple.js"></script>
@endPushOnce

@if(!@$include)
{{--    @php--}}
{{--        if(strpos($name,'view_component_') !== false){--}}
{{--             $name = uniqid(str_replace('view_component_','',$name));--}}
{{--        }--}}
{{--    @endphp--}}
<component id='component_breadcrumbsSimple_{{$name}}' data-name='{{$name}}'>
    <div id='component_{{$name}}' class='breadcrumbs_bode'>{{$textComponet}}</div>
</component>
        @if($includeToComponent__ != true )
            @push('component-js')
        @endif
        <script date-id_script="{{$name}}">
            @if($includeToComponent__ != true )
            $(document).ready(function() {
            @endif
                var params_{{$name}} = {
                    'data':@php echo isset($data)?$data:'null'; @endphp,
                    'globalData':@php echo isset($globalData)?"'".$globalData."'":'false'; @endphp,
                    'name': "{{isset($name)?$name:'noName'}}",
                    'url': "{{isset($url)?$url:'undefined'}}",
                    'list_routs':@php echo isset($list_routs)?json_encode($list_routs):'undefined'; @endphp,
                    'template':@php echo isset($template)?str_replace("\/","/",json_encode($template)):'undefined';@endphp,
                    'params':@php echo isset($params)?json_encode($params):'undefined'; @endphp,
                    'globalParams':{{isset($globalParams)?$globalParams:'false'}},
                    'callBeforloadComponent':@php echo isset($callBeforloadComponent)?preg_replace('/\r|\r|/u', "", $callBeforloadComponent):"null";@endphp,
                    'callAfterloadComponent':@php echo isset($callAfterloadComponent)?preg_replace('/\r|\r|/u', "", $callAfterloadComponent):"null";@endphp,
                    'callAjaxSuccess':@php echo isset($callAjaxSuccess)?preg_replace('/\r|\r|/u', "", $callAjaxSuccess):"null";@endphp,
                }
                var obj_{{$name}} = new BreadcrumbsSimple('#component_{{$name}}', params_{{$name}})
                page__.addNewElement(obj_{{$name}}, 'component_{{$name}}');
            @if($includeToComponent__ != true )
                });
            @endif
        </script>
        @if($includeToComponent__ != true )
            @endpush
        @endif
@endif
