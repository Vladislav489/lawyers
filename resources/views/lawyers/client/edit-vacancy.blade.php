@extends('lawyers.layouts.main')

@include('component_build',[
	'component' => 'component.loadComponent.loadGlobalData',
        'params_component' => [
        'name' => "VacancyInfo",
        'autostart' => 'false',
        'ssr' => 'true',
        'url' => route__("actionGetVacancy_mainstay_vacancy_vacancymainstaycontroller"),
        'params' => ['user_id' => auth()->id(), 'id' => request()->route('vacancy_id')],
    ]
])

@include('component_build',[
	'component' => 'component.loadComponent.loadGlobalData',
        'params_component' => [
        'name' => "VacancyFiles",
        'autostart' => 'false',
        'ssr' => 'true',
        'url' => route__("actionGetFilesList_mainstay_file_filemainstaycontroller"),
        'params' => ['path_start' => 'vacancy/' . request()->route('vacancy_id')],
    ]
])

@section('title', 'Моя заявка')

@section('content')
    <section class="request-section u-container">
        <div class="container">
            <div class="request-form">
                <div class="form-rows">

{{--                    @include('component_build', [--}}
{{--                        'component' => 'component.infoComponent.textInfo',--}}
{{--                        'params_component' => [--}}
{{--                            'autostart' => 'false',--}}
{{--                            'ssr' => 'true',--}}
{{--                            'name' => 'servicesForRadios',--}}
{{--                            'url' => route__('actionGetServiceTypeList_mainstay_service_servicemainstaycontroller'),--}}
{{--                            "callBeforloadComponent" => "function(component) {--}}
{{--                                            component.option['type_id'] = page__.getGolobalData('VacancyInfo').service_type_id--}}
{{--                                            return component.option--}}
{{--                                        }",--}}
{{--                            'callAfterloadComponent' => "function() {--}}
{{--                                    $('[name=request-type]').change(function() {--}}
{{--                                            if($(this).val() != undefined && $(this).val() != null) {--}}
{{--                                            page__.getElement('service_id')['obj'].setUrlParams({'type_id': $(this).val()})--}}
{{--                                         }--}}
{{--                                    })--}}

{{--                                }",--}}
{{--                            'template' =>--}}
{{--                            "<div class='form-row form-row_radio'>--}}
{{--                                <h3 class='form-row_header'>Какая услуга Вас интересует?</h3>--}}
{{--                                <label  v-for=\"item in data\" class='form-row_label'>--}}
{{--                                   <input type='radio' name='request-type' :value=\"item.id\" :checked=\"assertTrue(item.id, type_id)\">--}}
{{--                                   <span class='form-row_text'>@{{ item.name }}</span>--}}
{{--                                   <span class='form-row_subtext'>--}}
{{--                                       @{{ item.description }}--}}
{{--                                   </span>--}}
{{--                                </label>--}}
{{--                            </div>--}}
{{--                            ",--}}
{{--                        ]--}}
{{--                    ])--}}

{{--                    <div class="form-row form-row_all-services select">--}}
{{--                        <h3 class="form-row_header">Все услуги</h3>--}}
{{--                        @include('component_build',["component" => "component.listComponent.selectComponent",--}}
{{--                            "params_component" => [--}}
{{--                                "autostart" => 'false',--}}
{{--                                "name" => 'service_id',--}}
{{--                                "default_title" => 'Выберите сервис',--}}
{{--								"url" => route__("actionGetServiceListForSelect_mainstay_service_servicemainstaycontroller"),--}}
{{--//								"params" => ['type_id' => ],--}}
{{--								"callBeforloadComponent" => "function(component) {--}}
{{--								             component.option['service_id'] = page__.getGolobalData('VacancyInfo').service_id--}}
{{--								             return component.option--}}
{{--								}",--}}
{{--                                "callAfterloadComponent" => "function() {--}}
{{--                                   $('.js_select').select2({--}}
{{--                                       language: {--}}
{{--                                         noResults: function(){return 'Совпадений не найдено';},--}}
{{--                                       }--}}
{{--                                       });--}}
{{--                                       $('.js_select').one('select2:open', function(e) {--}}
{{--                                       $('input.select2-search__field').prop('placeholder', 'Поиск...');--}}
{{--                                   });--}}
{{--                                }",--}}
{{--                                "template" =>--}}
{{--                                "--}}
{{--                                <select class='form-row_header select-btn js_select' mark='service_id' :id=\"name\">--}}
{{--                                    <option v-for=\"(items_ , index) in data\" :data-text=\"items_\" :value=\"index\" :selected=\"assertTrue(index, service_id)\">@{{items_}}</option>--}}
{{--                                </select>--}}
{{--                                "--}}
{{--                            ]])--}}

{{--                    </div>--}}

                    <div class="form-row">
                        <h3 class="form-row_header">Мне нужно</h3>

                        <label class="form-row_label">
                        <textarea name="what-i-need" mark="title" rows="3"
                                  placeholder="Например: Подготовить документы для регистрации ООО"></textarea>
                        </label>
                        <span class="error_message" style="color: red;"></span>
                    </div>

                    <div class="form-row">
                        <h3 class="form-row_header">Подробное описание вопроса</h3>

                        <label class="form-row_label">
                        <textarea name="request-description" mark="description" rows="7"
                                  placeholder="Чем подробнее вы опишите детали вопроса или требования к документу, тем точнее юристы смогут оценить стоимость..."></textarea>
                        </label>
                        <span class="error_message" style="color: red;"></span>
                    </div>

                    <div class="form-row">
                        <h3 class="form-row_header">Прикрепленные файлы</h3>

                        @include('component_build', [
                        'component' => 'component.infoComponent.textInfo',
                        'params_component' => [
                            'autostart' => 'true',
                            'name' => 'get_existing_files',
                            'globalData' => "VacancyFiles",

                            'template' =>
                            "<ul class='files_list'>
                                <li v-for=\"item in data\">
                                    <a @click=\"viewFile(item.path, item.name)\">@{{item.name}}</a>
                                    <button style='color:red' @click=\"deleteFile(item.path)\">&nbsp;&nbsp;Удалить</button>
                                </li>
                            </ul>
                            ",
                            ]
                        ])
                    </div>
                    <div class="form-row">
                        <h3 class="form-row_header">Загрузить файлы</h3>

                        <label class="form-row_label form-row_files">
                            <ul class="attached-files" mark="files">
                            </ul>
                            <input type="file" name="files[]" id="files" multiple>
                            <span>
                                <img src="/lawyers/images/icons/folder-icon.svg" alt="folder-icon">
                                    <div>
                                        Выберите файлы
                                    </div>
                            </span>

                        </label>
                        <span class="error_message" style="color: red;"></span>
                    </div>

                    <div class="form-row form-row_price">
                        <h3 class="form-row_header">Какая услуга вас интересует?</h3>

                        <label class="form-row_label">
                            <input type="radio" name="request-price" value="by_agreement">
                            <span class="form-row_text">По договоренности с юристом</span>
                        </label>

                        <label class="form-row_label">
                            <input type="radio" name="request-price" value="set_price">
                            <span class="form-row_text">
                                Я планирую заплатить
                                <input type="text" class="form-row_price-input" id="price" placeholder="Сумма" disabled> рублей
                            </span>
                        </label>
                    </div>
                </div>

                <div class="reasons-block">
                    <h2>3 причины оставить заявку</h2>

                    <ol class="reasons">
                        <li>
                            <p class="num"><span>01</span></p>
                            <h3>Это бесплатно</h3>
                            <p class="text">Вы оставляете заявку бесплатно и ничего не теряете, если никто из
                                юристов не подойдет.</p>
                        </li>
                        <li>
                            <p class="num"><span>02</span></p>
                            <h3>У вас появится выбор</h3>
                            <p class="text">Вы сравниваете предложения разных юристов и выбираете выгодные
                                условия.</p>
                        </li>
                        <li>
                            <p class="num"><span>03</span></p>
                            <h3>Мы на Вашей стороне</h3>
                            <p class="text">Если Вы останетесь недовольны результатом, напишите нам, и мы вернем Вам
                                деньги!</p>
                        </li>
                    </ol>
                </div>

                <div class="request_bottom">
                    <div class="desc">
                        <p class="desc-text">Описание увидят только юристы сервиса Контакты увидит только выбранный
                            исполнитель</p>
                        <ul class="desc-users">
                            <li><img src="/lawyers/images/icons/user1.svg" alt="user-img"></li>
                            <li><img src="/lawyers/images/icons/user2.svg" alt="user-img"></li>
                            <li><img src="/lawyers/images/icons/user3.svg" alt="user-img"></li>
                            <li><img src="/lawyers/images/icons/user4.svg" alt="user-img"></li>
                            <li><span class="other-users">+2000</span></li>
                        </ul>
                    </div>

                    <button class="main-btn main-btn_blue"><span>Разместить заказ</span></button>

                    <label class="private-policy">
                        <input type="checkbox" name="private-policy">
                        <span class="label-text">
                            Я принимаю <a href="#">Правила</a> и <a href="#">Политику Конфидициальности</a>
                        </span>
                    </label>
                </div>
            </div>
        </div>
    </section>

    <script>
        let price = 0
        $(document).ready(function () {
            saveVacancy()
            $('#files').change(function () {
                showFilesInfo()
            })
            setPaymentType()
            setTextAreasValues()
            setPaymentValue()
            // getDataForSelect()
        })

        function setData() {
            const formData = setFiles()
            formData.append('service_id', $('[mark = service_id]').val())
            formData.append('description', $('[mark = description]').val())
            formData.append('title', $('[mark = title]').val())
            formData.append('payment', setPaymentType())
            formData.append('id', page__.getGolobalData('VacancyInfo').id)
            return formData
        }

        function setFiles() {
            const formData = new FormData()
            $.each($('#files')[0].files, function (key, input) {
                formData.append('files[]', input)
            })
            return formData
        }

        function setPaymentType() {
            $('#price').change(function () {
                price = $('#price').val()
            })
            $('input[type = radio][name = request-price]').change(function () {
                let priceField = $('#price')
                priceField.val(null)
                priceField.prop('disabled', true)
                price = 0
                if ($(this).val() === 'set_price') {
                    priceField.prop('disabled', false)
                }
            })
            return price
        }

        function sendData(data) {
            $.ajax({
                method: 'POST',
                contentType: false,
                processData: false,
                data: data,
                url: '{{ route__('actionVacancyUpdate_mainstay_vacancy_vacancymainstaycontroller') }}',
                success: function (response) {
                    window.location.href = '{{ route__('actionClientCabinet_controllers_client_clientcontroller') }}'
                },
                error: function (error) {
                    let messages = error.responseJSON.errors
                    console.log(messages);
                    for (let elem in messages) {
                        $('[mark = ' + elem.replace(/\.[0-9]+/, '') + ']').parent().next('.error_message').text(messages[elem][0])
                    }

                }

            })
        }

        function saveVacancy() {
            $('button').on('click', function (e) {
                e.preventDefault()
                if ($('input[type = checkbox][name = private-policy]')[0].checked === true) {
                    sendData(setData())
                }
            })
        }

        function showFilesInfo() {
            $('ul.attached-files > li').remove()
            let names = [];
            for (var i = 0; i < $("#files")[0].files.length; i++) {
                names.push($("#files")[0].files.item(i).name);
            }
            names.forEach((name) => {
                $('label > span > img[alt = folder-icon]').remove()
                $('label > span > div').remove()
                $('.attached-files')
                    .append('<li><img src="/lawyers/images/main/doc.png" alt="doc-type" class="file-type">' +
                        '<p class="upload_file_name">' + cutFileName(name) + '</p>'  + '</li>')
            })
        }

        function cutFileName(name) {
            if (name.length > 12) {
                return name.substring(0, 5) + '...' + name.slice(-5)
            }
            return name
        }

        function assertTrue(firstId, secondId) {
            return firstId == secondId ? true : false
        }

        function setTextAreasValues() {
            const data = page__.getGolobalData('VacancyInfo')
            $('[mark = title]').val(data.title)
            $('[mark = description]').val(data.description)
        }

        function setPaymentValue() {
            const payment = page__.getGolobalData('VacancyInfo').payment
            if (payment === 0) {
                $('[type = radio][value = by_agreement]').prop('checked', true)
            } else {
                $('[type = radio][value = set_price]').prop('checked', true)
                $('#price').val(payment).prop('disabled', false)
                price = payment
            }
        }

        function viewFile(path, name) {
            const route = `{{ route('download') }}?path=${path}&name=${name}`
            window.open(route)
        }

        function deleteFile(filePath) {
            $.ajax({
                method: 'POST',
                data: {
                    path: filePath,
                    user_id: '{{ auth()->id() }}',
                    path_start: 'vacancy/' + '{{ request()->route('vacancy_id') }}'
                },
                url: '{{ route__('actionDeleteFile_mainstay_file_filemainstaycontroller') }}',
                success: function (response) {
                    page__.getElementsGroup('get_existing_files')[0]['obj'].setData(response['result']);
                }
            })
        }

        function getDataForSelect() {
            $.ajax({
                method: 'POST',
                data: {
                    type_id: page__.getGolobalData('VacancyInfo').service_type_id
                },
                url: '{{ route__('actionGetServiceListForSelect_mainstay_service_servicemainstaycontroller') }}',
                success: function (response) {
                    page__.getElementsGroup('service_id')[0]['obj'].setData(response['result']);
                }
            })
        }

    </script>
@endsection
