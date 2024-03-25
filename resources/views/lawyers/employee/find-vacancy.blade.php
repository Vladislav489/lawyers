@extends('lawyers.layouts.main')

@section('title', 'Биржа заказов')

@section('content')
    <section class="find-section u-container">
        <div class="container">

            <form action="#" class="find-block find-form mobile-hidden">
                <label class="search-label">
                    <input type="search" id="search-spec" name="search-spec" placeholder="Имя и фамилия специалиста...">
                    <input id="send" type="image" src="/lawyers/images/icons/search-icon-blue-full.svg" class="search-icon" alt="search-icon">
                </label>

                <div class="unit-select">
                    <div class="unit-select_row">
                        <span class="unit-select_subtext">Страна</span>
                        @include('component_build',["component" => "component.listComponent.selectComponent",
                            "params_component" => [
                                "autostart" => 'true',
                                "name" => 'country_id',
                                "default_title" => 'Страна',
                                "url" => route("actionGetCountries_mainstay_helpdata_helpdatamainstaycontroller"),
                                "template" =>
                                '<select class="unit-select_select" name="country_id" :id="name" style="width:100%">
                                    <option value="" selected>Выбрать</option>
                                    <option v-for="(items_ , index) in data " :data-text="items_" :value="index">@{{items_}}</option>
                                </select>',
                                "change" => "function(){
                                            if($(this).val() !== '') {
                                                const param = {'country_id': $(this).find('option:selected').val()}
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
                                '<select class="unit-select_select" name="city_id" :id="name" style="width:100%">
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
                                "template" =>
                                '<select class="unit-select_select" name="service_type_id" :id="name" style="width:100%">
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
                                '<select class="unit-select_select" name="service_id" :id="name" style="width:100%">
                                    <option value="" selected="true">Выбрать</option>
                                    <option v-for="item in data " :data-text="item.name" :value="item.id">@{{ item.name }}</option>
                                </select>',
                                "change" => "function(){}"
                            ]])
                    </div>
                </div>

                <div class="unit-select unit-exchange">
                    <div class="checkbox-block">
                        <label>
                            <input type="checkbox" name="myReq">
                            <span class="checkbox"></span>
                            <span class="text">Мои заявки</span>
                        </label>
                    </div>

                    <button type="reset" class="find_reset-filter">сбросить фильтр</button>
                </div>

                <a class="rules-text">
                    Правила размещения заявок на получение юридической помощи
                </a>
            </form>

            <div class="find-block find-right">
                <h3 class="find-question_title">Не нашли того, что искали? </h3>
                <p class="find-question_text">Попробуйте подобрать подходящего специалиста или заказать услугу. Попробуйте
                    подобрать подходящего </p>
                <div class="buttons-container">
                    <button class="main-btn main-btn_orange">
            <span class="first">
                Подберите мне специалиста
                <img src="/lawyers/images/icons/arrow-icon-white.svg" alt="arrow-icon" class="icon">
            </span>
                        <span class="second">
                Подберите мне специалиста
                <img src="/lawyers/images/icons/arrow-icon-white.svg" alt="arrow-icon" class="icon">
            </span>
                    </button>
                    <button class="main-btn">
            <span class="first">
                Заказать услугу
                <img src="/lawyers/images/icons/arrow-icon-white.svg" alt="arrow-icon" class="icon">
            </span>
                        <span class="second">
                Заказать услугу
                <img src="/lawyers/images/icons/arrow-icon-white.svg" alt="arrow-icon" class="icon">
            </span>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <section class="fs-section u-container">
        <div class="container exchange-container">
            <div class="exchanges-container">
                <form action="#" class="search-lawyers-form">
                    <h3 class="mobile-filter_header">
                        <span class="main">Заявки</span>
                        <span class="second popup-btn" data-popup="mobile-filter-popup">
              <img src="/lawyers/images/icons/filter-icon.svg" alt="filter-icon">
              Фильтр
          </span>
                    </h3>

                    <label class="filter-search">
                        <input type="search" name="search-lawyers" placeholder="Имя и фамилия специалиста...">
                        <input type="image" src="/lawyers/images/icons/search-icon-gray.svg" alt="loop-icon">
                    </label>

                    <button type="reset">очистить</button>

                    <ul class="filter-unit">
                        <li>
                            Москва
                            <button type="button"><img src="/lawyers/images/icons/cross-icon-gray.svg" alt="cross-icon"></button>
                        </li>
                        <li>
                            От 10 лет
                            <button type="button"><img src="/lawyers/images/icons/cross-icon-gray.svg" alt="cross-icon"></button>
                        </li>
                        <li>
                            Гражданское право
                            <button type="button"><img src="/lawyers/images/icons/cross-icon-gray.svg" alt="cross-icon"></button>
                        </li>
                    </ul>

                    <a class="rules-text">
                        Правила размещения заявок на получение юридической помощи
                    </a>
                </form>

                <nav class="exchange_nav mobile">
                    <ul>
                        <li class="_selected">Заявки</li>
                        <li>ТОП-юристов</li>
                    </ul>
                </nav>





                        @include('component_build', [
                    'component' => 'component.gridComponent.simpleGrid',
                    'params_component' => [
                        'autostart' => 'true',
//						'ssr' => 'true',
                        'name' => 'vacancy_list',
                        'url' => route__("actionGetVacancyList_mainstay_vacancy_vacancymainstaycontroller"),
						'params' => ['user_id' => ''],

                        'template' =>
                        "<div class='exchanges' :id=\"name + '_body'\">
                        <h2 class='find-section_header mobile-hidden'>Найдено: <span>@{{ pagination.totalCount }} предложений</span></h2>
                        <div class='exchange' v-for=\"item in data\">
                        <div class='exchange_left'>
                            <h3 class='exchange_title'>
                                <span>@{{ item.title }}</span> <span class='exchange_status'>Только для PRO</span>
                            </h3>
                            <p class='exchange_text fs-text'>
                                @{{ item.description }}
                            </p>

                            <div class='exchange_info'>
                                <ul class='exchange_location'>
                                    <li class='fs-text'>
                                        <img src='/lawyers/images/icons/loc-icon-gray.svg' alt='loc-icon'>
                                        @{{ item.location }}
                                    </li>
                                    <li class='fs-text'>
                                        <img src='/lawyers/images/icons/bag-icon-gray.svg' alt='bag-icon' class='bag-icon'>
                                        Дистанционная консультация
                                    </li>
                                </ul>

                                <ul class='exchange_other-info'>
                                    <li>
                                        <img src='/lawyers/images/icons/lil-eye-gray.svg' alt='eye-icon'>234
                                    </li>
                                    <li>
                                        <img src='/lawyers/images/icons/lil-clock-gray.svg' alt='clock-icon'>@{{ item.time_ago }}
                                    </li>
                                    <li>
                                        <img src='/lawyers/images/icons/lil-fire-gray.svg' alt='fire-icon'>@{{ item.period_end == null ? 'Срок не установлен' : item.period_end + ' дней до конца' }}
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class='exchange_right'>
                            <span>за проект</span>
                            <p>@{{ item.payment !== 0 ? item.payment : 'Н/У' }} &#8381;</p>
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


                <div class="top-lawyers mobile hidden">
                    <div class="top-lawyers_container">
                        <div class="fs-block">
                            <div class="fs-img">
                                <img src="/lawyers/images/main/lawyer-img.png" alt="lawyer-img">
                            </div>

                            <div class="fs-info">
                                <h3 class="fs-name">
                                    Соколовский Владимир Александрович
                                    <img src="/lawyers/images/icons/check-icon-green.svg" alt="check-icon">
                                </h3>
                                <p class="fs-row">
                                    <img src="/lawyers/images/icons/loc-icon-gray.svg" alt="loc-icon" class="icon">
                                    <span class="fs-text">Москва и МО</span>
                                </p>
                                <p class="fs-row">
                                    <img src="/lawyers/images/icons/bag-icon-gray.svg" alt="bag-icon" class="icon">
                                    <span class="fs-text">15 лет практики</span>
                                </p>
                                <div class="lawyer_rate-block">
                                    <div class="specialist-rate">
                                        <div class="stars">
                                            <img src="/lawyers/images/icons/star-icon-full.svg" alt="star-icon">
                                            <img src="/lawyers/images/icons/star-icon-full.svg" alt="star-icon">
                                            <img src="/lawyers/images/icons/star-icon-full.svg" alt="star-icon">
                                            <img src="/lawyers/images/icons/star-icon-full.svg" alt="star-icon">
                                            <img src="/lawyers/images/icons/star-icon-empty.svg" alt="star-icon">
                                        </div>

                                        <span>32 ответа</span>
                                    </div>

                                    <div class="specialist-perm">
                                        <p>Право рейтинг:</p>
                                        <span>4.0</span>
                                        <img src="/lawyers/images/icons/info-icon-blue.svg" alt="info-icon" class="icon">
                                    </div>
                                </div>
                            </div>

                            <div class="buttons-container">
                                <button class="main-btn main-btn_white">
                                    <span class="first">Сообщение</span>
                                    <span class="second">Сообщение</span>
                                </button>
                                <button class="main-btn main-btn_blue">
                                    <span class="first">Предложить работу</span>
                                    <span class="second">Предложить работу</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

{{--                <div class="fs-section_pages">--}}
{{--                    <a href="#" class="fs-page_nav disabled mobile-hidden">Предыдущая страница</a>--}}
{{--                    <a href="#" class="fs-page_nav active mobile-hidden">Следующая страница</a>--}}

{{--                    <div class="fs-page_nav-mobile mobile">--}}
{{--                        <button>--}}
{{--                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="33" viewBox="0 0 32 33" fill="none">--}}
{{--                                <rect y="0.351318" width="32" height="32" rx="12" fill="#EAEAEA"/>--}}
{{--                                <path d="M22.2513 16.3513H10.0312" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>--}}
{{--                                <path d="M10 16.3513L14 20.3513" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>--}}
{{--                                <path d="M10 16.3513L14 12.3513" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>--}}
{{--                            </svg>--}}
{{--                        </button>--}}
{{--                        <button>--}}
{{--                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="33" viewBox="0 0 32 33" fill="none">--}}
{{--                                <rect y="0.351318" width="32" height="32" rx="12" fill="#5D74F1"/>--}}
{{--                                <path d="M9.99875 16.3513H22.2188" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>--}}
{{--                                <path d="M22.25 16.3513L18.25 12.3513" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>--}}
{{--                                <path d="M22.25 16.3513L18.25 20.3513" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>--}}
{{--                            </svg>--}}
{{--                        </button>--}}
{{--                    </div>--}}

{{--                    <ul class="gaps">--}}
{{--                        <li><a href="#">1</a></li>--}}
{{--                        <li><a href="#">2</a></li>--}}
{{--                        <li><a href="#" class="active">3</a></li>--}}
{{--                        <li><a href="#">4</a></li>--}}
{{--                        <li><a href="#">...</a></li>--}}
{{--                        <li><a href="#">28</a></li>--}}
{{--                    </ul>--}}
{{--                </div>--}}
            </div>

            <div class="top-lawyers mobile-hidden">
                <h2 class="top-lawyers_header">
                    ТОП-юристов по консультациям
                </h2>
                <div class="top-lawyers_container">
                    <div class="fs-block">
                        <div class="fs-img">
                            <img src="/lawyers/images/main/lawyer-img.png" alt="lawyer-img">
                        </div>

                        <div class="fs-info">
                            <h3 class="fs-name">
                                Соколовский Владимир Александрович
                                <img src="/lawyers/images/icons/check-icon-green.svg" alt="check-icon">
                            </h3>
                            <p class="fs-row">
                                <img src="/lawyers/images/icons/loc-icon-gray.svg" alt="loc-icon" class="icon">
                                <span class="fs-text">Москва и МО</span>
                            </p>
                            <p class="fs-row">
                                <img src="/lawyers/images/icons/bag-icon-gray.svg" alt="bag-icon" class="icon">
                                <span class="fs-text">15 лет практики</span>
                            </p>
                            <div class="lawyer_rate-block">
                                <div class="specialist-rate">
                                    <div class="stars">
                                        <img src="/lawyers/images/icons/star-icon-full.svg" alt="star-icon">
                                        <img src="/lawyers/images/icons/star-icon-full.svg" alt="star-icon">
                                        <img src="/lawyers/images/icons/star-icon-full.svg" alt="star-icon">
                                        <img src="/lawyers/images/icons/star-icon-full.svg" alt="star-icon">
                                        <img src="/lawyers/images/icons/star-icon-empty.svg" alt="star-icon">
                                    </div>

                                    <span>32 ответа</span>
                                </div>

                                <div class="specialist-perm">
                                    <p>Право рейтинг:</p>
                                    <span>4.0</span>
                                    <img src="/lawyers/images/icons/info-icon-blue.svg" alt="info-icon" class="icon">
                                </div>
                            </div>
                        </div>

                        <div class="buttons-container">
                            <button class="main-btn main-btn_white">
                                <span class="first">Сообщение</span>
                                <span class="second">Сообщение</span>
                            </button>
                            <button class="main-btn main-btn_blue">
                                <span class="first">Предложить работу</span>
                                <span class="second">Предложить работу</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

{{--<ul class="footer_mobile mobile">--}}
{{--    <li><a href="index.html">--}}
{{--            <img src="/lawyers/images/icons/main-menu-icon.svg" alt="main-icon"><span>Главная</span>--}}
{{--        </a></li>--}}
{{--    <li><a href="lawyer.html">--}}
{{--            <img src="/lawyers/images/icons/lawyers-menu-icon.svg" alt="lawyers-icon"><span>Юристы</span>--}}
{{--        </a></li>--}}
{{--    <li><a href="#" class="active">--}}
{{--            <img src="/lawyers/images/icons/help-menu-icon-active.svg" alt="help-icon"><span>Юр. Помощь</span>--}}
{{--        </a></li>--}}
{{--    <li><a href="lawyer.html">--}}
{{--            <img src="/lawyers/images/icons/profile-menu-icon.svg" alt="profile-icon"><span>Профиль</span>--}}
{{--        </a></li>--}}
{{--    <li><a href="#">--}}
{{--            <img src="/lawyers/images/icons/journal-menu-icon.svg" alt="journal-icon"><span>Юр. Журнал</span>--}}
{{--        </a></li>--}}
{{--</ul>--}}

{{--<div id="mobile-menu-popup" class="popup mobile popup_hide">--}}
{{--    <div class="popup_shadow"></div>--}}

{{--    <div class="step step1 mobile-step_top step_hide" data-step="1">--}}
{{--        <div class="mob_header">--}}
{{--            <button class="popup-close"><img src="/lawyers/images/icons/arrow-back-icon.svg" alt="arrow-icon"></button>--}}
{{--            <a href="index.html" class="logo image-container">Лого</a>--}}
{{--        </div>--}}

{{--        <h2 class="mob-header_title">Личный кабинет</h2>--}}

{{--        <ul class="nav-ul nav-ul_mobile">--}}
{{--            <li>--}}
{{--                <div>--}}
{{--                    <span class="cool-underline select-btn">Найти специалиста</span>--}}
{{--                    <img src="/lawyers/images/icons/arrow-icon-white.svg" alt="arrow-icon" class="arrow-icon sub-icon">--}}
{{--                </div>--}}
{{--                <ul class="select-window">--}}
{{--                    <li>--}}
{{--                        <a href="#">--}}
{{--                            <p>--}}
{{--                                Найти Юриста--}}
{{--                                <span>--}}
{{--                                    Awo has 97% of all lawyersin the. Awo has 97% of all lawyersin the.--}}
{{--                                </span>--}}
{{--                            </p>--}}
{{--                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11" fill="none">--}}
{{--                                <path d="M1 1L5 5.5L1 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>--}}
{{--                            </svg>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    <li>--}}
{{--                        <a href="#">--}}
{{--                            <p>Найти Адвоката--}}
{{--                                <span>Awo has 97% of all lawyersin the. Awo has 97% of all lawyersin the.</span>--}}
{{--                            </p>--}}
{{--                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11" fill="none">--}}
{{--                                <path d="M1 1L5 5.5L1 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>--}}
{{--                            </svg>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    <li>--}}
{{--                        <a href="#">--}}
{{--                            <p>Найти Нотариуса--}}
{{--                                <span>Awo has 97% of all lawyersin the. Awo has 97% of all lawyersin the.</span>--}}
{{--                            </p>--}}
{{--                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11" fill="none">--}}
{{--                                <path d="M1 1L5 5.5L1 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>--}}
{{--                            </svg>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    <li>--}}
{{--                        <a href="#">--}}
{{--                            <p>Найти Детектива--}}
{{--                                <span>Awo has 97% of all lawyersin the. Awo has 97% of all lawyersin the.</span>--}}
{{--                            </p>--}}
{{--                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11" fill="none">--}}
{{--                                <path d="M1 1L5 5.5L1 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>--}}
{{--                            </svg>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                </ul>--}}
{{--            </li>--}}
{{--            <li>--}}
{{--                <div>--}}
{{--                    <span class="cool-underline select-btn">наши юристы</span>--}}
{{--                    <img src="/lawyers/images/icons/arrow-icon-white.svg" alt="arrow-icon" class="arrow-icon sub-icon">--}}
{{--                </div>--}}
{{--                <ul class="select-window">--}}
{{--                    <li>--}}
{{--                        <a href="#">--}}
{{--                            <p>--}}
{{--                                Найти Юриста--}}
{{--                                <span>--}}
{{--                                    Awo has 97% of all lawyersin the. Awo has 97% of all lawyersin the.--}}
{{--                                </span>--}}
{{--                            </p>--}}
{{--                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11" fill="none">--}}
{{--                                <path d="M1 1L5 5.5L1 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>--}}
{{--                            </svg>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    <li>--}}
{{--                        <a href="#">--}}
{{--                            <p>Найти Адвоката--}}
{{--                                <span>Awo has 97% of all lawyersin the. Awo has 97% of all lawyersin the.</span>--}}
{{--                            </p>--}}
{{--                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11" fill="none">--}}
{{--                                <path d="M1 1L5 5.5L1 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>--}}
{{--                            </svg>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    <li>--}}
{{--                        <a href="#">--}}
{{--                            <p>Найти Нотариуса--}}
{{--                                <span>Awo has 97% of all lawyersin the. Awo has 97% of all lawyersin the.</span>--}}
{{--                            </p>--}}
{{--                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11" fill="none">--}}
{{--                                <path d="M1 1L5 5.5L1 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>--}}
{{--                            </svg>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    <li>--}}
{{--                        <a href="#">--}}
{{--                            <p>Найти Детектива--}}
{{--                                <span>Awo has 97% of all lawyersin the. Awo has 97% of all lawyersin the.</span>--}}
{{--                            </p>--}}
{{--                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11" fill="none">--}}
{{--                                <path d="M1 1L5 5.5L1 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>--}}
{{--                            </svg>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                </ul>--}}
{{--            </li>--}}
{{--            <li>--}}
{{--                <div>--}}
{{--                    <span class="cool-underline select-btn">наши услуги</span>--}}
{{--                    <img src="/lawyers/images/icons/arrow-icon-white.svg" alt="arrow-icon" class="arrow-icon sub-icon">--}}
{{--                </div>--}}
{{--                <ul class="select-window">--}}
{{--                    <li>--}}
{{--                        <a href="#">--}}
{{--                            <p>--}}
{{--                                Найти Юриста--}}
{{--                                <span>--}}
{{--                                    Awo has 97% of all lawyersin the. Awo has 97% of all lawyersin the.--}}
{{--                                </span>--}}
{{--                            </p>--}}
{{--                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11" fill="none">--}}
{{--                                <path d="M1 1L5 5.5L1 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>--}}
{{--                            </svg>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    <li>--}}
{{--                        <a href="#">--}}
{{--                            <p>Найти Адвоката--}}
{{--                                <span>Awo has 97% of all lawyersin the. Awo has 97% of all lawyersin the.</span>--}}
{{--                            </p>--}}
{{--                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11" fill="none">--}}
{{--                                <path d="M1 1L5 5.5L1 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>--}}
{{--                            </svg>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    <li>--}}
{{--                        <a href="#">--}}
{{--                            <p>Найти Нотариуса--}}
{{--                                <span>Awo has 97% of all lawyersin the. Awo has 97% of all lawyersin the.</span>--}}
{{--                            </p>--}}
{{--                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11" fill="none">--}}
{{--                                <path d="M1 1L5 5.5L1 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>--}}
{{--                            </svg>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    <li>--}}
{{--                        <a href="#">--}}
{{--                            <p>Найти Детектива--}}
{{--                                <span>Awo has 97% of all lawyersin the. Awo has 97% of all lawyersin the.</span>--}}
{{--                            </p>--}}
{{--                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11" fill="none">--}}
{{--                                <path d="M1 1L5 5.5L1 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>--}}
{{--                            </svg>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                </ul>--}}
{{--            </li>--}}
{{--            <li>--}}
{{--                <div>--}}
{{--                    <span class="cool-underline select-btn">юридическая помощь</span>--}}
{{--                    <img src="/lawyers/images/icons/arrow-icon-white.svg" alt="arrow-icon" class="arrow-icon sub-icon">--}}
{{--                </div>--}}
{{--                <ul class="select-window">--}}
{{--                    <li>--}}
{{--                        <a href="#">--}}
{{--                            <p>--}}
{{--                                Найти Юриста--}}
{{--                                <span>--}}
{{--                                    Awo has 97% of all lawyersin the. Awo has 97% of all lawyersin the.--}}
{{--                                </span>--}}
{{--                            </p>--}}
{{--                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11" fill="none">--}}
{{--                                <path d="M1 1L5 5.5L1 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>--}}
{{--                            </svg>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    <li>--}}
{{--                        <a href="#">--}}
{{--                            <p>Найти Адвоката--}}
{{--                                <span>Awo has 97% of all lawyersin the. Awo has 97% of all lawyersin the.</span>--}}
{{--                            </p>--}}
{{--                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11" fill="none">--}}
{{--                                <path d="M1 1L5 5.5L1 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>--}}
{{--                            </svg>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    <li>--}}
{{--                        <a href="#">--}}
{{--                            <p>Найти Нотариуса--}}
{{--                                <span>Awo has 97% of all lawyersin the. Awo has 97% of all lawyersin the.</span>--}}
{{--                            </p>--}}
{{--                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11" fill="none">--}}
{{--                                <path d="M1 1L5 5.5L1 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>--}}
{{--                            </svg>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    <li>--}}
{{--                        <a href="#">--}}
{{--                            <p>Найти Детектива--}}
{{--                                <span>Awo has 97% of all lawyersin the. Awo has 97% of all lawyersin the.</span>--}}
{{--                            </p>--}}
{{--                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11" fill="none">--}}
{{--                                <path d="M1 1L5 5.5L1 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>--}}
{{--                            </svg>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                </ul>--}}
{{--            </li>--}}
{{--            <li>--}}
{{--                <div>--}}
{{--                    <span class="cool-underline select-btn">юридический журнал</span>--}}
{{--                    <img src="/lawyers/images/icons/arrow-icon-white.svg" alt="arrow-icon" class="arrow-icon sub-icon">--}}
{{--                </div>--}}
{{--                <ul class="select-window">--}}
{{--                    <li>--}}
{{--                        <a href="#">--}}
{{--                            <p>--}}
{{--                                Найти Юриста--}}
{{--                                <span>--}}
{{--                                    Awo has 97% of all lawyersin the. Awo has 97% of all lawyersin the.--}}
{{--                                </span>--}}
{{--                            </p>--}}
{{--                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11" fill="none">--}}
{{--                                <path d="M1 1L5 5.5L1 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>--}}
{{--                            </svg>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    <li>--}}
{{--                        <a href="#">--}}
{{--                            <p>Найти Адвоката--}}
{{--                                <span>Awo has 97% of all lawyersin the. Awo has 97% of all lawyersin the.</span>--}}
{{--                            </p>--}}
{{--                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11" fill="none">--}}
{{--                                <path d="M1 1L5 5.5L1 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>--}}
{{--                            </svg>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    <li>--}}
{{--                        <a href="#">--}}
{{--                            <p>Найти Нотариуса--}}
{{--                                <span>Awo has 97% of all lawyersin the. Awo has 97% of all lawyersin the.</span>--}}
{{--                            </p>--}}
{{--                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11" fill="none">--}}
{{--                                <path d="M1 1L5 5.5L1 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>--}}
{{--                            </svg>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    <li>--}}
{{--                        <a href="#">--}}
{{--                            <p>Найти Детектива--}}
{{--                                <span>Awo has 97% of all lawyersin the. Awo has 97% of all lawyersin the.</span>--}}
{{--                            </p>--}}
{{--                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11" fill="none">--}}
{{--                                <path d="M1 1L5 5.5L1 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>--}}
{{--                            </svg>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                </ul>--}}
{{--            </li>--}}
{{--        </ul>--}}

{{--        <div class="support-phone">--}}
{{--            <span>Поддержка</span>--}}
{{--            <p>+7 (999) 999 99 99</p>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}

{{--<div id="mobile-filter-popup" class="popup mobile popup_hide">--}}
{{--    <div class="popup_shadow"></div>--}}

{{--    <form action="#" class="step step1 mobile-step_right step_hide" data-step="1">--}}
{{--        <h3 class="filter-header">--}}
{{--            <img src="/lawyers/images/icons/arrow-back-icon-white.svg" alt="arrow-icon" class="popup-close">--}}
{{--            <span class="main">Фильтр</span>--}}
{{--            <span class="second">4</span>--}}
{{--            <button type="reset">очистить</button>--}}
{{--        </h3>--}}

{{--        <div class="mobile-filter_filters">--}}
{{--            <div class="unit-select_row select">--}}
{{--                <span class="unit-select_subtext">Страна</span>--}}

{{--                <label class="unit-select_select select-btn">--}}
{{--                    <input name="country" class="unit-select_text" placeholder="Страна" readonly>--}}
{{--                    <img src="/lawyers/images/icons/arrow-icon-gray.svg" alt="arrow-icon" class="sub-icon">--}}
{{--                </label>--}}

{{--                <ul class="select-window">--}}
{{--                    <li>Россия</li>--}}
{{--                    <li>Германия</li>--}}
{{--                </ul>--}}
{{--            </div>--}}

{{--            <div class="unit-select_row select">--}}
{{--                <span class="unit-select_subtext">Город</span>--}}
{{--                <label class="unit-select_select select-btn">--}}
{{--                    <input type="text" readonly placeholder="Город">--}}
{{--                    <img src="/lawyers/images/icons/arrow-icon-gray.svg" alt="arrow-icon" class="sub-icon">--}}
{{--                </label>--}}
{{--                <ul class="select-window">--}}
{{--                    <li>Санкт-Петербург</li>--}}
{{--                    <li>Москва</li>--}}
{{--                </ul>--}}
{{--            </div>--}}

{{--            <div class="unit-select_row select">--}}
{{--                <span class="unit-select_subtext">Категория услуг</span>--}}
{{--                <label class="unit-select_select select-btn">--}}
{{--                    <input type="text" name="category" readonly placeholder="Категория услуг">--}}
{{--                    <img src="/lawyers/images/icons/arrow-icon-gray.svg" alt="arrow-icon" class="sub-icon">--}}
{{--                </label>--}}
{{--                <ul class="select-window">--}}
{{--                    <li>Категория услуг1</li>--}}
{{--                    <li>Категория услуг2</li>--}}
{{--                </ul>--}}
{{--            </div>--}}

{{--            <div class="unit-select_row select">--}}
{{--                <span class="unit-select_subtext">Тема услуги</span>--}}
{{--                <label class="unit-select_select select-btn">--}}
{{--                    <input type="text" readonly placeholder="Тема услуги">--}}
{{--                    <img src="/lawyers/images/icons/arrow-icon-gray.svg" alt="arrow-icon" class="sub-icon">--}}
{{--                </label>--}}
{{--                <ul class="select-window">--}}
{{--                    <li>Тема услуги1</li>--}}
{{--                    <li>Тема услуги2</li>--}}
{{--                </ul>--}}
{{--            </div>--}}

{{--            <div class="checkbox-block">--}}
{{--                <label>--}}
{{--                    <input type="checkbox" name="myReq">--}}
{{--                    <span class="checkbox"></span>--}}
{{--                    <span class="text">Мои заявки</span>--}}
{{--                </label>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </form>--}}
{{--</div>--}}
<script>
    $(document).ready(function () {
        filter()
        resetFilters()
    })

    function filter() {
        $('#send').on('click', function (e) {
            e.preventDefault()
            let params = getFilterParams()
            console.log(page__.getElementsGroup('vacancy_list')[0]['obj']);
            page__.getElementsGroup('vacancy_list')[0]['obj'].setUrlParams(params)
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
            const params = page__.getElementsGroup('vacancy_list')[0]['obj'].getUrlParams()
            let hasParams = false
            for (let index = 0; index < Object.keys(params).length; index++) {
                if (params[index] !== undefined || params[index] !== null || params[index] !== '') {
                    hasParams = true;
                }
            }
            if (hasParams) {
                clearFilterInputs()
                page__.getElementsGroup('vacancy_list')[0]['obj'].setUrlParams({user_id: ''})
            }
            clearFilterInputs()
        })
    }

    function getFilterParams() {
        return {
            'search_spec': $('#search-spec').val(),
            'country_id': $('[name = country_id]').val(),
            'city_id': $('[name = city_id]').val(),
            'service_id': $('[name = service_id]').val(),
            'user_id': ''
        }
    }
</script>
@endsection
