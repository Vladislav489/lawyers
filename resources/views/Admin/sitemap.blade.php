@extends('Admin.layouts.layout')
@push('content')
    <h1>Карта сайта</h1>
    <div>
        <button id="CreateSiteMap" class="btn btn-success">Создать Карту сайта</button>
    </div>
    <div>
        @include('component_build',['component' => 'component.gridComponent.simpleGrid',
        "params_component" => [
        "autostart" => 'true',
        "name" => 'sitemapfile',
        "url" => route__("actionGetListSiteMap_admin_mainstay_backcontroller"),
        "template" => "<table v-bind:id=\"name+'_body'\" class='table' style='width:100%'>
            <thead v-bind:id=\"name+'_head'\">
               <tr><th>Имя файла</th><th>Размер</th><th>Дата Создания</th></tr>
            </thead>
            <tbody>
                <tr v-for=\"(items_,index) in data\">
                    <td><a :href=\"'/'+items_.name\"><strong> @{{items_.name}}</strong></a></td>
                    <td>@{{items_.size}}</td>
                    <td>@{{items_.datetime}}</td>
                </tr>
            </tbody>
        </table>",
        "autostart" => 'true',
        "pagination" => ["page" => 1, "pageSize" => 1000, "countPage" => 1,"typePagination" => 0,
                         "showPagination" => 0, "showInPage" => 1000, "count_line" => 1, "all_load" => 0,
                         "physical_presence" => 0
                         ],
        ]])
    </div>
@endpush
@pushOnce('js-lib-component-head')
<script>
    $(document).ready(function () {Init()})
    function Init(){
        $('#CreateSiteMap').click(function () {createSiteMap()});
    }
    function createSiteMap(){
        page__.sendData("{{route__("actionCreateSiteMap_admin_mainstay_backcontroller")}}",
            {},function(data) {
                if (data['result'] == false) {
                    jAlertError('Ошибка',' данные не сохранены!');
                } else {
                    page__.getElementsGroup('sitemapfile')[0]['obj'].loadFromAajax();
                }
            })
    }
</script>
@endPushOnce
