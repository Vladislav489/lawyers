@php
    if(!isset($includeToComponent__)) $includeToComponent__ = false;
    if( $includeToComponent__  == true)$name = $clear_name;
    $includeFromHeadToDown = (isset($includeFromHeadToDown) && $includeFromHeadToDown = "true" )?true:false;
    $stackNameScript = (isset($includeFromHeadToDown) && $includeFromHeadToDown)? 'js-lib-component-head':'js-lib-component';
    $include =(isset($include))?$include:false;
    $textComponet = (isset($admin))?"Add component Text Info double click for set setting":"";
    $listPlaginCSS = [
        'FilePondPluginImagePreview' => '/js/dropzone/dropzone2/preview/filepond-plugin-image-preview.min.css',
        'FilePondPluginFilePoster' => '/js/dropzone/dropzone2/poster/filepond-plugin-file-poster.min.css',
        'FilePondPluginImageEdit' => '/js/dropzone/dropzone2/edit/filepond-plugin-image-edit.min.js',
    ];

    $listPlaginJS = [
        'FilePondPluginImagePreview' => '/js/dropzone/dropzone2/preview/filepond-plugin-image-preview.min.js',
        'FilePondPluginImageResize' => '/js/dropzone/dropzone2/resize/filepond-plugin-image-resize.min.js',
        'FilePondPluginImageTransform' => '/js/dropzone/dropzone2/transform/filepond-plugin-image-transform.min.js',
        'FilePondPlugi  nFilePoster' => '/js/dropzone/dropzone2/poster/filepond-plugin-file-poster.min.js',
        'FilePondPluginImageExifOrientation' => '/js/dropzone/dropzone2/orientation/filepond-plugin-image-exif-orientation.min.js',
        'FilePondPluginFileEncode' => '/js/dropzone/dropzone2/encode/filepond-plugin-file-encode.min.js',
        'FilePondPluginFileValidateType' => '/js/dropzone/dropzone2/type/filepond-plugin-file-validate-type.min.js',
        'FilePondPluginImageEdit' => '/js/dropzone/dropzone2/edit/filepond-plugin-image-edit.min.js',
        'FilePondPluginImageCrop' => '/js/dropzone/dropzone2/crop/filepond-plugin-image-crop.min.js',
        'FilePondPluginFileValidateSize' => '/js/dropzone/dropzone2/size/filepond-plugin-file-validate-size.min.js',
    ];

@endphp
@pushOnce('css-style')
@foreach(array_intersect_key($listPlaginCSS,array_combine($plagin,$plagin)) as $key => $item)
    <link type="text/css"  rel="stylesheet" href="{{$item}}">
@endforeach
<link type="text/css"  rel="stylesheet" href="/js/dropzone/dropzone2/filepond.min.css">
@endpushOnce
@pushOnce($stackNameScript)
    @foreach(array_intersect_key($listPlaginJS,array_combine($plagin,$plagin)) as $key => $item)
        <script type="module"  src="{{$item}}"></script>
    @endforeach
    <script src="/js/dropzone/dropzone2/filepond.min.js"></script>
    <script src="/js/dropzone/dropzone2/filepond.jquery.js"></script>
@endPushOnce
@if(!$include)
    @php
        if(strpos($name,'view_component_') !== false) $name = uniqid(str_replace('view_component_','',$name));
    @endphp
<component id="component_textInfo_{{$name}}" data-name="{{$name}}">
    <input id="component_{{$name}}" type="file" class="my-pond" name="filepond"/>
</component>
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
                @foreach($plagin as $item)
                    $.fn.filepond.registerPlugin({{$item}});
                @endforeach
                var inputElement = document.querySelector("#component_textInfo_{{$name}}")
            const pond = FilePond.create(inputElement, {
                imageResizeTargetWidth: 256,
                onaddfile: (err, fileItem) => {
                    console.log(err, fileItem.getMetadata('resize'));
                },

                // add onpreparefile callback
                onpreparefile: (fileItem, output) => {
                    // create a new image object
                    const img = new Image();

                    // set the image source to the output of the Image Transform plugin
                    img.src = URL.createObjectURL(output);

                    // add it to the DOM so we can see the result
                    document.body.appendChild(img);
                }});

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
