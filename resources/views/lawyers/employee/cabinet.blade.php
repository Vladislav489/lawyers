@extends('lawyers.layouts.main')
@section('title', 'Кабинет сотрудника')

@section('content')
    <section class="gradient-bg u-container lawyer-section">
        <div class="container">
{{--            <ul class="breadcrumbs mobile-hidden">--}}
{{--                <li class="cool-underline"><a href="#">Юрист</a></li>--}}
{{--                <li class="cool-underline"><a href="#">Город</a></li>--}}
{{--            </ul>--}}

            <div class="lawyer-container">
                <div class="left">
                    @include('component_build', [
                            'component' => 'component.infoComponent.textInfo',
                            'params_component' => [
                                'autostart' => 'true',
                                'name' => 'employee_info_main',
								'url' => route__('actionGetEmployee_mainstay_employee_employeemainstaycontroller'),
								'params' => ['user_id' => auth()->id()],
                                'template' =>
                    "
                    <div:id=\"name\">
                    <div class='lawyer-block'>
                        <div class='lawyer-top'>
                            <div class='lawyer-img'>
                                <img :src=\"data.avatar_full_path\" alt='lawyer-img'>
                            </div>

                            <div class='lawyer-info'>
                                <h2 class='lawyer-name'>@{{getFullName(data)}}</h2>
                                <span class='lawyer-check'>
                                    Проверенный юрист
                                    <img class='icon' src='/lawyers/images/icons/check-icon-white.svg' alt='check-icon'>
                                </span>
                            </div>
                        </div>

                        <div class='lawyer-bottom'>
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
                                    <img class='icon' src='/lawyers/images/icons/info-icon-blue.svg' alt='info-icon'>
                                </div>
                            </div>

                            <div class='lawyer-info'>
                                <div class='lawyer-info_row'>
                                    <img class='icon' src='/lawyers/images/icons/loc-icon-blue.svg' alt='loc-icon'>
                                    <span>@{{data.city_name}}</span>
                                </div>
                                <div class='lawyer-info_row'>
                                    <img class='icon' src='/lawyers/images/icons/bah-icon-blue.svg' alt='bah-icon'>
                                    <span name='practice_years'>@{{agetostr(data.practice_years)}} практики</span>
                                </div>
                                <div class='lawyer-info_row'>
                                    <img class='icon' src='/lawyers/images/icons/phone-icon-blue.svg' alt='phone-icon'>
                                    <a name='phone_number' href='tel:+@{{data.phone_number}}' class='span-link'>+@{{data.phone_number}}</a>
                                </div>
                                <div class='lawyer-info_row'>
                                    <img class='icon' src='/lawyers/images/icons/planet-icon-blue.svg' alt='planet-icon'>
                                    <span>www.site.ru</span>
                                </div>
                                <div class='lawyer-info_row'>
                                    <img class='icon' src='/lawyers/images/icons/message-icon-blue.svg' alt='message-icon'>
                                    <span>Консультации онлайн:</span>
                                    <span class='bold'>Да</span>
                                </div>
                                <div class='lawyer-info_row'>
                                    <img class='icon' src='/lawyers/images/icons/clock-icon-blue.svg' alt='clock-icon'>
                                    <span>Время работы:</span>
                                    <span class='bold'>Пн-Сб 9:00 - 18:00</span>
                                </div>
                                <button @click.prevent=\"goToEditPage()\" type='button' class='main-btn main-btn_white'>
                                    <span class='first'>Редактировать</span>
                                    <span class='second'>Редактировать</span>
                                </button>
                                <button type='button' class='main-btn main-btn_blue mobile'>
                                    <span class='first'>
                                        Редактировать
                                        <img src='images/icons/arrow-icon-white.svg' alt='arrow-icon' class='icon'>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div></div>",
                            ]
                        ])
                </div>

                <div class="right">
                    <ul class="round-top_nav">
                        <li class="active">
                            <button type="button">Мои заказы</button>
                        </li>
                        <li>
                            <button type="button">Услуги</button>
                        </li>
                        <li>
                            <button type="button">Отзывы</button>
                        </li>
                        <li>
                            <button type="button">Ответы юриста</button>
                        </li>
                    </ul>


                        @include('component_build', [
                            'component' => 'component.gridComponent.simpleGrid',
                            'params_component' => [
                                'autostart' => 'true',
                                'name' => 'employee_vacancies',
								'url' => route__('actionGetVacancyList_mainstay_vacancy_vacancymainstaycontroller'),
								'params' => ['executor_id' => auth()->id(), 'is_group' => 0],
                                'template' =>
                            "
                    <div class='my-orders lawyer-wrapper' :id=\"name + '_body'\">
                        <nav class='lawsuit_nav'>
                            <h2 class='lawyer-wrapper_title'>Мои заказы <span></span></h2>
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
                                    </div>

                                    <p class='my-orders_stage moderation-status'>
                                        @{{ vacancy.status_text }}
                                    </p>
                                </li>
                            </ul>
                        <button class='more-services' @click.prevent=\"goToMyOrdersPage()\">Еще</button>
                    </div>
                            ",
                            'pagination' => [
                                        'page' => 1,
                                        'pageSize' => 2,
                                        'countPage' => 1,
                                        'typePagination' => 2,
                                        'showPagination' => 0,
                                        'showInPage' => 2,
                                        'count_line' => 1,
                                        'all_load' => 0,
                                        'physical_presence' => 0
                                    ],
                            ]
                        ])



                    @include('component_build', [
                            'component' => 'component.infoComponent.textInfo',
                            'params_component' => [
                                'autostart' => 'true',
                                'name' => 'employee_group_vacancies',
								'url' => route__('actionGetGroupVacancyList_mainstay_vacancy_vacancymainstaycontroller'),
								'params' => ['executor_id' => auth()->id()],
                                'template' =>
                            "
                    <div class='lawsuit lawyer-wrapper'>
                        <h2 class='lawyer-wrapper_title lawyer-wrapper_title-left'>Коллективные иски </h2>

                        <ul class='my-orders_ul'>
                            <li>
                                <div class='my-orders_info'>
                                    <p class='my-orders_text'>
                                        Иск собственников многоквартирного дома в признании права общей долевой
                                        собственности на подвальные помещения здания
                                    </p>

                                    <ul class='my-orders_sub-ul'>
                                        <li>5 заявителей (Ожидают подтверждение 4)</li>
                                        <li>5 сообщений</li>
                                        <li>3 предложения от юристов</li>
                                    </ul>
                                </div>

                                <a href='#' type='button' class='main-btn main-btn_white'>
                                    <span class='first'>Открыть</span>
                                    <span class='second'>Открыть</span>
                                </a>
                            </li>

                            <li>
                                <div class='my-orders_info'>
                                    <p class='my-orders_text'>
                                        Иск собственников многоквартирного дома в признании права общей долевой
                                        собственности на подвальные помещения здания
                                    </p>

                                    <ul class='my-orders_sub-ul'>
                                        <li>5 заявителей (Ожидают подтверждение 4)</li>
                                        <li>5 сообщений</li>
                                        <li>3 предложения от юристов</li>
                                    </ul>
                                </div>

                                <a href='#' type='button' class='main-btn main-btn_white'>
                                    <span class='first'>Открыть</span>
                                    <span class='second'>Открыть</span>
                                </a>
                            </li>
                        </ul>

                        <button class='more-services'>Еще</button>
                    </div>
                            ",
                            'pagination' => [
                                        'page' => 1,
                                        'pageSize' => 2,
                                        'countPage' => 1,
                                        'typePagination' => 2,
                                        'showPagination' => 0,
                                        'showInPage' => 2,
                                        'count_line' => 1,
                                        'all_load' => 0,
                                        'physical_presence' => 0
                                    ],
                            ]
                        ])



                    <div class="subscribe-block lawyer-wrapper">
                        <h2 class="lawyer-wrapper_title lawyer-wrapper_title-left">Подписка</h2>

                        <div class="subscribe">
                            <p class="subscribe_text">SOS Поддержка</p>
                            <p class="subscribe-status">Активна</p>

                            <time class="subscribe-block_time mobile">
                                22 окт 2023
                            </time>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>

        function goToEditPage() {
            window.location.href = '{{ route__('actionEmployeeProfile_controllers_employee_employeecontroller') }}'
        }

        function switchCategory(categoryId) {
            // STATUS_NEW = 1;
            // STATUS_MODERATION = 2;
            // STATUS_PAYED = 3;
            // STATUS_IN_PROGRESS = 4;
            // STATUS_INSPECTION = 5;
            // STATUS_ACCEPTED = 6;
            // STATUS_CLOSED = 7;
            // STATUS_CANCELLED = 8;
            let component = page__.getElementsGroup('employee_vacancies')[0]['obj']
            $('nav[class = lawsuit_nav] > ul > li[mark=line_mark]').removeClass('active')
            event.currentTarget.parentElement.classList.add('active')
            component.setUrlParams(Object.assign({}, component.params, {status: categoryId}))
        }

        function goToMyOrdersPage() {
            window.location.href = '{{ route__('actionViewOrders_controllers_employee_employeecontroller') }}'
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

        function getFullName(data) {
            return data.last_name + ' ' + data.first_name + ' ' + data.middle_name
        }
    </script>
@endsection
