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
@section('title', 'Вакансия')

@section('content')
<section class="u-container order-section">
        <div class="container">
{{--            <ul class="breadcrumbs">--}}
{{--                <li class="cool-underline"><a href="#">Мои заказы</a></li>--}}
{{--                <li class="cool-underline"><a href="#">Заказ #1234</a></li>--}}
{{--            </ul>--}}
            <div class="order-container">
                <div class="order-question-block">
                @include('component_build', [
	                'component' => 'component.infoComponent.textInfo',
	                'params_component' => [
	                	'autostart' => 'false',
	                	'name' => 'vacancy_info',
                        'globalData' => 'VacancyInfo',
//	                	'url' => route__('actionGetVacancyForEmployeeRespond_mainstay_vacancy_vacancymainstaycontroller'),
//	                	'params' => ['id' => request()->route('vacancy_id')],

	                	'template' => "
                            <div class='order_quest'>
                        <div class='exchange_left'>
                            <ul class='question-tags-ul'>
                                <li>@{{ data.service_name }}</li>
                                <li><img src='/lawyers/images/icons/attach-icon-blue.svg' alt='attach-icon'>Вопрос закреплен</li>
                            </ul>

                            <h3 class='exchange_title'>
                                <span>@{{ data.title }}</span>
                                <span class='exchange_status'>Только для PRO</span>
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
                                        <img src='/lawyers/images/icons/lil-eye-gray.svg' alt='eye-icon'>234
                                    </li>
                                    <li>
                                        <img src='/lawyers/images/icons/lil-clock-gray.svg' alt='clock-icon'>@{{ data.time_ago }}
                                    </li>
                                    <li>
                                        <img src='/lawyers/images/icons/lil-fire-gray.svg' alt='fire-icon'>@{{ data.days_to_end == null ? 'Срок не установлен' : data.days_to_end + ' дней осталось' }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
	                	"
                        ]
                    ])

                    <form class="send-message-input mobile-hidden">
                        <label>
                            <span class="attach-icon"></span>
                            <input type="text" placeholder="(Опционально)Оставьте сообщение, которое получит клиент с вашим откликом..." name="message-text" id="response_text">
                            @include('component_build', [
	                'component' => 'component.infoComponent.textInfo',
	                'params_component' => [
	                	'autostart' => 'false',
	                	'name' => 'offer_price',
                        'globalData' => 'VacancyInfo',
						'callBeforloadComponent' => "function(component) {
                                let globalData = page__.getGolobalData('VacancyInfo')
                                let price = globalData.payment == 0 ? null : globalData.payment
                                component.option['price'] = price

                                return component.option
                            }",
	                	'template' => "
                            <input v-if=\"price\" type='text' placeholder='Предложите свою цену' name='message-text' id='response_payment'>
	                	"
                        ]
                    ])
                            <button id="respond">Откликнуться</button>
{{--                            <input type="image" src="/lawyers/images/icons/send-icon.svg" alt="send-message-icon">--}}
                        </label>
                    </form>
                </div>

                <div class="order-right">
                    @include('component_build', [
	                'component' => 'component.infoComponent.textInfo',
	                'params_component' => [
	                	'autostart' => 'false',
	                	'name' => 'vacancy_owner_info',
						'globalData' => 'VacancyInfo',
                        'callBeforloadComponent' => "function() {
                                let globalData = page__.getGolobalData('VacancyInfo')
                                let statusData = globalData.status_history
                                if(statusData !== null) {
                                    statusData = statusData.sort((a, b) => a.id > b.id ? 1 : -1)
                                    this.option['currentStatus'] = statusData[statusData.length - 1].status
                                    this.option['currentStatusCode'] = statusData[statusData.length - 1].status_code
                                }
                                this.option['currentStatus'] = null
                                this.option['currentStatusCode'] = null
                                this.option['statusData'] = statusData

                                return this.option
                            }",
//	                	'url' => route__('actionGetVacancyForEmployeeRespond_mainstay_vacancy_vacancymainstaycontroller'),
//	                	'params' => ['id' => request()->route('vacancy_id')],

	                	'template' => "
                        <div class='order-process'>
                        <div class='order-process-lawyer'>
                            <img src='/lawyers/images/main/lawyer-avatar.png' alt='lawyer-avatar-img'>
                            <div class='right'>
                                <h4>@{{ data.owner_name }}</h4>
                                <time>@{{ data.owner_online ?? 'Был(а) давно' }}</time>
                            </div>
                        </div>
                        <p class='order-process_row'>
                            <span>
                                Статус заказа
                            </span>
                            <span class='order-process_status _success'>
                                @{{ currentStatus }}
                            </span>
                        </p>
                        <p class='order-process_row'>
                            <span>
                                Цена заказа
                            </span>
                            <span>
                                @{{ data.payment }} &#8381;
                            </span>
                        </p>

                        <ul class='order-process_ul'>
                            <li :class=\"currentStatusCode >= 1 ? '_check' : '_inProgress'\"><span></span>Создан новый заказ</li>
                            <li :class=\"currentStatusCode >= 3 ? '_check' : '_inProgress'\"><span></span>Заказ оплачен</li>
                            <li :class=\"currentStatusCode >= 4 ? '_check' : '_inProgress'\"><span></span>Взят в работу</li>
                            <li :class=\"currentStatusCode >= 5 ? '_check' : '_inProgress'\">Сдан на проверку</li>
                            <li :class=\"currentStatusCode >= 6 ? '_check' : '_inProgress'\">Заказ принят</li>
                        </ul>
                    </div>
	                	"
                        ]
                    ])
                </div>

                <nav class="exchange_nav mobile tab_nav">
                    <ul>
                        <li class="_selected" data-toggle="order-answers_mobile">Ответы</li>
                        <li data-toggle="top-lawyers_mobile">ТОП-юристов</li>
                    </ul>
                </nav>

                <div class="order-answers_mobile">
                    <div class="order-answers mobile">
                        <span>11 ответов</span>
                        <div class="comments">
                            <div class="order-row comment">
                                <img src="/lawyers/images/main/lawyer-avatar.png" alt="avatar-img">
                                <div class="order-history_right commentator">
                                    <h4> Соколовский Владимир
                                        <img src="/lawyers/images/icons/chat-verify.svg" alt="verify-icon">
                                        <time>18:12</time>
                                    </h4>
                                    <p>
                                        <span>Собираюсь купить автомобиль в беларуссии. Автомобиль растаможен в
                                        белоруссии в апреле 2023 года. Сам автомобиль 2019 года. Имеет 420л. с. Объем
                                        двигателя 2998 кубических см. Интересует какие пошлины...</span>
                                        <span>Могли бы помочь?</span>
                                    </p>
                                </div>
                            </div>

                            <div class="order-row comment">
                                <img src="/lawyers/images/main/lawyer-avatar.png" alt="avatar-img">
                                <div class="order-history_right commentator">
                                    <h4> Victor
                                        <img src="/lawyers/images/icons/chat-verify.svg" alt="verify-icon">
                                        <time>18:12</time>
                                    </h4>
                                    <p>
                                        Добрый день, да. Что конкретно планируете??
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="order-history mobile">
                        <div class="order-history-block">
                            <time>5 февраля</time>
                            <div class="order-history_row order-row">
                                <img src="/lawyers/images/icons/order-created-icon.svg" alt="order-created-icon">

                                <div class="order-history_right">
                                    <h4>Создан новый заказ
                                        <time>18:12</time>
                                    </h4>
                                    <p>Вы создали заказ. Ознакомьтесь с
                                        <a href="#" class="a_link">программой защиты покупателей </a>
                                    </p>
                                </div>
                            </div>
                            <div class="order-history_row order-row">
                                <img src="/lawyers/images/icons/order-in-progress-icon.svg" alt="order-in-progress-icon">

                                <div class="order-history_right">
                                    <h4>В работе
                                        <time>18:12</time>
                                    </h4>
                                    <p>
                                        Заказ в работе
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="order-history-block">
                            <time>7 февраля</time>
                            <div class="order-history_row order-row">
                                <img src="/lawyers/images/icons/order-in-check-icon.svg" alt="order-in-check-icon">

                                <div class="order-history_right">
                                    <h4>Заказ сдан на проверку
                                        <time>18:12</time>
                                    </h4>
                                    <p>
                                        Заказ передан на проверку.
                                    </p>
                                </div>
                            </div>
                            <div class="order-history_row order-row">
                                <img src="/lawyers/images/icons/order-done-icon.svg" alt="order-done-icon">

                                <div class="order-history_right">
                                    <h4>Заказ выполнен
                                        <time>18:12</time>
                                    </h4>
                                    <p>
                                        <span>
                                            Вы проверили и приняли работу.
                                        </span>
                                        <span>
                                            Оставьте, пожалуйста, свой отзыв о работе. От отзывов зависит рейтинг исполнителя на сервисе.
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="#" class="send-message-input mobile">
                        <label>
                            <span class="attach-icon"></span>
                            <input type="text" placeholder="Введите сообщение..." name="message-text">
                            <input type="image" src="/lawyers/images/icons/send-icon.svg" alt="send-message-icon">
                        </label>
                    </form>
                </div>

                <div class="top-lawyers_mobile mobile hidden">
                    <div class="top-lawyers">
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
                                            <div class="stars"><span style="width: 80%;"></span></div>
                                            <p>32 ответа</p>
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
                                            <div class="stars"><span style="width: 80%;"></span></div>
                                            <p>32 ответа</p>
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
                                            <div class="stars"><span style="width: 80%;"></span></div>
                                            <p>32 ответа</p>
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
            </div>
        </div>
    </section>

<div id="mobile-menu-popup" class="popup mobile popup_hide">
    <div class="popup_shadow"></div>

    <div class="step step1 mobile-step_top step_hide" data-step="1">
        <div class="mob_header">
            <button class="popup-close"><img src="/lawyers/images/icons/arrow-back-icon.svg" alt="arrow-icon"></button>
            <a href="index.html" class="logo image-container">Лого</a>
        </div>

        <h2 class="mob-header_title">Личный кабинет</h2>

        <ul class="nav-ul nav-ul_mobile">
            <li>
                <div>
                    <span class="cool-underline select-btn">Найти специалиста</span>
                    <img src="/lawyers/images/icons/arrow-icon-white.svg" alt="arrow-icon" class="arrow-icon sub-icon">
                </div>
                <ul class="select-window">
                    <li>
                        <a href="#">
                            <p>
                                Найти Юриста
                                <span>
                                    Awo has 97% of all lawyersin the. Awo has 97% of all lawyersin the.
                                </span>
                            </p>
                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11" fill="none">
                                <path d="M1 1L5 5.5L1 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <p>Найти Адвоката
                                <span>Awo has 97% of all lawyersin the. Awo has 97% of all lawyersin the.</span>
                            </p>
                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11" fill="none">
                                <path d="M1 1L5 5.5L1 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <p>Найти Нотариуса
                                <span>Awo has 97% of all lawyersin the. Awo has 97% of all lawyersin the.</span>
                            </p>
                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11" fill="none">
                                <path d="M1 1L5 5.5L1 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <p>Найти Детектива
                                <span>Awo has 97% of all lawyersin the. Awo has 97% of all lawyersin the.</span>
                            </p>
                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11" fill="none">
                                <path d="M1 1L5 5.5L1 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <div>
                    <span class="cool-underline select-btn">наши юристы</span>
                    <img src="/lawyers/images/icons/arrow-icon-white.svg" alt="arrow-icon" class="arrow-icon sub-icon">
                </div>
                <ul class="select-window">
                    <li>
                        <a href="#">
                            <p>
                                Найти Юриста
                                <span>
                                    Awo has 97% of all lawyersin the. Awo has 97% of all lawyersin the.
                                </span>
                            </p>
                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11" fill="none">
                                <path d="M1 1L5 5.5L1 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <p>Найти Адвоката
                                <span>Awo has 97% of all lawyersin the. Awo has 97% of all lawyersin the.</span>
                            </p>
                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11" fill="none">
                                <path d="M1 1L5 5.5L1 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <p>Найти Нотариуса
                                <span>Awo has 97% of all lawyersin the. Awo has 97% of all lawyersin the.</span>
                            </p>
                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11" fill="none">
                                <path d="M1 1L5 5.5L1 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <p>Найти Детектива
                                <span>Awo has 97% of all lawyersin the. Awo has 97% of all lawyersin the.</span>
                            </p>
                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11" fill="none">
                                <path d="M1 1L5 5.5L1 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <div>
                    <span class="cool-underline select-btn">наши услуги</span>
                    <img src="/lawyers/images/icons/arrow-icon-white.svg" alt="arrow-icon" class="arrow-icon sub-icon">
                </div>
                <ul class="select-window">
                    <li>
                        <a href="#">
                            <p>
                                Найти Юриста
                                <span>
                                    Awo has 97% of all lawyersin the. Awo has 97% of all lawyersin the.
                                </span>
                            </p>
                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11" fill="none">
                                <path d="M1 1L5 5.5L1 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <p>Найти Адвоката
                                <span>Awo has 97% of all lawyersin the. Awo has 97% of all lawyersin the.</span>
                            </p>
                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11" fill="none">
                                <path d="M1 1L5 5.5L1 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <p>Найти Нотариуса
                                <span>Awo has 97% of all lawyersin the. Awo has 97% of all lawyersin the.</span>
                            </p>
                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11" fill="none">
                                <path d="M1 1L5 5.5L1 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <p>Найти Детектива
                                <span>Awo has 97% of all lawyersin the. Awo has 97% of all lawyersin the.</span>
                            </p>
                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11" fill="none">
                                <path d="M1 1L5 5.5L1 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <div>
                    <span class="cool-underline select-btn">юридическая помощь</span>
                    <img src="/lawyers/images/icons/arrow-icon-white.svg" alt="arrow-icon" class="arrow-icon sub-icon">
                </div>
                <ul class="select-window">
                    <li>
                        <a href="#">
                            <p>
                                Найти Юриста
                                <span>
                                    Awo has 97% of all lawyersin the. Awo has 97% of all lawyersin the.
                                </span>
                            </p>
                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11" fill="none">
                                <path d="M1 1L5 5.5L1 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <p>Найти Адвоката
                                <span>Awo has 97% of all lawyersin the. Awo has 97% of all lawyersin the.</span>
                            </p>
                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11" fill="none">
                                <path d="M1 1L5 5.5L1 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <p>Найти Нотариуса
                                <span>Awo has 97% of all lawyersin the. Awo has 97% of all lawyersin the.</span>
                            </p>
                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11" fill="none">
                                <path d="M1 1L5 5.5L1 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <p>Найти Детектива
                                <span>Awo has 97% of all lawyersin the. Awo has 97% of all lawyersin the.</span>
                            </p>
                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11" fill="none">
                                <path d="M1 1L5 5.5L1 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <div>
                    <span class="cool-underline select-btn">юридический журнал</span>
                    <img src="/lawyers/images/icons/arrow-icon-white.svg" alt="arrow-icon" class="arrow-icon sub-icon">
                </div>
                <ul class="select-window">
                    <li>
                        <a href="#">
                            <p>
                                Найти Юриста
                                <span>
                                    Awo has 97% of all lawyersin the. Awo has 97% of all lawyersin the.
                                </span>
                            </p>
                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11" fill="none">
                                <path d="M1 1L5 5.5L1 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <p>Найти Адвоката
                                <span>Awo has 97% of all lawyersin the. Awo has 97% of all lawyersin the.</span>
                            </p>
                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11" fill="none">
                                <path d="M1 1L5 5.5L1 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <p>Найти Нотариуса
                                <span>Awo has 97% of all lawyersin the. Awo has 97% of all lawyersin the.</span>
                            </p>
                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11" fill="none">
                                <path d="M1 1L5 5.5L1 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <p>Найти Детектива
                                <span>Awo has 97% of all lawyersin the. Awo has 97% of all lawyersin the.</span>
                            </p>
                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11" fill="none">
                                <path d="M1 1L5 5.5L1 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>

        <div class="support-phone">
            <span>Поддержка</span>
            <p>+7 (999) 999 99 99</p>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#respond').click(function (e) {
            e.preventDefault()
            sendRespondData(getResponseData())
        })
    })

    function getResponseData() {
        return {
            user_id: '{{ auth()->id() }}',
            vacancy_id: '{{ request()->route('vacancy_id') }}',
            text: $('#response_text').val(),
            payment: setPayment()
        }
    }

    function setPayment() {
        if($('#response_payment').val() !== '') {
            return $('#response_payment').val()
        }
        return page__.getGolobalData('VacancyInfo').payment
    }

    function sendRespondData(sendData) {
        $.ajax({
            method: 'POST',
            data: sendData,
            url: '{{ route__('actionRespondToVacancy_mainstay_employee_employeemainstaycontroller') }}',
            success: function (response) {
                if (!response) {
                    alert('Ошибка(возможно вы уже откликались на эту вакансию)')
                } else {
                    window.location.href = '{{ route__('actionVacancyExchange_controllers_employee_employeecontroller') }}'
                }
            }
        })
    }
</script>

@endsection

