@extends('lawyers.layouts.main')

@include('component_build',[
	'component' => 'component.loadComponent.loadGlobalData',
        'params_component' => [
        'name' => "ClientInfo",
        'autostart' => 'false',
        'ssr' => 'true',
        'url' => route__("actionGetClient_mainstay_client_clientmainstaycontroller"),
        'params' => ['id' => auth()->id()],
    ]
])

@section('title', 'Кабинет клиента')

@section('content')
    <section class="gradient-bg u-container lawyer-section">
        <div class="container">
            <section class="gradient-bg u-container lawyer-section">
                <div class="container">
                    <div class='modal fade' id='clientInfoEdit' tabindex='-1' role='dialog' aria-labelledby='exampleModalCenterTitle' aria-hidden='true'>
                        <div class='modal-dialog modal-dialog-centered' role='document'>
                            <div class='modal-content'>
                                <div class='modal-header'>
                                    <h5 class='modal-title' id='exampleModalLongTitle'>Окно редактирования</h5>
                                    <button type='button' class='close' data-dismiss='modal' aria-label='Закрыть'>
                                        <span aria-hidden='true'>&times;</span>
                                    </button>
                                </div>
                                <div class='modal-body flex-between'>
                                    @include('component_build', [
                                        'component' => 'component.infoComponent.textInfo',
                                        'params_component' => [
                                            'autostart' => 'false',
                                            'name' => 'employee_info_edit',
                                            'params' => [],
                                            'globalData' => 'ClientInfo',
                                            'ssr' => 'false',

                                            'template' =>
                                "<div id=\"common_info_container\">
                                    <label>Имя</label>
                                    <input type=\"text\" name=\"first_name_edit\" :value=\"data.first_name\" class=\"border\">
                                    <label>Фамилия</label>
                                    <input type=\"text\" name=\"last_name_edit\" :value=\"data.last_name\" class=\"border\">
                                    <label>Отчество</label>
                                    <input type=\"text\" name=\"middle_name_edit\" :value=\"data.middle_name\" class=\"border\">
                                    <label>Телефон</label>
                                    <input type=\"text\" name=\"phone_number_edit\" :value=\"data.phone_number\" class=\"border\">
                                </div>",
                                        ]
                                    ])
                                    <label for="">Страна</label>
                                    @include('component_build',["component" => "component.listComponent.selectComponent",
                                    "params_component" => [
                                        "autostart" => 'true',
                                        "name" => 'country_id',
                                        "default_title" => 'Страна',
                                        "url" => route("actionGetCountries_mainstay_helpdata_helpdatamainstaycontroller"),
                                        "callBeforloadComponent" => "function(component) {
                                                component.option['currentSelectId'] = page__.getGolobalData('ClientInfo').country_id
                                                component.option['currentSelectName'] = page__.getGolobalData('ClientInfo').country_name
                                                return component.option
                                            }",
                                        "callAfterloadComponent" => "function(component) {
                                                const param = {'country_id': component.vueObject._data.currentSelectId}
                                                page__.getElementsGroup('city_id')[0]['obj'].setUrlParams(param)
                                            }",
                                        "template" =>
                                        '<select class="unit-select_select" name="country_id" :id="name" style="width:100%">
                                            <option v-for="(items_ , index) in data" :data-text="items_" :value="index" :selected="index == currentSelectId">@{{items_}}</option>
                                        </select>',
                                        "change" => "function(){
                                                    if($(this).val() !== '') {
                                                        const param = {'country_id': $(this).find('option:selected').val()}
                                                        page__.getElementsGroup('city_id')[0]['obj'].setUrlParams(param)
                                                        }
                                                    }"
                                    ]])

                                    <label for="">Город</label>
                                    @include('component_build',["component" => "component.listComponent.selectComponent",
                                    "params_component" => [
                                        "autostart" => 'false',
                                        "name" => 'city_id',
                                        "default_title" => 'Город',
                                        "url" => route("actionGetCities_mainstay_helpdata_helpdatamainstaycontroller"),
                                        "callBeforloadComponent" => "function(component) {
                                                component.option['currentSelectId'] = page__.getGolobalData('ClientInfo').city_id
                                                component.option['currentSelectName'] = page__.getGolobalData('ClientInfo').city_name
                                                return component.option
                                            }",

                                        "template" =>
                                        '<select class="unit-select_select" name="city_id" :id="name" style="width:100%">
                                            <option v-for="(items_ , index) in data " :data-text="items_" :value="index" :selected="index == currentSelectId">@{{items_}}</option>
                                        </select>',
                                        "change" => "function(){}"
                                    ]])
                                </div>
                                <div class='modal-footer'>
                                    <button type='button' id="save_edit_main" class='btn btn-success'>Сохранить</button>
                                    <button type='button' class='btn btn-secondary' data-dismiss='modal'>Закрыть</button>
                                </div>
                            </div>
                        </div>
                    </div>
            <div class="lawyer-container">
                <div class="left">
                    @include('component_build', [
                            'component' => 'component.infoComponent.textInfo',
                            'params_component' => [
                                'autostart' => 'false',
                                'name' => 'employee_info_main',
								'globalData' => 'ClientInfo',
								'change' => 'function() {}',
                                'template' =>
                            '<div class="lawyer-block">
                                <div class="lawyer-top">

                                    <div class="lawyer-info">
                                        <h2 class="lawyer-name">@{{ data.full_name }}</h2>
                                    </div>
                                </div>

                                <div class="lawyer-bottom">

                                    <div class="lawyer-info">
                                        <div class="lawyer-info_row">
                                            <img src="/lawyers/images/icons/loc-icon-blue.svg" alt="loc-icon" class="icon">
                                            <span>@{{ data.city_name }}</span>
                                        </div>

                                        <div class="lawyer-info_row">
                                            <img src="/lawyers/images/icons/phone-icon-blue.svg" alt="phone-icon" class="icon">

                                            <a href="tel:+@{{ data.phone_number }}" class="link">+@{{ data.phone_number }}</a>
                                        </div>

                                    </div>

                                    <button id="edit_info" type="button" class="main-btn main-btn_white">
                                        <span class="first">Редактировать</span>
                                        <span class="second">Редактировать</span>
                                    </button>

                                    <div class="balance-block">
                                        <div class="balance-block_left">
                                            <p class="balance-text">Ваш баланс</p>
                                            <p class="balance_balance">
                                                <span>&#8381</span>
                                                @{{ data.balance == null ? 0 : data.balance }} руб
                                            </p>
                                        </div>

                                        <a href="{{route__("actionPaymentPage_controllers_client_clientcontroller")}}" class="main-btn">
                                            <span class="first">
                                                Пополнить
                                                <img src="/lawyers/images/icons/arrow-icon-white.svg" alt="arrow-icon" class="icon">
                                            </span>
                                            <span class="second">
                                                Пополнить
                                                <img src="/lawyers/images/icons/arrow-icon-white.svg" alt="arrow-icon" class="icon">
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </div>',
                            ]
                        ])
                </div>

                <div class="right">
                    <ul class="round-top_nav">
                        <li class="active"><button type="button">Мои заказы</button></li>
                        <li><button type="button">Услуги</button></li>
                        <li><button type="button">Отзывы</button></li>
                        <li><button type="button">Ответы юриста</button></li>
                    </ul>

                    <div class="my-orders lawyer-wrapper">
                        <h2 class="lawyer-wrapper_title">Мои вопросы <span>1/2</span></h2>

                        @include('component_build', [
                            'component' => 'component.gridComponent.simpleGrid',
                            'params_component' => [
                                'autostart' => 'true',
                                'name' => 'client_questions',
                                'url' => route__("actionGetClientQuestions_mainstay_client_clientmainstaycontroller"),
								'params' => ['user_id' => auth()->id()],

                                'template' => '
                                <ul class="my-orders_ul" :id="name + \'_body\'">
                            <li v-for="item in data">
                                <div class="my-orders_info">
                                    <p class="my-orders_text">
                                        @{{ item.text }}
                                    </p>
                                    <p class="my-orders_price" v-if="item.is_payed">
                                        @{{ item.is_payed == 1 ? "Платный" : "Бесплатный" }}
                                        <span>@{{ item.count_answers }} ответов</span>
                                    </p>
                                </div>

                                <p class="my-orders_stage closed-status" v-if="item.status">
                                    @{{ item.status }}
                                </p>
                            </li>
                        </ul>',
                            ]
                        ])

                        <button class="more-services">Еще</button>
                    </div>

                    <div class="lawsuit lawyer-wrapper">
                        <h2 class="lawyer-wrapper_title lawyer-wrapper_title-left">Коллективные иски </h2>

                        @include('component_build', [
                            'component' => 'component.gridComponent.simpleGrid',
                            'params_component' => [
                                'autostart' => 'true',
                                'name' => 'client_questions',
                                'url' => route__("actionGetVacancies_mainstay_client_clientmainstaycontroller"),
								'params' => ['user_id' => auth()->id(), 'is_group' => 1],

                                'template' => '<ul class="my-orders_ul" :id="name + \'_body\'">
                            <li v-for="item in data">
                                <div class="my-orders_info">
                                    <p class="my-orders_text">
                                        @{{ item.description }}
                                    </p>

                                    <ul class="my-orders_sub-ul">
                                        <li>@{{ item.description }} заявителей (Ожидают подтверждение 4)</li>
                                        <li>5 сообщений</li>
                                        <li>3 предложения от юристов</li>
                                    </ul>
                                </div>

                                <a href="#" type="button" class="main-btn main-btn_white">
                                    <span class="first">Открыть</span>
                                    <span class="second">Открыть</span>
                                </a>
                            </li>

                            <li>
                                <div class="my-orders_info">
                                    <p class="my-orders_text">
                                        Иск собственников многоквартирного дома в признании права общей долевой
                                        собственности на подвальные помещения здания
                                    </p>

                                    <ul class="my-orders_sub-ul">
                                        <li>5 заявителей (Ожидают подтверждение 4)</li>
                                        <li>5 сообщений</li>
                                        <li>3 предложения от юристов</li>
                                    </ul>
                                </div>

                                <a href="#" type="button" class="main-btn main-btn_white">
                                    <span class="first">Открыть</span>
                                    <span class="second">Открыть</span>
                                </a>
                            </li>
                        </ul>',
                            ]
                        ])


                        <ul class="my-orders_ul">
                            <li>
                                <div class="my-orders_info">
                                    <p class="my-orders_text">
                                        Иск собственников многоквартирного дома в признании права общей долевой
                                        собственности на подвальные помещения здания
                                    </p>

                                    <ul class="my-orders_sub-ul">
                                        <li>5 заявителей (Ожидают подтверждение 4)</li>
                                        <li>5 сообщений</li>
                                        <li>3 предложения от юристов</li>
                                    </ul>
                                </div>

                                <a href="#" type="button" class="main-btn main-btn_white">
                                    <span class="first">Открыть</span>
                                    <span class="second">Открыть</span>
                                </a>
                            </li>

                            <li>
                                <div class="my-orders_info">
                                    <p class="my-orders_text">
                                        Иск собственников многоквартирного дома в признании права общей долевой
                                        собственности на подвальные помещения здания
                                    </p>

                                    <ul class="my-orders_sub-ul">
                                        <li>5 заявителей (Ожидают подтверждение 4)</li>
                                        <li>5 сообщений</li>
                                        <li>3 предложения от юристов</li>
                                    </ul>
                                </div>

                                <a href="#" type="button" class="main-btn main-btn_white">
                                    <span class="first">Открыть</span>
                                    <span class="second">Открыть</span>
                                </a>
                            </li>
                        </ul>

                        <button class="more-services">Еще 2 услуги</button>
                    </div>

                    <div class="subscribe-block lawyer-wrapper">
                        <h2 class="lawyer-wrapper_title lawyer-wrapper_title-left">Подписка</h2>

                        <div class="subscribe">
                            <p class="subscribe_text">SOS Поддержка</p>
                            <p class="subscribe-status">Активна</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<script>
    $(document).ready(function () {
        setEdit()
        updateData()
    })

    function setEdit() {
        $(document).on('click', '#edit_info', function () {
            $('#clientInfoEdit').modal('toggle')
        })
    }

    function getMainDataForUpdate() {
        return {
            'first_name': $('[name = first_name_edit]').val(),
            'last_name': $('[name = last_name_edit]').val(),
            'middle_name': $('[name = middle_name_edit]').val(),
            'phone_number': $('[name = phone_number_edit]').val(),
            'country_id': $('[name = country_id]').val(),
            'city_id': $('[name = city_id]').val(),
        }
    }

    function updateData() {
        $('#save_edit_main').on('click', function () {
            const data = getMainDataForUpdate()
            page__.sendData('{{ route__('actionUpdateClient_mainstay_client_clientmainstaycontroller') }}',
                data, function (data) {
                    Object.assign(page__.getGolobalData('ClientInfo'), data)
                })
            $('#clientInfoEdit').modal('hide')
        })
    }
</script>
@endsection
