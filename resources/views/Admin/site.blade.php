@extends('Admin.layouts.layout')
@push('content')
    <h1>Подключение Доменов</h1>
    <div class='modal fade' id='editDialog' tabindex='-1' role='dialog' aria-labelledby='exampleModalCenterTitle' aria-hidden='true'>
        <div class='modal-dialog modal-dialog-centered' role='document'>
            <div style='width:700px' class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title' id='exampleModalLongTitle'>Окно редактирования</h5>
                    <button type='button' class='close' data-dismiss='modal' aria-label='Закрыть'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>
                <div class='modal-body' style='width:700px'>
                    <div class='flex-between'>
                        <div><p>Доменное Имя</p><input class='search-input-admin' name='domain_name_edit' type='text'></div>
                        <div><p>Владелец сайта</p><input class='search-input-admin' name='user_main_id_edit' type='text'></div>
                    </div>
                    <div class='flex-between'>
                        <div><p>Стиль</p><input class='search-input-admin' name='style_edit' type='text'></div>
                        <div><p>Язык</p>
                            <input class='search-input-admin' name='lang_id_edit' type='text'>
                        </div>
                    </div>
                </div>
                <div class='modal-footer'>
                    <button type='button' id='editDialog_saveEdit' class='btn btn-success'>Сохранить</button>
                    <button type='button' class='btn btn-secondary' data-dismiss='modal'>Закрыить</button>
                </div>
            </div>
        </div>
    </div>
    <button id="add_new_domain" class='btn btn-success'>Добавить новый домен</button>
    @include('component_build',['component' => 'component.gridComponent.simpleGrid',
    "params_component" => [
    "autostart" => 'true',
    "name" => "routes",
    "url" => route__("actionGetListSite_backcontroller"),
    "template" => "<table v-bind:id=\"name+'_body'\" class='table' style='width:100%'>
        <thead v-if=\"typeof(column) !== 'undefined'\" v-bind:id=\"name+'_head'\">
           <tr><th v-for=\"items_col in column\">@{{items_col.name}}</th><th></th></tr>
        </thead>
        <tbody>
            <tr v-for=\"(items_,index) in data\">
                <td v-for=\"items_col in column\"> @{{getByKey(items_,items_col.key)}}</td>
                <td><div class='flex adm-ml20'>
                            <div><a v-on:click=\"Edit(items_)\"><i class='grid_admn_btn fa fa-pencil-square-o '>&nbsp;</i></a></div>
                            <div><a v-on:click=\"showHide(items_,index)\"><i class='grid_admn_btn fa' v-bind:class=\"[items_.active ? 'fa-eye' : 'fa-eye-slash']\">&nbsp;</i></a></div>
                            <div><a v-on:click=\"DeleteItem(items_.id)\"><i class='grid_admn_btn fa fa-trash-o'>&nbsp;</i></a></div>
                </div></td>
            </tr>
        </tbody>
    </table>",
    "autostart" => 'true',
    "pagination" => ["page" => 1, "pageSize" => 14, "countPage" => 1, "typePagination" => 1,
                     "showPagination" => 1, "showInPage" => 14, "count_line" => 1, "all_load" => 0,
                     "physical_presence" => 0
                    ],
    ]])
    @pushOnce('js-lib-component-head')
    <script>
        $(document).ready(function () {Init()})
        function Init(){
            $('#editDialog').find('.close').click(function () {clearInput();})
            $('#editDialog_saveEdit').click(function() {saveSite()});
            $('#add_new_domain').click(function() { Edit(null)})
        }
        function saveSite(){
            dataEdit = $('#editDialog').find('.modal-body').find('input');
            dataSend = {};
            for (var index = 0; index < dataEdit.length; index++){
                var key = $(dataEdit[index]).attr('name');
                if (key) {
                    if($(dataEdit[index]).attr('type') == 'checkbox') {
                        var value = ($(dataEdit[index]).is(':checked'))? 1:0;
                        dataSend[key.replace('_edit', '')] = value;
                    } else {
                        var value = $(dataEdit[index]).val();
                        dataSend[key.replace('_edit', '')] = value;
                    }
                }
            }
            page__.sendData("{{route__("actionSiteSave_backcontroller")}}",
                dataSend,function(data) {
                    if (data['result'] == 0) {
                        jAlertError('Ошибка',' данные не сохранены!');
                    } else {
                        $('#editDialog').modal('hide')
                        page__.getElementsGroup('routes')[0]['obj'].loadFromAajax();
                        clearInput();
                    }
                })
        }

        function showHide(data,index) {
            data['active'] = (data['active'] == 1)? 0:1;
            page__.sendData("{{route__("actionSiteSave_backcontroller")}}",
                {'active':data['active'], 'id':data['id']}, function(data) {
                    if (data['result'] == 0) jAlertError('Ошибка',' данные не сохранены!');
                })
        }
        function Edit(data) {
            if(data != null) {
                var inputId = $("<input type='hidden' name='id_edit' value='" + data['id'] + "'>")
                var Phscl = (flag == 1)?1:0;
                var inputPhscl = $("<input type='hidden' name='physically_edit'>").val(Phscl);
                $('#editDialog').find('.modal-body').append(inputId)
                $('#editDialog').find('.modal-body').append(inputPhscl)
                for (var key in data) {
                    var element = $('#editDialog').find('[name = ' + key + '_edit]')
                    setDateToFormElement(element, data[key]);
                }
            }
            $('#editDialog').modal('toggle')
        }
        function DeleteItem(id){
            if(confirm('Точно хотите удалить ?')){
                page__.sendData("{{route__("actionSiteDelete_backcontroller")}}",
                    {'id':id}, function(data) {
                        if (data['result'] == 0) {
                            jAlertError('Ошибка',' данные не удвлены!');
                        }else{
                            page__.getElementsGroup('routes')[0]['obj'].loadFromAajax();
                        }
                    })
            }
        }
        function clearInput(){
            dataEdit = $('#editDialog').find('.modal-body').find('input');
            dataEdit.push($('#editDialog').find('.modal-body').find("select[name = 'parent_id']"));
            for (var index = 0; index < dataEdit.length; index++) {
                var key = $(dataEdit[index]).attr('name');
                if (key) {
                    if ($(dataEdit[index]).prop('tagName') == 'SELECT') {
                        $(dataEdit[index]).find("option[value='']").attr('selected',true)
                    } else {
                        $(dataEdit[index]).val('');
                    }
                }
            }
        }
    </script>
    @endPushOnce
@endpush