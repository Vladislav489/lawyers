@extends('lawyers.layouts.main')
@include('component_build',[
	'component' => 'component.loadComponent.loadGlobalData',
        'params_component' => [
        'name' => "EmployeeInfo",
        'autostart' => 'false',
        'ssr' => 'true',
        'url' => route__("actionGetEmployee_mainstay_employee_employeemainstaycontroller"),
        'params' => ['user_id' => auth()->id()],
    ]
])
@section('title', 'Профиль сотрудника')


@section('content')
    <section class="u-container lawyer-section">
        <div class="container">
            <div class='modal profile_modal' id='employeeInfoEdit'>
                <h5 class='order-modal_title' id='exampleModalLongTitle'>Редактировать</h5>
                            @include('component_build', [
                                'component' => 'component.infoComponent.textInfo',
                                'params_component' => [
                                    'autostart' => 'false',
                                    'name' => 'employee_info_edit',
                                    'params' => [],
									'globalData' => 'EmployeeInfo',
									'ssr' => 'false',

                                    'template' =>"
                            <div id=\"common_info_container\" class=\"flexbox\">
                            <div class=\"registration-form_label\">
                                <label class=\"label-title\">Имя</label>
                                <input type=\"text\" name=\"first_name_edit\" :value=\"data.first_name\">
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
                            <div class="flexbox">
                            <div class="registration-form_label">
                            <label class="label-title">Регион</label>
                            @include('component_build',["component" => "component.listComponent.selectComponent",
                            "params_component" => [
                                "autostart" => 'true',
                                "name" => 'region_id',
                                "default_title" => 'Регион',
                                "url" => route("actionGetRegions_mainstay_helpdata_helpdatamainstaycontroller"),
								"callBeforloadComponent" => "function(component) {
                                        component.option['currentSelectId'] = page__.getGolobalData('EmployeeInfo').region_id
                                        component.option['currentSelectName'] = page__.getGolobalData('EmployeeInfo').region_name
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
                                '<select class="js_select" name="region_id" :id="name" style="width:100%">
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
                            <div class="registration-form_label">
                            <label class="label-title">Город</label>
                            @include('component_build',["component" => "component.listComponent.selectComponent",
                            "params_component" => [
                                "autostart" => 'false',
                                "name" => 'city_id',
                                "default_title" => 'Город',
                                "url" => route("actionGetCities_mainstay_helpdata_helpdatamainstaycontroller"),
                                "callBeforloadComponent" => "function(component) {
                                        component.option['currentSelectId'] = page__.getGolobalData('EmployeeInfo').city_id
                                        component.option['currentSelectName'] = page__.getGolobalData('EmployeeInfo').city_name
                                        return component.option
								    }",
								"callAfterloadComponent" => "function(component) {
								    $('.js_select').select2({
								        minimumResultsForSearch: -1,
								    });
                                }",

                                "template" =>
                                '<select class="js_select" name="city_id" :id="name" style="width:100%">
                                    <option v-for="(items_ , index) in data " :data-text="items_" :value="index" :selected="index == currentSelectId">@{{items_}}</option>
                                </select>',
                                "change" => "function(){}"
                            ]])
                            </div>
                        </div>

                @include('component_build', [
                                'component' => 'component.infoComponent.textInfo',
                                'params_component' => [
                                    'autostart' => 'false',
                                    'name' => 'employee_info_worktime_edit',
									'globalData' => 'EmployeeInfo',
                                    "callAfterloadComponent" => "function(component) {
                                        $('.js_checkbox_worktime input').click(function(){
                                            $(this).parent().toggleClass('active');
                                            $('#set_schedule').fadeToggle();
                                        });
                                        $('.js_checkbox input').click(function(){
                                            $(this).parent().toggleClass('active');
                                        });
                                        $('.js_select_time').select2({
                                            minimumResultsForSearch: -1,
                                        });
                                    }",
                                    'template' =>"
                                <div>
                                <div class='modal_time_container'>
                                    <h6 class='modal_smalltitle'>Время работы</h6>
                                    <label class='checkbox_24 js_checkbox_worktime' id='label_select_worktime'><input type='checkbox' id='select_worktime'>Круглосуточно</label>
                                </div>
                                <div id='set_schedule' class='schedule-container'>
                                    <p class='schedule_result'>Пн-Сб 9:00 - 18:00</p>
                                    <div class='days-container'>
                                        <p class='days-container-title'>Дни недели</p>
                                        <label class='days-checkbox js_checkbox'>
                                            <input type='checkbox' name='day_of_week' id='day1' value='1'>Пн
                                        </label>
                                        <label class='days-checkbox js_checkbox'>
                                            <input type='checkbox' name='day_of_week' id='day2' value='2'>Вт
                                        </label>
                                        <label class='days-checkbox js_checkbox'>
                                            <input type='checkbox' name='day_of_week' id='day3' value='3'>Ср
                                        </label>
                                        <label class='days-checkbox js_checkbox'>
                                            <input type='checkbox' name='day_of_week' id='day4' value='4'>Чт
                                        </label>
                                        <label class='days-checkbox js_checkbox'>
                                            <input type='checkbox' name='day_of_week' id='day5' value='5'>Пт
                                        </label>
                                        <label class='days-checkbox js_checkbox'>
                                            <input type='checkbox' name='day_of_week' id='day6' value='6'>Сб
                                        </label>
                                        <label class='days-checkbox js_checkbox'>
                                            <input type='checkbox' name='day_of_week' id='day7' value='7'>Вс
                                        </label>
                                    </div>
                                    <div class='flexbox'>
                                        <div class='registration-form_label'>
                                        <label class='label-title'>От</label>
                                        <select id='from_time' class='js_select_time'>
                                            <option value='1'>1:00</option>
                                            <option value='2'>2:00</option>
                                            <option value='3'>3:00</option>
                                            <option value='4'>4:00</option>
                                            <option value='5'>5:00</option>
                                            <option value='6'>6:00</option>
                                            <option value='7'>7:00</option>
                                            <option value='8'>8:00</option>
                                            <option value='9'>9:00</option>
                                            <option value='10'>10:00</option>
                                            <option value='11'>11:00</option>
                                            <option value='12'>12:00</option>
                                            <option value='13'>13:00</option>
                                            <option value='14'>14:00</option>
                                            <option value='15'>15:00</option>
                                            <option value='16'>16:00</option>
                                            <option value='17'>17:00</option>
                                            <option value='18'>18:00</option>
                                            <option value='19'>19:00</option>
                                            <option value='20'>20:00</option>
                                            <option value='21'>21:00</option>
                                            <option value='22'>22:00</option>
                                            <option value='23'>23:00</option>
                                            <option value='24'>24:00</option>
                                        </select>
                                    </div>
                                    <div class='registration-form_label'>
                                    <label class='label-title'>До</label>
                                        <select id='to_time' class='js_select_time'>
                                            <option value='1'>1:00</option>
                                            <option value='2'>2:00</option>
                                            <option value='3'>3:00</option>
                                            <option value='4'>4:00</option>
                                            <option value='5'>5:00</option>
                                            <option value='6'>6:00</option>
                                            <option value='7'>7:00</option>
                                            <option value='8'>8:00</option>
                                            <option value='9'>9:00</option>
                                            <option value='10'>10:00</option>
                                            <option value='11'>11:00</option>
                                            <option value='12'>12:00</option>
                                            <option value='13'>13:00</option>
                                            <option value='14'>14:00</option>
                                            <option value='15'>15:00</option>
                                            <option value='16'>16:00</option>
                                            <option value='17'>17:00</option>
                                            <option value='18'>18:00</option>
                                            <option value='19'>19:00</option>
                                            <option value='20'>20:00</option>
                                            <option value='21'>21:00</option>
                                            <option value='22'>22:00</option>
                                            <option value='23'>23:00</option>
                                            <option value='24'>24:00</option>
                                        </select>
                                    </div>
                                    </div>
                                </div>
                                </div>
                            ",
                                ]
                            ])



                        <div class='flex align-center form--submit'>
                            <button type='button' id="save_edit_main" class='main-btn main-btn_blue'>Сохранить</button>
                            <button type='button' class='main-btn main-btn_white' data-fancybox-close>Закрыть</button>
                        </div>
            </div>
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
                        <a href=\"#employeeInfoEdit\" id=\"edit_more_modal\" data-fancybox class=\"edit\"></a>
                        <div class=\"lawyer-top\">
                            <div class=\"lawyer-img\">
                                <img :src=\"data.avatar_full_path\" alt=\"lawyer-img\">
                            </div>

                            <div class=\"lawyer-info\">
                                <h2 class=\"lawyer-name with_ico\">@{{getFullName(data)}}</h2>
                                <span class=\"lawyer-check\">
                                    Проверенный юрист
                                    <img class=\"icon\" src=\"/lawyers/images/icons/check-icon-white.svg\" alt=\"check-icon\">
                                </span>
                            </div>
                        </div>

                        <div class=\"lawyer-bottom\">
                            <div class=\"lawyer_rate-block\">
                                <div class=\"specialist-rate\">
                                    <div class=\"stars\">
                                        <img src=\"/lawyers/images/icons/star-icon-full.svg\" alt=\"star-icon\">
                                        <img src=\"/lawyers/images/icons/star-icon-full.svg\" alt=\"star-icon\">
                                        <img src=\"/lawyers/images/icons/star-icon-full.svg\" alt=\"star-icon\">
                                        <img src=\"/lawyers/images/icons/star-icon-full.svg\" alt=\"star-icon\">
                                        <img src=\"/lawyers/images/icons/star-icon-empty.svg\" alt=\"star-icon\">
                                    </div>
                                    <span>32 ответа</span>
                                </div>

                                <div class=\"specialist-perm\">
                                    <p>Право рейтинг:</p>
                                    <span>4.0</span>
                                    <img class=\"icon\" src=\"/lawyers/images/icons/info-icon-blue.svg\" alt=\"info-icon\">
                                </div>
                            </div>

                            <div class=\"lawyer-info\">
                                <div class=\"lawyer-info_row\">
                                    <img class=\"icon\" src=\"/lawyers/images/icons/loc-icon-blue.svg\" alt=\"loc-icon\">
                                    <span>@{{data.city_name}}</span>
                                </div>
                                <div class=\"lawyer-info_row\">
                                    <img class=\"icon\" src=\"/lawyers/images/icons/bah-icon-blue.svg\" alt=\"bah-icon\">
                                    <span name=\"practice_years\">@{{agetostr(data.practice_years)}} практики</span>
                                </div>
                                <div class=\"lawyer-info_row\">
                                    <img class=\"icon\" src=\"/lawyers/images/icons/phone-icon-blue.svg\" alt=\"phone-icon\">
                                    <a name=\"phone_number\" href=\"tel:+@{{data.phone_number}}\" class=\"span-link\">+@{{data.phone_number}}</a>
                                </div>
                                <div class=\"lawyer-info_row\">
                                    <img class=\"icon\" src=\"/lawyers/images/icons/planet-icon-blue.svg\" alt=\"planet-icon\">
                                    <span>www.site.ru</span>
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

                <div class='modal registration-section' id='employeeInfoMoreEdit'>
                    <h5 class='section_header' id='exampleModalLongTitle'>Окно редактирования</h5>
                    <div class='registration-form_block'>
                        <div id='common_info_container' class='registration-form_block'>
                                @include('component_build', [
                                    'component' => 'component.infoComponent.textInfo',
                                    'params_component' => [
                                        'autostart' => 'false',
                                        'name' => 'employee_info_more_edit',
                                        'globalData' => 'EmployeeInfo',
                                        'params' => [],
                                        'ssr' => 'false',

                                        'template' =>
                                "<div class=\"registration-form_label full\">
                                    <label class=\"label-title\">О себе</label>
                                    <textarea id=\"about_edit\" type=\"text\" name=\"about_edit\" :value=\"data.about\">
                                </div>",
                                    ]
                                ])
                                <div class='registration-form_label full'>
                                <label class='label-title'>Добавить фотографию</label>
                                @include('component_build', [
                                    'component' => 'component.imageComponet.imageDropZoneEditor',
                                    'params_component' => [
                                        'name' => 'employee_info_more_add_photos',

                                        'url' => route__('actionGetEmployee_mainstay_employee_employeemainstaycontroller'),
                                    ]
                                ])
                                </div>
                            <div class='registration-form_label full'>
                                <label>Добавить сертификат</label>
                                @include('component_build', [
                                    'component' => 'component.imageComponet.imageDropZoneEditor',
                                    'params_component' => [
                                        'name' => 'employee_info_more_add_achievements',
                                        'url' => route__('actionGetEmployee_mainstay_employee_employeemainstaycontroller'),
                                    ]
                                ])
                            </div>
                        </div>
                    </div>
                            <div class='flex align-center form--submit'>
                                <button type='button' id="save_info_more_edit" class='main-btn main-btn_blue'>Сохранить</button>
                                <button type='button' class='main-btn main-btn_white' data-fancybox-close>Закрыть</button>
                            </div>
                </div>
                <div class="right">
                    <ul class="round-top_nav">
                        <li class="active"><button type="button">О себе</button></li>
                        <li><button type="button">Услуги</button></li>
                        <li><button type="button">Отзывы</button></li>
                        <li><button type="button">Ответы юриста</button></li>
                    </ul>

                    @include('component_build', [
                    'component' => 'component.infoComponent.textInfo',
                    'params_component' => [
                        'autostart' => 'false',
                        'name' => 'employee_info_more',
                        'globalData' => "EmployeeInfo",

                        'template' =>
                        "<div class='lawyer-card'>
                        <a href='#employeeInfoMoreEdit' id='edit_more_modal' data-fancybox class='edit'></a>
                        <div class='lawyer-info'>
                            <h2 class='lawyer-name with_ico'>
                                @{{getFullName(data)}}
                            </h2>
                            <span class='lawyer-check'>
                                Проверенный юрист
                                <img src='/lawyers/images/icons/check-icon-white.svg' alt='check-icon'>
                            </span>
                        </div>

                        <p class='lawyer-text_p'>
                            @{{data.about}}
                        </p>

                        <div class='lawyer-card_block'>
                            <h2 class='lawyer-card_block-title'>Фото<span>
                            @{{data.photos === null ? 0 : data.photos.length}}
                            </span></h2>
                            <ul class='lawyer-photos' v-for=\"item in data.photos\">
                                <li>
                                    <img :src='item.path' alt='lawyer-img'>
                                    <div :name=\"'delete_photo_' + item.id\" v-bind:photo_id=\"item.id\">delete</div>
                                </li>
                            </ul>
                        </div>

                        <div class='lawyer-card_block'>
                            <h2 class='lawyer-card_block-title'>Документы и сертификаты <span>
                            @{{data.achievements === null ? 0 : data.achievements.length}}
                            </span></h2>
                            <ul class='lawyer-certs_container' v-for=\"item in data.achievements\">
                                <li class='lawyer-cert'>
                                    <img :src=\"item.path\" alt='cert-img'>
                                    <div :name=\"'delete_achievement_' + item.id\" v-bind:achievement_id=\"item.id\">delete</div>
                                </li>
                            </ul>
                        </div>
                    </div>"
                    ]
                ])

                    <div class='modal fade' id='employeeServiceCreate' tabindex='-1' role='dialog' aria-labelledby='exampleModalCenterTitle' aria-hidden='true'>
                        <div class='modal-dialog modal-dialog-centered' role='document'>
                            <div class='modal-content'>
                                <div class='modal-header'>
                                    <h5 class='modal-title' id='exampleModalLongTitle'>Окно редактирования</h5>
                                    <button type='button' class='close' data-dismiss='modal' aria-label='Закрыть'>
                                        <span aria-hidden='true'>&times;</span>
                                    </button>
                                </div>
                                <div class='modal-body flex-between'>
                                    <label for="">Тип услуги</label>

                                    @include('component_build',["component" => "component.listComponent.selectComponent",
                            "params_component" => [
                                "autostart" => 'true',
                                "name" => 'type_id',
                                "default_title" => 'Сервис',
                                "url" => route("actionGetServiceList_mainstay_service_servicemainstaycontroller"),
                                "template" =>
                                '<select class="unit-select_select" name="service_id" :id="name" style="width:100%">
                                    <option value="" selected="true">Выбрать</option>
                                    <option v-for="(item,index) in data" :value="item.id">@{{ item.name }}</option>
                                </select>',
                                "change" => "function(){
                                    }"
                            ]])

                                    @include('component_build', [
                            'component' => 'component.infoComponent.textInfo',
                            'params_component' => [
                                'autostart' => 'false',
                                'name' => 'employee_create_service_info',
								'data' => "{'description': '', 'price': ''}",

                                'template' =>
                                "<div>
                                <label for=''>Описание услуги</label>
                                     <input id='service_description' type='text' name='service_description' :value=\"data.description\" class='border'>
                                <label for=''>Цена услуги</label>
                                      <input id='service_price' type='text' name='service_price' :value=\"data.price\" class='border'>
                                </div>"
                            ]
                        ])
                                </div>
                                <div class='modal-footer'>
                                    <button type='button' id="save_service_create" class='btn btn-success'>Сохранить</button>
                                    <button type='button' class='btn btn-secondary' data-dismiss='modal'>Закрыть</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class='modal fade' id='employeeServiceEdit' tabindex='-1' role='dialog' aria-labelledby='exampleModalCenterTitle' aria-hidden='true'>
                        <div class='modal-dialog modal-dialog-centered' role='document'>
                            <div class='modal-content'>
                                <div class='modal-header'>
                                    <h5 class='modal-title' id='exampleModalLongTitle'>Окно редактирования</h5>
                                    <button type='button' class='close' data-dismiss='modal' aria-label='Закрыть'>
                                        <span aria-hidden='true'>&times;</span>
                                    </button>
                                </div>
                                <div class='modal-body flex-between'>
                                    @include('component_build', [
                                    'component' => 'component.infoComponent.textInfo',
                                    'params_component' => [
                                        'autostart' => 'true',
                                        'name' => 'employee_edit_service_info',
										'url' => route__('actionGetService_mainstay_employee_employeemainstaycontroller'),

                                        'template' =>
                                        "<div>
                                        <input type='hidden' id='service_edit_id' :value=\"data.id\">
                                        <label for=''>Описание услуги</label>
                                             <input id='service_description_edit' type='text' name='service_description' :value=\"data.description\" class='border'>
                                        <label for=''>Цена услуги</label>
                                              <input id='service_price_edit' type='text' name='service_price' :value=\"data.price\" class='border'>
                                        </div>"
                                    ]
                                ])
                                </div>
                                <div class='modal-footer'>
                                    <button type='button' id="save_service_edit" class='btn btn-success'>Сохранить</button>
                                    <button type='button' class='btn btn-secondary' data-dismiss='modal'>Закрыть</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="lawyer-all-services lawyer-wrapper">
                        <h2 class="lawyer-wrapper_title _line-blue">Оказываемые услуги</h2>
                        <div id="edit_services_modal">Добавить</div>
                        @include('component_build', [
                            'component' => 'component.gridComponent.simpleGrid',
                            'params_component' => [
                                'autostart' => 'true',
                                'name' => 'employee_services',
                                'url' => route__("actionGetServices_mainstay_employee_employeemainstaycontroller"),
								'params' => ['user_id' => auth()->id()],

                                'template' => "
                                <ul name=\"lawyer_services\" :id=\"name + '_body'\">
                                    <div v-for=\"item in data\">
                                        <div :name=\"'edit_service_' + item.id\" v-bind:employee_service_id=\"item.id\">edit</div>
                                        <div :name=\"'delete_service_' + item.id\" v-bind:employee_service_id=\"item.id\">delete</div>
                                        <li>@{{item.name}}</li>
                                    </div>
                                </ul>",
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
{{--                            <div style="position:relative;overflow:hidden;">--}}
{{--                                <a href="https://yandex.ru/maps/213/moscow/?utm_medium=mapframe&utm_source=maps" style="color:#eee;font-size:12px;position:absolute;top:0px;">Москва</a><a href="https://yandex.ru/maps/geo/moskva/53000094/?ll=37.608537%2C55.754059&utm_medium=mapframe&utm_source=maps&z=12.96" style="color:#eee;font-size:12px;position:absolute;top:14px;">Москва — Яндекс Карты</a><iframe src="https://yandex.ru/map-widget/v1/?ll=37.608537%2C55.754059&mode=search&ol=geo&ouri=ymapsbm1%3A%2F%2Fgeo%3Fdata%3DCgg1MzAwMDA5NBIa0KDQvtGB0YHQuNGPLCDQnNC-0YHQutCy0LAiCg2GeBZCFQEGX0I%2C&z=12.96" width="600" height="150" frameborder="1" allowfullscreen="true" style="position:relative;"></iframe>--}}
{{--                            </div>--}}
{{--                            <img src="/lawyers/images/main/map.png" alt="map-img">--}}
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
                                <span>@{{data.city_name}}</span>
                            </div>
                            <div class=\"lawyer-info_row\">
                                <img class=\"icon\" src=\"/lawyers/images/icons/bah-icon-blue.svg\" alt=\"bah-icon\">
                                <span name=\"data.practice_years\">@{{agetostr(data.practice_years)}}</span>
                            </div>
                            <div class=\"lawyer-info_row\">
                                <img class=\"icon\" src=\"/lawyers/images/icons/phone-icon-blue.svg\" alt=\"phone-icon\">
                                <a name=\"phone_number\" href=\"tel:+@{{data.phone_number}}\" class=\"span-link\">+@{{data.phone_number}}</a>
                            </div>
                            <div class=\"lawyer-info_row\">
                                <img class=\"icon\" src=\"/lawyers/images/icons/planet-icon-blue.svg\" alt=\"planet-icon\">
                                <a href=\"#\" class=\"span-link\">www.site.ru</a>
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
    <script>
        $(document).ready(function () {
            setEdit()
            updateData()
            deleteData()

        })

        function getMainDataForUpdate() {
            return {
                'first_name': $('[name = first_name_edit]').val(),
                'last_name': $('[name = last_name_edit]').val(),
                'middle_name': $('[name = middle_name_edit]').val(),
                'phone_number': $('[name = phone_number_edit]').val(),
                'region_id': $('[name = region_id]').val(),
                'city_id': $('[name = city_id]').val(),
                'working_days': getWorkingDaysArray(),
                'from_time': getWorkingDaysArray() ? $('#from_time').val() : null,
                'to_time': getWorkingDaysArray() ? $('#to_time').val() : null
            }
        }

        function getMoreDataForUpdate() {
            return {
                'about': $('[name = about_edit]').val(),
                'photos': prepareImageData('employee_info_more_add_photos'),
                'achievements': prepareImageData('employee_info_more_add_achievements')
            }
        }

        function getServiceForUpdate() {
            return {
                'description': $('#service_description_edit').val(),
                'price': $('#service_price_edit').val(),
                'id': $('#service_edit_id').val(),
                'user_id': {{ auth()->id() }},
            }
        }

        function getNewService() {
            return {
                'description': $('[name = service_description]').val(),
                'price': $('[name = service_price]').val(),
                'service_id': $('[name = service_id]').val(),
                'user_id': {{ auth()->id() }}
            }
        }

        function prepareImageData(componentName) {
            const Obj = page__.getElementsGroup(componentName)[0]['obj']['files']
            let imgArr = []
            Obj.forEach((e) => {
                imgArr.push(e['dataURL'])
            })
            return imgArr
        }

        function updateData() {
            $('#save_edit_main').on('click', function () {
                const data = getMainDataForUpdate()
                sendData(data)
            })
            $('#save_info_more_edit').on('click', function () {
                const data = getMoreDataForUpdate()
                sendData(data)
            })
            $('#save_service_edit').on('click', function () {
                const data = getServiceForUpdate()
                page__.sendData('{{ route__('actionEmployeeServiceStore_mainstay_employee_employeemainstaycontroller') }}',
                    data, function (data) {
                    page__.getElementsGroup('employee_services')[0]['obj'].setData(data['result'])
                    page__.getElementsGroup('employee_services')[0]['obj'].startWidget()
                })
            })
            $('#save_service_create').on('click', function () {
                const data = getNewService()
                page__.sendData('{{ route__('actionEmployeeServiceStore_mainstay_employee_employeemainstaycontroller') }}',
                    data, function (data) {
                        page__.getElementsGroup('employee_services')[0]['obj'].setData(data['result'])
                        page__.getElementsGroup('employee_services')[0]['obj'].startWidget()
                    })
            })
        }

        function sendData(data) {
            $.ajax({
                method: 'POST',
                data: data,
                url: '{{ route__('actionUpdateEmployeeInfo_mainstay_employee_employeemainstaycontroller') }}',
                success: function (response) {
                    $.fancybox.close()
                    updateGlobalData(response)
                    clearDropZones(true, response)
                }

            })
        }

        function deleteData() {
            $(document).on('click', '[name ^= delete_service_]', function () {
                page__.sendData('{{ route__('actionDeleteService_mainstay_employee_employeemainstaycontroller') }}', {
                    'id': $(this).attr('employee_service_id'),
                    'user_id': '{{ auth()->id() }}'
                }, function (data) {
                    page__.getElementsGroup('employee_services')[0]['obj'].setData(data['result'])
                    page__.getElementsGroup('employee_services')[0]['obj'].startWidget()
                })
            })
            $(document).on('click', '[name ^= delete_photo_]', function () {
                page__.sendData('{{ route__('actionDeleteImage_mainstay_employee_employeemainstaycontroller') }}', {
                    'photo_id': $(this).attr('photo_id'),
                    'user_id': '{{ auth()->id() }}'
                }, function (data) {
                    page__.getElementsGroup('employee_info_more').forEach(function(element) {
                        updateGlobalData(data)
                        clearDropZones(true, data)
                    })
                })
            })
            $(document).on('click', '[name ^= delete_achievement_]', function () {
                page__.sendData('{{ route__('actionDeleteImage_mainstay_employee_employeemainstaycontroller') }}', {
                    'achievement_id': $(this).attr('achievement_id'),
                    'user_id': '{{ auth()->id() }}'
                }, function (data) {
                    updateGlobalData(data)
                    clearDropZones(true, data)
                })
            })
        }

        function intersect(o1, o2){
            return Object.keys(o1).filter(k => Object.hasOwn(o2, k))
        }

        function setEdit() {
            /*$(document).on('click', '#edit_main_modal', function () {
                $('#employeeInfoEdit').modal('toggle')
            })*/
            $(document).on('click', '#edit_services_modal', function () {
                $('#employeeServiceCreate').modal('toggle')
            })
            $(document).on('click', '[name ^= edit_service_]', function () {
                page__.getElementsGroup('employee_edit_service_info')[0]['obj'].setUrlParams({
                    'id': $(this).attr('employee_service_id'),
                    'user_id': '{{ auth()->id() }}'
                })
                $('#employeeServiceEdit').modal('toggle')
            })
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

        function clearDropZones(restartElements = false, data = []) {
                page__.getElementsGroup('employee_info_more').forEach(function(element) {
                    if(element['obj']['files'] !== undefined) {
                        element['obj'].removeAllFiles()
                    } else {
                        if (restartElements) {
                            element['obj'].setData(data)
                            element['obj'].startWidget()
                        }
                    }
                })
        }

        function updateGlobalData(data) {
            Object.assign(page__.getGolobalData('EmployeeInfo'), data)
        }

        /*function showScheduleBlock() {
            $('#select_worktime').prop('checked', !$('#select_worktime').prop('checked'))
            $('#set_schedule').prop('hidden', !$('#set_schedule').prop('hidden'))
        }*/

        function getWorkingDaysArray() {
            if ($('#select_worktime').prop('checked')) {
                return $('input[name=day_of_week]:checked').map(function () {
                    return $(this).val();
                }).get();
            }
            return null
        }
    </script>
@endsection
