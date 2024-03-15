@php
    if(!isset($includeToComponent__)) $includeToComponent__ = false;
    if( $includeToComponent__  == true)$name = $clear_name;
    $includeFromHeadToDown = (isset($includeFromHeadToDown) && $includeFromHeadToDown = "true" )?true:false;
    $stackNameScript = (isset($includeFromHeadToDown) && $includeFromHeadToDown)? 'js-lib-component-head':'js-lib-component';
    $include =(isset($include))?$include:false;
    $textComponet = (isset($admin))?"Add component Text Info double click for set setting":"";
@endphp
@pushOnce('css-style')
<link type="text/css" href="/js/editor/dist/tui-image-editor.css" rel="stylesheet" />
@endpushOnce
@pushOnce($stackNameScript)
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/1.6.7/fabric.js"></script>
<script type="text/javascript" src="https://uicdn.toast.com/tui.code-snippet/v1.5.0/tui-code-snippet.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/1.3.3/FileSaver.min.js"></script>
<script type="text/javascript" src="https://uicdn.toast.com/tui-color-picker/v2.2.0/tui-color-picker.js"></script>
<!--<script type="text/javascript" src="https://uicdn.toast.com/tui-image-editor/v3.3.0/tui-image-editor.js"></script>-->
<script type="text/javascript" src="/js/editor/dist/tui-image-editor.js"></script>
<script type="text/javascript" src="/js/editor/js/theme/white-theme.js"></script>
<script type="text/javascript" src="/js/component/imageComponent/imageEditor.js"></script>
@endPushOnce
@if(!$include)
    @php
        if(strpos($name,'view_component_') !== false) $name = uniqid(str_replace('view_component_','',$name));
    @endphp
    @if(isset($targetReturnData))
    @push('content')
    <component id="component_imageEditor_{{$name}}" data-name="{{$name}}">

        <div id='dialog_imageEditor_{{$name}}' class='popup popup--add-product'>
            <div class='popup__background'>
                <div class='popup__boundary'>
                    <div class='popup__container'>
                        <div class='icon--popup-close' onclick="$('#AddGroupTag').removeClass('popup-active')"></div>
                        <div class='popup_inner flex fd-column'>
                            <h2 class='popup_heading app-section_heading '>Группы Тегов</h2>
                            <div class='label text-muted'>Группы Тегов</div>
                            <div class='modal-body' style='width:1100px;height:850px'>
                                <div  id='editor_imageEditor_{{$name}}'><canvas></canvas></div>
                            </div>
                            <div class='popup_buttons flex justify-center'>
                                <button id='saveImageEdit_{{$name}}()' class='button'>Сохранить</button>
                                <button id='closeImageEdit_{{$name}}()' class='button' onclick="$('#dialog_imageEditor_{{$name}}').removeClass('popup-active')">Отменить</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </component>
    @endpush
    @else
        <component id="component_imageEditor_{{$name}}" data-name="{{$name}}">
                <div  id='editor_imageEditor_{{$name}}'><canvas></canvas></div>
        </component>
    @endif
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
                        var imageEditor_{{$name}} = new imageEditor({
                            'name':'editor_imageEditor_{{$name}}',
                            'name_component':'component_imageEditor_{{$name}}',
                            @if(isset($targetReturnData))
                            'dialog':'dialog_imageEditor_{{$name}}',
                            @endif
                                'targetReturnData':'{{isset($targetReturnData)?$targetReturnData:'undefined'}}',
                            'option':{{isset($option)? $option:'undefined'}},
                        });


                        @if(isset($targetReturnData))
                            $('#closeImageEdit_{{$name}}').click(function(){
                                imageEditor_{{$name}}.clear()
                            });
                            $('#dialog_imageEditor_{{$name}}').find('.close').click(function () {
                                imageEditor_{{$name}}.clear()
                            })
                            $('#saveImageEdit_{{$name}}').click(function () {
                                imageEditor_{{$name}}.getFileFromEdit();
                            })
                        @endif

                        page__.addNewElement(imageEditor_{{$name}},'component_imageEditor_{{$name}}')

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
