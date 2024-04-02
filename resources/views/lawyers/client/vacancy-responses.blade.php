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

@section('title', 'Просмотр заказа')

@section('content')
<section class="u-container response-section">
    <div class="container">
        <ul class="breadcrumbs">
            <li class="cool-underline"><a href="#">Мои заказы</a></li>
            <li class="cool-underline"><a href="#">Заказ #1234</a></li>
        </ul>

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

                                <button type='button' class='change-info' @click=\"editVacancy(data.id)\">Изменить</button>
                            </div>
                        </div>
                        "
        ]
        ])

    </div>
</section>

<section class="u-container all-responses-section">

    @include('component_build', [
                        'component' => 'component.gridComponent.simpleGrid',
                        'params_component' => [
                        'autostart' => 'true',
                        'name' => 'vacancy_offers',
						'url' => route__('actionGetEmployeeRespondsList_mainstay_vacancy_vacancymainstaycontroller'),
						'params' => ['vacancy_id' => request()->route('vacancy_id')],

                        'template' => "
    <div class='container'>
        <span class='resp-count'>@{{ pagination.totalCount }} откликов</span>

        <div class='responses-container'>

            <div class='fs-block' v-for=\"item in data\">
                <div class='fs-img'>
                    <img :src=\"item.avatar\" alt='lawyer-img'>
                </div>

                <div class='fs-info'>
                    <h3 class='fs-name'>@{{ item.full_name }}</h3>
                    <p class='fs-row'>
                        <img src='/lawyers/images/icons/loc-icon-gray.svg' alt='loc-icon' class='icon'>
                        <span class='fs-text'>@{{ item.location }}</span>
                    </p>
                    <p class='fs-row'>
                        <img src='/lawyers/images/icons/bag-icon-gray.svg' alt='bag-icon' class='icon'>
                        <span class='fs-text'>@{{ agetostr(item.practice_years) }} практики</span>
                    </p>
                    <!--<div class='lawyer_rate-block'>
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
                    </div>-->
                    <p class='fs-text'>@{{ item.response_text }}</p>
                </div>

                <div class='buttons-container'>
                    <div class='blue-container mobile-hidden'>
                        <span>@{{ item.period }} дней</span><span>@{{ item.payment }} ₽</span>
                    </div>
                    <ul class='mobile blue-container_mobile'>
                        <li><span>Стоимость</span><span class='b-price'>@{{ item.payment }} ₽</span></li>
                        <li><span>Срок выполнения</span><span class='b-days'>@{{ item.period }} дней</span></li>
                    </ul>
                    <button class='main-btn main-btn_blue' @click.prevent=\"setEmployeeForOrder(item.employee_user_id)\">
                        <span class='first' @click.prevent=\"setEmployeeForOrder(employee_user_id)\">Заказать</span>
                        <span class='second'>Заказать</span>
                    </button>
                    <button class='main-btn main-btn_white'>
                        <span class='first'>Сообщение</span>
                        <span class='second'>Сообщение</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
                        ",

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

</section>

<script>
    $(document).ready(function () {

    })

    function editVacancy(id) {
        window.location.href = `{{ route__('actionEditVacancy_controllers_client_clientcontroller') }}/${id}`
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

    function setEmployeeForOrder(employeeId) {
        const vacancyId = {{ request()->route('vacancy_id') }}
        $.ajax({
            method: 'POST',
            data: {
                vacancy_id: vacancyId,
                executor_id: employeeId
            },
            url: '{{ route__('actionSetExecutorForVacancy_mainstay_client_clientmainstaycontroller') }}',
            success: function (response) {
                if (response) {
                    window.location.href = `{{ route__('actionViewVacancy_controllers_client_clientcontroller') }}/${vacancyId}`
                }
                alert('error')
            }
        })
    }
</script>

@endsection
