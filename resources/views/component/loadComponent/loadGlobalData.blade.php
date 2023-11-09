@php
    $includeFromHeadToDown = (isset($includeFromHeadToDown) && $includeFromHeadToDown == "true" )?true:false;
    $stackNameScript = (isset($includeFromHeadToDown) && $includeFromHeadToDown)? 'js-lib-component-head':'component-load-js';

@endphp
<component id="component_loadGlobalData_{{uniqid("data")}}">
    @if(!$includeFromHeadToDown)
        @push($stackNameScript)
    @endif
    <script date-id_script="{{$url}}">
        @if(!$includeFromHeadToDown)
            $(document).ready(function() {
        @endif
            page__.loadGlobalData(
                '{{$url}}',
                @php echo json_encode(isset($params)?$params:[]) @endphp,
                '{{$clear_name
                }}',
                @php echo isset($callback)? $callback: "null" @endphp,
                @php echo isset($data)? $data: "null" @endphp,
            )
        @if(!$includeFromHeadToDown)
            });
        @endif
    </script>
    @if(!$includeFromHeadToDown)
        @endpush
    @endif
</component>