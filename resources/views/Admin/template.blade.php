@extends('Admin.layouts.layout')
@pushOnce('css-style')
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css'>
@endpushOnce

@php
    $list = [];
    $check = false;
    if (!is_null($template['template_url']) && !empty($template['template_url'])) {
        $list = $params_route;
        $check = true;
    } else {
        $check = preg_match_all('/(\{(.+?)})/s',$template['url'],$list);
    }

@endphp

@push('content')
        <style>
            .mrgn-editor{margin-left:40px;}
            .fnt-editor24{font-size:24px;}

            .radius-boreder {
                border-radius: 10px;
            }
            .item-componet-menu{
                width: 60px;
                height: 60px;
                background: #3dd5f3;

            }

            .nav-tabs li > a {
                border-top: 1px black solid;
                border-left: 1px black solid;
                border-right: 1px black solid;
            }
            .nav-tabs li > a:hover {
                border-top: 1px black solid;
                border-left: 1px black solid;
                border-right: 1px black solid;
            }
        </style>
        <div class='flex' style='font-size:17px;'>
            <div class='flex-between' style='width:100%;'>
                <div class='mrgn-editor'>
                    <table>
                        <tr><td>Название страници:</td><td>{{$template['name_title']}}</td></tr>
                        <tr><td>Домен:</td><td>{{$template['domain_name']}}</td></tr>
                        <tr><td>Адресс:</td><td>{{$template['url']}}</td></tr>
                        @isset($template['template_url'])
                            <tr><td>Темплейт Адресс:</td><td>{{$template['template_url']}}</td></tr>
                        @endisset
                        <tr><td>Отк/Закр</td><td>{{($template['open'])?"Да":"Нет"}}</td></tr>
                        <tr><td>Язык:</td><td>{{$template['lang_id']}}</td></tr>
                    </table>

                    @if($check)
                        <div id='globalParamsTempalte'>
                            @if(!is_null($template['template_url']) && !empty($template['template_url']))
                                @foreach($list as $key=>$item)
                                    <span>{{$key}}</span> <input class='search-input-admin' type='text' name='{{$key}}'  value='{{request()->get($key,$item)}}'>
                                @endforeach
                            @else
                                @foreach($list[0] as $item)
                                    @php $item =  str_replace(['{','}','?'],'',$item) @endphp
                                    <span>{{$item}}</span> <input class='search-input-admin' type='text' name='{{$item}}'  value='{{request()->get($item,'')}}'>
                                @endforeach
                            @endif
                                <button id='addParamsTemplate'>Применить</button>
                        </div>
                        @endif
                </div>
                <div class='mrgn-editor'>
                    <button id='SaveTemplate' class='btn btn-success save-button radius-boreder fnt-editor24' style='margin-right:20px'>Сохранить</button>
                    <button class='btn btn-danger radius-boreder cansel  fnt-editor24'>Отмена</button>
                </div>
            </div>
        </div>
        <hr>
        <ul class='nav nav-tabs'>
            <li ><a data-toggle='tab' class='fnt-editor24' href='#html'>Страница</a></li>
            <li><a data-toggle='tab' class='fnt-editor24' href='#seo'>СЕО</a></li>
            <li><a data-toggle='tab' class='fnt-editor24' href='#file_include'>Файлы</a></li>
        </ul>
        <div class='tab-content'>
          <div id='html' class='tab-pane fade in active show'>
            <h1 class="title-gradient">Управление Шаблонами страниц</h1>
              <div style='width:100%; '>

                  @include('component_build',["component" => 'component.menuComponent.menu',
                             "params_component" => [
                              'name'=>"componentSite",
                              'autostart' => 'false',
                              'style' => '4',
                              "data"=>json_encode($component_tree),
                              'url' => route__('actionGetListComponentWithCode_admin_mainstay_backcontroller'),
                              'template'=>"<div id=\"\${this.id}\" v-bind:class=\"' nav_menu_component_'+style\" onselectstart=\"return false\">
                                              <ul class=\"topmenu_menu_component\" onselectstart=\"return false\">
                                                  <menu-tree v-for=\"hItem in data\" v-bind:item=\"hItem\"></menu-tree>
                                              </ul>
                                            </div>",
                              'templateItem'=>" <li class=\"h-card\" onselectstart=\"return false\" v-bind:id=\"item.item.id\"   :draggable=\"item.children == null ? true : false\">
                                                  <div class=\"item-componet-menu  radius-boreder\" onselectstart=\"return false\" >
                                                      @{{item.item.lable}}
                                                  </div>
                                                  <ul v-if=\"item.children !== null\" class=\"submenu_menu_component\">
                                                      <menu-tree v-for=\"y in item.children\" v-bind:item=\"y\"></menu-tree>
                                                  </ul>
                                                </li>",
                             ]
                           ])
              </div>
              <div style="clear: both;">
              </div>
                <hr>
                <div style='width:100%;'>
                    @if(!isset($error_view))
                    @include("component.htmlEditorComponent.htmlEditor",[
                           'autostart' => 'false',
                           'name' => "HtmlEditor",
                           'url_buld_view' => route__("actionBuildView_admin_mainstay_backcontroller"),
                           'template' => $template,
                           'ConnectElement'=>'componentSite',
                           'UrlForComponent'=>route__("actiongetNewCodeForComponent_admin_mainstay_backcontroller")
                       ])
                     @else
                        <p style="color: red">В темплейте компонентов есть не экранированые кавычки или нарушен структура компонента!!! </p>
                        <p>{{$error_view['message']}}</p>
                        <textarea id="ErrorEditor" style="width: 900px;height: 600px">@php echo $template['body_view'];@endphp</textarea>
                     @endif
                </div>


          </div>
          <div id='seo' class='tab-pane fade in'>
              <table>
                <tr style='vertical-align:top;padding-left:10px'>
                    <td style='width: 60%'>
                        @if(isset($seo['id']))<input type='hidden' name="seo['id']" data-name='id' value='{{$seo['id']}}'>@endif
                        <h1 class="title-gradient" >Заголовок</h1>
                        <textarea class='fnt-editor24 w-full' style="height: 30vh;" id='titleSeo' name="seo['title']" data-name='title' cols='100' rows='1'>@if(isset($seo['title'])){{$seo['title']}}@endif</textarea>
                        <h1 class="title-gradient">Дескрипшен</h1>
                        <textarea class='fnt-editor24 w-full' style="height: 70vh;" id='descriptionSeo' name="seo['description']" data-name='description' cols='100' rows='10'>@if(isset($seo['description'])){{$seo['description']}}@endif</textarea>
                    </td>
                    <td style='width:20%;padding-right:10px;padding-left: 10px'>
                        <h1>Список значений</h1>
                        @include('component.dragDropComponent.dragDropUlList',[
                                 "name" => 'paramsDescription',
                                 "autostart" => 'true',
                                 "url" => route__("actionGetModuleLogic_admin_mainstay_backcontroller"),
                                 "container" => ["descriptionSeo","titleSeo"],

                                 "callDropFunction" => "function(event,ui) {
                                       var cursor = $(event.target).data('cursorPos')
                                       if(cursor == undefined && cursor > 0){
                                            $(event.target).val($(event.target).val() + '<<' + $(ui.draggable[0]).data('template')) + '>>'
                                       }else{
                                           var text = $(event.target).val()
                                           var textStatr = text.substr(0,cursor)
                                           var textEnd = text.substr(cursor)
                                           $(event.target).val(textStatr + '<<' + $(ui.draggable[0]).data('template') + '>>' + textEnd);
                                       }
                                 }",
                                 "template" => "<div class='dropdown' v-bind:id='name'
                                           <ul v-bind:id=\"name+'_body'\">
                                                <li v-for=\"(items_ , index) in data \">
                                                    <p class='btn btn-primary dropdown-toggle group_drop' v-bind:id=\"items_.class_name\" v-if=\"items_.lable.length > 0\">@{{items_.class_name}}</p>
                                                    <ul v-bind:id=\"items_.class_name+'dragendrop'\"  v-bind:data-target=\"items_.class_name\" style='margin-left:10px;display:none' v-if=\"items_.lable.length > 0\">
                                                        <li v-bind:data-template=\"items_.class_name+'.'+nameCode.key \" class='group_drop_item dragendrop' v-for=\"(nameCode , index_) in items_.lable\"  v-if=\"nameCode.key !=  nameCode.name \" >
                                                            <a >@{{nameCode.name}}</a>
                                                        </li>
                                                    </ul>
                                                </li>
                                           </ul>
                                 </div>"
                        ])
                        <!---->
                    </td>
                </tr>
              </table>
          </div>
          <div id='file_include'  class='tab-pane fade in'>
                <h1 class="title-gradient">Script js file</h1>
                <textarea class='fnt-editor24 w-full' name="include['script']" data-name='body_script_view' readonly='true' cols='120' rows='10'></textarea>
                <h1 class="title-gradient">Style file</h1>
                <textarea class='fnt-editor24 w-full' name="include['style']" data-name='body_link_view' readonly='true' cols='120' rows='10'></textarea>
            </div>
        </div>
@endpush

@pushOnce('component-js')
<script>
    $(document).ready(function(){
        @if(isset($error_view))
            var template = @php echo json_encode($template) @endphp;
        @endif

        $('#descriptionSeo,#titleSeo').blur(function() {
            var cursorPos = $(this).prop('selectionStart');
            $(this).data('cursorPos',cursorPos)
        });
        page__.waitLoadComponent(function () {
            if ($('.dropdown-toggle').length > 0) {
                $('.dropdown-toggle').click(function() {
                    $("[data-target='"+$(this).attr("id")+"'").toggle(1000,"linear");
                })
                return true;
            } else {
                return false;
            }
        })
        @if($check)
        $('#addParamsTemplate').click(function() {
            var urlData = page__.getUrlInfo();
            var params = new URLSearchParams(urlData.search);
            $('#globalParamsTempalte').find('input').each(function() {
                params.append($(this).attr('name'),$(this).val())
            })
            location.href = urlData.origin + urlData.pathname + "?"+params.toString()
        });
        @endif
        $('#SaveTemplate').click(function() {
            var dataSend = {}
            @if(!isset($error_view))
                dataSend['template'] = page__.getElementPage('HtmlEditor').obj.getTemplate();
            @else
                dataSend['template'] =  @php echo json_encode($template) @endphp;
                dataSend['template']['body_view'] = $("#ErrorEditor").val();
            @endif
            var seo = $('textarea[name*=seo],input[name*=seo]').get();
            dataSend['seo'] = {};
            for (var key in seo)
                dataSend['seo'][$(seo[key]).data('name')] = $(seo[key]).val()
            var file = $('textarea[name*=include]').get();

            page__.sendData('{{route__("actionTempateSave_admin_mainstay_backcontroller")}}', dataSend, function(data) {
                if (data !== false) {window.close();}
            });
        })
    });

</script>
@endPushOnce
