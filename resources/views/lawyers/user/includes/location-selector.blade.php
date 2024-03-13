<label class="registration-form_label">
                        <span class="label-title">Страна</span>
@include('component_build',["component" => "component.listComponent.selectComponent",
    "params_component" => [
        "autostart" => 'true',
        "name" => 'country_id',
        "default_title" => 'Страна',
        "url" => route("actionGetCountries_mainstay_helpdata_helpdatamainstaycontroller"),
        "template" => 'simpleSelect',
        "change" => "function(){
                if($(this).val() !== '') {
                        const param = {'country_id': $(this).find('option:selected').val()}
                        page__.getElementsGroup('state_id')[0]['obj'].setUrlParams(param)
                    }
            }"
    ]])
@error('country_id')
<div style="color: red">{{ $message }}</div>
@enderror
</label>
<label class="registration-form_label">
    <span class="label-title">Область</span>
    @include('component_build',["component" => "component.listComponent.selectComponent",
        "params_component" => [
            "autostart" => 'true',
            "name" => 'state_id',
            "default_title" => 'Область',
            "url" => route("actionGetStates2_mainstay_helpdata_helpdatamainstaycontroller"),
            "template" => 'simpleSelect',
            "change" => "function(){
                    if($(this).val() !== '') {
                            const param = {
                            'state_id': $(this).find('option:selected').val()
                            }
                            page__.getElementsGroup('district_id')[0]['obj'].setUrlParams(param)
                        }
                }"
        ]])
    @error('state_id')
    <div style="color: red">{{ $message }}</div>
    @enderror
</label>
<label class="registration-form_label date-label">
    <span class="label-title">Район</span>
    @include('component_build',["component" => "component.listComponent.selectComponent",
        "params_component" => [
            "autostart" => 'true',
            "name" => 'district_id',
            "default_title" => 'Район',
            "url" => route("actionGetDistricts_mainstay_helpdata_helpdatamainstaycontroller"),
            "template" => 'simpleSelect',
            "change" => "function(){
                if($(this).val() !== '') {
                            const param = {'country_id': $(this).find('option:selected').val()}
                            page__.getElementsGroup('city_id')[0]['obj'].setUrlParams(param)
                        }
            }"
        ]])
    @error('district_id')
    <div style="color: red">{{ $message }}</div>
    @enderror
</label>
<label class="registration-form_label">
    <span class="label-title">Город</span>
    @include('component_build',["component" => "component.listComponent.selectComponent",
        "params_component" => [
            "autostart" => 'true',
            "name" => 'city_id',
            "default_title" => 'Город',
            "url" => route("actionGetCities_mainstay_helpdata_helpdatamainstaycontroller"),
            "template" => 'simpleSelect',
            "change" => "function(){}"
        ]])
    @error('city_id')
    <div style="color: red">{{ $message }}</div>
    @enderror</label>
