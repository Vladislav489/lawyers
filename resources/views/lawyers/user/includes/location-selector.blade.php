<label class="registration-form_label">
                        <span class="label-title">Регион</span>
@include('component_build',["component" => "component.listComponent.selectComponent",
    "params_component" => [
        "autostart" => 'true',
        "name" => 'region_id',
        "default_title" => 'Регион',
        "url" => route("actionGetRegions_mainstay_helpdata_helpdatamainstaycontroller"),
        "template" => 'simpleSelect',
        "change" => "function(){
                if($(this).val() !== '') {
                        const param = {'region_id': $(this).find('option:selected').val()}
                        page__.getElementsGroup('city_id')[0]['obj'].setUrlParams(param)
                    }
            }"
    ]])
@error('region_id')
<div style="color: red">{{ $message }}</div>
@enderror
</label>

<label class="registration-form_label">
    <span class="label-title">Город</span>
    @include('component_build',["component" => "component.listComponent.selectComponent",
        "params_component" => [
            "autostart" => 'false',
            "name" => 'city_id',
            "default_title" => 'Город',
            "url" => route("actionGetCities_mainstay_helpdata_helpdatamainstaycontroller"),
            "template" => 'simpleSelect',
            "change" => "function(){}"
        ]])
    @error('city_id')
    <div style="color: red">{{ $message }}</div>
    @enderror</label>
