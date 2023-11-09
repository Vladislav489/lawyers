@php
    $includeFromHeadToDown = (isset($includeFromHeadToDown) && $includeFromHeadToDown == "true" )?true:false;
    $stackNameScript = (isset($includeFromHeadToDown) && $includeFromHeadToDown)? 'js-lib-component-head':'component-load-js';
@endphp
<component id="component_sendData_{{uniqid("data")}}">
    @if(!$includeFromHeadToDown)
        @push($stackNameScript)
    @endif
    <script date-id_script="{{$url}}">
        $(document).ready(function() {
            page__.sendData('{{$url}}',@php echo json_encode(isset($params)?$params:[]) @endphp,@php echo isset($callback)?$callback:"" @endphp)
        });
    </script>
    @if(!$includeFromHeadToDown)
        @endpush
    @endif
</component>