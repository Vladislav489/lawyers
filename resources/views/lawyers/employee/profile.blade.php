@extends('lawyers.layouts.main')
@section('title', 'Профиль сотрудника')

@section('content')
    <section class="gradient-bg u-container lawyer-section">
        <div class="container">
            <ul class="breadcrumbs">
                <li class="cool-underline"><a href="#">Юрист</a></li>
                <li class="cool-underline"><a href="#">Город</a></li>
            </ul>

            <div class="lawyer-container">
                <div class="left">
                    <div class="lawyer-block">
                        <div class="lawyer-top">
                            <div class="lawyer-img">
                                <img src="/lawyers/images/main/lawyer-img.png" alt="lawyer-img">
                            </div>

                            <div class="lawyer-info">
                                <h2 class="lawyer-name">Соколовский Владимир Александрович</h2>
                                <span class="lawyer-check">
                                    Проверенный юрист
                                    <img class="icon" src="/lawyers/images/icons/check-icon-white.svg" alt="check-icon">
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
                                    <img class="icon" src="/lawyers/images/icons/info-icon-blue.svg" alt="info-icon">
                                </div>
                            </div>

                            <div class="lawyer-info">
                                <div class="lawyer-info_row">
                                    <img class="icon" src="/lawyers/images/icons/loc-icon-blue.svg" alt="loc-icon">
                                    <span>Москва и МО</span>
                                </div>
                                <div class="lawyer-info_row">
                                    <img class="icon" src="/lawyers/images/icons/bah-icon-blue.svg" alt="bah-icon">
                                    <span>15 лет практики</span>
                                </div>
                                <div class="lawyer-info_row">
                                    <img class="icon" src="/lawyers/images/icons/phone-icon-blue.svg" alt="phone-icon">
                                    <span>+7 (999) 999 99 99</span>
                                </div>
                                <div class="lawyer-info_row">
                                    <img class="icon" src="/lawyers/images/icons/planet-icon-blue.svg" alt="planet-icon">
                                    <span>www.site.ru</span>
                                </div>
                                <div class="lawyer-info_row">
                                    <img class="icon" src="/lawyers/images/icons/message-icon-blue.svg" alt="message-icon">
                                    <span>Консультации онлайн:</span>
                                    <span class="bold">Да</span>
                                </div>
                                <div class="lawyer-info_row">
                                    <img class="icon" src="/lawyers/images/icons/clock-icon-blue.svg" alt="clock-icon">
                                    <span>Время работы:</span>
                                    <span class="bold">Пн-Сб 9:00 - 18:00</span>
                                </div>
                            </div>

                            <div class="buttons-container">
                                <button class="main-btn main-btn_white">
                                    <span class="first">Сообщение</span>
                                    <span class="second">Сообщение</span>
                                </button>
                                <button class="main-btn main-btn_blue">
                                    <span class="first">
                                        Предложить работу
                                        <img class="icon" src="/lawyers/images/icons/arrow-icon-white.svg" alt="arrow-icon">
                                    </span>
                                    <span class="second">
                                        Предложить работу
                                        <img class="icon" src="/lawyers/images/icons/arrow-icon-white.svg" alt="arrow-icon">
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="exchange-block">
                        <h2 class="exchange-title _line-blue">Биржа юридических задач</h2>

                        <div class="exchange-info">
                            <div class="exchange-img">
                                <img src="/lawyers/images/main/hammer-img.jpg" alt="hammer-img">
                            </div>
                            <ol class="block">
                                <li class="exchange-info_line">
                                    <p class="exchange-info_number"><span>01</span></p>
                                    <p class="exchange-info_text">Опишите ситуацию</p>
                                </li>
                                <li class="exchange-info_line">
                                    <p class="exchange-info_number"><span>02</span></p>
                                    <p class="exchange-info_text">Получите предложения по срокам и стоимости от юристов</p>
                                </li>
                                <li class="exchange-info_line">
                                    <p class="exchange-info_number"><span>03</span></p>
                                    <p class="exchange-info_text">Выбирайте лучших по отзывам и цене</p>
                                </li>
                            </ol>
                        </div>

                        <div class="exchange-dogovor">
                            <h2 class="exchange-dogovor_title">Нужно составить договор дарения</h2>
                            <p class="exchange-dogovor_text"><span>&#8381;</span> 7 предложений от 1 500&#8381;</p>
                        </div>

                        <button class="main-btn">
                            <span class="first">
                                Создать задачу
                                <img class="icon" src="/lawyers/images/icons/arrow-icon-white.svg" alt="arrow-icon">
                            </span>
                            <span class="second">
                                Создать задачу
                                <img class="icon" src="/lawyers/images/icons/arrow-icon-white.svg" alt="arrow-icon">
                            </span>
                        </button>
                    </div>
                </div>

                <div class="right">
                    <ul class="round-top_nav">
                        <li class="active"><button type="button">О себе</button></li>
                        <li><button type="button">Услуги</button></li>
                        <li><button type="button">Отзывы</button></li>
                        <li><button type="button">Ответы юриста</button></li>
                    </ul>

                    <div class="lawyer-card">
                        <div class="lawyer-info">
                            <h2 class="lawyer-name">Соколовский Владимир Александрович</h2>
                            <span class="lawyer-check">
                                Проверенный юрист
                                <img src="/lawyers/images/icons/check-icon-white.svg" alt="check-icon">
                            </span>
                        </div>

                        <p class="lawyer-text_p lawyer-text_blue bold">Главный тезис от юриста с лимитом символа</p>
                        <p class="lawyer-text_p">
                            Имею многолетний опыт работы по всем направлениям правового поля РФ и успешную судебную практику.
                            Профессионально и добросовестно оказываю юридическую помощь и по доступным ценам.
                            <br>
                            Являюсь высококалифицированным, практикующим юристом, имею многолетний опыт работы в юриспруденции.
                            Специализируюсь на гражданских и административных делах.
                        </p>

                        <div class="lawyer-card_block">
                            <h2 class="lawyer-card_block-title">Фото и видео <span>5</span></h2>
                            <ul class="lawyer-photos">
                                <li><img src="/lawyers/images/main/lawyer-img.png" alt="lawyer-img"></li>
                                <li><img src="/lawyers/images/main/lawyer-img.png" alt="lawyer-img"></li>
                                <li><img src="/lawyers/images/main/lawyer-img.png" alt="lawyer-img"></li>
                                <li><img src="/lawyers/images/main/lawyer-img.png" alt="lawyer-img"></li>
                                <li><img src="/lawyers/images/main/lawyer-img.png" alt="lawyer-img"></li>
                                <li><img src="/lawyers/images/main/lawyer-img.png" alt="lawyer-img"></li>
                            </ul>
                        </div>

                        <div class="lawyer-card_block">
                            <h2 class="lawyer-card_block-title">Документы и сертификаты <span>2</span></h2>
                            <ul class="lawyer-certs_container">
                                <li class="lawyer-cert">
                                    <img src="/lawyers/images/main/lawyer-cert-img.png" alt="cert-img">
                                    <p>Сертификат о повышении квалифи...</p>
                                </li>
                                <li class="lawyer-cert">
                                    <img src="/lawyers/images/main/lawyer-cert-img.png" alt="cert-img">
                                    <p>Диплом о ВО юриста</p>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="lawyer-services lawyer-wrapper">
                        <h2 class="lawyer-wrapper_title">Заказать услугу по фикс прайсу с гарантией от нашего портала</h2>
                        <h3 class="lawyer-wrapper_subtitle _line-blue">Краткое описание логики заказа и зачем.</h3>

                            @include('component_build', [
                                'component' => 'component.gridComponent.simpleGrid',
                                'params_component' => [
                                    'autostart' => 'true',
                                    'name' => 'service_list',
                                    'url' => route__("actionGetServiceList_mainstay_service_servicemainstaycontroller"),

                                    'template' => '<ul class="lawyer-services_block">
                                        <li v-for="item in data" class="lawyer-service_line" style="justify-content: space-between;">
                                            <div class="lawyer-service_left">
                                                <div class="lawyer-service_title">@{{ item.name }}</div>
                                                <p class="lawyer-service_text">
                                                    @{{ item.description }}
                                                    <button class="lawyer-service_red-more">
                                                        ЧИТАТЬ ЕЩЕ
                                                    </button>
                                                </p>
                                            </div>

                                            <div class="lawyer-service_price">
                                                <span>500&#8381;</span>
                                                <button class="main-btn main-btn_white">Заказать услугу</button>
                                            </div>
                                        </li>
                                    </ul>',

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

                        <button class="more-services">Еще 2 услуги</button>
                    </div>

                    <div class="lawyer-all-services lawyer-wrapper">
                        <h2 class="lawyer-wrapper_title _line-blue">Оказываемые услуги</h2>
                        <ul>
                            <li>Юридическое обслуживание</li>
                            <li>Ведение дел в судебных делах</li>
                            <li>Ведение уголовных дел</li>
                            <li>Ведение уголовных дел</li>
                            <li>Сопровождение разводов</li>
                            <li>Ведение административных дел</li>
                            <li>Ведение дел по наследству</li>
                            <li>Ведение гражданских дел</li>
                            <li>Ведение дел в арбитражных судах</li>
                        </ul>

                        <button class="more-services">Еще 2 услуги</button>
                    </div>

                    <div class="lawyer-practice lawyer-wrapper">
                        <h2 class="lawyer-wrapper_title _line-blue">Судебная практика</h2>
                        <ul class="lawyer-practice_ul">
                            <li>
                                <h3 class="lawyer-practic_title">Прекращение уголовного дела по ч. 3 ст. 159 УК РФ</h3>
                                <span class="lawyer-practic_number">Дело №01-038/2019</span>
                                <img class="icon" src="/lawyers/images/icons/arrow-icon-gray.svg" alt="arrow-icon">
                            </li>
                        </ul>

                        <button class="more-services">Еще 2 услуги</button>
                    </div>

                    <div class="lawyer-comments lawyer-wrapper">
                        <h2 class="lawyer-wrapper_title _line-blue">Отзывы</h2>

                        <div class="lawyer-comment_block">
                            <div class="block specialist-comment">
                                <div class="comment-rate">
                                    <div class="stars">
                                        <img src="/lawyers/images/icons/star-icon-full.svg" alt="star-icon">
                                        <img src="/lawyers/images/icons/star-icon-full.svg" alt="star-icon">
                                        <img src="/lawyers/images/icons/star-icon-full.svg" alt="star-icon">
                                        <img src="/lawyers/images/icons/star-icon-full.svg" alt="star-icon">
                                        <img src="/lawyers/images/icons/star-icon-full.svg" alt="star-icon">
                                    </div>

                                    <p class="name">Алексеева Юлия</p>
                                    <span class="date">16.05.2023</span>
                                    <img
                                        class="check-icon icon"
                                        src="/lawyers/images/icons/check-icon-green-transparent.svg"
                                        alt="check-icon"
                                    >
                                </div>

                                <blockquote>
                                    <p class="comment-text">
                                        Спасибо большое владимиру Александровичу. Он по уголовному делу вел защиту в суде
                                        моего брата и вместо обещанных ему следователем 5-6 лет колонии, брат получил один
                                        год условно. Считаю это большой победой адвоката, который смог.
                                        <br><br>
                                        Спасибо большое владимиру Александровичу. Он по уголовному делу вел защиту в суде
                                        моего брат...

                                        <span class="comment-read-more">читать еще</span>
                                    </p>
                                </blockquote>
                            </div>

                            <button class="more-services">Смотреть все отзывы</button>
                        </div>

                        <div class="lawyer-comment_rate-block">
                            <div class="lawyer-comment_rate">
                                <div class="stars">
                                    <img class="star" src="/lawyers/images/icons/star-icon-full.svg" alt="star-icon">
                                    <img class="star" src="/lawyers/images/icons/star-icon-full.svg" alt="star-icon">
                                    <img class="star" src="/lawyers/images/icons/star-icon-full.svg" alt="star-icon">
                                    <img class="star" src="/lawyers/images/icons/star-icon-full.svg" alt="star-icon">
                                    <img class="star" src="/lawyers/images/icons/star-icon-empty.svg" alt="star-icon">
                                    <span>32 ответа</span>
                                </div>
                                <div class="lawyer-comment_points">4.6 / 5</div>
                            </div>

                            <p class="line"></p>

                            <div class="lawyer-comment_stars-lines">
                                <div class="lawyer-comment_stars-line">
                                    <span class="span_gray">5 звезд</span>
                                    <div class="lawyer-comment_line">
                                        <span class="lawyer-comment_span-orange span1"></span>
                                    </div>
                                    <span class="span_main">99%</span>
                                </div>
                                <div class="lawyer-comment_stars-line">
                                    <span class="span_gray">4 звезды</span>
                                    <div class="lawyer-comment_line">
                                        <span class="lawyer-comment_span-orange span2"></span>
                                    </div>
                                    <span class="span_main">1%</span>
                                </div>
                                <div class="lawyer-comment_stars-line">
                                    <span class="span_gray">3 звезды</span>
                                    <div class="lawyer-comment_line">
                                        <span class="lawyer-comment_span-orange"></span>
                                    </div>
                                    <span class="span_main">0%</span>
                                </div>
                                <div class="lawyer-comment_stars-line">
                                    <span class="span_gray">2 звезды</span>
                                    <div class="lawyer-comment_line">
                                        <span class="lawyer-comment_span-orange"></span>
                                    </div>
                                    <span class="span_main">0%</span>
                                </div>
                                <div class="lawyer-comment_stars-line">
                                    <span class="span_gray">1 звезда</span>
                                    <div class="lawyer-comment_line">
                                        <span class="lawyer-comment_span-orange"></span>
                                    </div>
                                    <span class="span_main">0%</span>
                                </div>
                            </div>

                            <p class="lawyer-comment_lil-text">
                                Отзывы могут оставлять только те, кто воспользовался услугами юриста.
                                Так мы формируем честный рейтинг
                            </p>
                        </div>
                    </div>

                    <div class="lawyer-contacts lawyer-wrapper">
                        <h2 class="lawyer-wrapper_title _line-blue">Контакты и Адрес</h2>
                        <div class="lawyer-contacts_map">
                            <img src="/lawyers/images/main/map.png" alt="map-img">
                            <div class="lawyer-contacts_loc">
                                <span>ул. Морская, 178/20</span>
                                <img src="/lawyers/images/icons/loc-icon-blue.svg" alt="loc-icon">
                            </div>
                        </div>

                        <div class="lawyer-info">
                            <div class="lawyer-info_row">
                                <img class="icon" src="/lawyers/images/icons/loc-icon-blue.svg" alt="loc-icon">
                                <span>Москва и МО</span>
                            </div>
                            <div class="lawyer-info_row">
                                <img class="icon" src="/lawyers/images/icons/bah-icon-blue.svg" alt="bah-icon">
                                <span>15 лет практики</span>
                            </div>
                            <div class="lawyer-info_row">
                                <img class="icon" src="/lawyers/images/icons/phone-icon-blue.svg" alt="phone-icon">
                                <a href="tel:+71234567890" class="span-link">+7 (123) 456 78 90</a>
                            </div>
                            <div class="lawyer-info_row">
                                <img class="icon" src="/lawyers/images/icons/planet-icon-blue.svg" alt="planet-icon">
                                <a href="#" class="span-link">www.site.ru</a>
                            </div>
                            <div class="lawyer-info_row">
                                <img class="icon" src="/lawyers/images/icons/message-icon-blue.svg" alt="message-icon">
                                <span>Консультации онлайн:</span>
                                <span class="lawyer-info_span-black">Да</span>
                            </div>
                            <div class="lawyer-info_row">
                                <img class="icon" src="/lawyers/images/icons/clock-icon-blue.svg" alt="clock-icon">
                                <span>Время работы:</span>
                                <span class="lawyer-info_span-black">Пн-Сб 9:00 - 18:00</span>
                            </div>
                        </div>

                        <div class="buttons-container">
                            <button class="main-btn main-btn_white">Сообщение</button>
                            <button class="main-btn main-btn_white">Заказать звонок</button>
                        </div>
                    </div>

                    <div class="lawyer-answers lawyer-wrapper">
                        <h2 class="lawyer-wrapper_title _line-blue">Посмотрите на ответы юриста на общие вопросы с сайта</h2>
                        <div class="lawyer-answers_table">
                            <div class="table-col col1">
                                <h4 class="col-header">Вопрос</h4>
                                <p>Апелляционная жалоба, как правильно составить?</p>
                                <p>Апелляционная жалоба, как правильно составить?</p>
                                <p>Апелляционная жалоба, как правильно составить?</p>
                            </div>
                            <div class="table-col col2">
                                <h4 class="col-header">Город и дата</h4>
                                <p>Сосногорск, 22.04.2022</p>
                                <p>Сосногорск, 22.04.2022</p>
                                <p>Сосногорск, 22.04.2022</p>
                            </div>
                            <div class="table-col col3">
                                <h4 class="col-header">Ответов</h4>
                                <p>3</p>
                                <p>3</p>
                                <p>3</p>
                            </div>
                        </div>

                        <button class="more-services">Смотреть все ответы</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
