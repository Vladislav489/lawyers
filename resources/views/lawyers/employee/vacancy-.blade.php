@extends('lawyers.layouts.main')

@include('component_build',[
'component' => 'component.loadComponent.loadGlobalData',
'params_component' => [
'name' => "VacancyInfo",
'autostart' => 'false',
'ssr' => 'true',
'url' => route__("actionGetVacancyForEmployeeRespond_mainstay_vacancy_vacancymainstaycontroller"),
'params' => ['id' => request()->route('vacancy_id')],
]
])

@include('component_build',[
'component' => 'component.loadComponent.loadGlobalData',
'params_component' => [
'name' => "LawyerResponse",
'autostart' => 'false',
'ssr' => 'true',
'url' => route__('actionGetResponse_mainstay_employee_employeemainstaycontroller'),
'params' => ['vacancy_id' => request()->route('vacancy_id'), 'employee_id' => auth()->id()],
]
])

@section('title', 'Отклик на вакансию')

@section('content')

    <section class="u-container response-section">
        <div class="container">

            @include('component_build', [
                        'component' => 'component.infoComponent.textInfo',
                        'params_component' => [
                        'autostart' => 'true',
                        'name' => 'vacancy_info',
                        'globalData' => 'VacancyInfo',
//						'url' => route__("actionGetVacancyForEmployeeRespond_mainstay_vacancy_vacancymainstaycontroller"),
//                        'params' => ['id' => request()->route('vacancy_id')],


                        'template' => "
                        <div class='response-block'>
                            <div class='exchange'>
                                <div class='exchange_left'>
                                    <h3 class='exchange_title' hidden>
                                        <span>@{{ data.title }}</span>
                                    </h3>
                                    <p class='exchange_text fs-text'>
                                        @{{ data.description }}
                                    </p>
                                <ul v-if=\"data.files\" class='files_list'>
                                    <li v-for=\"item in data.files\">
                                        <a @click=\"viewFile(item.path, item.name)\">@{{item.name}}</a>
                                    </li>
                                </ul>

                                    <div class='exchange_info'>
                                        <ul class='exchange_location'>
                                            <li class='fs-text'>
                                                <img src='/lawyers/images/icons/loc-icon-gray.svg' alt='loc-icon'>
                                                @{{ data.location }}
                                            </li>
                                            <li class='fs-text'>
                                                <img src='/lawyers/images/icons/bag-icon-gray.svg' alt='bag-icon' class='bag-icon'>
                                                Дистанционная консультация
                                            </li>
                                        </ul>

                                        <ul class='exchange_other-info'>
                                            <li>
                                                <img src='/lawyers/images/icons/lil-clock-gray.svg' alt='clock-icon'>@{{ data.time_ago }}
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class='exchange_right'>
                                    <span>за проект</span>
                                    <p>@{{ data.payment !== 0 ? data.payment : 'Н/У' }} &#8381;</p>
                                </div>
                            </div>

                            <div class='response-info'>
                                <ul>
                                    <li><span>Покупатель</span><span class='bold'>@{{ data.owner_name }}</span></li>
                                    <!-- <li><span>Размешено проектов</span><span>94</span></li> -->
                                    <!-- <li><span>Нанято</span><span>47%</span></li> -->
                                    <li><span>Статус заказа</span><span class='green'>@{{ data.current_status_text }}</span></li>
                                    <li><span>Цена заказа</span><span>@{{ data.payment !== 0 ? data.payment : 'Н/У' }} &#8381;</span></li>
                                </ul>

                                <button id='switch_button' type='button' class='responce-call main-btn main-btn_blue'
                                 @click.prevent = \"openResponseSection()\" v-if=\"!checkAlreadyRespondCondition()\">Откликнуться</button>
                            </div>
                        </div>
                        "
        ]
        ])

{{--            @include('component_build',[--}}
{{--	        'component' => 'component.infoComponent.textInfo',--}}
{{--            'params_component' => [--}}
{{--            'name' => "vacancy_files",--}}
{{--            'autostart' => 'true',--}}
{{--            'url' => route__("actionGetFilesList_mainstay_file_filemainstaycontroller"),--}}
{{--            'params' => ['path_start' => 'vacancy/' . request()->route('vacancy_id')],--}}
{{--            'template' =>--}}
{{--            "<ul>--}}
{{--                <li v-for=\"item in data\">--}}
{{--                    <a @click=\"viewFile(item.path, item.name)\">@{{item.name}}</a>--}}
{{--                </li>--}}
{{--            </ul>"--}}
{{--                ]--}}
{{--            ])--}}

        </div>
    </section>

                    @include('component_build', [
                        'component' => 'component.infoComponent.textInfo',
                        'params_component' => [
                        'autostart' => 'true',
                        'name' => 'my_response',
                        'url' => route__("actionGetEmployee_mainstay_employee_employeemainstaycontroller"),
						'params' => ['id' => auth()->id()],
						'callBeforloadComponent' => "function(component) {
						        component.option['lawyerResponse'] = page__.getGolobalData('LawyerResponse')
						        component.option['vacancyInfo'] = page__.getGolobalData('VacancyInfo')
						        return component.option
						    }",

						'template' => "
                            <section class='u-container response-section section--lawyer-response all-responses-section'
                             id='response_section' v-if=\"lawyerResponse\">
                                <div class='container' id='response_card'>
                                    <div class='responses-container'>

                                        <div class='fs-block'>
                                            <div class='fs-img-container'>
                                                <div class='fs-img'>
                                                    <img :src=\"data.avatar_full_path\" alt='lawyer-img' alt='' height='100' width='100' />
                                                </div>
                                                <h3 class='fs-name'>@{{ data.full_name }}</h3>
                                            </div>

                                            <div class='fs-info'>
                                                <h3 class='fs-name'>@{{ data.full_name }}</h3>
                                                <p class='fs-row'>
                                                    <img src='/lawyers/images/icons/loc-icon-gray.svg' alt='loc-icon' class='icon'>
                                                    <span class='fs-text'>@{{ data.location }}</span>
                                                </p>
                                                <p class='fs-row'>
                                                    <img src='/lawyers/images/icons/bag-icon-gray.svg' alt='bag-icon' class='icon'>
                                                    <span class='fs-text'>@{{ data.practice_years }} лет практики</span>
                                                </p>
                                                <!-- <div class='lawyer_rate-block'>
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
                                                        <img src='/lawyers/images/icons/info-icon-gray.svg' alt='info-icon' class='icon'>
                                                    </div>

                                                    <p class='fs-text'>16 заказов</p>
                                                    <p class='fs-text'>94% сдано</p>
                                                    <p class='fs-text'><span class='green'>100% </span> сдано вовремя</p>
                                                </div> -->
                                                <p class='fs-text'>@{{ lawyerResponse.response_text }}</p>
                                                <!-- <button class='read-more' type='button'>Показать еще</button> -->
                                            </div>

                                            <div class='buttons-container'>
                                                <div class='blue-container mobile-hidden'>
                                                    <span>@{{ lawyerResponse.period }} дней</span><span>@{{ lawyerResponse.payment }} ₽</span>
                                                </div>
                                                <ul class='mobile blue-container_mobile'>
                                                    <li><span>Стоимость</span><span class='b-price'>@{{ lawyerResponse.payment }} ₽</span></li>
                                                    <li><span>Срок выполнения</span><span class='b-days'>@{{ lawyerResponse.period }} дней</span></li>
                                                </ul>
                                                <button v-if=\"vacancyInfo.status != 8\" class='main-btn main-btn_blue' @click.prevent=\"openResponseForm()\">Редактировать</button>
                                                <button v-if=\"vacancyInfo.status != 8\" class='main-btn main-btn_red' @click.prevent=\"deleteResponse(lawyerResponse.id, lawyerResponse.employee_response_id)\">Удалить</button>
                                                <button v-else class='main-btn main-btn_blue' @click.prevent=\"acceptToWork(vacancyInfo.id)\">Принять</button>
                                                <button v-else class='main-btn main-btn_white' @click.prevent=\"declineToWork(vacancyInfo.id)\">Отказаться</button>
                                            </div>

                                        </div>
                                    </div>
                                </div>


                                <!--<div class='container' id='response_form' hidden>
                                        <div class='lawyer-responce_inner'>
                                            <h2 class='heading--lawyer-response'>Текст отклика</h2>
                                            <form action='#' class='form--lawyer-response'>
                                                <textarea id='response_text' class='form--lr-textarea' placeholder='Введите сопроводительный текст...' >@{{ lawyerResponse.response_text }}</textarea>
                                                <div class='flex align-center form--group'>
                                                    <span class='form--group_heading'>Стоимость услуги</span>
                                                    <input id='response_price' type='number' placeholder='Сумма' class='form--field' :value=\"lawyerResponse.payment\">
                                                    <span>рублей</span>
                                                </div>
                                                <div class='flex align-center form--group'>
                                                    <span class='form--group_heading'>Срок</span>
                                                    <input id='response_period' type='number' placeholder='Число' class='form--field' :value=\"lawyerResponse.period\">
                                                    <span>дней</span>
                                                </div>
                                                <button id='respond' @click.prevent=\"send()\"
                                                 type='submit' class='main-btn main-btn_blue form--lr_submit'>Отправить</button>
                                                <button id='cancel_respond' @click.prevent=\"openResponseForm()\"
                                                 type='submit' class='main-btn main-btn_white form--lr_submit'>Отменить</button>
                                                <div class='flex form--acceptance'>
                                                    <label class='form--checkbox-wrap'>
                                                        <input type='checkbox' class='acceptance-checkbox' hidden>
                                                        <div class='acceptance-icon'></div>
                                                    </label>
                                                    <p>Я принимаю <a>Правила</a> и <a>Политику Конфидициальности</a></p>
                                                </div>
                                                <input type='hidden' id='offer_id' :value=\"lawyerResponse.id\">
                                                <input type='hidden' id='employee_response_id' :value=\"lawyerResponse.employee_response_id\">
                                            </form>
                                        </div>
                                    </div>
                                </div>-->
                            </section>
						"

                        ]
                    ])

                    @include('component_build', [
                        'component' => 'component.infoComponent.textInfo',
                        'params_component' => [
                        'autostart' => 'false',
                        'name' => 'response_form',
						'callBeforloadComponent' => "function(component) {
						        component.option['lawyerResponse'] = page__.getGolobalData('LawyerResponse')
						        component.option['vacancyInfo'] = page__.getGolobalData('VacancyInfo')
						        return component.option
						    }",

						'template' => "
                            <section class='u-container response-section section--lawyer-response all-responses-section'
                             id='response_section_form' hidden>

                                <div class='container' id='response_form'>
                                    <div class='lawyer-responce_card'>
                                        <div class='lawyer-responce_inner'>
                                            <h2 class='heading--lawyer-response'>Текст отклика</h2>
                                            <form action='#' class='form--lawyer-response'>
                                                <textarea id='response_text' class='form--lr-textarea' placeholder='Введите сопроводительный текст...' >@{{ lawyerResponse.response_text }}</textarea>
                                                <div class='flex align-center form--group'>
                                                    <span class='form--group_heading'>Стоимость услуги</span>
                                                    <input id='response_price' type='number' placeholder='Сумма' class='form--field' :value=\"lawyerResponse.payment\">
                                                    <span>рублей</span>
                                                </div>
                                                <div class='flex align-center form--group'>
                                                    <span class='form--group_heading'>Срок</span>
                                                    <input id='response_period' type='number' placeholder='Число' class='form--field' :value=\"lawyerResponse.period\">
                                                    <span>дней</span>
                                                </div>
                                                <button id='respond' @click.prevent=\"send()\"
                                                 type='submit' class='main-btn main-btn_blue form--lr_submit'>Отправить</button>
                                                <button id='cancel_respond' @click.prevent=\"openResponseForm()\"
                                                 type='submit' class='main-btn main-btn_white form--lr_submit'>Отменить</button>
                                                <div class='flex form--acceptance'>
                                                    <label class='form--checkbox-wrap'>
                                                        <input type='checkbox' class='acceptance-checkbox' hidden>
                                                        <div class='acceptance-icon'></div>
                                                    </label>
                                                    <p>Я принимаю <a>Правила</a> и <a>Политику Конфидициальности</a></p>
                                                </div>
                                                <input type='hidden' id='offer_id' :value=\"lawyerResponse.id\">
                                                <input type='hidden' id='employee_response_id' :value=\"lawyerResponse.employee_response_id\">
                                            </form>
                                        </div>
                                    </div>
                            </section>
						"

                        ]
                    ])


    <script>
        $(document).ready(function () {
            // $('#respond').click(function (e) {
            //     e.preventDefault()
            //     if ($('.acceptance-checkbox').prop('checked')) {
            //         sendRespondData(getResponseData())
            //     }
            // })
        })

        function send() {
            if ($('.acceptance-checkbox').prop('checked')) {
                sendRespondData(getResponseData())
            }
        }

        function openResponseForm() {
            let elem = $('#response_section_form')
            elem.prop('hidden', !elem.prop('hidden'))
            $('#response_section').prop('hidden', !$('#response_section').prop('hidden'))
        }

        function openResponseSection() {
            let elem = $('#response_section_form')
            elem.prop('hidden', !elem.prop('hidden'))
        }

        function deleteResponse(offerId, offerResponseId) {
            $.ajax({
                method: 'POST',
                data: {
                    id: offerId,
                    employee_response_id: offerResponseId,
                    vacancy_id: {{ request()->route('vacancy_id') }},
                    employee_id: {{ auth()->id() }},
                },
                url: '{{ route__('actionDeleteVacancyResponse_mainstay_employee_employeemainstaycontroller') }}',
                success: function (response) {
                    location.reload();
                }
            })
        }

        function getResponseData() {
            return {
                user_id: '{{ auth()->id() }}',
                vacancy_id: '{{ request()->route('vacancy_id') }}',
                text: $('#response_text').val(),
                payment: $('#response_price').val(),
                period: $('#response_period').val(),
                offer_id: $('#offer_id').val(),
                employee_response_id: $('#employee_response_id').val(),
            }
        }

        function sendRespondData(sendData) {
            $.ajax({
                method: 'POST',
                data: sendData,
                url: '{{ route__('actionRespondToVacancy_mainstay_employee_employeemainstaycontroller') }}',
                success: function (response) {
                    if (!response) {
                        alert('Ошибка')
                    } else {
                        // page__.globlaData['LawyerResponse'] = response
                        // let components = page__.getElementsGroup('my_response')
                        // components.forEach((component) => {
                            // component.obj.setUrlParams(component.obj.getUrlParams())
                            // component.obj.updateVue()
                        // })
                        location.reload()
                    }
                }
            })
        }

        function checkAlreadyRespondCondition() {
            page__.getGolobalData('LawyerResponse');
            return page__.getGolobalData('LawyerResponse');
        }

        function viewFile(path, name) {
            const route = `{{ route('download') }}?path=${path}&name=${name}`
            window.open(route)
        }

        function acceptToWork(vacancyId) {
            $.ajax({
                method: 'POST',
                data: {
                    vacancy_id: vacancyId,
                    employee_user_id: {{ auth()->id() }},
                },
                url: '{{ route__('actionAcceptWork_mainstay_employee_employeemainstaycontroller') }}',
                success: function (response) {
                    console.log(response);
                }
            })
        }
        function declineToWork(vacancyId) {
            $.ajax({
                method: 'POST',
                data: {
                    vacancy_id: vacancyId,
                    employee_user_id: {{ auth()->id() }}
                },
                url: '{{ route__('actionDeclineWork_mainstay_employee_employeemainstaycontroller') }}',
                success: function (response) {
                    console.log(response);
                }
            })
        }
    </script>

@endsection
