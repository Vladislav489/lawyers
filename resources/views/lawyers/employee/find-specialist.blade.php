@extends('lawyers.layouts.main')
@section('title', 'Найти специалиста')

@section('content')
    <section class="find-section u-container">
        <div class="container">
            <ul class="breadcrumbs">
                <li class="cool-underline"><a href="#">Юрист</a></li>
                <li class="cool-underline"><a href="#">Город</a></li>
            </ul>

            <form action="#" class="find-block find-form" id="search_form">
                <label class="search-label">
                    <input type="search" name="search-spec" id="search-spec" placeholder="Имя и фамилия специалиста...">
                    <button type="submit" id="send" class="search-icon">
                        <img type="image" src="/lawyers/images/icons/search-icon-blue-full.svg" alt="search-icon">
                    </button>
                </label>
                <div class="unit-select">
                    <div class="unit-select_row">
                        <span class="unit-select_subtext">Регион</span>
                        @include('component_build',["component" => "component.listComponent.selectComponent",
                            "params_component" => [
                                "autostart" => 'true',
                                "name" => 'region_id',
                                "default_title" => 'Регион',
                                "url" => route("actionGetRegions_mainstay_helpdata_helpdatamainstaycontroller"),
                                "callAfterloadComponent" => "function(component) {
                                    $('.js_select').select2({
                                    	minimumResultsForSearch: -1,
                                    });
                                    return component.option;
                                 }",
                                "template" =>
                                '<select class="unit-select_select js_select" name="region_id" :id="name" style="width:100%">
                                    <option value="" selected>Выбрать</option>
                                    <option v-for="(items_ , index) in data " :data-text="items_" :value="index">@{{items_}}</option>
                                </select>',
                                "change" => "function(){
                                            if($(this).val() !== '') {
                                                const param = {'region_id': $(this).find('option:selected').val()}
                                                page__.getElementsGroup('city_id')[0]['obj'].setUrlParams(param)
                                                }

                                            }"
                            ]])
                    </div>

                    <div class="unit-select_row">
                        <span class="unit-select_subtext">Город</span>
                        @include('component_build',["component" => "component.listComponent.selectComponent",
                            "params_component" => [
                                "autostart" => 'false',
                                "name" => 'city_id',
                                "default_title" => 'Город',
                                "url" => route("actionGetCities_mainstay_helpdata_helpdatamainstaycontroller"),
                                "template" =>
                                '<select class="unit-select_select js_select" name="city_id" :id="name" style="width:100%">
                                    <option id="stub" value="" selected="true">Выбрать</option>
                                    <option v-for="(items_ , index) in data " :data-text="items_" :value="index">@{{items_}}</option>
                                </select>',
                                "change" => "function(){

                                    }"
                            ]])
                    </div>

                    <div class="unit-select_row">
                        <span class="unit-select_subtext">Категория услуг</span>
                        @include('component_build',["component" => "component.listComponent.selectComponent",
                            "params_component" => [
                                "autostart" => 'true',
                                "name" => 'service_id',
                                "default_title" => 'Сервис',
                                "url" => route("actionGetServiceTypeListForSelect_mainstay_service_servicemainstaycontroller"),
                                "callAfterloadComponent" => "function(component) {
                                    $('.js_select').select2({
                                    	minimumResultsForSearch: -1,
                                    });
                                    return component.option;
                                 }",
                                "template" =>
                                '<select class="unit-select_select js_select" name="service_type_id" :id="name" style="width:100%">
                                    <option value="" selected="true">Выбрать</option>
                                    <option v-for="(items_ , index) in data " :data-text="items_" :value="index">@{{items_}}</option>
                                </select>',
                                "change" => "function(){
                                            if($(this).val() !== '') {
                                                const param = {'type_id': $(this).find('option:selected').val()}
                                                page__.getElementsGroup('type_id')[0]['obj'].setUrlParams(param)
                                                }
                                            }"
                            ]])
                    </div>

                    <div class="unit-select_row">
                        <span class="unit-select_subtext">Тема услуги</span>
                        @include('component_build',["component" => "component.listComponent.selectComponent",
                            "params_component" => [
                                "autostart" => 'false',
                                "name" => 'type_id',
                                "default_title" => 'Сервис',
                                "url" => route("actionGetServiceList_mainstay_service_servicemainstaycontroller"),
                                "template" =>
                                '<select class="unit-select_select js_select" name="service_id" :id="name" style="width:100%">
                                    <option value="" selected="true">Выбрать</option>
                                    <option v-for="item in data " :data-text="item.name" :value="item.id">@{{ item.name }}</option>
                                </select>',
                                "change" => "function(){}"
                            ]])
                    </div>
                </div>

                <div class="unit-select">
                    <div class="unit-select_row">
                        <span class="unit-select_subtext">Рейтинг</span>
{{--                        <div class="unit-select_select select-btn">--}}
{{--                            <p class="unit-select_text">Не важен</p>--}}
{{--                            <img class="sub-icon" src="/lawyers/images/icons/arrow-icon-gray.svg" alt="arrow-icon">--}}
{{--                        </div>--}}
{{--                        <ul class="select-window">--}}
{{--                            <li>1 звезда</li>--}}
{{--                            <li>2 звезды</li>--}}
{{--                            <li>5 звезд</li>--}}
{{--                            <li>Не важен</li>--}}
{{--                        </ul>--}}
                        <select class="unit-select_select js_select" name="rating" id="rating">
                            <option value="" selected="true">Любой</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>

                    <div class="unit-select_row">
                        <span class="unit-select_subtext">Оценка</span>
{{--                        <div class="unit-select_select select-btn">--}}
{{--                            <p class="unit-select_text">Не важна</p>--}}
{{--                            <img class="sub-icon" src="/lawyers/images/icons/arrow-icon-gray.svg" alt="arrow-icon">--}}
{{--                        </div>--}}
{{--                        <ul class="select-window">--}}
{{--                            <li>Не важна</li>--}}
{{--                            <li>1</li>--}}
{{--                            <li>2</li>--}}
{{--                            <li>3</li>--}}
{{--                        </ul>--}}
                        <select class="unit-select_select js_select" name="evaluation" id="evaluation">
                            <option value="" selected="true">Любая</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>

                    <div class="unit-select_row">
                        <span class="unit-select_subtext">Опыт работы</span>
{{--                        <div class="unit-select_select select-btn">--}}
{{--                            <p class="unit-select_text">От 10 лет</p>--}}
{{--                            <img class="sub-icon" src="/lawyers/images/icons/arrow-icon-gray.svg" alt="arrow-icon">--}}
{{--                        </div>--}}
{{--                        <ul class="select-window">--}}
{{--                            <li>От 20 лет</li>--}}
{{--                            <li>От 30 лет</li>--}}
{{--                        </ul>--}}
                        <select class="unit-select_select js_select" name="experience" id="experience">
                            <option value="" selected="true">Любой</option>
                            <option value="1">От 1 года</option>
                            <option value="3">От 3 лет</option>
                            <option value="6">От 6 лет</option>
                            <option value="10">От 10 лет</option>
                        </select>
                    </div>

                    <button class="find_reset-filter" type="reset">сбросить фильтр</button>
                </div>
            </form>

            <div class="find-block find-right">
                <h3 class="find-question_title">Есть определенный вопрос?</h3>
                <p class="find-question_text">
                    Задай вопрос юристу или создай задачу на бирже.
                    Командапрофессионалов поможет решить задачу в юридическом поле и дать консультацию.
                </p>
                <div class="buttons-container">
                    <a href="#" class="main-btn main-btn_orange"><span>Бесплатная консультация</span></a>
                    <a href="{{route__('actionCreateVacancy_controllers_client_clientcontroller')}}" class="main-btn"><span>Создать задачу на бирже</span></a>
                </div>
            </div>
        </div>
    </section>

    <section class="fs-section u-container">
        <div class="container">


                @include('component_build', [
                    'component' => 'component.gridComponent.simpleGrid',
                    'params_component' => [
                        'autostart' => 'false',
						'ssr' => 'true',
                        'name' => 'employee_list',
                        'url' => route__("actionGetEmployeeList_mainstay_employee_employeemainstaycontroller"),

                        'template' =>
                        "<div :id=\"name + '_body'\">
                        <h2 class='find-section_header'>Найдено: <span>@{{ pagination.totalCount }} специалистов</span></h2>
                        <div class='found-specialists' >
                            <div v-for=\"item in data\" class='fs-block' >
                                <div class='fs-img'>
                                    <img :src=\"'/storage' + item.avatar_path\" alt='lawyer-img'>
                                </div>

                                <div class='fs-info'>
                                    <a :href=\"'".route__('actionSpecialistCard_controller')."/'+item.user_id\">
                                    <h3 class='fs-name'>@{{ item.last_name + ' ' + item.first_name + ' ' + item.middle_name }}</h3></a>
                                    <p class='fs-row'>
                                        <img class='icon' src='/lawyers/images/icons/loc-icon-gray.svg' alt='loc-icon'>
                                        <span class='fs-text'>Москва и МО, пр. Роберта Рождественского, 522</span>
                                    </p>
                                    <p class='fs-row'>
                                        <img class='icon' src='/lawyers/images/icons/bag-icon-gray.svg' alt='bag-icon'>
                                        <span class='fs-text'>@{{ agetostr(item.practice_years) }} практики</span>
                                    </p>

                                    <div class='lawyer_rate-block'>
                                        <div class='specialist-rate'>
                                            <div class='stars'>
                                                <img src='/lawyers/images/icons/star-icon-full.svg' alt='star-icon'>
                                                <img src='/lawyers/images/icons/star-icon-full.svg' alt='star-icon'>
                                                <img src='/lawyers/images/icons/star-icon-full.svg' alt='star-icon'>
                                                <img src='/lawyers/images/icons/star-icon-full.svg' alt='star-icon'>
                                                <img src='/lawyers/images/icons/star-icon-empty.svg' alt='star-icon'>
                                            </div>
                                            <span>32 ответа</span>
                                        </div>
                                        <div class='specialist-perm'>
                                            <p>Право рейтинг:</p>
                                            <span>4.0</span>
                                            <img class='icon' src='/lawyers/images/icons/info-icon-gray.svg' alt='info-icon'>
                                        </div>
                                    </div>

                                    <p class='fs-text'>
                                        @{{ item.about }}
                                    </p>
                                    <ul class='fs-text_bold' v-for=\"service in JSON.parse(item.service)\">
                                        <li>@{{ service.service_name }} </li>
                                    </ul>
                                </div>

                                <div class='buttons-container'>
                                    <button class='main-btn main-btn_blue'>Консультация: @{{ item.consultation_price }} &#8381;</button>
                                    <button class='main-btn main-btn_white'>Заказать звонок</button>
                                    <button class='main-btn main-btn_white'>Сообщение</button>
                                </div>
                            </div>
                        </div>
                        </div>",
                        'pagination'=>
                        [
							'page'=> 1,
							'pageSize'=> 3,
							'countPage'=> 1,
							'typePagination'=> 1,
							'showPagination'=> 1,
							'showInPage'=> 4,
							'count_line'=> 1,
							'all_load'=> 0,
							'physical_presence'=> 0
						],
                    ]
                ])
        </div>
    </section>
    <script>
        $(document).ready(function () {
            filter()
            resetFilters()
        })

        function filter() {
            $('#send').on('click', function (e) {
                e.preventDefault()
                let params = getFilterParams()
                console.log(page__.getElementsGroup('employee_list')[0]['obj']);
                page__.getElementsGroup('employee_list')[0]['obj'].setUrlParams(params)
            })
        }

        function clearFilterInputs() {
            const form = $('form')
            form.find('input').val('')
            const selects = form.find('select')
            for(let index = 0; index < selects.length;index++){
                let key = $(selects[index]).attr("name");
                if(key){
                    $(selects[index]).prop('selectedIndex', 0)
                }
            }
        }

        function resetFilters() {
            $('.find_reset-filter').on('click', function () {
                const params = page__.getElementsGroup('employee_list')[0]['obj'].getUrlParams()
                delete params['page']
                delete params['pageSize']
                let hasParams = false
                for (let index = 0; index < Object.keys(params).length; index++) {
                    if (params[index] !== undefined || params[index] !== null || params[index] !== '') {
                        hasParams = true;
                    }
                }
                if (hasParams) {
                    clearFilterInputs()
                    page__.getElementsGroup('employee_list')[0]['obj'].setUrlParams({})
                }
                clearFilterInputs()
            })
        }

        function getFilterParams() {
            return {
                'search_spec': $('#search-spec').val(),
                'region_id': $('[name = region_id]').val(),
                'city_id': $('[name = city_id]').val(),
                'service_type_id': $('[name = service_type_id]').val(),
                'service_id': $('[name = service_id]').val(),
                'rating': $('#rating').val(),
                'evaluation': $('#evaluation').val(),
                'experience': $('#experience').val(),
            }
        }

        function agetostr(age) {
            var txt;
            count = age % 100;
            if (count >= 5 && count <= 20) {
                txt = 'лет';
            } else {
                count = count % 10;
                if (count == 1) {
                    txt = 'год';
                } else if (count >= 2 && count <= 4) {
                    txt = 'года';
                } else {
                    txt = 'лет';
                }
            }
            return age+" "+txt;
        }
    </script>
@endsection
