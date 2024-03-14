@extends('lawyers.layouts.main')
@section('title', 'Кабинет клиента')

@section('content')
    <section class="gradient-bg u-container lawyer-section">
        <div class="container">
            <div class="lawyer-container">
                <div class="left">
                    <div class="lawyer-block">
                        <div class="lawyer-top">
                            <div class="lawyer-img">
                                <img src="/lawyers/images/main/lawyer-img.png" alt="lawyer-img">
                            </div>

                            <div class="lawyer-info">
                                <h2 class="lawyer-name">Соколовский Владимир Александрович</h2>

                                <span class="lawyer-check">Проверенный юрист
                                    <img src="/lawyers/images/icons/check-icon-white.svg" alt="check-icon" class="icon">
                                </span>
                            </div>
                        </div>

                        <div class="lawyer-bottom">
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

                            <div class="lawyer-info">
                                <div class="lawyer-info_row">
                                    <img src="/lawyers/images/icons/loc-icon-blue.svg" alt="loc-icon" class="icon">

                                    <span>Москва и МО</span>
                                </div>
                                <div class="lawyer-info_row">
                                    <img src="/lawyers/images/icons/bah-icon-blue.svg" alt="bah-icon" class="icon">

                                    <span>15 лет практики</span>
                                </div>
                                <div class="lawyer-info_row">
                                    <img src="/lawyers/images/icons/phone-icon-blue.svg" alt="phone-icon" class="icon">

                                    <a href="tel:+7 (999) 999 99 99" class="link">+7 (999) 999 99 99</a>
                                </div>
                                <div class="lawyer-info_row">
                                    <img src="/lawyers/images/icons/planet-icon-blue.svg" alt="planet-icon" class="icon">

                                    <a href="#" class="link">www.site.ru</a>
                                </div>
                                <div class="lawyer-info_row">
                                    <img src="/lawyers/images/icons/message-icon-blue.svg" alt="message-icon" class="icon">

                                    <span>Консультации онлайн:</span>
                                    <span class="bold">Да</span>
                                </div>
                                <div class="lawyer-info_row">
                                    <img src="/lawyers/images/icons/clock-icon-blue.svg" alt="clock-icon" class="icon">

                                    <span>Время работы:</span>
                                    <time class="bold">Пн-Сб 9:00 - 18:00</time>
                                </div>
                            </div>

                            <button type="button" class="main-btn main-btn_white">
                                <span class="first">Редактировать</span>
                                <span class="second">Редактировать</span>
                            </button>

                            <div class="balance-block">
                                <div class="balance-block_left">
                                    <p class="balance-text">Ваш баланс</p>
                                    <p class="balance_balance">
                                        <span>&#8381</span>
                                        0 руб
                                    </p>
                                </div>

                                <a href="#" class="main-btn">
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
                    </div>
                </div>

                <div class="right">
                    <ul class="round-top_nav">
                        <li class="active"><button type="button">Мои заказы</button></li>
                        <li><button type="button">Услуги</button></li>
                        <li><button type="button">Отзывы</button></li>
                        <li><button type="button">Ответы юриста</button></li>
                    </ul>

                    <div class="my-orders lawyer-wrapper">
                        <h2 class="lawyer-wrapper_title">Мои заказы <span>1/2</span></h2>

                        <ul class="my-orders_ul">
                            <li>
                                <div class="my-orders_info">
                                    <p class="my-orders_text">
                                        Оглавление вопроса, который задал клиент... Оглавление вопроса, который задал
                                        клиент... Оглавление вопроса, который задал клиент... Оглавление вопроса,
                                        который задал клиент...
                                    </p>
                                    <p class="my-orders_price">
                                        Бесплатный
                                        <span>0 ответов</span>
                                    </p>
                                </div>

                                <p class="my-orders_stage moderation-status">
                                    модерация
                                </p>
                            </li>
                            <li>
                                <div class="my-orders_info">
                                    <p class="my-orders_text">
                                        Оглавление вопроса, который задал клиент... Оглавление вопроса, который задал
                                        клиент... Оглавление вопроса, который задал клиент... Оглавление вопроса,
                                        который задал клиент...
                                    </p>
                                    <p class="my-orders_price">
                                        Бесплатный
                                        <span>0 ответов</span>
                                    </p>
                                </div>

                                <p class="my-orders_stage closed-status">
                                    закрыт
                                </p>
                            </li>
                        </ul>

                        <button class="more-services">Еще 2 услуги</button>
                    </div>

                    <div class="lawsuit lawyer-wrapper">
                        <h2 class="lawyer-wrapper_title lawyer-wrapper_title-left">Коллективные иски </h2>

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
@endsection
