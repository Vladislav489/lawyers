@extends('Admin.layouts.layout')
@push('content')
    <h1>Сервисы</h1>
    <div class='modal fade'  id='editDialog' tabindex='-1' role='dialog' aria-labelledby='exampleModalCenterTitle' aria-hidden='true'>
        <div class='modal-dialog modal-dialog-centered'  role='document'>
            <div style='width:700px' class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title' id='exampleModalLongTitle'>Окно редактирования</h5>
                    <button type='button' class='close' data-dismiss='modal' aria-label='Закрыть'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>
                <div class='modal-body flex-between' style='width:700px'>
                    <div>
                        <div>
                            <p>Название</p>
                            <input class='search-input-admin' id='name' name='name_edit' type='text'>
                        </div>
                        <div>
                            <p>Описание</p>
                            <input class='search-input-admin' id='description' name='description_edit' type='text'>
                        </div>
                        <div>
                            <p>Тип сервиса</p>
                            @include('component_build',["component" => "component.listComponent.selectComponent",
                            "params_component" => [
                                "autostart" => 'true',
                                "name" => 'type_id_edit',
                                "default_title" => 'Выбрать',
                                "url" => route("actionGetServiceTypeListForSelect_mainstay_service_servicemainstaycontroller"),
                                "template" => 'simpleSelect',
                                "change" => "function(){}"
                            ]])
                        </div>
                    </div>
                </div>
                <div class='modal-footer'>
                    <button type='button' id='editDialog_saveEdit' class='btn btn-success'>Сохранить</button>
                    <button type='button' id='closeDialogEdit' class='btn btn-secondary' data-dismiss='modal'>Закрыить</button>
                </div>
            </div>
        </div>
    </div>
    <button id="add_new_item"  class='btn btn-success'>Добавить</button>
    @include('component_build',["component" => 'component.gridComponent.simpleGrid',
    'params_component' => [
    "autostart" => 'true',
    "name"=>"service_list",
    "url"=>route("actionGetServiceList_mainstay_service_servicemainstaycontroller"),
    "template"=>"<table v-bind:id=\"name+'_body'\" class='table' style='width:100%'>
        <thead>
           <tr>
           <th>Название</th>
           <th>Описание</th>
           <th>Действия</th>
           </tr>
        </thead>
        <tbody>
            <tr v-for=\"item in data\">
                <td> @{{item.name}}</td>
                <td> @{{item.description}}</td>
                <td><div class='flex adm-ml20'>
                            <div><a v-on:click=\"Edit(item)\"><i class='grid_admn_btn fa fa-pencil-square-o '>&nbsp;</i></a></div>
                            <div><a v-on:click=\"DeleteItem(item.id)\"><i class='grid_admn_btn fa fa-trash-o'>&nbsp;</i></a></div>
                </div></td>
            </tr>
        </tbody>
    </table>",
    "autostart"=>'true',
    'pagination'=>['page'=>1,'pageSize'=> 14,'countPage'=>1,'typePagination'=>1,"showPagination"=>1
    ,'showInPage'=>14,'count_line'=>1, 'all_load'=>0,'physical_presence'=>0],
    ]])
    @pushOnce('js-lib-component-head')
        <script>
            $(document).ready(function() {Init();})
            function Init() {
                $("#editDialog").find('.close').click(function () {clearInput()})
                $("#closeDialogEdit").click(function(){clearInput();})
                $("#editDialog_saveEdit").click(function(){Save()});
                $("#add_new_item").click(function(){Edit(null)});
            }
            function Save(){
                dataEdit = $("#editDialog").find(".modal-body").find("input");
                dataEdit.push($("#editDialog").find(".modal-body").find("select[name='type_id_edit']"));
                dataSend = {};
                for(var index = 0; index < dataEdit.length;index++){
                    var key = $(dataEdit[index]).attr("name");
                    if(key){
                        dataSend[key.replace('_edit', '')] = $(dataEdit[index]).val();
                    }
                }
                page__.sendData("{{route__("actionServiceStore_mainstay_service_servicemainstaycontroller")}}",
                    dataSend,function(data){
                        if(data['result'] == 0){
                            jAlertError("Ошибка","  данные не сохранены!");
                        }else{
                            $("#editDialog").modal('hide')
                            page__.getElementsGroup('service_list')[0]['obj'].loadFromAajax();
                            clearInput();
                        }
                    })
            }
            function Edit(data){
                if(data!=null) {
                    var DataClone = {}
                    Object.assign(DataClone,data);
                    var inputId = $("<input type='hidden' name='id_edit' value='" + data['id'] + "'>");
                    $("#editDialog").find(".modal-body").append(inputId)
                    for (var key in Array.prototype.reverse.call(DataClone)) {
                        var element = $("#editDialog").find("[name=" + key + "_edit]")
                        setDateToFormElement(element, DataClone[key]);
                    }
                }
                $("#editDialog").modal('toggle')
            }
            function DeleteItem(id){
                if(confirm("Точно хотите удалить ?")){
                    page__.sendData("{{route__("actionServiceDelete_mainstay_service_servicemainstaycontroller")}}",
                        {'id':id},function(data){
                            if(data['result'] == 0){
                                jAlertError("Ошибка","  данные не удалены!");
                            }else{
                                page__.getElementsGroup('service_list')[0]['obj'].loadFromAajax();
                            }
                        })
                }
            }
            function clearInput(){
                dataEdit = $("#editDialog").find(".modal-body").find("input");
                dataEdit.push($("#editDialog").find(".modal-body").find("select[name='type_id_edit']"));
                for(var index = 0; index < dataEdit.length;index++){
                    var key = $(dataEdit[index]).attr("name");
                    if(key){
                        if($(dataEdit[index]).prop("tagName") =="SELECT"){
                            $(dataEdit[index]).find("option").removeAttr("selected")
                            $(dataEdit[index]).find("option[value='']").attr("selected",true)
                        }else{
                            $(dataEdit[index]).val('');
                        }
                    }
                }
            }
        </script>
    @endpushonce
@endpush
