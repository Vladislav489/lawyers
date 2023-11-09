@extends('Admin.layouts.layout')
@push('content')
<h1>Пути сайтов</h1>
<div class='modal fade' id='editDialog' tabindex='-1' role='dialog' aria-labelledby='exampleModalCenterTitle' aria-hidden='true'>
    <div class='modal-dialog modal-dialog-centered'  role='document'>
        <div style='width:700px' class='modal-content'>
            <div class='modal-header'>
                <h5 class='modal-title' id='exampleModalLongTitle'>Окно редактирования</h5>
                <button type='button' class='close' data-dismiss='modal' aria-label='Закрыть'>
                    <span aria-hidden='true'>&times;</span>
                </button>
            </div>
            <div class='modal-body' style='width:700px'>
                <div class='flex-between'>
                    <div class='p-4'><p>Страницы</p>
                        @include('component_build',["component" => "component.listComponent.selectComponent",
                        'params_component' => [
                            "autostart" =>'true',
                            "name" => 'select_url_edit',
                            "default_title" => 'Выбрать',
                            "url" => route("actionGetListPageSite_backcontroller"),
                            "template" => 'simpleSelect',
                            "change" => "function(){
                                if($(this).val()!=''){
                                    $('[name = url_edit]').val($(this).val());
                                    $('[name = template_url_edit]').val($(this).val());
                                    var Text = $(this).find('option:selected').text();
                                    $('[name=name_title_edit]').val(Text.substr(0,Text.indexOf('->')));
                                }
                             }"
                        ]])
                        <div class='flex-between'>
                            <div class='m-2 text-center'>
                                <p>Url</p>
                                <input class='search-input-admin' id ='url_edit' name ='url_edit' type='text'>
                            </div>
                            <div class='m-2 text-center'>
                                <p>Название стр.</p>
                                <input class='search-input-admin' name='name_title_edit' type='text'>
                            </div>
                            <div class='m-2 text-center'>
                                <p>Темплей урла ЧПУ</p>
                                <input class='search-input-admin' id='template_url_edit' name='template_url_edit' type='text'>
                            </div>
                        </div>
                    </div>

                </div>
                <div class='flex-between'>
                    <div><p>Темплайт</p>
                        @include('component_build',['component' => "component.listComponent.selectComponent",
                        "params_component" => [
                            "autostart" => 'true',
                            "name" => 'template_edit',
                            "default_title" => "Выбрать",
                            "url" => route("actionGetListViews_backcontroller"),
                            "template" => "simpleSelect",
                            "change" => "function(){}"
                        ]])
                    </div>
                    <div>
                        <p>Проверка на наличие данных</p>
                        @include('component_build',['component' => "component.listComponent.selectComponent",
                            "params_component" => [
                                "autostart" => 'true',
                                "name" => 'check_module_edit',
                                "default_title" => 'Выбрать',
                                "url" => route("actionGetModuleLogicList_backcontroller"),
                                "template" => "simpleSelect",
                                "change" => "function(){}"
                        ]])
                    </div>
                </div>
                <div class='flex-between'>
                    <div><p>Псевдоним</p><input class='search-input-admin'  name='alias_url_edit' type='text'></div>
                    <div><p>Айди Страницы</p><input class='search-input-admin' name='page_id_edit' type='text'></div>
                </div>
                <div class='flex-between'>
                    <div><p>Сайт айди</p>
                        @include('component_build',["component" => "component.listComponent.selectComponent",
                        "params_component" => [
                           "autostart" => 'true',
                           "name" => 'site_id_edit',
                           "default_title" => 'Выбрать',
                           "url" => route("actionGetListSiteSelect_backcontroller"),
                           "template" => 'simpleSelect',
                           "change" => "function(){}"
                        ]])
                    </div>
                    <div><p>Язык</p>
                        <input class='search-input-admin' name='lang_id_edit' type='text'>
                    </div>
                </div>
                <div class='flex-between text-center'>
                    <div><p>Физический</p><input disabled='true'  name='physically_edit'  type='checkbox'></div>
                    <div><p>Открытый закрвтый</p>
                        <input name='open_edit' type='checkbox'>
                    </div>
                </div>
            </div>
            <div class='modal-footer'>
                <button type='button' id="editDialog_saveEdit" class='btn btn-success'>Сохранить</button>
                <button type='button' id="closeDialogEdit" class='btn btn-secondary' data-dismiss='modal'>Закрыить</button>
            </div>
        </div>
    </div>
</div>
<button id='add_physical' class='btn btn-success'>Добавить из физически путей </button>
<button id='add_custom' class='btn btn-success'>Создать Виртуальный пуит </button>
@include('component_build',["component" => 'component.gridComponent.simpleGrid',
        "params_component" => [
        "autostart" => 'true',
        "name" => 'routes',
        "url" => route("actionGetSystemRoute_backcontroller"),
        "template" => "<table v-bind:id=\"name+'_body'\" class='table' style='width:100%'>
                            <thead v-if=\"typeof(column) !== 'undefined'\" v-bind:id=\"name+'_head'\">
                                <tr><th v-for=\"items_col in column\">@{{items_col.name}}</th><th></th></tr>
                            </thead>
                            <tbody>
                                <tr v-for=\"(items_,index) in data\">
                                    <td v-for=\"items_col in column\"> @{{getByKey(items_,items_col.key)}}</td>
                                    <td><div class='flex adm-ml20'>
                                        <div><a v-on:click=\"Edit(items_,items_.physically_val)\"><i class='grid_admn_btn fa fa-pencil-square-o '>&nbsp;</i></a></div>
                                        <div><a v-on:click=\"showHide(items_,index)\"><i class='grid_admn_btn fa' v-bind:class=\"[items_.active ? 'fa-eye' : 'fa-eye-slash']\">&nbsp;</i></a></div>
                                        <div><a v-on:click=\"DeleteItem(items_.id)\"><i class='grid_admn_btn fa fa-trash-o'>&nbsp;</i></a></div>
                                    </div></td>
                                </tr>
                            </tbody>
                     </table>",
        "urlparams" => ['sort_by'=>"url"],
        "autostart" => 'true',
        "pagination" => ["page" => 1, "pageSize" => 14, "countPage" => 1, "typePagination" => 1,
                         "showPagination" => 1,"showInPage" => 14,"count_line" => 1, "all_load" => 0,
                         "physical_presence" => 0],
]])
@pushOnce('js-lib-component-head')
<script>
    $(document).ready(function () {
        initPage();
    })
    function initPage() {

        $("#editDialog_saveEdit").click(function() {SaveRoute()});
        $("#closeDialogEdit").click(function() {clearInput()});
        $("#editDialog").find('.close').click(function() {clearInput()});
        $("#closeDialogEdit").click(function() {clearInput()});
        $("#add_physical").click(function (){Edit(null,0)});
        $("#add_custom").click(function (){Edit(null,1)});
    }

    function SaveRoute() {
        dataEdit = $("#editDialog").find(".modal-body").find("input");
        dataEdit.push($("#editDialog").find(".modal-body").find("select[name='template_edit']"));
        dataEdit.push($("#editDialog").find(".modal-body").find("select[name='site_id_edit']"));
        dataEdit.push($("#editDialog").find(".modal-body").find("select[name='check_module_edit']"));
        dataSend = {};
        for(var index = 0; index < dataEdit.length;index++){
            var key = $(dataEdit[index]).attr("name");
            if(key){
                if($(dataEdit[index]).attr("type") == "checkbox") {
                    var value = ($(dataEdit[index]).is(":checked"))?'1':'0';
                    dataSend[key.replace('_edit', '')] = value;
                }else{
                    var value = $(dataEdit[index]).val();
                    dataSend[key.replace('_edit', '')] = value;
                }
            }
        }
        page__.sendData("{{route__("actionSystemRouteSave_backcontroller")}}",
            dataSend,function(data){
                if(data['result'] == 0){
                    jAlertError("Ошибка","  данные не сохранены!");
                }else{
                    $("#editDialog").modal('hide')
                    page__.getElementsGroup('routes')[0]['obj'].loadFromAajax();
                    clearInput()
                }
            })
    }

    function showHide(data,index){
        data['active'] = (data['active'] == 1)?0:1;
        page__.sendData("{{route__("actionSystemRouteSave_backcontroller")}}",
            {'active':data['active'],'id':data['id']},function(data){
                if(data['result'] == 0) jAlertError("Ошибка","  данные не сохранены!");
            })
    }
    function Edit(data,flag){
        if(data!=null) {
            clearInput();
            var DataClone = {}
            Object.assign(DataClone,data);
            var inputId = $("<input type='hidden' name='id_edit' value='" + data['id'] + "'>")
            DataClone['select_url'] = DataClone['url'];
            $("#editDialog").find(".modal-body").append(inputId)
            DataClone['open'] = DataClone['open_'];
            for (var key in Array.prototype.reverse.call(DataClone)) {
                var element = $("#editDialog").find("[name=" + key + "_edit]")
                setDateToFormElement(element, DataClone[key]);
            }
            $("select[name='select_url_edit']").find("option[data-text^='"+data['name_title']+"*']").attr("selected",'true')
            $("select[name='check_module_edit']").find("option[value='"+data['check_module']+"']").attr("selected",'true')
            $("select[name='site_id_edit']").find("option[value='"+data['site_id']+"']").attr("selected",'true')
            if(flag == 0){
                $("#editDialog").find("[name=select]").hide();
                $("#editDialog").find("[name=physically_edit]").attr("checked",'false');
            }else{
                $("#editDialog").find("[name=select]").show();
                $("#editDialog").find("[name=physically_edit]").attr("checked",'true');
            }
        }else{
            $("#editDialog").find(".modal-body").find("input[name='id_edit']").remove();
            if(flag == 0){
                $("#editDialog").find("[name=select]").hide();
                $("#editDialog").find("[name=physically_edit]").attr("checked",'false');
            }else{
                $("#editDialog").find("[name=select]").show();
                $("#editDialog").find("[name=physically_edit]").attr("checked",'true');
            }
        }
        $("#editDialog").modal('toggle')
    }
    function DeleteItem(id){
        if(confirm("Точно хотите удалить ?")){
            page__.sendData("{{route__("actionSystemRouteDelete_backcontroller")}}",
                {'id':id},function(data){
                if(data['result'] == 0){
                    jAlertError("Ошибка","  данные не удвлены!");
                }else{
                    page__.getElementsGroup('routes')[0]['obj'].loadFromAajax();
                }
            })
        }
    }
    function clearInput(){
        dataEdit = $("#editDialog").find(".modal-body").find("input");
        dataEdit.push($("#editDialog").find(".modal-body").find("select[name='template_edit']"));
        dataEdit.push($("#editDialog").find(".modal-body").find("select[name='site_id_edit']"));
        dataEdit.push($("#editDialog").find(".modal-body").find("select[name='check_module_edit']"));
        for(var index = 0; index < dataEdit.length;index++){
            var key = $(dataEdit[index]).attr("name");
            if(key){
                switch ($(dataEdit[index]).prop("tagName")) {
                    case "SELECT":
                        $(dataEdit[index]).find("option").removeAttr("selected");
                        $(dataEdit[index]).find("option[value='']").attr("selected","true")
                        $(dataEdit[index]).val('');
                        break;
                    default:
                        switch ($(dataEdit[index]).attr('type')) {
                            case "checkbox":
                                if($(dataEdit[index]).attr('name') != 'physically')
                                    $(dataEdit[index]).attr("checked",'false');
                                break;
                            default:
                                $(dataEdit[index]).val('');
                                break;
                        }
                    break;
                }
            }
        }
    }
</script>
@endPushOnce
@endpush