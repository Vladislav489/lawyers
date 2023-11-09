@isset($format)
        @php  $data = date($format) @endphp
        @php echo $data @endphp
@endisset
