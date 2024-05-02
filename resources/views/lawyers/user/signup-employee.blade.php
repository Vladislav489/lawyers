@extends('lawyers.layouts.main')
@section('title', 'Регистрация аккаунта | Юрист')

@section('content')
    <section class="registration-section u-container">
        <div class="container">
            <h1 class="section_header">Регистрация аккаунта</h1>

            @include('lawyers.user.includes.user-type-switch', ['lawyer' => 1])

            <form
                class="registration-form"
                action="{{ route__('actionEmployeeStore_mainstay_employee_employeemainstaycontroller') }}"
                method="post"
                enctype="multipart/form-data"
            >
                @csrf

                <input type="hidden" name="type_id" value="2">
                @include('lawyers.user.includes.general-info')

                <div class="registration-form_block">
                    <h3 class="registration-form_block-header">Заполните данные о работе</h3>

                    <label class="registration-form_label">
                        <span class="label-title">Стоимость консультации</span>
                        <input type="text" name="consultation_price" placeholder="Стоимость консультации">
                        @error('consultation_price')
                        <div style="color: red">{{ $message }}</div>
                        @enderror
                    </label>
                    <label class="registration-form_label date-label">
                        <span class="label-title">Дата начала юр. практики</span>
                        <input type="date" name="dt_practice_start" placeholder="Дата начала юр. практики">
{{--                        <img src="/lawyers/images/icons/calendar-icon.svg" alt="calendar-icon">--}}
                        @error('dt_practice_start')
                        <div style="color: red">{{ $message }}</div>
                        @enderror
                    </label>
                    <label class="registration-form_label">
                        <span class="label-title">Лицензионный номер</span>
                        <input type="number" name="license_number" placeholder="Лицензионный номер">
                        @error('license_number')
                        <div style="color: red">{{ $message }}</div>
                        @enderror
                    </label>
                    <label class="registration-form_label">
                        <span class="label-title">Компания</span>
                        @include('component_build',["component" => "component.listComponent.selectComponent",
                            "params_component" => [
                                "autostart" => 'true',
                                "name" => 'company_id',
                                "default_title" => 'Компания',
                                "url" => route("actionGetCompanies_mainstay_company_companymainstaycontroller"),
                                "template" => 'simpleSelect',
                                "change" => "function(){}"
                            ]])
                        @error('company_id')
                        <div style="color: red">{{ $message }}</div>
                        @enderror
                    </label>
                </div>

                <div class="registration-form_block">
                    <h3 class="registration-form_block-header">Сертификаты</h3>

                    <label class="registration-form_label full file-label">
                        <span class="label-title">Выберите файл</span>
                        <input id="achievements_fields" type="file" name="achievements[]" multiple>
                        <span id="achievements_preview" class="choose-img _downloaded">
                                Загрузить файл
                        </span>
                    </label>
                </div>
                <div class="registration-form_block">
                    <h3 class="registration-form_block-header">Аватар</h3>

                    <label class="registration-form_label full file-label">
                        <span class="label-title">Выберите файл</span>
                        <input type="file" name="avatar">
                        <span id="avatar_preview" class="choose-img _downloaded">
                                Загрузить файл
                        </span>
                    </label>
                </div>

                <button type="submit" class="main-btn">Зарегистрироваться</button>
            </form>
        </div>
    </section>

    <script>
        $(document).ready(function () {
            setElement()
        })

        function setElement() {
            showFiles($('[name = avatar]'))
            showFiles($('#achievements_fields'))
        }

        function showFiles(element) {
            element.on('change', function () {
                console.log(element.next());
                var files = $(this)[0].files;

                element.next().empty();

                for (var i = 0; i < files.length; i++) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        element.next().append('<img src="' + e.target.result + '" class="uploaded-image" style="width: 20%;">');
                    };

                    reader.readAsDataURL(files[i]);

                }
            })
        }

    </script>
@endsection
