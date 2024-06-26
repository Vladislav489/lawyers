@php
    if(!isset($includeToComponent__)) $includeToComponent__ = false;
    if( $includeToComponent__  == true)$name = $clear_name;
    $includeFromHeadToDown = (isset($includeFromHeadToDown) && $includeFromHeadToDown = "true" )?true:false;
    $stackNameScript = (isset($includeFromHeadToDown) && $includeFromHeadToDown)? 'js-lib-component-head':'js-lib-component';
    $include =(isset($include))?$include:false;
    $textComponet = (isset($admin))?"Add component Text Info double click for set setting":"";

    $style = "";
    if(isset($sizePreview)){
       $style = "style='".implode(";",$sizePreview)."'";
    }
    $defaultViewTemplate = "`<div class='dz-preview dz-file-preview' >
                                                <div class='dz-image' $style ><img data-dz-thumbnail/></div>
                                            <div class='dz-details'>
                                               <div class='dz-size'><span data-dz-size></span></div>
                                                <div class='dz-filename'><span data-dz-name></span></div>
                                            </div>
                                            <div class='dz-error-message'>  <span data-dz-errormessage></span></div>
                                            <div class='dz-success-mark'>
                                                <svg width='54px' height='54px' viewBox='0 0 54 54' version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'>
                                                    <title>Check</title>
                                                    <g stroke='none' stroke-width='1' fill='none' fill-rule='evenodd'>
                                                        <path d='M23.5,31.8431458 L17.5852419,25.9283877 C16.0248253,24.3679711 13.4910294,24.366835 11.9289322,25.9289322 C10.3700136,27.4878508 10.3665912,30.0234455 11.9283877,31.5852419 L20.4147581,40.0716123 C20.5133999,40.1702541 20.6159315,40.2626649 20.7218615,40.3488435 C22.2835669,41.8725651 24.794234,41.8626202 26.3461564,40.3106978 L43.3106978,23.3461564 C44.8771021,21.7797521 44.8758057,19.2483887 43.3137085,17.6862915 C41.7547899,16.1273729 39.2176035,16.1255422 37.6538436,17.6893022 L23.5,31.8431458 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z' stroke-opacity='0.198794158' stroke='#747474' fill-opacity='0.816519475' fill='#FFFFFF'></path>
                                                    </g>
                                                </svg>
                                            </div>
                                            <div class='dz-error-mark'>
                                                <svg width='54px' height='54px' viewBox='0 0 54 54' version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'>
                                                    <title>Error</title>
                                                    <g stroke='none' stroke-width='1' fill='none' fill-rule='evenodd'>
                                                        <g stroke='#747474' stroke-opacity='0.198794158' fill='#FFFFFF' fill-opacity='0.816519475'>
                                                            <path d='M32.6568542,29 L38.3106978,23.3461564 C39.8771021,21.7797521 39.8758057,19.2483887 38.3137085,17.6862915 C36.7547899,16.1273729 34.2176035,16.1255422 32.6538436,17.6893022 L27,23.3431458 L21.3461564,17.6893022 C19.7823965,16.1255422 17.2452101,16.1273729 15.6862915,17.6862915 C14.1241943,19.2483887 14.1228979,21.7797521 15.6893022,23.3461564 L21.3431458,29 L15.6893022,34.6538436 C14.1228979,36.2202479 14.1241943,38.7516113 15.6862915,40.3137085 C17.2452101,41.8726271 19.7823965,41.8744578 21.3461564,40.3106978 L27,34.6568542 L32.6538436,40.3106978 C34.2176035,41.8744578 36.7547899,41.8726271 38.3137085,40.3137085 C39.8758057,38.7516113 39.8771021,36.2202479 38.3106978,34.6538436 L32.6568542,29 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z'></path>
                                                        </g>
                                                    </g>
                                                </svg>
                                            </div>
                                    </div>`";

    $templateView = (isset($templateView))?$templateView:$defaultViewTemplate;
@endphp
@pushOnce('css-style')
<link type="text/css"  rel="stylesheet" href="/js/dropzone/dropzone1/dropzone.css">

@endpushOnce
@pushOnce($stackNameScript)
<script type="text/javascript" src="/js/dropzone/dropzone1/dropzone.min.js"></script>
@if(isset($editplagin) && $editplagin)
    <script type="text/javascript" src="/js/editor/dist/tui-image-editor.js"></script>
    <script type="text/javascript" src="/js/editor/js/theme/white-theme.js"></script>
@endif
@endPushOnce
@if(!$include)
    @if(isset($editplagin) && $editplagin)
        @include('component_build',["component" => 'component.imageComponet.imageEditor',
               'params_component' => [
               'name' => 'dropZoneimageEditor',
               'targetReturnData' => 'component_dropzone_'.$name,
               "url" => route__("actionList_admin_mainstay_entity_entitycontroller")
           ]])
    @endif
    @php
        if(strpos($name,'view_component_') !== false) $name = uniqid(str_replace('view_component_','',$name));
    @endphp
    <component id="component_dropzone_{{$name}}" data-name="{{$name}} ">
           <div id="dropzone_{{$name}}" class="dropzone image-carousel_wrap app-part"></div>
    </component>
    @if($includeToComponent__ != true)
        @if(!$includeFromHeadToDown)
            @push('component-js')
                @endif
                @endif
                <script date-id_script="{{$name}}">
                    function openEditImage(object,name){
                        console.log(object,name);
                        console.log(page__.object);
                        var file = page__.object[name]['obj'].getAcceptedFiles();
                        var target = null;
                        for(key in file){
                            if(file[key].name == $(object).parent().find(".dz-filename span").text()){
                                target = file[key];
                                target['index'] = key;
                                target['targetPreview'] = $(object);
                            }
                        }
                        page__.getComponentByName('dropZoneimageEditor').setFileToEdit(target);
                    }

                    function deleteImage(object,name,routedelete) {
                       var name = $(object).parent().parent().find('.dz-image > img').attr('alt');
                       page__.sendData(routedelete,{'file_name':name},function() {
                           if(data['result'] == 0) {
                               jAlertError("Ошибка","Изображение не удаленно!");
                               return false;
                           }
                           jAlertMessage("Изображение удаленно",name+" !");
                       });
                    }

                    @if($includeToComponent__ != true )
                        @if(!$includeFromHeadToDown)
                            $(document).ready(function() {
                        @endif
                    @endif



                        var dropzone_{{$name}} = new Dropzone("#dropzone_{{$name}}", {
                            url:"{{$url}}",
                            uploadMultiple:true,
                            autoQueue:false,
                            maxFilesize: 2,
                            maxFiles:10,
                            paramName:true,
                            addRemoveLinks:true,
                            dictDefaultMessage:"Перетащите файл или нажмите",
                            dictRemoveFile:"<span onclick=\"deleteImage(this,'component_dropzone_{{$name}}','{{(isset($deleteRout))?$deleteRout:''}}')\">Удалить</span>",
                            thumbnailWidth:{{(isset($thumbnailWidth))?$thumbnailWidth:150}},
                            thumbnailHeight:{{(isset($thumbnailWidth))?$thumbnailHeight:270}},
                            autoProcessQueue:false,
                            previewTemplate:@php echo $templateView; @endphp
                        });
                        page__.addNewElement(dropzone_{{$name}}, 'component_dropzone_{{$name}}')

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
