@extends('Admin.layouts.layout')
@push('content')
    <h1>Пути Системы</h1>
    @include('component_build',['component' => 'component.gridComponent.simpleGrid',
    "params_component" => [
    "autostart" => 'true',
    "name" => 'routes',
    "url" => route__("actionRouteList_backcontroller"),
    "template" => "<table v-bind:id=\"name+'_body'\" class='table' style='width:100%'>
        <thead v-bind:id=\"name+'_head'\">
           <tr>
                <th>Name</th>
                <th>Url</th>
                <th>Controller</th>
                <th>Action</th>
           </tr>
        </thead>
        <tbody>
            <tr v-for=\"(items_,index) in data\">
                <td>@{{items_.name}}</td>
                <td>@{{items_.url}}</td>
                <td>@{{items_.controller}}</td>
                <td>@{{items_.action}}</td>
            </tr>
        </tbody>
    </table>",
    "autostart" => 'true',
    "pagination" => ["page" => 1, "pageSize" => 1000, "countPage" => 1,"typePagination" => 0,
                     "showPagination" => 0, "showInPage" => 1000, "count_line" => 1, "all_load" => 0,
                     "physical_presence" => 0
                     ],
    ]])
@endpush