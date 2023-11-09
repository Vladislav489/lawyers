@php
    if(!isset($includeToComponent__)) $includeToComponent__ = false;
    if( $includeToComponent__  == true) $name = $clear_name;
    $includeFromHeadToDown = (isset($includeFromHeadToDown) && $includeFromHeadToDown == "true" )?true:false;
    $stackNameScript = (isset($includeFromHeadToDown) && $includeFromHeadToDown)? 'js-lib-component-head':'js-lib-component';
    $include =(isset($include))?$include:false;
@endphp
@pushOnce('css-style')
    <link href="/js/component/dragDropComponent/dragDropUlList.css" rel="stylesheet" type="text/css">
@endpushOnce
@pushOnce($stackNameScript)
    <script src="/js/component/dragDropComponent/dragDropUlList.js"></script>
@endPushOnce
@if(!$include)
    @php
        if(strpos($name,'view_component_') !== false) $name = uniqid(str_replace('view_component_','',$name));
    @endphp
<component id="component_dragDropUlList_{{$name}}" data-name="{{$name}}">
    <div id="component_{{$name}}"><span>Loading...</span></div>
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
                'autostart':@php echo isset($autostart)?$autostart:'false' @endphp,
                'url':'{{isset($url)?$url:'undefined'}}',
                'name':'{{isset($name)?$name:'noName'}}',
                'data':@php echo isset($data)?$data:'null'; @endphp,
                'globalData':@php echo isset($globalData)?"'".$globalData."'":'false'; @endphp,
                'column':@php echo isset($column)?json_encode($column):'undefined'; @endphp,
                'template':@php echo isset($template)?str_replace("\/","/",json_encode($template)):"template_1";@endphp,
                'callDropFunction':@php echo isset($callDropFunction)?$callDropFunction:'null'; @endphp,
                'params':@php echo isset($urlparams)?json_encode($urlparams):'undefined'; @endphp,
                'target':@php echo isset($target)?json_encode($target):'undefined'; @endphp,
                pagination: pagination_{{$name}},
            };
            @php
                $query = "";
                if(isset($container) && !is_null($container) && !empty($container)){
                    if (is_array($container) )
                        foreach ($container as $item)
                            $query.="#".$item.",";
                     else
                        $query.="#".$container;


                    $query = substr( $query,0,strlen($query)-1);
                    $query = "\$('$query')";
                }else{
                    $query = 'null';
                }

            @endphp
            var obj_{{$name}}  = new dragDropUlList('#component_{{$name}}',@php echo $query @endphp, params_{{$name}})
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
