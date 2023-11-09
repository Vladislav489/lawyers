@extends('Admin.layouts.layout')
@push('content')
    <h1>Системные логи</h1>
    <div class='modal fade' id='longtext' tabindex='-1' role='dialog' aria-labelledby='exampleModalCenterTitle' aria-hidden='true'>
        <div class='modal-dialog modal-dialog-centered' role='document'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title' id='exampleModalLongTitle'>Полный текст ошибки</h5>
                    <button type='button' class='close' data-dismiss='modal' aria-label='Закрыть'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>
                <div class='modal-body'>
                    ...
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-secondary' data-dismiss='modal'>Закрыить</button>
                </div>
            </div>
        </div>
    </div>
    <div class="search-body-admin">
        <table>
            <tr>
                <td><div>Тип</div>
                @include('component_build',['component' => "component.listComponent.selectComponent",
                "params_component" => [
                    "autostart" => 'true',
                    "name" => 'type',
                    "url" => route__("actiongetListTypeLog_backcontroller"),
                    "template" => "simpleSelect"
                ]])
                </td>
                <td><div>Заголовок</div><input class='search-input-admin' name='title' type='text'></td>
                <td><div>Дискрипция</div><input class='search-input-admin' name='description' type='text'></td>
                <td><div>Дата с</div><input id='date_from'  class='search-input-admin' name='date_from' type='text'></td>
                <td><div>Дата по</div><input id='date_to' class='search-input-admin' name='date_to' type='text'></td>
                <td ><button id="search_button" class="btn btn-success" style='margin-top:22px;width: 150px'>Искать</button></td>
            </tr>
        </table>
    </div>
    @include('component_build',['component' => 'component.gridComponent.simpleGrid',
        "params_component" => [
        "autostart" => 'true',
        "name"=>"logsystems",
        "url"=>route__("actionSystemLog_backcontroller"),
        "column"=>[['name'=>'Ид'],['name'=>'Тип'],['name'=>'Заголовок'],['name'=>'Дискрипция'],['name'=>'Дата добавления']],
        "template"=>
        "<div v-bind:id='name' class='card-item-info'>
            <div v-bind:id=\"name+'_body'\" class='simple_grid_body' style='overflow: hidden'>
                 <table class='table' style='width:100%'>
                 <thead>
                    <tr>
                        <td v-for=\"items_col in column\" >@{{items_col.name}}</td>
                    </tr>
                 </thead>
                 <tbody>
                 <tr v-for=\"(items_ , index) in data \">
                         <td>@{{(index+1)+((pagination.page-1) * pagination.pageSize)}}</td>
                        <td>@{{items_.code}}</td>
                        <td>@{{items_.title}}</td>
                        <td>
                             <code>
                                @{{items_.short_text}}
                             </code><br>
                             <button  @click=\"dialogFullText('full_text_'+items_.id)\" type='button' class='btn btn-primary'style='margin-bottom:5px'>
                                Показать все
                             </button>
                             <div :id=\"'full_text_'+items_.id\" style='display:none'>
                             <code>
                                    @{{items_.log}}
                                    </code>
                             </div>
                        </td>
                        <td>@{{items_.created_at}}</td>
                 </div>
                 </tr>
                 </tbody>
                 </table>
             </div>
         </div>",
        "autostart"=>'true',

        'pagination'=>['page'=>1,'pageSize'=> 9,'countPage'=>1,'typePagination'=>1,"showPagination"=>1
                                   ,'showInPage'=>9,'count_line'=>1, 'all_load'=>0,'physical_presence'=>0],
        'target'=>['route'=>route__("actionForecast_controller"),
                         'params'=>[
                                 ['value'=>'type_inf_id','filter'=>"type"],
                                 ['value'=>'short_name','filter'=>"code"]
                         ]
                   ],
     ]])
@endpush
@pushOnce('js-lib-component-head')
<script src="/js/jquery/ui/i18n/datepicker-ru.js"></script>
<script>

    $(document).ready(function () {

    })

    function Init(){
        $("#date_from").datepicker({dateFormat: 'yy-mm-dd'} );
        $("#date_to").datepicker({dateFormat: 'yy-mm-dd'} );
        $("#search_button").click(function(){search()});
    }
    function search(){
        var urlParams =  page__.getElementsGroup('logsystems')[0]['obj'].getUrlParams();
        urlParams['type'] = $('[name=type]').find("option:selected").val();
        urlParams['title'] = $('[name=title]').val();
        urlParams['description'] = $('[name=description]').val();
        urlParams['date_from'] = $('[name=date_from]').val();
        urlParams['date_to'] = $('[name=date_to]').val();
        page__.getElementsGroup('logsystems')[0]['obj'].setUrlParams(urlParams);
    }

    function dialogFullText(id){
        $('#longtext').find(".modal-body").html($("#"+id).html())
        $('#longtext').modal('toggle')
    }
</script>
@endPushOnce