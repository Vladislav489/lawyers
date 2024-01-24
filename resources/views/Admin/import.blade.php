@extends('Admin.layouts.layout')
@push('content')
    <h1>Импорт данных</h1>
    <table style="width:100%">
        <tr>
            <td>
                <p>Загрузите CSV файл</p>
                <input type="file">
            </td>
            <td>
                <p>Выберете таблицу</p>
                @include("component.listComponent.selectComponent",[
                          'name'=>'teble_edit',
                          'default_title'=>"Выбрать",
                          'url'=>route("actionGetListTable_admin_mainstay_backcontroller"),
                          'template'=>"simpleSelect",
                          'change'=>"function(event){
                                if(event.data.obj_class!=null){
                                  var selectTarget = page__.getComponentByName('teble_field_edit');
                                  selectTarget.addUrlParams({table:$(this).val()}).loadFromAajax();
                                }
                          }"
                ])
            </td>
        </tr>
        <tr style="text-align: center">
            <td>
                <h1> Поля CSV файла </h1>
            </td>
            <td>
                <h1> Поля Таблицы </h1>
                @include("component.listComponent.selectComponent",[
                          'name'=>'teble_field_edit',
                          'default_title'=>"Выбрать",
                          'url'=>route("actionGetListFieldTable_admin_mainstay_backcontroller"),
                          'template'=>"simpleSelect",
                          'change'=>"function(){}"
                ])
            </td>

        </tr>
    </table>
@pushOnce('js-lib-component-head')
    <script>

    </script>
@endPushOnce
@endpush
