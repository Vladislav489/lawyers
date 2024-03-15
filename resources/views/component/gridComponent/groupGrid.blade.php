@php
    if(!isset($includeToComponent__)) $includeToComponent__ = false;
    if( $includeToComponent__  == true) $name = $clear_name;
    $includeFromHeadToDown = (isset($includeFromHeadToDown) && $includeFromHeadToDown == "true" )?true:false;
    $stackNameScript = (isset($includeFromHeadToDown) && $includeFromHeadToDown)? 'js-lib-component-head':'js-lib-component';
    $include =(isset($include))?$include:false;
    $textComponet = (isset($admin))?"Add component GroupGrid double click for set setting":"";
@endphp
@pushOnce('css-style')
    <link type='text/css' rel='stylesheet' href='/js/component/gridComponent/groupGrid.css'>
@endpushOnce
@pushOnce($stackNameScript)
    <script src='/js/component/gridComponent/groupGrid.js'></script>
@endPushOnce
@if(!$include)
    @php
        if(strpos($name,'view_component_') !== false)
             $name = uniqid(str_replace('view_component_','',$name));

    @endphp
<component id='component_groupGrid_{{$name}}' data-name='{{$name}}'>
    <div id='component_{{$name}}' class='group_grid'><span>{{$textComponet}}</span></div>
</component>
    @if($includeToComponent__ != true)
        @if(!$includeFromHeadToDown)
            @push('component-js')
        @endif
    @endif
    <script date-id_script='{{$name}}'>
        @if($includeToComponent__ != true )
             @if(!$includeFromHeadToDown)
                $(document).ready(function() {
             @endif
        @endif

        var pagination_{{$name}} = {
            'pageSize':{{(isset($pagination['pageSize']))?$pagination['pageSize']:'false'}},
            'page':{{(isset($pagination['page']))?$pagination['page']:1}},
            'countPage':{{(isset($pagination['countPage']))?$pagination['countPage']:'false'}},
            'totalCount':{{(isset($pagination['totalCount']))?$pagination['totalCount']:'false'}},
            'typePagination':{{(isset($pagination['typePagination']))?$pagination['typePagination']:'false'}},
            'showPagination':{{(isset($pagination['showPagination']))?$pagination['showPagination']:'false'}},
            'all_load':{{(isset($pagination['all_load']))?$pagination['all_load']:'false'}},
            'showInPage':{{(isset($pagination['showInPage']))?$pagination['showInPage']:'false'}},
            'physical_presence':{{(isset($pagination['physical_presence']))?$pagination['physical_presence']:'false'}},
            'count_line':{{(isset($pagination['count_line']))?$pagination['count_line']:'1'}}
        };
        var params_{{$name}} = {
            'callbackComponent': function (nameId, idgroup,add_params, data_component, Obj) {
                if(data_component == undefined){
                    data_component = null;
                }
                @php
                    if(isset($includeComponentScript))
                        echo $includeComponentScript
                @endphp
            },
            'data':@php echo isset($data)?$data:'null'; @endphp,
            'globalData':@php echo isset($globalData)?"'".$globalData."'":'false'; @endphp,
            'autostart':{{ isset($autostart)?$autostart:'false'}},
            'type_query':'{{ isset($type_query)?$type_query:'false'}}',
            'group_query':@php echo isset($group_query)?json_encode($group_query):'undefined';@endphp,
            'templateComponent':@php echo isset($includeComponentHtml)?json_encode($includeComponentHtml):'null';@endphp,
            'name_group': '{{isset($name_group)?$name_group:'undefined'}}',
            'indefication':'{{isset($indefication)?$indefication:'undefined'}}',
            'add_params':@php echo isset($add_params)?json_encode($add_params):'undefined';@endphp,
            'name':'{{isset($name)?$name:'noName'}}',
            'url':'{{isset($url)?$url:'undefined'}}',
            'template':@php echo (isset($template))?str_replace("\/","/",json_encode($template)):'';@endphp,
            'params':@php echo isset($params)?json_encode($params):'undefined'; @endphp,
            'globalParams':{{(isset($globalParams) && $globalParams)?'true':'false'}},
            'target':@php echo isset($target)?json_encode($target):'undefined';@endphp,
            'pagination': pagination_{{$name}},
        };
        var component_{{$name}} = new groupGrid('#component_{{$name}}', params_{{$name}});
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
