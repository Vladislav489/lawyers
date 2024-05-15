@extends('lawyers.layouts.main')


@section('title', 'Создать заявку')

@section('content')
    <section class="request-section u-container">
        <div class="container">

            <div class="request-form">
                <div class="form-rows">

                    <input type="hidden" name="service_id" value="{{ $serviceId }}">
                    <input type="hidden" name="executor_id" value="{{ $employeeId }}">

                        @include('component_build', [
                            'component' => 'component.infoComponent.textInfo',
                            'params_component' => [
                                'autostart' => 'false',
								'ssr' => 'true',
                                'name' => 'servicesForRadios',
								'url' => route__('actionGetServiceTypeList_mainstay_service_servicemainstaycontroller'),
								'callAfterloadComponent' => "function() {
								        $('[name=request-type]').change(function() {
								                if($(this).val() != undefined && $(this).val() != null) {
					                            page__.getElement('service_id')['obj'].setUrlParams({'type_id': $(this).val()})
					                            }
								            })
								    }",
                                'template' =>
                                '
                                <div class="form-row form-row_radio">
                                    <h3 class="form-row_header">Какая услуга Вас интересует?</h3>
                                    <label  v-for="item in data" class="form-row_label">
                                       <input type="radio" name="request-type" :value="item.id">
                                       <span class="form-row_text">@{{ item.name }}</span>
                                       <span class="form-row_subtext">
                                           @{{ item.description }}
                                       </span>
                                    </label>
                                </div>
                                ',
                            ]
                        ])

                        @include('component_build',["component" => "component.listComponent.selectComponent",
                            "params_component" => [
                                "autostart" => 'true',
                                "name" => 'service_type_id',
                                "default_title" => 'Категория услуг',
                                "url" => route("actionGetServiceTypeListForSelect_mainstay_service_servicemainstaycontroller"),
								'callAfterloadComponent' => "function(component) {
                                    $('.js_select').select2({
                                       language: {
                                         noResults: function(){return 'Совпадений не найдено';},
                                       }
                                       });
                                       $('.js_select').one('select2:open', function(e) {
                                       $('input.select2-search__field').prop('placeholder', 'Поиск...');
                                   });
								}",
                                "template" =>
                                '<div class="form-row form-row_all-services select">
                                    <h3 class="form-row_header">Категория услуг</h3>
                                    <select class="form-row_header select-btn js_select" mark="service_type_id" :id="name">
                                        <option>Выбрать</option>
                                        <option v-for="(items_ , index) in data " :data-text="items_" :value="index">@{{items_}}</option>
                                    </select>
                                </div>',
                                'change' => "function() {
                                    if($(this).val() != undefined && $(this).val() != null) {
					                    page__.getElement('service_id')['obj'].setUrlParams({'type_id': $(this).val()})
					                    $('select[mark=service_id]').prop('selectedIndex', 0)
                                        setTimeout(function () {
                                            $('.js_select').select2({
                                               language: {
                                                 noResults: function(){return 'Совпадений не найдено';},
                                               }
                                               });
                                               $('.js_select').one('select2:open', function(e) {
                                               $('input.select2-search__field').prop('placeholder', 'Поиск...');
                                           });
                                        }, 200)
                                    }
                                }"
                        ]])

                        @include('component_build',["component" => "component.listComponent.selectComponent",
                            "params_component" => [
                                "autostart" => 'false',
                                "name" => 'service_id',
                                "default_title" => 'Тема услуги',
                                "url" => route("actionGetServiceListForSelect_mainstay_service_servicemainstaycontroller"),

								'callAfterloadComponent' => "function() {

								}",
                                "template" =>
                                '<div class="form-row form-row_all-services select">
                                    <h3 class="form-row_header">Тема услуги</h3>
                                    <select class="form-row_header select-btn js_select" mark="service_id" :id="name">
                                        <option>Выбрать</option>
                                        <option v-for="(items_ , index) in data " :data-text="items_" :value="index">@{{items_}}</option>
                                    </select>
                                </div>'
                        ]])



                    <div class="form-row">
                        <h3 class="form-row_header">Мне нужно</h3>

                        <label class="form-row_label">
                        <textarea name="what-i-need" mark="title" rows="3"
                                  placeholder="Например: Подготовить документы для регистрации ООО"></textarea>
                        </label>
                    </div>

                        <div class="form-row">
                            <h3 class="form-row_header">Подробное описание вопроса</h3>

                            <label class="form-row_label">
                        <textarea name="request-description" mark="description" rows="7"
                                  placeholder="Чем подробнее вы опишите детали вопроса или требования к документу, тем точнее юристы смогут оценить стоимость..."></textarea>
                            </label>
                        </div>

                        <div class="form-row">
                            <h3 class="form-row_header">Прикрепленные файлы</h3>

                            <label class="form-row_label form-row_files">
                                <ul class="attached-files">

                                </ul>
                                <input type="file" name="files[]" id="files" multiple>
                                <span>
                                <img src="/lawyers/images/icons/folder-icon.svg" alt="folder-icon">
                                    <div>
                                        Выберите файлы
                                    </div>
                            </span>

                            </label>

                        </div>

                        <div class="form-row form-row_price">
                            <h3 class="form-row_header">Какая услуга вас интересует?</h3>

                            <label class="form-row_label" >
                                <input type="radio" name="request-price" value="by_agreement" checked>
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
                    {{--<div class="form-row form-row_all-services select">
                    </div>--}}

                <div class="reasons-block">
                    <h2>3 причины оставить заявку</h2>

                    <ol class="reasons">
                        <li>
                            <p class="num"><span>01</span></p>
                            <h3>Это бесплатно</h3>
                            <p class="text">Вы оставляете заявку бесплатно и ничего не теряете, если никто из юристов не подойдет.</p>
                        </li>
                        <li>
                            <p class="num"><span>02</span></p>
                            <h3>У вас появится выбор</h3>
                            <p class="text">Вы сравниваете предложения разных юристов и выбираете выгодные условия.</p>
                        </li>
                        <li>
                            <p class="num"><span>03</span></p>
                            <h3>Мы на Вашей стороне</h3>
                            <p class="text">Если Вы останетесь недовольны результатом, напишите нам, и мы вернем Вам деньги!</p>
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

                    <button class="main-btn main-btn_blue">Разместить заказ</button>

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
            deleteFile()
        })

        function setData() {
            const formData = setFiles()
            formData.append('service_id', $('[mark = service_id]').val())
            formData.append('description', $('[mark = description]').val())
            formData.append('title', $('[mark = title]').val())
            formData.append('payment', setPaymentType())
            formData.append('executor_id', $('[name = executor_id]').val())
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
            $('input[type = radio][name = request-price]').change(function () {
                let priceField = $('#price')
                priceField.val(null)
                priceField.prop('disabled', true)
                price = 0
                if ($(this).val() === 'set_price') {
                    priceField.prop('disabled', false)
                    priceField.change(function () {
                        price = $('#price').val()
                    })
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
                url: '{{ route__('actionVacancyStore_mainstay_vacancy_vacancymainstaycontroller') }}',
                success: function (response) {
                    window.location.href = '{{ route__('actionClientCabinet_controllers_client_clientcontroller') }}'
                },
                error: function (error) {
                    let messages = error.responseJSON.errors
                    console.log(messages);
                    for (let elem in messages) {
                        $('[mark = ' + elem + ']').after(`<div style="color: red">${messages[elem][0]}</div>`)
                    }

                }

            })
        }

        function saveVacancy() {
            $('button').on('click', function (e) {
                e.preventDefault()
                if($('input[type = checkbox][name = private-policy]')[0].checked === true) {
                    sendData(setData())
                }
            })
        }

        function showFilesInfo() {
            $('ul.attached-files > li').remove()
            //$('ul.attached-files > div').remove()
            let names = [];
            for(var i = 0; i < $("#files")[0].files.length; i++){
                names.push($("#files")[0].files.item(i).name);
            }
            names.forEach((name) => {
                $('label > span > img[alt = folder-icon]').prop('hidden', true)
                $('label > span > div').prop('hidden', true)
                $('.attached-files')
                    .append('<li>' +
                        '<img id="preview" src="/lawyers/images/main/doc.png" alt="doc-type">' +
                        // '<span class="delete-img"></span>' +
                        '<p class="upload_file_name">' + cutFileName(name) + '</p>'  + '</li>'
                        )
            })
        }

        function deleteFile() {
            $('.delete-img').click(function (e) {
                e.preventDefault()
                console.log(1111)
            })
        }

        function cutFileName(name) {
            if(name.length > 12) {
                return name.substring(0, 5) + '...' + name.slice(-7)
            }
            return name
        }

    </script>
@endsection
