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

@section('title', 'Отклик на вакансию')

@section('content')

    <section class="u-container response-section">
        <div class="container">


            @include('component_build', [
                        'component' => 'component.infoComponent.textInfo',
                        'params_component' => [
                        'autostart' => 'false',
                        'name' => 'vacancy_info',
                        'globalData' => 'VacancyInfo',


                        'template' => "
                            <div class='response-block'>
                            <div class='exchange'>
                                <div class='exchange_left'>
                                    <h3 class='exchange_title'>
                                        <span>@{{ data.title }}</span>
                                    </h3>
                                    <p class='exchange_text fs-text'>
                                        @{{ data.description }}
                                    </p>

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

                                <button id='switch_button' type='button' class='responce-call main-btn main-btn_blue' @click.prevent = \"openResponseForm()\">Откликнуться</button>
                            </div>
                        </div>
                        "
        ]
        ])

        </div>
    </section>

    @include('component_build', [
                        'component' => 'component.infoComponent.textInfo',
                        'params_component' => [
                        'autostart' => 'true',
                        'name' => 'my_response',
                        'url' => route__('actionGetResponse_mainstay_employee_employeemainstaycontroller'),
						'params' => ['vacancy_id' => request()->route('vacancy_id'), 'employee_id' => auth()->id()],

						'template' => "
                            <section class='u-container response-section section--lawyer-response' id='response_form' :hidden=\"data ? false : true\">
                                <div class='container'>
                                    <div class='lawyer-responce_card'>
                                        <div class='lawyer-responce_inner'>
                                            <h2 class='heading--lawyer-response'>Текст отклика</h2>
                                            <form action='#' class='form--lawyer-response'>
                                                <input type='hidden' id='offer_id' :value=\"data.id\">
                                                <input type='hidden' id='employee_response_id' :value=\"data.employee_response_id\">
                                                <textarea id='response_text' class='form--lr-textarea' placeholder='Введите сопроводительный текст...' >@{{ data.response_text }}</textarea>
                                                <div class='flex align-center form--group'>
                                                    <span class='form--group_heading'>Стоимость услуги</span>
                                                    <input id='response_price' type='number' placeholder='Сумма' class='form--field' :value=\"data.payment\">
                                                    <span>рублей</span>
                                                </div>
                                                <div class='flex align-center form--group'>
                                                    <span class='form--group_heading'>Срок</span>
                                                    <input id='response_period' type='number' placeholder='Число' class='form--field' :value=\"data.period\">
                                                    <span>дней</span>
                                                </div>
                                                <button id='respond' @click.prevent=\"send()\" type='submit' class='main-btn main-btn_blue form--lr_submit'>@{{ data ? 'Обновить отклик' : 'Отправить' }}</button>
                                                <div class='flex form--acceptance'>
                                                    <label class='form--checkbox-wrap'>
                                                        <input type='checkbox' class='acceptance-checkbox' hidden>
                                                        <div class='acceptance-icon'></div>
                                                    </label>
                                                    <p>Я принимаю <a>Правила</a> и <a>Политику Конфидициальности</a></p>
                                                </div>
                                            </form>
                                        </div>
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
            let elem = $('#response_form')
            elem.prop('hidden', !elem.prop('hidden'))
            if (elem.prop('hidden') == true) {
                $('#switch_button').text('Откликнуться')
            } else {
                $('#switch_button').text('Отмена')
            }
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
                        window.location.href = '{{ route__('actionVacancyExchange_controllers_employee_employeecontroller') }}'
                    }
                }
            })
        }
    </script>

@endsection
