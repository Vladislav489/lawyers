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

                    <div class="registration-form_label">
                        <label class="label-title">Стоимость консультации</label>
                        <input type="text" name="consultation_price" placeholder="Стоимость консультации">
                        @error('consultation_price')
                        <div style="color: red">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="registration-form_label date-label">
                        <label class="label-title">Дата начала юр. практики</label>
                        <input type="date" name="dt_practice_start" placeholder="Дата начала юр. практики">
                        @error('dt_practice_start')
                        <div style="color: red">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="registration-form_label">
                        <label class="label-title">Лицензионный номер</label>
                        <input type="number" name="license_number" placeholder="Лицензионный номер">
                        @error('license_number')
                        <div style="color: red">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="registration-form_label">
                        <label class="label-title">Компания</label>
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
                    </div>
                </div>

                <div class="registration-form_block">
                    <h3 class="registration-form_block-header">Сертификаты</h3>
                    <div class="registration-form_label full file-label">
                        <label class="label-title">Выберите файл</label>
                        <input id="achievements_fields" type="file" name="achievements[]" multiple>
                        <span id="achievements_preview" class="choose-img _downloaded">Загрузить файл</span>
                    </div>
                </div>
                <div class="registration-form_block">
                    <h3 class="registration-form_block-header">Аватар</h3>

                    <div class="registration-form_label full file-label">
                        <label class="label-title">Выберите файл</label>
                        <input type="file" name="avatar">
                        <span id="avatar_preview" class="choose-img _downloaded">Загрузить файл</span>
                    </div>
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
