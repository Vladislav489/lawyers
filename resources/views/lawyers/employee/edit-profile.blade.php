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
									'globalData' => 'EmployeeInfo',

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
									'callBeforloadComponent' => "function(component) {
									    let workingDaysArray = []
									    let workingTimeFromInt = ''
									    let workingTimeToInt = ''
									    let schedule = page__.getGolobalData('EmployeeInfo')['schedule']
									    if (schedule != null || schedule != undefined) {
									        workingDaysArray = schedule.map(item => item.day_number)
									        workingTimeFromInt = parseInt(schedule[0].time_from.split(':')[0])
									        workingTimeToInt = parseInt(schedule[0].time_to.split(':')[0])
									    }
									    component.option['workingDaysArray'] = workingDaysArray
									    component.option['workingTimeFromInt'] = workingTimeFromInt
									    component.option['workingTimeToInt'] = workingTimeToInt
									    return component.option
									}",
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
                                <div>
                                <div class=\"registration-form_label\">
                                    <label class=\"label-title\">Ссылка на сайт</label>
                                    <input type=\"text\" name=\"site_url_edit\" placeholder='https://' :value=\"data.site_url\">
                                </div>
                                <div class='modal_time_container'>
                                    <h6 class='modal_smalltitle'>Время работы</h6>
                                    <label :class=\"['checkbox_24 js_checkbox_worktime', {active: !data.schedule}]\" id='label_select_worktime'>
                                        <input type='checkbox' id='select_worktime' :checked=\"data.schedule\">Круглосуточно
                                    </label>
                                </div>
                                <div id='set_schedule' class='schedule-container' :hidden=\"!data.schedule\">
                                    <p class='schedule_result'>@{{ data.working_days_interval + ' ' + data.work_time }}</p>
                                    <div class='days-container'>
                                        <p class='days-container-title'>Дни недели</p>
                                        <label :class=\"['days-checkbox js_checkbox', {'active': workingDaysArray.includes(1)}]\">
                                            <input type='checkbox' name='day_of_week' id='day1' value='1' :checked=\"workingDaysArray.includes(1)\">Пн
                                        </label>
                                        <label :class=\"['days-checkbox js_checkbox', {'active': workingDaysArray.includes(2)}]\">
                                            <input type='checkbox' name='day_of_week' id='day2' value='2' :checked=\"workingDaysArray.includes(2)\">Вт
                                        </label>
                                        <label :class=\"['days-checkbox js_checkbox', {'active': workingDaysArray.includes(3)}]\">
                                            <input type='checkbox' name='day_of_week' id='day3' value='3' :checked=\"workingDaysArray.includes(3)\">Ср
                                        </label>
                                        <label :class=\"['days-checkbox js_checkbox', {'active': workingDaysArray.includes(4)}]\">
                                            <input type='checkbox' name='day_of_week' id='day4' value='4' :checked=\"workingDaysArray.includes(4)\">Чт
                                        </label>
                                        <label :class=\"['days-checkbox js_checkbox', {'active': workingDaysArray.includes(5)}]\">
                                            <input type='checkbox' name='day_of_week' id='day5' value='5' :checked=\"workingDaysArray.includes(5)\">Пт
                                        </label>
                                        <label :class=\"['days-checkbox js_checkbox', {'active': workingDaysArray.includes(6)}]\">
                                            <input type='checkbox' name='day_of_week' id='day6' value='6' :checked=\"workingDaysArray.includes(6)\">Сб
                                        </label>
                                        <label :class=\"['days-checkbox js_checkbox', {'active': workingDaysArray.includes(7)}]\">
                                            <input type='checkbox' name='day_of_week' id='day7' value='7' :checked=\"workingDaysArray.includes(7)\">Вс
                                        </label>
                                    </div>
                                    <div class='flexbox'>
                                        <div class='registration-form_label'>
                                        <label class='label-title'>От</label>
                                        <select id='from_time' class='js_select_time'>
                                            <option value='0' :selected=\"workingTimeFromInt == 0\">00:00</option>
                                            <option value='1' :selected=\"workingTimeFromInt == 1\">1:00</option>
                                            <option value='2' :selected=\"workingTimeFromInt == 2\">2:00</option>
                                            <option value='3' :selected=\"workingTimeFromInt == 3\">3:00</option>
                                            <option value='4' :selected=\"workingTimeFromInt == 4\">4:00</option>
                                            <option value='5' :selected=\"workingTimeFromInt == 5\">5:00</option>
                                            <option value='6' :selected=\"workingTimeFromInt == 6\">6:00</option>
                                            <option value='7' :selected=\"workingTimeFromInt == 7\">7:00</option>
                                            <option value='8' :selected=\"workingTimeFromInt == 8\">8:00</option>
                                            <option value='9' :selected=\"workingTimeFromInt == 9\">9:00</option>
                                            <option value='10' :selected=\"workingTimeFromInt == 10\">10:00</option>
                                            <option value='11' :selected=\"workingTimeFromInt == 11\">11:00</option>
                                            <option value='12' :selected=\"workingTimeFromInt == 12\">12:00</option>
                                            <option value='13' :selected=\"workingTimeFromInt == 13\">13:00</option>
                                            <option value='14' :selected=\"workingTimeFromInt == 14\">14:00</option>
                                            <option value='15' :selected=\"workingTimeFromInt == 15\">15:00</option>
                                            <option value='16' :selected=\"workingTimeFromInt == 16\">16:00</option>
                                            <option value='17' :selected=\"workingTimeFromInt == 17\">17:00</option>
                                            <option value='18' :selected=\"workingTimeFromInt == 18\">18:00</option>
                                            <option value='19' :selected=\"workingTimeFromInt == 19\">19:00</option>
                                            <option value='20' :selected=\"workingTimeFromInt == 20\">20:00</option>
                                            <option value='21' :selected=\"workingTimeFromInt == 21\">21:00</option>
                                            <option value='22' :selected=\"workingTimeFromInt == 22\">22:00</option>
                                            <option value='23' :selected=\"workingTimeFromInt == 23\">23:00</option>
                                        </select>
                                    </div>
                                    <div class='registration-form_label'>
                                    <label class='label-title'>До</label>
                                        <select id='to_time' class='js_select_time'>
                                            <option value='0' :selected=\"workingTimeToInt == 0\">00:00</option>
                                            <option value='1' :selected=\"workingTimeToInt == 1\">1:00</option>
                                            <option value='2' :selected=\"workingTimeToInt == 2\">2:00</option>
                                            <option value='3' :selected=\"workingTimeToInt == 3\">3:00</option>
                                            <option value='4' :selected=\"workingTimeToInt == 4\">4:00</option>
                                            <option value='5' :selected=\"workingTimeToInt == 5\">5:00</option>
                                            <option value='6' :selected=\"workingTimeToInt == 6\">6:00</option>
                                            <option value='7' :selected=\"workingTimeToInt == 7\">7:00</option>
                                            <option value='8' :selected=\"workingTimeToInt == 8\">8:00</option>
                                            <option value='9' :selected=\"workingTimeToInt == 9\">9:00</option>
                                            <option value='10' :selected=\"workingTimeToInt == 10\">10:00</option>
                                            <option value='11' :selected=\"workingTimeToInt == 11\">11:00</option>
                                            <option value='12' :selected=\"workingTimeToInt == 12\">12:00</option>
                                            <option value='13' :selected=\"workingTimeToInt == 13\">13:00</option>
                                            <option value='14' :selected=\"workingTimeToInt == 14\">14:00</option>
                                            <option value='15' :selected=\"workingTimeToInt == 15\">15:00</option>
                                            <option value='16' :selected=\"workingTimeToInt == 16\">16:00</option>
                                            <option value='17' :selected=\"workingTimeToInt == 17\">17:00</option>
                                            <option value='18' :selected=\"workingTimeToInt == 18\">18:00</option>
                                            <option value='19' :selected=\"workingTimeToInt == 19\">19:00</option>
                                            <option value='20' :selected=\"workingTimeToInt == 20\">20:00</option>
                                            <option value='21' :selected=\"workingTimeToInt == 21\">21:00</option>
                                            <option value='22' :selected=\"workingTimeToInt == 22\">22:00</option>
                                            <option value='23' :selected=\"workingTimeToInt == 23\">23:00</option>
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
                        <div class=\"lawyer-top\">
                            <div class=\"lawyer-img\">
                                <img :src=\"data.avatar_full_path\" alt=\"lawyer-img\">
                            </div>

                            <div class=\"lawyer-info\">
                                <h2 class=\"lawyer-name with_ico\">@{{ data.full_name }}</h2>
                                <span class=\"lawyer-check\" v-if=\"data.is_confirmed == 1\">Проверенный юрист</span>
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
                                <div class=\"lawyer-info_row\" v-if=\"data.phone_number\">
                                    <img class=\"icon\" src=\"/lawyers/images/icons/phone-icon-blue.svg\" alt=\"phone-icon\">
                                    <a name=\"phone_number\" class=\"span-link\">+@{{data.phone_number}}</a>
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
                                    <span class=\"bold\">@{{ data.working_days_interval + ' ' + data.work_time }}</span>
                                </div>
                                <div class='flexbox lawyer-info_buttons'>
                                    <a href=\"#employeeInfoEdit\" id=\"edit_more_modal\" data-fancybox class='main-btn main-btn_white with_ico edit_ico'><span>Редактировать</span></a>
                                    <a class='logout' href=\"\">Выход</a>
                                </div>
                                <div class='lawyer-info_balance'>
                                    <div class='lawyer-balance-block'>
                                        <p>Ваш баланс</p>
                                        <span class='balance-summ'><span class='balance-summ_ico'>₽</span> 0 руб</span>
                                    </div>
                                    <a href='#' class='main-btn'><span>Вывести</span></a>
                                </div>
                            </div>
                        </div>
                    </div></div>",
                            ]
                        ])

                    <div class='lawyer-block'>
                        <div class='lawyer-block_subscribe'>
                            <h2 class='lawyer-block_subscribe_title'>SOS Поддержка</h2>
                            <p class='lawyer-block_subscribe_days'>30 дней</p>
                            <span class='lawyer-block_subscribe_date'>22 окт 2023</span>
                            <a href='#' class='lawyer-block_subscribe_extension'>продлить</a>
                        </div>
                    </div>
                </div>


                                @include('component_build', [
                                    'component' => 'component.infoComponent.textInfo',
                                    'params_component' => [
                                        'autostart' => 'false',
                                        'name' => 'employee_info_more_edit',
                                        'globalData' => 'EmployeeInfo',
                                        'params' => [],

                                        'template' =>
                                "<div class='modal profile_modal' id='employeeInfoMoreEdit'>
                                    <h5 class='order-modal_title' id='exampleModalLongTitle'>Редактировать текст</h5>
                                    <div class='registration-form_label full'>
                                        <label class='label-title'>Тезис с лимитом символов</label>
                                        <input id='thesis_edit' type='text' name='thesis_edit' :value=\"data.thesis\"/>
                                    </div>
                                    <div class='registration-form_label full'>
                                        <label class='label-title'>Описание</label>
                                        <textarea id='about_edit' type='text' name='about_edit' :value=\"data.about\"/>
                                    </div>
                                    <div class='flex align-center form--submit'>
                                        <button @click.prevent=\"saveAboutInfo()\" type='button' id='save_info_more_edit' class='main-btn main-btn_blue'>Сохранить</button>
                                        <button type='button' class='main-btn main-btn_white' data-fancybox-close>Закрыть</button>
                                    </div>
                                </div>
                                    ",
                                    ]
                                ])



                    @include('component_build', [
                    'component' => 'component.infoComponent.textInfo',
                    'params_component' => [
                        'autostart' => 'false',
                        'name' => 'employee_add_cert',

                        'template' =>
                        "<div class='modal profile_modal' id='employeeAddCert'>
                            <h5 class='order-modal_title' id='exampleModalLongTitle'>Добавить документ или сертификат</h5>
                            <div class='registration-form_label full'>
                                <label for='cert_name'>Название</label>
                                <input type='text' placeholder='Название' name='cert_name' id='cert_name'>
                            </div>
                            <div class='registration-form_label full' name='container'>
                                <label class='label-title' name='container_name'>Выберите файлы</label>
                                <div class='form-row_files add-cert_btn' id='file_input' @click=\"clickInput($('#cert'))\">
                                    <input type='file' class='form-row_files' name='cert' id='cert'>
                                    <div data-img-container class='form-img-container'>
                                        <span data-delete class='delete-img'></span>
                                        <img id='preview' src='' alt='' width='100' height='100'/>
                                    </div>
                                    <span data-text class='load-file-text'>Загрузить файл</span>
                                </div>
                            </div>
                            <div class='flex align-center form--submit'>
                                <button type='button' id='add_cert' class='main-btn main-btn_blue' @click.prevent=\"addCertData()\">Добавить</button>
                                <button type='button' class='main-btn main-btn_white' data-fancybox-close>Отменить</button>
                            </div>
                        </div>
                        "
                    ]
                ])
                <div class="right">

                    <div class='modal profile_modal' id='employeeCertEdit'>
                        <h5 class='order-modal_title' id='exampleModalLongTitle'>Редактировать документ или сертификат</h5>
                        <input type='hidden' id='employee_cert_edit_id'>
                        <div class="registration-form_label full">
                            <label class='label-title'>Название</label>
                            <input id='employee_cert_edit_description' type='text' name='employee_cert_edit_description'>
                        </div>
                        <div class='img-edit' id='edit_cert_input'>
                            <img id="img_cert_src" src="" alt="cert">
                        </div>
                        <div class='flex align-center form--submit'>
                            <button type='button' id='update_employee_cert' class='main-btn main-btn_blue'>Сохранить</button>
                            <button type='button' class='main-btn main-btn_white' data-fancybox-close>Отменить</button>
                            <button type='button' id='delete_employee_cert' class='main-btn main-btn_red'>Удалить</button>
                        </div>
                    </div>

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
                            <h2 class='lawyer-name with_ico'>
                                @{{ data.full_name }}
                            </h2>
                            <a href='#employeeInfoMoreEdit' id='edit_more_modal' data-fancybox class='edit'></a>
                        </div>
                        <p class='lawyer-text_p lawyer-text_blue bold'>@{{ data.thesis }}</p>
                        <p class='lawyer-text_p'>
                            @{{data.about}}
                        </p>

                        <h2 class='lawyer-card_block-title'>Документы и сертификаты</h2> {{-- @{{data.achievements === null ? 0 : data.achievements.length}}  --}}
                        <div class='docs-slider_container js_slider_nav'>
                            <div class='docs-slider_item'>
                                <span data-src='#employeeAddCert' id='add_cert' data-fancybox class='docs-slider_item_add'><span>Добавить</span></span>
                                <p class='docs-slider_title'>Добавьте документ или сертификат</p>
                            </div>
                            <div class='docs-slider-content'>
                            <ul class='docs-slider js_docs-slider'>
                                <li class='docs-slider_item' v-for=\"item in data.achievements\">
                                    <a :href=\"item.path\" data-fancybox>
                                        <img :src=\"item.path\" alt='cert-img' width='130' height='130' />
                                        <span @click.prevent=\"setDataForEditModal(item, '#employeeCertEdit')\" class='docs-slider_item-edit' id='edit_cert_modal'></span>
                                    </a>
                                    <p class='docs-slider_title'>@{{ item.description }}</p>
                                </li>
                            </ul>
                            </div>
                        </div>
                    </div>"
                    ]
                ])


                    <div class='modal profile_modal' id='employeeAddService'>
                        <h5 class='order-modal_title' id='exampleModalLongTitle'>Добавить услугу</h5>
                        @include('component_build',["component" => "component.listComponent.selectComponent",
                            "params_component" => [
                            "autostart" => 'true',
                            "name" => 'service_type_id',
                            "default_title" => 'Категория услуги',
                            "url" => route("actionGetServiceTypeListForSelect_mainstay_service_servicemainstaycontroller"),
					        "callAfterloadComponent" => "function(component) {
                               $('.js_select').select2({
                               	minimumResultsForSearch: -1,
                               });
                               return component.option;
                            }",
                            "template" =>
                            '
                            <div class="registration-form_label full">
                                <label class="label-title">Категория услуги</label>
                                <select class="js_select" name="type_id" :id="name">
                                    <option></option>
                                    <option v-for="(items_ , index) in data " :data-text="items_" :value="index">@{{items_}}</option>
                                </select>
                            </div>
                            ',
                            "change" => "function(){
                                if($(this).val() !== '') {
                                    const param = {'type_id': $(this).find('option:selected').val()}
                                    page__.getElementsGroup('service_id')[0]['obj'].setUrlParams(param)
                                }
                            }"
                        ]])

                        @include('component_build',["component" => "component.listComponent.selectComponent",
                            "params_component" => [
                            "autostart" => 'false',
                            "name" => 'service_id',
                            "default_title" => 'Тема услуги',
                            "url" => route("actionGetServiceListForSelect_mainstay_service_servicemainstaycontroller"),
					        "callAfterloadComponent" => "function(component) {
                               $('.js_select').select2({
                               	minimumResultsForSearch: -1,
                               });
                               return component.option;
                            }",
                            "template" =>
                            '
                            <div class="registration-form_label full">
                                <label class="label-title">Тема услуги</label>
                                <select class="js_select" name="add_service_id" :id="name">
                                    <option v-for="(items_ , index) in data " :data-text="items_" :value="index">@{{items_}}</option>
                                </select>
                            </div>
                            '
                        ]])

                        @include('component_build', [
                            'component' => 'component.infoComponent.textInfo',
                            'params_component' => [
                                'autostart' => 'false',
                                'name' => 'employee_info_more_edit',
                            "callAfterloadComponent" => "function(component) {
                               filterText();
                               return component.option;
                            }",
                                'template' =>
                        "<div>
                        <div class='registration-form_label full'>
                            <label class='label-title'>Название</label>
                            <input id='employee_service_title' type='text' placeholder='Название' name='employee_service_title'>
                        </div>
                        <div class='registration-form_label full'>
                            <label class='label-title'>Краткое описание</label>
                            <textarea id='employee_service_description' placeholder='Краткое описание' name='employee_service_description'></textarea>
                        </div>
                        <div class='registration-form_label full'>
                            <label class='label-title'>Цена</label>
                            <input id='employee_service_price' class='js_number_filter' type='text' name='employee_service_price'>
                        </div>
                        </div>
                        ",
                            ]
                        ])

                        <div class='flex align-center form--submit'>
                            <button type='button' id="store_employee_service" class='main-btn main-btn_blue'>Сохранить</button>
                            <button type='button' class='main-btn main-btn_white' data-fancybox-close>Закрыть</button>
                        </div>
                    </div>

                    <div class="lawyer-services lawyer-wrapper">
                        <h2 class="lawyer-wrapper_title _line-blue">Услуги юриста</h2>
                        <a href="#employeeAddService" data-fancybox class="add-services-btn"><span>Добавить</span></a>

                        <div class='modal profile_modal' id='employeeServiceEdit'>
                            <h5 class='order-modal_title' id='exampleModalLongTitle'>Редактировать</h5>
                            <input type='hidden' id='employee_service_edit_type_id'>
                            <input type='hidden' id='employee_service_edit_service_id'>
                            <input type='hidden' id='employee_service_edit_id'>
                            <div class="registration-form_label full">
                                <label class='label-title'>Название</label>
                                <input id='employee_service_edit_title' type='text' name='employee_service_edit_title'>
                            </div>
                            <div class="registration-form_label full">
                                <label class='label-title'>Краткое описание</label>
                                <input id='employee_service_edit_description' type='text' name='employee_service_edit_description'>
                            </div>
                            <div class="registration-form_label full">
                                <label class='label-title'>Цена</label>
                                <input id='employee_service_edit_price' type='text' class='js_number_filter' name='employee_service_edit_price'>
                            </div>
                            <div class='flex align-center form--submit'>
                                <button type='button' id='update_employee_service' class='main-btn main-btn_blue'>Сохранить</button>
                                <button type='button' class='main-btn main-btn_white' data-fancybox-close>Закрыть</button>
                                <button type='button' id="delete_employee_service" class='main-btn main-btn_red'>Удалить</button>
                            </div>
                        </div>

                        @include('component_build', [
                            'component' => 'component.gridComponent.simpleGrid',
                            'params_component' => [
                                'autostart' => 'true',
                                'name' => 'service_list',
                                'url' => route__('actionGetServices_mainstay_employee_employeemainstaycontroller'),
								'params' => ['user_id' => auth()->id()],

                                'template' => "
                                <div :id=\"name + '_body'\">

                                    <div v-for=\"item in data\">
                                        <ul class='lawyer-services_block'>
                                            <li class='lawyer-service_line' style='justify-content: space-between;'>
                                                <div class='lawyer-service_left'>
                                                    <div class='lawyer-service_title'>@{{ item.title }}</div>
                                                    <p class='lawyer-service_text'>
                                                        @{{ item.description }}
                                                        <!-- <button class='lawyer-service_red-more'>
                                                            ЧИТАТЬ ЕЩЕ
                                                        </button> -->
                                                    </p>
                                                </div>

                                                <a id='edit_employee_service' @click.prevent=\"setDataForEditModal(item, '#employeeServiceEdit')\" class='edit'></a>
                                                <div class='lawyer-service_price'>
                                                    <span>@{{ item.price }} &#8381;</span>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                ",

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

                    </div>

                    <div class='modal profile_modal' id='employeeSpecializationEdit'>
                        <h2 class="order-modal_title">Редактировать</h2>
                        <a href="#" class="specializations-clear">Очистить</a>
                        @include('component_build', [
                                'component' => 'component.gridComponent.simpleGrid',
                                'params_component' => [
                                    'autostart' => 'true',
                                    'name' => 'employee_service_list',
                                    'url' => route__("actionGetServiceList_mainstay_service_servicemainstaycontroller"),
									'callBeforloadComponent' => "function(component) {
                                        let employeeSpecialization = page__.getGolobalData('EmployeeInfo').specialization ?? ''
                                        component.option['employeeSpecialization'] = employeeSpecialization
                                        if(employeeSpecialization) {
                                            component.data.sort((item1, item2) => {
                                                if (employeeSpecialization.includes(item1.id) && employeeSpecialization.includes(item2.id)) {
                                                    return 0
                                                }
                                                if (employeeSpecialization.includes(item1.id)) {
                                                    return -1
                                                }
                                                if (employeeSpecialization.includes(item2.id)) {
                                                    return 1
                                                }
                                            })
                                        }
									    return component.option
									}",

                                    'template' => "
                                    <div name=\"lawyer_services\" :id=\"name + '_body'\">
                                        <div class='specializations-btn_container'>
                                            <div class='specializations-btn' v-if=\"data\" v-for=\"item in data\">
                                                <button class='specializations-add' name='add' v-if=\"!employeeSpecialization.includes(item.id)\" @click.prevent=\"addSpecialization(item.id)\">@{{item.name}}</button>
                                                <button class='specializations-delete' name='delete' v-if=\"employeeSpecialization.includes(item.id)\" @click.prevent=\"deleteSpecialization(item.id)\">@{{item.name}}</button>
                                            </div>
                                        </div>
                                    </div>",
                                ]
                        ])



                        <div class='flex align-center form--submit'>
                            <button type='button' id='save_specialization' class='main-btn main-btn_blue'>Сохранить</button>
                            <button type='button' class='main-btn main-btn_white' data-fancybox-close>Отменить</button>
                        </div>
                    </div>

                    <div class="lawyer-all-services lawyer-wrapper">
                        <h2 class="lawyer-wrapper_title _line-blue">Специализация</h2>
                        <a href="#employeeSpecializationEdit" id="edit_specialization_modal" data-fancybox class='edit'></a>
                        @include('component_build', [
                            'component' => 'component.gridComponent.simpleGrid',
                            'params_component' => [
                                'autostart' => 'true',
                                'name' => 'employee_specialization',
                                'url' => route__("actionGetSpecialization_mainstay_employee_employeemainstaycontroller"),
								'params' => ['user_id' => auth()->id()],

                                'template' => "
                                <ul name=\"lawyer_services\" :id=\"name + '_body'\">
                                    <div v-if=\"data\" v-for=\"item in data\">
                                        <li>@{{item.name}}</li>
                                    </div>
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

{{--                    <div class="lawyer-practice lawyer-wrapper">--}}
{{--                        <h2 class="lawyer-wrapper_title _line-blue">Судебная практика</h2>--}}
{{--                        <ul class="lawyer-practice_ul">--}}
{{--                            <li>--}}
{{--                                <h3 class="lawyer-practic_title">Прекращение уголовного дела по ч. 3 ст. 159 УК РФ</h3>--}}
{{--                                <span class="lawyer-practic_number">Дело №01-038/2019</span>--}}
{{--                                <img class="icon" src="/lawyers/images/icons/arrow-icon-gray.svg" alt="arrow-icon">--}}
{{--                            </li>--}}
{{--                        </ul>--}}

{{--                        <button class="more-services">Еще 2 услуги</button>--}}
{{--                    </div>--}}

                    <div class="lawyer-comments lawyer-wrapper">
                        <h2 class="lawyer-wrapper_title _line-blue">Отзывы</h2>

                        <div class="lawyer-comment_block">
                            <div class="block specialist-comment">
                                <div class="comment-rate">
                                    <div class="stars"><span style="width: 80%;"></span></div>
                                    <p class="name">Алексеева Юлия</p>
                                    <span class="date">16.05.2023</span>
                                    <img class="check-icon icon" src="/lawyers/images/icons/check-icon-green-transparent.svg" alt="check-icon" >
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
                        <div class="map-container">
                        <div class="lawyer-contacts_map" id="map"></div>

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
                            <div class=\"lawyer-info_row\" v-if=\"data.phone_number\">
                                <img class=\"icon\" src=\"/lawyers/images/icons/phone-icon-blue.svg\" alt=\"phone-icon\">
                                <a name=\"phone_number\" class=\"span-link\">+@{{data.phone_number}}</a>
                            </div>
                            <div class=\"lawyer-info_row\" v-if=\"data.site_url\">
                                <img class=\"icon\" src=\"/lawyers/images/icons/planet-icon-blue.svg\" alt=\"planet-icon\">
                                <a :href=\"data.site_url\" class=\"span-link\">@{{ data.site_url }}</a>
                            </div>
                            <div class=\"lawyer-info_row\">
                                <img class=\"icon\" src=\"/lawyers/images/icons/message-icon-blue.svg\" alt=\"message-icon\">
                                <span>Консультации онлайн:</span>
                                <span class=\"lawyer-info_span-black\">Да</span>
                            </div>
                            <div class=\"lawyer-info_row\">
                                <img class=\"icon\" src=\"/lawyers/images/icons/clock-icon-blue.svg\" alt=\"clock-icon\">
                                <span>Время работы:</span>
                                <span class=\"lawyer-info_span-black\">@{{ data.working_days_interval + ' ' + data.work_time }}</span>
                            </div>
                        </div>",
                            ]
                        ])
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
    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=de2b1fff-b759-4fe3-8bc3-e496e0eb9b13" type="text/javascript"></script>
    <script>
        $(document).ready(function () {
            setEdit()
            updateData()
            updateService()
            updateCert()
            deleteData()
            filterText()
            storeSpecialization()
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
            myMap.events.add('click', function (e) {
                var coords = e.get('coords');

                // Если метка уже создана – просто передвигаем ее.
                if (myPlacemark) {
                    myPlacemark.geometry.setCoordinates(coords);
                }
                // Если нет – создаем.
                else {
                    myPlacemark = createPlacemark(coords);
                    myMap.geoObjects.add(myPlacemark);
                    // Слушаем событие окончания перетаскивания на метке.
                    myPlacemark.events.add('dragend', function () {
                        getAddress(myPlacemark.geometry.getCoordinates());
                    });
                }
                getAddress(coords);
            });


            function createPlacemark(coords) {
                return new ymaps.Placemark(coords, {
                    iconCaption: 'поиск...'
                }, {
                    preset: 'islands#violetDotIconWithCaption',
                    draggable: true
                });
            }

            function getAddress(coords) {
                myPlacemark.properties.set('iconCaption', 'поиск...');
                ymaps.geocode(coords).then(function (res) {
                    var firstGeoObject = res.geoObjects.get(0);
                    page__.sendData('{{ route__('actionUpdateLocation_mainstay_employee_employeemainstaycontroller') }}',
                        {
                            location_coordinates: coords,
                            location_address: firstGeoObject.getAddressLine(),
                            id: {{ auth()->id() }}
                        },
                    function(response) {
                        console.log(response)
                    })
                    myPlacemark.properties
                        .set({
                            // Формируем строку с данными об объекте.
                            iconCaption: [
                                // Название населенного пункта или вышестоящее административно-территориальное образование.
                                firstGeoObject.getLocalities().length ? firstGeoObject.getLocalities() : firstGeoObject.getAdministrativeAreas(),
                                // Получаем путь до топонима, если метод вернул null, запрашиваем наименование здания.
                                firstGeoObject.getThoroughfare() || firstGeoObject.getPremise()
                            ].filter(Boolean).join(', '),
                            // В качестве контента балуна задаем строку с адресом объекта.
                            balloonContent: firstGeoObject.getAddressLine()
                        });
                });
            }
        }

        function getSliderSettings() {
            let slidesLength = $('.js_docs-slider').find('li').length;
            return {
                slidesToShow: 5,
                infinite: false,
                dots: false,
                arrows: slidesLength > 5 ? true : false,
                appendArrows: '.js_slider_nav',
                prevArrow: '<button type="button" class="slick-prev"><svg width="14" height="11" viewBox="0 0 14 11" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 5.26758H13.25" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M13.25 5.26758L9.25 1.26758" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M13.25 5.26758L9.25 9.26758" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></button>',
                nextArrow: '<button type="button" class="slick-next"><svg width="14" height="11" viewBox="0 0 14 11" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 5.26758H13.25" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M13.25 5.26758L9.25 1.26758" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M13.25 5.26758L9.25 9.26758" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></button>',
                responsive: [{
                    breakpoint: 1281,
                    settings: {
                        slidesToShow: 6,
                        arrows: slidesLength > 6 ? true : false,
                    }
                },{
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 4,
                        arrows: slidesLength > 4 ? true : false,
                    }
                },{
                    breakpoint: 769,
                    settings: {
                        slidesToShow: 4,
                        arrows: false,
                    }
                },{
                    breakpoint: 665,
                    settings: {
                        slidesToShow: 3,
                        arrows: false,
                    }
                    },{
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 2,
                        arrows: false,
                    }
                }],
            }
        }

        function docSlider() {
            $('.js_docs-slider').not('.slick-initialized').slick(getSliderSettings());
        }

        function refreshSlider() {
            $('.js_docs-slider').slick('unslick')
            setTimeout(function() {
                $('.js_docs-slider').slick(getSliderSettings())
            }, 100)
        }

        function filterText(){
            $('.js_number_filter').on('keypress', function() {
                if ( event.which != 0 && event.which != 8 ) {
                    if( event.which < 48 || event.which > 57 ) {event.preventDefault();}
                }
            });
        }
        function getMainDataForUpdate() {
            return {
                'first_name': $('[name = first_name_edit]').val(),
                'last_name': $('[name = last_name_edit]').val(),
                'middle_name': $('[name = middle_name_edit]').val(),
                'phone_number': $('[name = phone_number_edit]').val(),
                'site_url': $('[name = site_url_edit]').val(),
                'region_id': $('[name = region_id]').val(),
                'city_id': $('[name = city_id]').val(),
                'working_days': getWorkingDaysArray(),
                'time_from': getWorkingDaysArray() !== 0 ? $('#from_time').val() : null,
                'time_to': getWorkingDaysArray() !== 0 ? $('#to_time').val() : null
            }
        }

        function getMoreDataForUpdate() {
            return {
                'thesis': $('#thesis_edit').val(),
                'about': $('#about_edit').val(),
            }
        }

        function addCertData() {
            let formData = new FormData()
            formData.append('cert_description', $('#cert_name').val())
            $.each($('#cert')[0].files, function (key, input) {
                formData.append('cert_file', input)
            })
            $.ajax({
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                url: '{{ route__('actionUpdateEmployeeInfo_mainstay_employee_employeemainstaycontroller') }}',
                success: function (response) {
                    if (response) {
                        $.fancybox.close()
                        updateGlobalData(response)
                        refreshSlider()
                    }
                }
            })

        }

        function setDataForEditModal(data, modalId) {
            $.fancybox.open({
                src: modalId,
                type: 'inline',
            })
            if (modalId == '#employeeServiceEdit') {
                $('#employee_service_edit_description').val(data.description)
                $('#employee_service_edit_id').val(data.id)
                $('#employee_service_edit_price').val(data.price)
                $('#employee_service_edit_service_id').val(data.service_id)
                $('#employee_service_edit_type_id').val(data.type_id)
                $('#employee_service_edit_title').val(data.title)
                $('#delete_employee_service').attr('delete-id', data.id)
            }
            if (modalId == '#employeeCertEdit') {
                $('#employee_cert_edit_id').val(data.id)
                $('#employee_cert_edit_description').val(data.description)
                $('#img_cert_src').attr('src', data.path)
                $('#delete_employee_cert').attr('delete-id', data.id)
            }

        }

        function getServiceForUpdate() {
            return {
                'description': $('#employee_service_edit_description').val(),
                'id': $('#employee_service_edit_id').val(),
                'price': $('#employee_service_edit_price').val(),
                'service_id': $('#employee_service_edit_service_id').val(),
                'type_id': $('#employee_service_edit_type_id').val(),
                'user_id': {{ auth()->id() }},
                'title': $('#employee_service_edit_title').val(),
            }
        }

        function getNewService() {
            return {
                'description': $('#employee_service_description').val(),
                'price': $('#employee_service_price').val(),
                'service_id': $('[name = add_service_id]').val(),
                'type_id': $('[name = type_id]').val(),
                'user_id': {{ auth()->id() }},
                'title': $('#employee_service_title').val(),
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

            $('#store_employee_service').on('click', function () {
                const data = getNewService()
                page__.sendData('{{ route__('actionEmployeeServiceStore_mainstay_employee_employeemainstaycontroller') }}',
                    data, function (data) {
                        $.fancybox.close()
                        page__.getElementsGroup('service_list')[0]['obj'].setUrlParams({'user_id': {{auth()->id()}} })
                    })
            })
        }

        function saveAboutInfo() {
            const data = getMoreDataForUpdate()
            sendData(data)
        }

        function updateService() {
            $('#update_employee_service').click(function () {
                const data = getServiceForUpdate()
                page__.sendData('{{ route__('actionEmployeeServiceStore_mainstay_employee_employeemainstaycontroller') }}',
                    data, function (data) {
                        $.fancybox.close()
                        page__.getElementsGroup('service_list')[0]['obj'].setUrlParams({'user_id': {{auth()->id()}} })
                    })
            })

        }
        function updateCert() {
            $('#update_employee_cert').click(function () {
                const data = {
                    cert_id: $('#employee_cert_edit_id').val(),
                    cert_description: $('#employee_cert_edit_description').val(),
                    cert_path: $('#img_cert_src').attr('src')
                }
                page__.sendData('{{ route__('actionUpdateEmployeeCertificates_mainstay_employee_employeemainstaycontroller') }}',
                    data, function (data) {
                        $.fancybox.close()
                        updateGlobalData(data)
                        refreshSlider()
                        page__.getElementsGroup('employee_info_more')[1]['obj'].updateVue()
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
                }

            })
        }

        function deleteData() {
            $(document).on('click', '#delete_employee_service', function () {
                page__.sendData('{{ route__('actionDeleteService_mainstay_employee_employeemainstaycontroller') }}', {
                    'id': $(this).attr('delete-id'),
                    'user_id': '{{ auth()->id() }}'
                }, function (data) {
                    $.fancybox.close()
                    page__.getElementsGroup('service_list')[0]['obj'].setUrlParams({'user_id': {{auth()->id()}} })
                })
            })
            $(document).on('click', '#delete_employee_cert', function () {
                page__.sendData('{{ route__('actionDeleteImage_mainstay_employee_employeemainstaycontroller') }}', {
                    'achievement_id': $(this).attr('delete-id'),
                    'user_id': '{{ auth()->id() }}'
                }, function (data) {
                    $.fancybox.close()
                    updateGlobalData(data)
                    refreshSlider()
                })
            })
        }

        function intersect(o1, o2){
            return Object.keys(o1).filter(k => Object.hasOwn(o2, k))
        }

        function setEdit() {
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

        function updateGlobalData(data) {
            Object.assign(page__.getGolobalData('EmployeeInfo'), data['result'])
        }

        function getWorkingDaysArray() {
            if ($('#select_worktime').prop('checked')) {
                return $('input[name=day_of_week]:checked').map(function () {
                    return $(this).val();
                }).get();
            }
            // Если выставлено 'круглосуточно', возвращаем 0
            return 0
        }

        function updateModals() {
            page__.getElementsGroup('employee_info_worktime_edit')[0]['obj'].startWidget()
        }

        function clickInput(element) {
            element.click()
        }

        function addSpecialization(id) {
            let specialicationArray = page__.getGolobalData('EmployeeInfo').specialization ?? []
            specialicationArray.push(id)
            page__.getGolobalData('EmployeeInfo').specialization = specialicationArray
            page__.getElementsGroup('employee_service_list')[0]['obj'].startWidget()

        }

        function deleteSpecialization(id) {
            let specialicationArray = page__.getGolobalData('EmployeeInfo').specialization
            page__.getGolobalData('EmployeeInfo').specialization = specialicationArray.filter(item => item !== id)
            console.log(page__.getGolobalData('EmployeeInfo').specialization)
            page__.getElementsGroup('employee_service_list')[0]['obj'].startWidget()
        }

        function storeSpecialization() {
            $('#save_specialization').click(function () {
                $.ajax({
                    method: 'POST',
                    data: {
                        user_id: {{ auth()->id() }},
                        service_ids: page__.getGolobalData('EmployeeInfo').specialization
                    },
                    url: '{{ route__('actionUpdateSpecialization_mainstay_employee_employeemainstaycontroller') }}',
                    success: function (response) {
                        $.fancybox.close()
                        page__.getElementsGroup('employee_specialization')[0]['obj'].setUrlParams({user_id: {{ auth()->id() }} })
                        updateGlobalData(response)
                    }

                })
            })

        }

    </script>
@endsection
