@push('js-lib-component-head')
    <script date-id_script="{{$url}}">
          page__.loadGlobalData(
                '{{$url}}',
                @php echo json_encode(isset($params)?$params:[]) @endphp,
                '{{$clear_name}}',
                @php echo isset($callback)? $callback: "null" @endphp,
                @php echo isset($data)? $data: "null" @endphp,
            )
    </script>
@endpush
