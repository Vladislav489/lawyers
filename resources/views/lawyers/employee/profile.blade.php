@extends('lawyers.layouts.main')
@include('component_build',[
	'component' => 'component.loadComponent.loadGlobalData',
        'params_component' => [
        'name' => "EmployeeInfo",
        'autostart' => 'false',
        'ssr' => 'true',
        'url' => route__("actionGetEmployee_mainstay_employee_employeemainstaycontroller"),
        'params' => ['user_id' => request()->route('employee_id')],
    ]
])
@section('title', 'Профиль сотрудника')

@section('content')
    <section class="gradient-bg u-container lawyer-section">
        <div class="container">

            <div class="lawyer-container">
                <div class="left">
                    @include('component_build', [
                            'component' => 'component.infoComponent.textInfo',
                            'params_component' => [
                                'autostart' => 'false',
                                'name' => 'employee_info_main',
								'globalData' => 'EmployeeInfo',
								'change' => 'function() {}',
                                'template' =>
                    "
                    <div:id=\"name\">
                    <div class=\"lawyer-block\">
                        <div class=\"lawyer-top\">
                            <div class=\"lawyer-img\">
                                <img :src=\"data.avatar_full_path\" alt=\"lawyer-img\">
                            </div>

                            <div class=\"lawyer-info\">
                                <h2 class=\"lawyer-name\">@{{getFullName(data)}}</h2>
                                <span class=\"lawyer-check\">Проверенный юрист</span>
                            </div>
                        </div>

                        <div class=\"lawyer-bottom\">
                            <div class=\"lawyer_rate-block\">
                                <div class=\"specialist-rate\">
                                    <div class=\"stars\"><span style=\"width: 80%;\"></span></div>
                                    <p>32 ответа</p>
                                </div>
                            </div>

                            <div class=\"lawyer-info\">
                                <div class=\"lawyer-info_row\">
                                    <img class=\"icon\" src=\"/lawyers/images/icons/loc-icon-blue.svg\" alt=\"loc-icon\">
                                    <span>@{{data.location_address ?? data.city_name}}</span>
                                </div>
                                <div class=\"lawyer-info_row\">
                                    <img class=\"icon\" src=\"/lawyers/images/icons/bah-icon-blue.svg\" alt=\"bah-icon\">
                                    <span name=\"practice_years\">@{{agetostr(data.practice_years)}} практики</span>
                                </div>
                                <div class=\"lawyer-info_row\">
                                    <img class=\"icon\" src=\"/lawyers/images/icons/phone-icon-blue.svg\" alt=\"phone-icon\">
                                    <a name=\"phone_number\" href=\"tel:+@{{data.phone_number}}\" class=\"span-link\">+@{{data.phone_number}}</a>
                                </div>
                                <div class=\"lawyer-info_row\" v-if=\"data.site_url\">
                                    <img class=\"icon\" src=\"/lawyers/images/icons/planet-icon-blue.svg\" alt=\"planet-icon\">
                                    <span>@{{ data.site_url }}</span>
                                </div>
                                <div class=\"lawyer-info_row\">
                                    <img class=\"icon\" src=\"/lawyers/images/icons/message-icon-blue.svg\" alt=\"message-icon\">
                                    <span>Консультации онлайн:</span>
                                    <span class=\"bold\">Да</span>
                                </div>
                                <div class=\"lawyer-info_row\">
                                    <img class=\"icon\" src=\"/lawyers/images/icons/clock-icon-blue.svg\" alt=\"clock-icon\">
                                    <span>Время работы:</span>
                                    <span class=\"bold\">Пн-Сб 9:00 - 18:00</span>
                                </div>
                            </div>
                        </div>
                    </div></div>",
                            ]
                        ])


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

                        <button class="main-btn"><span>Создать задачу</span></button>
                    </div>
                </div>

                <div class="right">
                    {{--<ul class="round-top_nav">
                        <li class="active"><button type="button">О себе</button></li>
                        <li><button type="button">Услуги</button></li>
                        <li><button type="button">Отзывы</button></li>
                        <li><button type="button">Ответы юриста</button></li>
                    </ul>--}}

                    @include('component_build', [
                   'component' => 'component.infoComponent.textInfo',
                   'params_component' => [
                       'autostart' => 'false',
                       'name' => 'employee_info_more',
                       'globalData' => "EmployeeInfo",
                        "callAfterloadComponent" => "function() {
                            docSlider()
                        }",
                       'template' =>
                       "<div class='lawyer-card'>
                       <div class='lawyer-info'>
                           <h2 class='lawyer-name'>@{{getFullName(data)}}</h2>
                       </div>

                       <p class='lawyer-text_p'>@{{data.about}}</p>

                           <h2 class='lawyer-card_block-title'>Документы и сертификаты</h2>
                            <div class='docs-slider_container js_slider_nav'>
                                <div class='docs-slider-content full_width'>
                                <ul class='docs-slider js_docs-slider'>
                                    <li class='docs-slider_item'  v-for=\"item in data.achievements\">
                                        <a :href=\"item.path\" data-fancybox>
                                            <img :src=\"item.path\" alt='cert-img' width='130' height='130' />
                                        </a>
                                        <p class='docs-slider_title'>@{{ item.description }}</p>
                                    </li>
                                </ul>
                                </div>
                            </div>
                   </div>"
                   ]
               ])

                    <div class="lawyer-services lawyer-wrapper">
                        <h2 class="lawyer-wrapper_title _line-blue">Услуги юриста</h2>

                            @include('component_build', [
                                'component' => 'component.gridComponent.simpleGrid',
                                'params_component' => [
                                    'autostart' => 'true',
                                    'name' => 'service_list',
                                    'url' => route__("actionGetServices_mainstay_employee_employeemainstaycontroller"), // получаем список EmployeeServices!!!!

                                    'template' => "<ul class=\"lawyer-services_block\" :id=\"name + '_body'\">
                                        <li v-for=\"item in data\" class=\"lawyer-service_line\" style=\"justify-content: space-between;\">
                                            <div class=\"lawyer-service_left\">
                                                <div class=\"lawyer-service_title\">@{{ item.name }}</div>
                                                <p class=\"lawyer-service_text\">
                                                    @{{ item.description }}
                                                <!--    <button class=\"lawyer-service_red-more\">
                                                        ЧИТАТЬ ЕЩЕ
                                                    </button> -->
                                                </p>
                                            </div>

                                            <div class=\"lawyer-service_price\">
                                                <span>500&#8381;</span>
                                                <button class=\"main-btn main-btn_white\">Заказать услугу</button>
                                            </div>
                                        </li>
                                    </ul>",

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

{{--                        <button class="more-services">Еще 2 услуги</button>--}}
                    </div>

                    <div class="lawyer-all-services lawyer-wrapper">
                        <h2 class="lawyer-wrapper_title _line-blue">Специализация</h2>
                            @include('component_build', [
                                'component' => 'component.gridComponent.simpleGrid',
                                'params_component' => [
                                    'autostart' => 'true',
                                    'name' => 'employee_services',
                                    'url' => route__("actionGetSpecialization_mainstay_employee_employeemainstaycontroller"),
									'params' => ['user_id' => request()->route('employee_id')],

                                    'template' => "<ul name=\"lawyer_services\" :id=\"name + '_body'\">
                                        <li v-for=\"item in data\">@{{item.name}}</li>
                                    </ul>",

                                    'pagination' => [
                                        'page' => 1,
                                        'pageSize' => 6,
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

{{--                        <button class="more-services">Еще 2 услуги</button>--}}
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
                                    <div class="stars"><span style="width: 80%;"></span></div>

                                    <p class="name">Алексеева Юлия</p>
                                    <span class="date">16.05.2023</span>
                                    <img class="check-icon icon" src="/lawyers/images/icons/check-icon-green-transparent.svg" alt="check-icon">
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
                                <div class="stars"><span style="width: 80%;"></span></div>
                                <p>32 ответа</p>
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
                        <div class="lawyer-contacts_map" id="map">
                        </div>

                        @include('component_build', [
                            'component' => 'component.infoComponent.textInfo',
                            'params_component' => [
                                'autostart' => 'true',
                                'name' => 'employee_info_place',
								'globalData' => 'EmployeeInfo',

                                'template' =>
                    "<div class=\"lawyer-info\">
                            <div class=\"lawyer-info_row\">
                                <img class=\"icon\" src=\"/lawyers/images/icons/loc-icon-blue.svg\" alt=\"loc-icon\">
                                <span>@{{data.location_address ?? data.city_name}}</span>
                            </div>
                            <div class=\"lawyer-info_row\">
                                <img class=\"icon\" src=\"/lawyers/images/icons/bah-icon-blue.svg\" alt=\"bah-icon\">
                                <span name=\"data.practice_years\">@{{agetostr(data.practice_years)}}</span>
                            </div>
                            <div class=\"lawyer-info_row\">
                                <img class=\"icon\" src=\"/lawyers/images/icons/phone-icon-blue.svg\" alt=\"phone-icon\">
                                <a name=\"phone_number\" href=\"tel:+@{{data.phone_number}}\" class=\"span-link\">+@{{data.phone_number}}</a>
                            </div>
                            <div class=\"lawyer-info_row\" v-if=\"data.site_url\">
                                <img class=\"icon\" src=\"/lawyers/images/icons/planet-icon-blue.svg\" alt=\"planet-icon\">
                                <a href=\"data.site_url\" class=\"span-link\">@{{ data.site_url }}</a>
                            </div>
                            <div class=\"lawyer-info_row\">
                                <img class=\"icon\" src=\"/lawyers/images/icons/message-icon-blue.svg\" alt=\"message-icon\">
                                <span>Консультации онлайн:</span>
                                <span class=\"lawyer-info_span-black\">Да</span>
                            </div>
                            <div class=\"lawyer-info_row\">
                                <img class=\"icon\" src=\"/lawyers/images/icons/clock-icon-blue.svg\" alt=\"clock-icon\">
                                <span>Время работы:</span>
                                <span class=\"lawyer-info_span-black\">Пн-Сб 9:00 - 18:00</span>
                            </div>
                        </div>",
                            ]
                        ])
                        <div class="buttons-container">
                            <button class="main-btn main-btn_white">Сообщение</button>
                            <button class="main-btn main-btn_white">Заказать услугу</button>
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
    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=de2b1fff-b759-4fe3-8bc3-e496e0eb9b13" type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            ymaps.ready(init);
        })

        function init() {
            let coords = page__.getGolobalData('EmployeeInfo').location_coordinates
            let savedCoordinates = coords ? JSON.parse(page__.getGolobalData('EmployeeInfo').location_coordinates).map(Number) : '';
            var center = savedCoordinates ?? [55.733842, 37.588144];
            const zoom = 15;
            var myPlacemark;
            var myMap = new ymaps.Map('map', {
                center: center,
                zoom: zoom,
                controls: []
            }, {
                suppressMapOpenBlock: true
            });

            myPlacemark = new ymaps.Placemark(myMap.getCenter(), {
                hintContent: 'Тут',
            }, {
                preset: 'islands#violetDotIconWithCaption',
                draggable: true
            });

            if (savedCoordinates === '') {
                let city = page__.getGolobalData('EmployeeInfo').city_name;
                ymaps.geocode(city, {results: 1}).then(
                    function (res) {
                        myMap.setCenter(res.geoObjects.get(0).geometry.getCoordinates())
                        myPlacemark.geometry.setCoordinates(myMap.getCenter())
                    })
            }

            myMap.geoObjects.add(myPlacemark);
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
        function getSliderSettings() {
            let slidesLength = $('.js_docs-slider').find('li').length;
            return {
                slidesToShow: 6,
                infinite: false,
                dots: false,
                arrows: slidesLength > 6 ? true : false,
                appendArrows: '.js_slider_nav',
                prevArrow: '<button type="button" class="slick-prev"><svg width="14" height="11" viewBox="0 0 14 11" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 5.26758H13.25" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M13.25 5.26758L9.25 1.26758" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M13.25 5.26758L9.25 9.26758" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></button>',
                nextArrow: '<button type="button" class="slick-next"><svg width="14" height="11" viewBox="0 0 14 11" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 5.26758H13.25" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M13.25 5.26758L9.25 1.26758" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M13.25 5.26758L9.25 9.26758" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></button>',
                responsive: [{
                    breakpoint: 1281,
                    settings: {
                        slidesToShow: 7,
                        arrows: slidesLength > 7 ? true : false,
                    }
                },{
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 5,
                        arrows: slidesLength > 5 ? true : false,
                    }
                },{
                    breakpoint: 769,
                    settings: {
                        slidesToShow: 5,
                        arrows: false,
                    }
                },{
                    breakpoint: 665,
                    settings: {
                        slidesToShow: 4,
                        arrows: false,
                    }
                    },{
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 3,
                        arrows: false,
                    }
                }],
            }
        }

        function docSlider() {
            $('.js_docs-slider').not('.slick-initialized').slick(getSliderSettings());
        }
    </script>
@endsection
