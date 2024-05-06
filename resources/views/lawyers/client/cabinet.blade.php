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
    <section class="u-container lawyer-section">
        <div class="container">
                <div class="container">
                    <div class='modal registration-section' id='clientInfoEdit' tabindex='-1' role='dialog' aria-labelledby='exampleModalCenterTitle' aria-hidden='true'>
                        <h5 class='order-modal_title' id='exampleModalLongTitle'>Редактировать</h5>
                                <div class='registration-form_block'>
                                    @include('component_build', [
                                        'component' => 'component.infoComponent.textInfo',
                                        'params_component' => [
                                            'autostart' => 'false',
                                            'name' => 'employee_info_edit',
                                            'params' => [],
                                            'globalData' => 'ClientInfo',
                                            'ssr' => 'false',

                                            'template' =>
                                    "
                                    <div id=\"common_info_container\" class=\"registration-form_block\">
                                        <div class=\"registration-form_label\">
                                            <label class=\"label-title\">Имя</label>
                                            <input type=\"text\" name=\"first_name_edit\" :value=\"data.first_name\" >
                                        </div>
                                        <div class=\"registration-form_label\">
                                            <label class=\"label-title\">Фамилия</label>
                                            <input type=\"text\" name=\"last_name_edit\" :value=\"data.last_name\">
                                        </div>
                                        <div class=\"registration-form_label\">
                                            <label class=\"label-title\">Отчество</label>
                                            <input type=\"text\" name=\"middle_name_edit\" :value=\"data.middle_name\">
                                        </div>
                                        <div class=\"registration-form_label\">
                                            <label class=\"label-title\">Телефон</label>
                                            <input type=\"text\" name=\"phone_number_edit\" :value=\"data.phone_number\">
                                        </div>
                                    </div>
                                    ",
                                        ]
                                    ])
                                    <div class='registration-form_label'>
                                        <label class='label-title'>Регион</label>
                                    @include('component_build',["component" => "component.listComponent.selectComponent",
                                    "params_component" => [
                                        "autostart" => 'true',
                                        "name" => 'region_id',
                                        "default_title" => 'Регион',
                                        "url" => route("actionGetRegions_mainstay_helpdata_helpdatamainstaycontroller"),
                                        "callBeforloadComponent" => "function(component) {
                                                component.option['currentSelectId'] = page__.getGolobalData('ClientInfo').region_id
                                                component.option['currentSelectName'] = page__.getGolobalData('ClientInfo').region_name
                                                return component.option
                                            }",
                                        "callAfterloadComponent" => "function(component) {
                                                const param = {'region_id': component.vueObject._data.currentSelectId}
                                                page__.getElementsGroup('city_id')[0]['obj'].setUrlParams(param)
                                                $('.js_select').select2({
                                                    minimumResultsForSearch: -1,
                                                });
                                            }",
                                        "template" =>
                                        '<select class="js_select" name="region_id" :id="name">
                                            <option v-for="(items_ , index) in data" :data-text="items_" :value="index" :selected="index == currentSelectId">@{{items_}}</option>
                                        </select>',
                                        "change" => "function(){
                                                    if($(this).val() !== '') {
                                                        const param = {'region_id': $(this).find('option:selected').val()}
                                                        page__.getElementsGroup('city_id')[0]['obj'].setUrlParams(param)
                                                        }
                                                    }"
                                    ]])
                                    </div>
                                    <div class='registration-form_label'>
                                        <label class='label-title'>Город</label>
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
                                        '<select class="js_select" name="city_id" :id="name">
                                            <option v-for="(items_ , index) in data " :data-text="items_" :value="index" :selected="index == currentSelectId">@{{items_}}</option>
                                        </select>',
                                        "change" => "function(){}"
                                    ]])
                                    </div>
                                <div class='flex align-center form--submit'>
                                    <button type='button' id="save_edit_main" class='main-btn main-btn_blue'>Сохранить</button>
                                    <button type='button' class='main-btn main-btn_white' data-fancybox-close >Отменить</button>
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

                                        <div class="lawyer-info_row" v-if="data.phone_number">
                                            <img src="/lawyers/images/icons/phone-icon-blue.svg" alt="phone-icon" class="icon">

                                            <a href="tel:+@{{ data.phone_number }}" class="link">+@{{ data.phone_number }}</a>
                                        </div>

                                    </div>

                                    <div class="flexbox lawyer-info_buttons">
                                        <a href="#clientInfoEdit" data-fancybox id="edit_info" class="main-btn main-btn_white">Редактировать</a>
                                        <a class="logout" href="{{route__("actionUserLogout_logincontroller")}}">Выход</a>
                                    </div>
                                    <div class="lawyer-info_balance">
                                        <div class="lawyer-balance-block">
                                            <p>Ваш баланс</p>
                                            <span class="balance-summ"><span class="balance-summ_ico">₽</span>@{{ data.balance == null ? 0 : data.balance }} руб</span>
                                        </div>
                                        <a href="{{route__("actionPaymentPage_controllers_client_clientcontroller")}}" class="main-btn"><span>Пополнить</span></a>
                                    </div>
                                </div>
                            </div>',
                            ]
                        ])
                    <div class='lawyer-block'>
                        <div class='lawyer-block_subscribe'>
                            <h2 class='lawyer-block_subscribe_title'>SOS Поддержка</h2>
                            <p class='lawyer-block_subscribe_days'>30 дней</p>
                            <span class='lawyer-block_subscribe_date'>22 окт 2023</span>
                            <a href='#' class='lawyer-block_subscribe_extension'>продлить</a>
                        </div>
                    </div>
                </div>

                <div class="right">

                        @include('component_build', [
                            'component' => 'component.gridComponent.simpleGrid',
                            'params_component' => [
                                'autostart' => 'true',
                                'name' => 'client_orders',
                                'url' => route__("actionGetVacancies_mainstay_client_clientmainstaycontroller"),
								'params' => ['user_id' => auth()->id(), 'is_group' => 0, 'except_status' => [7, 6]],

                                'template' => "
                    <div class='lawsuit lawyer-wrapper' :id=\"name + '_body'\">
                        <nav class='lawsuit_nav'>
                        <div class='client-add-order_btn'>
                            <h2 class='lawyer-wrapper_title lawyer-wrapper_title-left'>Мои заказы </h2>
                            <a :href=\"'{{ route__('actionCreateVacancy_controllers_client_clientcontroller') }}'\" class='main-btn main-btn_orange small add_ico'><span>Новый заказ</span></a>
                        </div>
                            <ul>
                                <li mark='line_mark' class='has_new'>
                                    <button type='button' @click.prevent=\"switchCategory(3)\">Новые <span>@{{ count_new_items }}</span></button>
                                </li>
                                <li mark='line_mark'>
                                    <button type='button' @click.prevent=\"switchCategory(7)\">Выполненные</button>
                                </li>
                                <li mark='line_mark'>
                                    <button type='button' @click.prevent=\"switchCategory(8)\">Отмененные</button>
                                </li>
                                <li mark='line_mark' class='active'>
                                    <button type='button' @click.prevent=\"switchCategory(null)\">Все</button>
                                </li>
                            </ul>
                        </nav>
                        <ul class='my-orders_ul' :id=\"name\">
                                <li v-for=\"vacancy in data\">
                                    <div class='my-orders_info'>
                                        <p class='my-orders_text'>
                                            @{{ vacancy.title }}
                                        </p>
                                        <p class='my-orders_price'>
                                            @{{ vacancy.payment }} руб.
                                        <span>0 ответов</span>
                                        </p>
                                    </div>

                                    <time class='my-orders_time mobile'>
                                        15мин назад
                                    </time>

                                    <p :class=\"['my-orders_stage', {'moderation-status': vacancy.status != 7}, {'closed-status': vacancy.status == 7}]\">
                                        @{{ vacancy.status_text }}
                                    </p>
                                </li>
                            </ul>
                        <button class='more-services' @click.prevent=\"goToOrdersPage()\">Еще</button>
                    </div>
                        ",

                        'pagination' => [
                                        'page' => 1,
                                        'pageSize' => 3,
                                        'countPage' => 1,
                                        'typePagination' => 2,
                                        'showPagination' => 0,
                                        'showInPage' => 3,
                                        'count_line' => 1,
                                        'all_load' => 0,
                                        'physical_presence' => 0
                                    ],
                            ]
                        ])





                        @include('component_build', [
                            'component' => 'component.gridComponent.simpleGrid',
                            'params_component' => [
                                'autostart' => 'true',
                                'name' => 'client_group_orders',
                                'url' => route__("actionGetVacancies_mainstay_client_clientmainstaycontroller"),
								'params' => ['user_id' => auth()->id(), 'is_group' => 1],

                                'template' => '
                                <div class="lawsuit lawyer-wrapper">
                                    <h2 class="lawyer-wrapper_title lawyer-wrapper_title-left">Коллективные иски </h2>
                                    <ul class="my-orders_ul" :id="name + \'_body\'">
                                        <li v-for="item in data">
                                            <div class="my-orders_info">
                                                <p class="my-orders_text">
                                                    @{{ item.title }}
                                                </p>

                                                <ul class="my-orders_sub-ul">
                                                    <li>
                                                    @{{ item.count_group_users ?? 0 }} заявителей (Ожидают подтверждение @{{ item.count_not_approved ?? 0 }})</li>
                                                    <li>@{{ item.count_messages ?? 0 }} сообщений</li>
                                                    <li>@{{ item.count_offers ?? 0 }} предложения от юристов</li>
                                                </ul>
                                            </div>
                                            <a href="#" type="button" class="main-btn main-btn_white">Открыть</a>
                                        </li>
                                    </ul>
                                </div>',
                        'pagination' => [
                                        'page' => 1,
                                        'pageSize' => 3,
                                        'countPage' => 1,
                                        'typePagination' => 2,
                                        'showPagination' => 1,
                                        'showInPage' => 3,
                                        'count_line' => 1,
                                        'all_load' => 0,
                                        'physical_presence' => 0
                                    ],
                            ]
                        ])


                    @include('component_build', [
                            'component' => 'component.gridComponent.simpleGrid',
                            'params_component' => [
                                'autostart' => 'true',
                                'name' => 'client_questions',
                                'url' => route__("actionGetClientQuestions_mainstay_client_clientmainstaycontroller"),
								'params' => ['user_id' => auth()->id()],

                                'template' => '<div class="my-orders lawyer-wrapper">
                        <h2 class="lawyer-wrapper_title">Вопрос-ответ</h2>
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
                        </ul>
                        </div>',

                        'pagination' => [
                                        'page' => 1,
                                        'pageSize' => 3,
                                        'countPage' => 1,
                                        'typePagination' => 2,
                                        'showPagination' => 1,
                                        'showInPage' => 3,
                                        'count_line' => 1,
                                        'all_load' => 0,
                                        'physical_presence' => 0
                                    ],
                            ]
                        ])

                    </div>
                </div>
            </div>
        </div>
    </section>

<script>
    $(document).ready(function () {
        updateData()
    })

    function getMainDataForUpdate() {
        return {
            'first_name': $('[name = first_name_edit]').val(),
            'last_name': $('[name = last_name_edit]').val(),
            'middle_name': $('[name = middle_name_edit]').val(),
            'phone_number': $('[name = phone_number_edit]').val(),
            'region_id': $('[name = region_id]').val(),
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
            $.fancybox.close();
        })
    }

    function goToOrdersPage() {
        window.location.href = '{{ route__('actionMyOrders_controllers_client_clientcontroller') }}'
    }
</script>
@endsection
