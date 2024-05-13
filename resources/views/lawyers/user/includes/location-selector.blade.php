<label class="registration-form_label">
                        <span class="label-title">Регион*</span>
@include('component_build',["component" => "component.listComponent.selectComponent",
    "params_component" => [
        "autostart" => 'true',
        "name" => 'region_id',
        "default_title" => 'Регион',
        "url" => route("actionGetRegions_mainstay_helpdata_helpdatamainstaycontroller"),
		"callAfterloadComponent" => "function() {
           $('.js_select').select2({
               language: {
                 noResults: function(){return 'Совпадений не найдено';},
               }
               });
               $('.js_select').one('select2:open', function(e) {
               $('input.select2-search__field').prop('placeholder', 'Поиск...');
           });
        }",
        "template" => '
        <select class="unit-select_select js_select" name="region_id" :id="name" style="width:100%">
            <option value="" selected="true">Выбрать</option>
            <option v-for="(items_ , index) in data " :data-text="items_" :value="index">@{{items_}}</option>
        </select>',
        "change" => "function(){
                if($(this).val() !== '') {
                    const param = {'region_id': $(this).find('option:selected').val()}
                    page__.getElementsGroup('city_id')[0]['obj'].setUrlParams(param)
                    $('select[name=city_id]').prop('selectedIndex', 0)
                    setTimeout(function () {
                        $('.js_select').select2({});
                    }, 200)
                }
            }"
    ]])
@error('region_id')
<div style="color: red">{{ $message }}</div>
@enderror
</label>

<label class="registration-form_label">
    <span class="label-title">Город*</span>
    @include('component_build',["component" => "component.listComponent.selectComponent",
        "params_component" => [
            "autostart" => 'false',
            "name" => 'city_id',
            "default_title" => 'Город',
            "url" => route("actionGetCities_mainstay_helpdata_helpdatamainstaycontroller"),
			"callAfterloadComponent" => "function() {
               $('.js_select').select2({});
            }",
            "template" => '
            <select class="unit-select_select js_select" name="city_id" :id="name" style="width:100%">
                <option value="" selected="true">Выбрать</option>
                <option v-for="(items_ , index) in data " :data-text="items_" :value="index">@{{items_}}</option>
            </select>',
            "change" => "function(){}"
        ]])
    @error('city_id')
    <div style="color: red">{{ $message }}</div>
    @enderror</label>
