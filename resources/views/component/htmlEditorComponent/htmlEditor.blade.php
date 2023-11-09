@pushOnce('css-style')
@endpushOnce
@pushOnce('js-lib-component-head')
<script src="/js/CKEditor/ckeditor.js"></script>
<script src="/js/component/htmlEditirComponent/htmlEditirComponent.js"></script>
@endPushOnce
<component id="component_htmlEditor_{{$name}}" data-name="{{$name}}">
    <div id="html_editor_{{$name}}">
        <textarea  style="visibility: hidden"  id="{{$name}}_editor" rows="120"></textarea>
    </div>
    @push('component-js')
        <script date-id_script="{{$name}}">
            $(document).ready(function () {
                var params_{{$name}} ={
                    'name':'{{$name}}',
                    'template': @php echo (empty($template))?"''":json_encode($template) @endphp,
                    'url_buld_view':'{{$url_buld_view}}',
                    'ConnectElement':'{{isset($ConnectElement)?$ConnectElement:'null'}}',
                    'callAfterInit':@php echo isset($callAfterInit)?$callAfterInit:'null'; @endphp,
                    'UrlForComponent':'{{isset($UrlForComponent)?$UrlForComponent:'null'}}'
                };
                var obj_{{$name}}  = new HtmlEditor('#{{$name}}_editor',params_{{$name}})
               if(params_{{$name}}.ConnectElement != null){
                    if(document.querySelector('#'+params_{{$name}}.ConnectElement) == undefined){
                        var intervakey = setInterval(function (){
                            if(document.querySelector('#'+params_{{$name}}.ConnectElement) !== undefined){
                                obj_{{$name}}.init();
                                page__.addNewElement(obj_{{$name}},'{{$name}}')
                                clearInterval(intervakey)
                            }

                        },400)
                    } else {
                        obj_{{$name}}.init();
                        page__.addNewElement(obj_{{$name}},'{{$name}}')
                    }
               }
            })
        </script>
    @endpush
</component>
