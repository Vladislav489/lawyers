@php
    if(!isset($includeToComponent__))$includeToComponent__ = false;
    if( $includeToComponent__  == true) $name = $clear_name;
    $includeFromHeadToDown = (isset($includeFromHeadToDown) && $includeFromHeadToDown == "true" )?true:false;
    $stackNameScript = (isset($includeFromHeadToDown) && $includeFromHeadToDown)? 'js-lib-component-head':'js-lib-component';
    $include =(isset($include))?$include:false;
    $textComponet = (isset($admin))?"Add component SimpleGrid double click for set setting":"Loading";
@endphp
@pushOnce('css-style')
    <link type="text/css"  rel="stylesheet" href="/js/component/gridComponent/simpleGrid.css">
@endpushOnce
@pushOnce($stackNameScript)
    <script src="/js/component/gridComponent/simpleGrid.js"></script>
@endPushOnce
@if(!$include)
    @php
        if(strpos($name,'view_component_') !== false) $name = uniqid(str_replace('view_component_','',$name));
    @endphp
<component id="component_simpleGrid_{{$name}}" data-name="{{$name}}">
    <div id="component_{{$name}}" class="simple_grid"><span>{{$textComponet}}</span></div>
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

            var pagination_{{$name}} = {
                'pageSize':{{(isset($pagination['pageSize']))?$pagination['pageSize']:'false'}},
                'page':{{(isset($pagination['page']))?$pagination['page']:1}},
                'totalCount':{{(isset($pagination['totalCount']))?$pagination['totalCount']:'false'}},
                'typePagination':{{(isset($pagination['typePagination']))?$pagination['typePagination']:'false'}},
                'showPagination':{{(isset($pagination['showPagination']))?$pagination['showPagination']:'false'}},
                'all_load':{{(isset($pagination['all_load']))?$pagination['all_load']:'false'}},
                'showInPage':{{(isset($pagination['showInPage']))?$pagination['showInPage']:'false'}},
                'physical_presence':{{(isset($pagination['physical_presence']))?$pagination['physical_presence']:'false'}}
            };

            var params_{{$name}} = {
                'autostart':{{(isset($autostart) && $autostart)?$autostart:'false'}},
                'url': '{{isset($url)?$url:'undefined'}}',
                'name':'{{isset($name)?$name:'noName'}}',
                'data':@php echo isset($data)? $data:'null'; @endphp,
                'globalData':@php echo isset($globalData)?"'".$globalData."'":'false'; @endphp,
                'column':@php echo isset($column)?json_encode($column):'undefined'; @endphp,
                'template':@php echo isset($template)?str_replace("\/","/",json_encode($template)):"template_1";@endphp,
                'maxLenText':{{isset($maxLenText)?json_encode($maxLenText):'undefined'}},
                'globalParams':{{(isset($globalParams) && $globalParams)?'true':'false'}},
                'params':@php echo isset($params)?json_encode($params):'undefined'; @endphp,
                'target':@php echo isset($target)?json_encode($target):'undefined'; @endphp,
                pagination: pagination_{{$name}},
            };

            var obj_{{$name}}  = new simpleGrid('#component_{{$name}}', params_{{$name}})
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
