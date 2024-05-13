@extends('lawyers.layouts.main')
@section('title', 'Регистрация аккаунта | Юрист')

@section('content')
    <section class="registration-section u-container">
        <div class="container">
            <h1 class="section_header">Регистрация аккаунта</h1>

            @include('lawyers.user.includes.user-type-switch', ['lawyer' => 1])

            @if($errors)
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <form
                class="registration-form"
                action="{{ route__('actionEmployeeStore_mainstay_employee_employeemainstaycontroller') }}"
                method="post"
                enctype="multipart/form-data"
            >
                @csrf

                <input type="hidden" name="type_id" value="2">
                <div class="registration-form_label date-label full">
                    <label class="label-title">Специализация*</label>
                    @include('component_build',["component" => "component.listComponent.selectComponent",
                            "params_component" => [
                                "autostart" => 'true',
                                "name" => 'modifier_id',
                                "default_title" => 'Специализация',
                                "url" => route("actionGetModifiersForSelect_mainstay_employee_employeemainstaycontroller"),
                                "callAfterloadComponent" => "function(component) {
                                    $('select[name=modifier_id]').select2({
                                        minimumResultsForSearch: -1
                                    });
                                    return component.option;
                                 }",
                                "template" =>
                                '<select class="unit-select_select js_select" name="modifier_id" :id="name" style="width:100%">
                                    <option v-for="(items_ , index) in data " :data-text="items_" :value="index">@{{items_}}</option>
                                </select>',
                                "change" => "function(){}"
                            ]])
                </div>
                @include('lawyers.user.includes.general-info')

                <div class="registration-form_block">
                    <h3 class="registration-form_block-header">Заполните данные о работе</h3>

{{--                    <div class="registration-form_label full">--}}
{{--                        <label class="label-title">Стоимость консультации</label>--}}
{{--                        <input type="text" name="consultation_price" placeholder="Стоимость консультации">--}}
{{--                        @error('consultation_price')--}}
{{--                        <div style="color: red">{{ $message }}</div>--}}
{{--                        @enderror--}}
{{--                    </div>--}}
                    <div class="registration-form_label date-label full">
                        <label class="label-title">Дата начала юр. практики</label>
                        <input type="date" name="dt_practice_start" placeholder="Дата начала юр. практики" value="{{ old('dt_practice_start') }}">
                        @error('dt_practice_start')
                        <div style="color: red">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="registration-form_label full">
                        <label class="label-title">Компания</label>
                        <input type="text" name="company" placeholder="Компания" value="{{ old('company') }}">
                    </div>
                    <div class="registration-form_label full">
                        <label class="label-title">Тип удостоверяющего документа</label>
                        <select class="unit-select_select js_select" name="cert_description" style="width:100%">
                            <option value="Подтверждающий документ" selected="true">Сертификат</option>
                        </select>
                    </div>
                    <div class="registration-form_label full">
                        <label class="label-title">Лицензионный номер</label>
                        <input type="text" name="license_number" placeholder="Лицензионный номер" value="{{ old('license_number') }}">
                        @error('license_number')
                        <div style="color: red">{{ $message }}</div>
                        @enderror
                    </div>
{{--                    <div class="registration-form_label">--}}
{{--                        <label class="label-title">Компания</label>--}}
{{--                        @include('component_build',["component" => "component.listComponent.selectComponent",--}}
{{--                            "params_component" => [--}}
{{--                                "autostart" => 'true',--}}
{{--                                "name" => 'company_id',--}}
{{--                                "default_title" => 'Компания',--}}
{{--                                "url" => route("actionGetCompanies_mainstay_company_companymainstaycontroller"),--}}
{{--                                "template" => 'simpleSelect',--}}
{{--                                "change" => "function(){}"--}}
{{--                            ]])--}}
{{--                        @error('company_id')--}}
{{--                        <div style="color: red">{{ $message }}</div>--}}
{{--                        @enderror--}}
{{--                    </div>--}}
                </div>

                <div class="registration-form_block">
                    <h3 class="registration-form_block-header">Загрузите подтверждающий документ</h3>
                    <div class='registration-form_label full' name='container'>
                        <label class='label-title' name='container_name'>Выберите файл</label>
                        <div class='form-row_files add-cert_btn' name='file_input'>
                            <input type='file' class='form-row_files' name='cert_file' id='cert'>
                            <div data-img-container class='form-img-container'>
                            <span data-delete class='delete-img'></span>
                            <img id="preview" src="" alt="" width="100" height="100"/>
                            </div>
                            <span data-text class='load-file-text'>Загрузить файл</span>
                        </div>
                    </div>
                </div>
                <div class="registration-form_block">
                    <h3 class="registration-form_block-header">Аватар</h3>

                    <div class='registration-form_label full' name='container'>
                        <label class='label-title' name='container_name'>Выберите файл</label>
                        <div class='form-row_files add-cert_btn' name='file_input'>
                            <input type='file' class='form-row_files' name='avatar' id='avatar'>
                            <div data-img-container class='form-img-container'>
                            <span data-delete class='delete-img'></span>
                            <img id="preview" src="" alt="" width="100" height="100"/>
                            </div>
                            <span data-text class='load-file-text'>Загрузить файл</span>
                        </div>
                    </div>
                </div>

                <button type="submit" class="main-btn">Зарегистрироваться</button>
            </form>
        </div>
    </section>

    <script>
        $(document).ready(function () {
            setElement()
            clickInput()
            deleteFiles()
        })

        function clickInput() {
            $('div[name=file_input] input').click((element) => {
                // console.log(element.target);
                //$(element.target).find('input').click()
            })
        }

        function setElement() {
            showFiles($('input#cert'))
            showFiles($('input#avatar'))
        }

        function showFiles(element) {
            element.on('change', function () {
                var files = $(this)[0].files;
                $(this).closest('[name=container]').find('[name=container_name]').text('Файл');
                // element.next().empty();

                for (var i = 0; i < files.length; i++) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        element.parent().addClass('loaded').find('[data-text]').fadeOut(0);
                        element.parent().find('[data-img-container]').fadeIn();
                        element.parent().find('#preview').attr('src', e.target.result)
                        element.fadeOut(0);
                    };

                    reader.readAsDataURL(files[i]);

                }
            })
        }
        function deleteFiles(){
            $('[data-delete]').on('click',function(){
                var formParent = $(this).closest('[name=file_input]');
                $(formParent).removeClass('loaded').find('[data-text]').fadeIn();
                $(formParent).find('#preview').attr('src','');
                $(formParent).find('input').val('').fadeIn(0);
                $(formParent).find('[data-img-container]').fadeOut(0);
                $(this).closest('[name=container]').find('[name=container_name]').text('Выберите файл');
            })
        }

    </script>
@endsection
