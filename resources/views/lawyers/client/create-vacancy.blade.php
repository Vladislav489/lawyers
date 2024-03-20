@extends('lawyers.layouts.main')


@section('title', 'Создать заявку')

@section('content')
    <section class="request-section u-container">
        <div class="container">

            <div class="request-form">
                <div class="form-rows">

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

                    <div class="form-row form-row_all-services select">
                        <h3 class="form-row_header">Все услуги</h3>
                        @include('component_build',["component" => "component.listComponent.selectComponent",
                            "params_component" => [
                                "autostart" => 'false',
                                "name" => 'service_id',
                                "default_title" => 'Выберите сервис',
                                "url" => route("actionGetServiceListForSelect_mainstay_service_servicemainstaycontroller"),
                                "template" =>
                                '
                                <select class="form-row_header select-btn" mark="service_id" :id="name">
                                    <option v-for="(items_ , index) in data " :data-text="items_" :value="index">@{{items_}}</option>
                                </select>
                                '
                            ]])

                    </div>

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
                                <input type="file" name="files[]" id="files" multiple>
                                <span>
                                <img src="/lawyers/images/icons/folder-icon.svg" alt="folder-icon">
                                    <div>
                                        Выберите файлы
                                    </div>
                            </span>
                                <ul class="attached-files">

                                </ul>
                            </label>

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
                    <div class="form-row form-row_all-services select">
                    </div>

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

                    <button class="main-btn main-btn_blue">
                        <span class="first">
                            Разместить заказ

                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="19" viewBox="0 0 10 19" fill="none" class="arrow-icon">
                                <path d="M1.27478 1.82703L7.98864 9.50001L1.27478 17.173" stroke="white" stroke-width="2.10997" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>

                        <span class="second">
                        Разместить заказ

                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="19" viewBox="0 0 10 19" fill="none" class="arrow-icon">
                                <path d="M1.27478 1.82703L7.98864 9.50001L1.27478 17.173" stroke="white" stroke-width="2.10997" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                    </button>

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
        let price = null
        $(document).ready(function () {
            saveVacancy()
            $('#files').change(function () {
                showFilesInfo()
            })
            setPaymentType()

        })

        function setData() {
            const formData = setFiles()
            formData.append('service_id', $('[mark = service_id]').val())
            formData.append('description', $('[mark = description]').val())
            formData.append('title', $('[mark = title]').val())
            formData.append('payment', setPaymentType())
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
                priceField.prop('disabled', true)
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
            let names = [];
            for(var i = 0; i < $("#files")[0].files.length; i++){
                names.push($("#files")[0].files.item(i).name);
            }
            names.forEach((name) => {
                $('label > span > img[alt = folder-icon]').remove()
                $('label > span > div').remove()
                $('.attached-files')
                    .append('<li><img src="/lawyers/images/main/doc.png" alt="doc-type" class="file-type"></li>' +
                        '<div>' + cutFileName(name) + '</div>')
            })
        }

        function cutFileName(name) {
            if(name.length > 12) {
                return name.substring(0, 5) + '...' + name.slice(-5)
            }
            return name
        }

    </script>
@endsection
