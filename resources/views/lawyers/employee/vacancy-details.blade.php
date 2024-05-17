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

                            </ul>

                            <ul class='exchange_other-info'>
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

                @include('component_build',[
                'component' => 'component.infoComponent.textInfo',
                'params_component' => [
                'name' => "vacancy_files",
                'autostart' => 'true',
                'url' => route__("actionGetFilesList_mainstay_file_filemainstaycontroller"),
                'params' => ['path_start' => 'vacancy/' . request()->route('vacancy_id'), 'type' => '1'],
                'template' =>
                "<ul class='files_list'>
                    <li v-for=\"item in data\">
                        <a @click=\"viewFile(item.path, item.name)\">@{{item.name}}</a>
                    </li>
                </ul>"
                ]
                ])



                <div class="order-answers mobile-hidden">
                    <span>11 сообщений</span>
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

                        @include('component_build', [
                'component' => 'component.infoComponent.textInfo',
                'params_component' => [
                'autostart' => 'true',
                'name' => 'vacancy_closing_info',
                'url' => route__('actionGetClosingMessage_mainstay_vacancy_vacancymainstaycontroller'),
                'params' => ['id' => request()->route('vacancy_id')],

                'template' => "
                <div class='order-row comment'>
                    <img src='/lawyers/images/main/lawyer-avatar.png' alt='avatar-img'>
                    <div class='order-history_right commentator'>
                        <h4> @{{ data.executor_name }}
                            <img src='/lawyers/images/icons/chat-verify.svg' alt='verify-icon'>
                            <time>@{{ data.time }}</time>
                        </h4>
                        <p>
                            <span>@{{ data.text }}</span>
                        </p>
                        <ul class='files_list'>
                            <li v-for=\"item in data.files\">
                                <a @click=\"viewFile(item.path, item.name)\">@{{item.name}}</a>
                            </li>
                </ul>
                    </div>
                </div>
                "
                ]
                ])

                    </div>
                </div>{{-- END order-answers --}}
{{--                <div class="order-history mobile-hidden">--}}
{{--                    @include('component_build', [--}}
{{--                'component' => 'component.infoComponent.textInfo',--}}
{{--                'params_component' => [--}}
{{--                'autostart' => 'true',--}}
{{--                'name' => 'vacancy_history',--}}
{{--                'globalData' => 'VacancyInfo',--}}
{{--                'callBeforloadComponent' => "function (component) {--}}
{{--                        let history = page__.getGolobalData('VacancyInfo').status_history--}}
{{--                        let groupHistory = history.reduce((acc, obj) => {--}}
{{--                                let key = obj.date;--}}
{{--                                if (!acc[key]) {--}}
{{--                                  acc[key] = [];--}}
{{--                                }--}}
{{--                                acc[key].push(obj);--}}
{{--                                return acc;--}}
{{--                                }, {})--}}

{{--                        component.option['groupHistoryByDate'] = groupHistory--}}
{{--                        return component.option--}}
{{--                    }",--}}
{{--                'template' => "--}}
{{--                <div>--}}
{{--                    <div v-for=\"(historyForDate, date) in groupHistoryByDate\" :key=\"date\" class='order-history-block'>--}}
{{--                        <time>@{{ date }}</time>--}}
{{--                        <div v-for=\"item in historyForDate\" :key=\"item.id\" class='order-history_row order-row'>--}}
{{--                            <img src='/lawyers/images/icons/order-created-icon.svg' alt='order-created-icon'>--}}
{{--                            <div class='order-history_right'>--}}
{{--                                <h4>Заказ @{{ item.status }}--}}
{{--                                    <time>@{{ item.time }}</time>--}}
{{--                                </h4>--}}
{{--                                <p name='additional-info-place' v-html=\"setAdditionalInfoForHistory(item.status)\">--}}

{{--                                </p>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                "--}}
{{--                ]--}}
{{--                ])--}}

{{--                </div>--}}





                <form action="#" class="send-message-input mobile-hidden">
                    <label>
                        <span class="attach-icon"></span>
                        <input type="text" placeholder="Введите сообщение..." name="message-text">
                        <input type="image" src="/lawyers/images/icons/send-icon.svg" alt="send-message-icon">
                    </label>
                </form>
                @include('component_build', [
                'component' => 'component.infoComponent.textInfo',
                'params_component' => [
                'autostart' => 'true',
                'name' => 'action_block',
                'globalData' => 'VacancyInfo',
                'callBeforloadComponent' => "function(component) {
                    let globalData = page__.getGolobalData('VacancyInfo')
                    let statusData = globalData.status_history
                    statusData = statusData.sort((a, b) => a.id > b.id ? 1 : -1)
                    component.option['currentStatus'] = statusData[statusData.length - 1].status
                    component.option['currentStatusCode'] = statusData[statusData.length - 1].status_code
                    component.option['statusData'] = statusData
                    component.option['daysToEnd'] = globalData['days_to_end']
                    component.option['hoursToAccept'] = globalData['time_left_to_accept']
                    return component.option
                }",

                'template' => "
                <div class='order-status'>
                    <div class='order-status-buttons'>
                        <button v-if=\"currentStatusCode == 4\" class='order-status-btn ico_support'>Тех.поддержка</button>
                        <a v-if=\"currentStatusCode == 4\" href='#modal_info' data-fancybox class='order-status-btn ico_done'>Заказ выполнен</a>
                        <p v-if=\"currentStatusCode == 5\" class='noactive'>Заказ отправлен на проверку</p>
                        <p v-if=\"currentStatusCode == 7\" class='noactive'>Заказ завершен</p>
                        {{-- Тест --}}
                        <button v-if=\"currentStatusCode == 8\" class='order-status-btn ico_done' @click=\"acceptToWork(data.id)\">Принять заказ</button>
                        <button v-if=\"currentStatusCode == 8\" class='order-status-btn ico_delete' @click=\"declineToWork(data.id)\">Отменить заказ</button>

                    </div>
                    <div class='order-status_message'>
                        <p v-if=\"currentStatusCode == 4\" class='noactive'>До конца проекта осталось @{{ daysToEnd }} дней</p>
                        <p v-if=\"currentStatusCode == 8\">На принятие проекта осталось @{{ hoursToAccept }} часов</p>
                        <p v-if=\"currentStatusCode == 5\" class='noactive'>До конца проекта осталось @{{ daysToEnd }} дня</p>
                        <p v-if=\"currentStatusCode == 5\">На принятие проекта осталось @{{ hoursToAccept }} часов</p>
                    </div>
                </div>
                "
                ]
                ])
            </div>

            <div class="modal order-modal" id="modal_info">
                <h5 class="order-modal_title">Укажите информацию</h5>
                <span data-error mark="text" style="color: red"></span>
                <div class="order-modal-content">
                    <textarea placeholder="Текст" name="order_closing_text"></textarea>
                    <div class="registration-form_label full">
                        <label class="label-title">Выберите файлы</label>
                        <span data-error mark="files" style="color: red"></span>

                        <div class="form-row_files" id="file_input">
                            <ul class="attached-files">

                            </ul>
                            <input type="file" class="form-row_files" name="files[]" id="files" multiple>
                            <span>
                                <img src="/lawyers/images/icons/folder-icon.svg" alt="folder-icon">
                                <div>Выберите файлы</div>
                            </span>
                        </div>
                    </div>
                </div>
                <div class='flex align-center form--submit'>
                    <button type='button' id="send" class='main-btn main-btn_blue'>Отправить</button>
                    <button type='button' class='main-btn main-btn_white' data-fancybox-close >Отменить</button>
                </div>
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
                    statusData = statusData.sort((a, b) => a.id > b.id ? 1 : -1)
                    this.option['currentStatus'] = statusData[statusData.length - 1].status
                    this.option['currentStatusCode'] = statusData[statusData.length - 1].status_code
                    this.option['statusData'] = statusData
                    console.log(this.option['currentStatusCode'])
                    return this.option
                }",

                'template' => "
                <div class='order-process'>
                    <div class='order-process-lawyer'>
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
                        <li :class=\"currentStatusCode >= 4 && currentStatusCode != 8 ? '_check' : '_inProgress'\"><span></span>Взят в работу</li>
                        <li :class=\"currentStatusCode >= 5 && currentStatusCode != 8 ? '_check' : '_inProgress'\"><span></span>Сдан на проверку</li>
                        <li :class=\"currentStatusCode >= 6 && currentStatusCode != 8 ? '_check' : '_inProgress'\"><span></span>Заказ принят</li>
                    </ul>
                </div>
                "
                ]
                ])

                <div class="top-lawyers mobile-hidden">
                    <h2 class="top-lawyers_header">
                        ТОП-юристов по консультациям
                    </h2>
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
                                <button class="main-btn main-btn_white">Сообщение</button>
                                <button class="main-btn main-btn_blue">Предложить работу</button>
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
                                <button class="main-btn main-btn_white">Сообщение</button>
                                <button class="main-btn main-btn_blue">Предложить работу</button>
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
                                <button class="main-btn main-btn_white">Сообщение</button>
                                <button class="main-btn main-btn_blue">Предложить работу</button>
                            </div>
                        </div>
                    </div>
                </div>
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
                @include('component_build', [
                'component' => 'component.infoComponent.textInfo',
                'params_component' => [
                'autostart' => 'true',
                'name' => 'action_block',
                'globalData' => 'VacancyInfo',
                'callBeforloadComponent' => "function() {
                    let globalData = page__.getGolobalData('VacancyInfo')
                    let statusData = globalData.status_history
                    statusData = statusData.sort((a, b) => a.id > b.id ? 1 : -1)
                    this.option['currentStatus'] = statusData[statusData.length - 1].status
                    this.option['currentStatusCode'] = statusData[statusData.length - 1].status_code
                    this.option['statusData'] = statusData
                    return this.option
                }",

                'template' => "
                <div class='order-status mobile'>
                    <div class='order-status-buttons'>
                        <button v-if=\"currentStatusCode == 4\" class='order-status-btn ico_done'>Тех.поддержка</button>
                        <button v-if=\"currentStatusCode == 5\" class='order-status-btn ico_support'>Отправить заказ <wbr />на доработку</button>
                        <button v-if=\"currentStatusCode == 5\" class='order-status-btn ico_support'>Тех.поддержка</button>
                        <button v-if=\"currentStatusCode == 5\" class='order-status-btn ico_done'>Заказ выполнен</button>
                        <p v-if=\"currentStatusCode == 7\" class='noactive'>Заказ завершен</p>
                        {{-- Тест --}}
                        <button v-if=\"currentStatusCode == 8\" class='order-status-btn ico_done'>Заказ выполнен</button>
                        <button v-if=\"currentStatusCode == 8\" class='order-status-btn ico_support'>Тех.поддержка</button>
                        <button v-if=\"currentStatusCode == 8\" class='order-status-btn ico_delete noactive'>Отменить заказ</button>

                    </div>
                    <div class='order-status_message'>
                        <p v-if=\"currentStatusCode == 8\">Ожидает принятия исполнителем...</p>
                        <p v-if=\"currentStatusCode == 8\">На принятие проекта осталось 20 часов</p>
                        <p v-if=\"currentStatusCode == 4\">До конца проекта осталось 33 дня</p>
                        <p v-if=\"currentStatusCode == 5\">На принятие проекта осталось 20 часов</p>
                        <p v-if=\"currentStatusCode == 5\">До конца проекта осталось 33 дня</p>
                    </div>
                </div>
                "
                ]
                ])
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
                                <button class="main-btn main-btn_white">Сообщение</button>
                                <button class="main-btn main-btn_blue">Предложить работу</button>
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
                                <button class="main-btn main-btn_white">Сообщение</button>
                                <button class="main-btn main-btn_blue">Предложить работу</button>
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
                                <button class="main-btn main-btn_white">Сообщение</button>
                                <button class="main-btn main-btn_blue">Предложить работу</button>
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
        $('#file_input').click(function () {
            $('#files')[0].click()
        })
        $('#files').change(function () {
            showFilesInfo()
        })
        sendToInspection()
    })

    function acceptToWork(vacancyId) {
        $.ajax({
            method: 'POST',
            data: {
                vacancy_id: vacancyId,
                employee_user_id: {{ auth()->id() }},
            },
            url: '{{ route__('actionAcceptWork_mainstay_employee_employeemainstaycontroller') }}',
            success: function (response) {
                location.reload();
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
                location.href = `{{ route__('actionViewOrders_controllers_employee_employeecontroller') }}`;
            }
        })
    }

    function getDataForSend() {
        let formData = new FormData()
        formData.append('text', $('[name=order_closing_text]').val())
        $.each($('#files')[0].files, function (key, input) {
            formData.append('files[]', input)
        })
        formData.append('vacancy_id', {{ request()->route('vacancy_id') }})
        return formData
    }

    function sendToInspection() {
        $('#send').click(function () {
            $.ajax({
                method: 'POST',
                data: getDataForSend(),
                contentType: false,
                processData: false,
                url: '{{ route__('actionSendWorkToInspection_mainstay_employee_employeemainstaycontroller') }}',
                success: function (response) {
                    if (response) {
                        location.reload()
                    }
                },
                error: function (error) {
                    if (error.status == 422) {
                        let messages = error.responseJSON.errors
                        for (let elem in messages) {
                            $('[mark = ' + elem.replace(/\.[0-9]+/, '') + '][data-error]').text(messages[elem][0])
                        }
                    }
                }
            })
        })

    }

    function showFilesInfo() {
        $('ul.attached-files > li').remove()
        $('ul.attached-files > div').remove()
        let names = [];
        for(var i = 0; i < $("#files")[0].files.length; i++){
            names.push($("#files")[0].files.item(i).name);
        }
        names.forEach((name) => {
            $('div.order-modal-content > div > div > span > img').prop('hidden', true)
            $('div.order-modal-content > div > div > span > div').prop('hidden', true)
            $('.attached-files')
                .append('<li>' +
                    '<img id="preview" src="/lawyers/images/main/doc.png" alt="doc-type">' +
                    // '<span class="delete-img"></span>' +
                    '<p class="upload_file_name">' + cutFileName(name) + '</p>'  + '</li>'
                )
        })
    }

    function cutFileName(name) {
        if(name.length > 12) {
            return name.substring(0, 5) + '...' + name.slice(-7)
        }
        return name
    }

    function viewFile(path, name) {
        const route = `{{ route('download') }}?path=${path}&name=${name}`
        window.open(route)
    }

</script>
@endsection


