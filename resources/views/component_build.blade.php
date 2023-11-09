@php
    if(isset($params_component['data'])  && !is_array($params_component['data']) &&
        strpos($params_component['data'],'template_clear') !== false)
            $params_component['data'] = str_replace(["'",'"'],'',$params_component['data']);

    $componemt = new \App\Models\System\Component\ComponentBuilder();

@endphp
@if(isset($add_link_component))
    @php echo $componemt->buildComponent([
        'component' => $component,
        'params_component' => $params_component,
        'add_link_component' => $add_link_component,
        'route' => (isset($route))?$route:[]
    ])@endphp
@else
    @php echo $componemt->buildComponent([
        'component' => $component,
        'params_component' => $params_component,
        'route' => (isset($route))?$route:[]
    ])@endphp
@endif
