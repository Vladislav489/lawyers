@extends('lawyers.layouts.main')
@section('title', 'Найти специалиста')

@section('content')
    <section class="find-section u-container">
        <div class="container">
            <ul class="breadcrumbs">
                <li class="cool-underline"><a href="#">Юрист</a></li>
                <li class="cool-underline"><a href="#">Город</a></li>
            </ul>

            <form action="#" class="find-block find-form">
                <label class="search-label">
                    <input type="search" name="search-spec" placeholder="Имя и фамилия специалиста...">
                    <input class="search-icon" type="image" src="/lawyers/images/icons/search-icon-blue-full.svg" alt="search-icon">
                </label>

                <div class="unit-select">
                    <div class="unit-select_row">
                        <span class="unit-select_subtext">Страна</span>
                        <div class="unit-select_select select-btn">
                            <p class="unit-select_text">Россия</p>
                            <img class="sub-icon" src="/lawyers/images/icons/arrow-icon-gray.svg" alt="arrow-icon">
                        </div>
                        <ul class="select-window">
                            <li>Россия</li>
                            <li>Германия</li>
                        </ul>
                    </div>

                    <div class="unit-select_row">
                        <span class="unit-select_subtext">Город</span>
                        <div class="unit-select_select select-btn">
                            <p class="unit-select_text">Санкт-Петербург</p>
                            <img class="sub-icon" src="/lawyers/images/icons/arrow-icon-gray.svg" alt="arrow-icon">
                        </div>
                        <ul class="select-window">
                            <li>Санкт-Петербург</li>
                            <li>Москва</li>
                        </ul>
                    </div>

                    <div class="unit-select_row">
                        <span class="unit-select_subtext">Категория услуг</span>
                        <div class="unit-select_select select-btn">
                            <p class="unit-select_text">Категория услуг1</p>
                            <img class="sub-icon" src="/lawyers/images/icons/arrow-icon-gray.svg" alt="arrow-icon">
                        </div>
                        <ul class="select-window">
                            <li>Категория услуг1</li>
                            <li>Категория услуг2</li>
                        </ul>
                    </div>

                    <div class="unit-select_row">
                        <span class="unit-select_subtext">Тема услуги</span>
                        <div class="unit-select_select select-btn">
                            <p class="unit-select_text">Тема услуги1</p>
                            <img class="sub-icon" src="/lawyers/images/icons/arrow-icon-gray.svg" alt="arrow-icon">
                        </div>
                        <ul class="select-window">
                            <li>Тема услуги1</li>
                            <li>Тема услуги2</li>
                        </ul>
                    </div>
                </div>

                <div class="unit-select">
                    <div class="unit-select_row">
                        <span class="unit-select_subtext">Рейтинг</span>
                        <div class="unit-select_select select-btn">
                            <p class="unit-select_text">Не важен</p>
                            <img class="sub-icon" src="/lawyers/images/icons/arrow-icon-gray.svg" alt="arrow-icon">
                        </div>
                        <ul class="select-window">
                            <li>1 звезда</li>
                            <li>2 звезды</li>
                            <li>5 звезд</li>
                            <li>Не важен</li>
                        </ul>
                    </div>

                    <div class="unit-select_row">
                        <span class="unit-select_subtext">Оценка</span>
                        <div class="unit-select_select select-btn">
                            <p class="unit-select_text">Не важна</p>
                            <img class="sub-icon" src="/lawyers/images/icons/arrow-icon-gray.svg" alt="arrow-icon">
                        </div>
                        <ul class="select-window">
                            <li>Не важна</li>
                            <li>1</li>
                            <li>2</li>
                            <li>3</li>
                        </ul>
                    </div>

                    <div class="unit-select_row">
                        <span class="unit-select_subtext">Опыт работы</span>
                        <div class="unit-select_select select-btn">
                            <p class="unit-select_text">От 10 лет</p>
                            <img class="sub-icon" src="/lawyers/images/icons/arrow-icon-gray.svg" alt="arrow-icon">
                        </div>
                        <ul class="select-window">
                            <li>От 20 лет</li>
                            <li>От 30 лет</li>
                        </ul>
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
                    <button class="main-btn main-btn_orange">
                        Бесплатная консультация
                        <img class="icon" src="/lawyers/images/icons/arrow-icon-white.svg" alt="arrow-icon">
                    </button>
                    <button class="main-btn">
                        Создать задачу на бирже
                        <img class="icon" src="/lawyers/images/icons/arrow-icon-white.svg" alt="arrow-icon">
                    </button>
                </div>
            </div>
        </div>
    </section>

    <section class="fs-section u-container">
        <div class="container">
            <h2 class="find-section_header">Найдено: <span>177 специалистов</span></h2>

                @include('component_build', [
                    'component' => 'component.gridComponent.simpleGrid',
                    'params_component' => [
                        'autostart' => 'true',
                        'name' => 'service_list',
                        'url' => route__("actionGetEmployeeList_mainstay_employee_employeemainstaycontroller"),

                        'template' => '<div class="found-specialists">
                            <div v-for="item in data" class="fs-block">
                                <div class="fs-img">
                                    <img src="/lawyers/images/main/lawyer-img.png" alt="lawyer-img">
                                </div>

                                <div class="fs-info">
                                    <h3 class="fs-name">@{{ item.first_name }}</h3>
                                    <p class="fs-row">
                                        <img class="icon" src="/lawyers/images/icons/loc-icon-gray.svg" alt="loc-icon">
                                        <span class="fs-text">Москва и МО, пр. Роберта Рождественского, 522</span>
                                    </p>
                                    <p class="fs-row">
                                        <img class="icon" src="/lawyers/images/icons/bag-icon-gray.svg" alt="bag-icon">
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
                                            <img class="icon" src="/lawyers/images/icons/info-icon-gray.svg" alt="info-icon">
                                        </div>
                                    </div>

                                    <p class="fs-text">
                                        Строжайшая политика конфиденциальности и заинтересованность в долгосрочномсотрудничестве исключают возможность утечки информации.
                                        Доверие клиентов и деловая репутация стоят на первом месте.
                                    </p>
                                    <ul class="fs-text_bold">
                                        <li>Контракты,</li>
                                        <li>Разводы,</li>
                                        <li>ДТП,</li>
                                        <li>Гражданское право</li>
                                    </ul>
                                </div>

                                <div class="buttons-container">
                                    <button class="main-btn main-btn_blue">Консультация: 3 000 &#8381;</button>
                                    <button class="main-btn main-btn_white">Заказать звонок</button>
                                    <button class="main-btn main-btn_white">Сообщение</button>
                                </div>
                            </div>
                        </div>',

                        'autostart' => 'true',
                        'pagination' => [
                            'page' => 1,
                            'pageSize' => 14,
                            'countPage' => 1,
                            'typePagination' => 0,
                            'showPagination' => 1,
                            'showInPage' => 14,
                            'count_line' => 1,
                            'all_load' => 0,
                            'physical_presence' => 0
                        ],
                    ]
                ])

            <div class="fs-section_pages">
                <a class="fs-page_nav disabled" href="#">Предыдущая страница</a>
                <a class="fs-page_nav active" href="#">Следующая страница</a>

                <ul class="gaps">
                    <li><a href="#">1</a></li>
                    <li><a href="#">2</a></li>
                    <li><a class="active" href="#">3</a></li>
                    <li><a href="#">4</a></li>
                    <li><a href="#">...</a></li>
                    <li><a href="#">28</a></li>
                </ul>
            </div>
        </div>
    </section>
@endsection
