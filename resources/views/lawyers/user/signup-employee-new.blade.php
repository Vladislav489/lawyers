@extends('lawyers.layouts.main')
@section('title', 'Регистрация аккаунта | Юрист')

@section('content')
    <section class="registration-section u-container">
        <div class="container">
            <h1 class="section_header">Регистрация аккаунта</h1>

            <ul class="round-top_nav">
                <li>
                    <a href="{{ route__('actionSignupClient_usercontroller') }}">Клиент</a>
                </li>
                <li class="active">
                    <a href="{{ route__('actionSignupEmployee_usercontroller') }}">Юрист</a>
                </li>
            </ul>

            <form action="#" class="registration-form">
                <div class="registration-form_block">
                    <h3 class="registration-form_block-header">Заполните информацию о себе</h3>

                    <label class="registration-form_label">
                        <span class="label-title">Имя</span>
                        <input type="text" name="name" placeholder="Имя">
                    </label>
                    <label class="registration-form_label">
                        <span class="label-title">Фамилия</span>
                        <input type="text" name="lastname" placeholder="Фамилия">
                    </label>
                    <label class="registration-form_label">
                        <span class="label-title">Отчество</span>
                        <input type="text" name="middleName" placeholder="Отчество">
                    </label>
                    <label class="registration-form_label date-label">
                        <span class="label-title">Дата рождения</span>
                        <input type="date" name="birthday" placeholder="Дата рождения">
                        <img src="/lawyers/images/icons/calendar-icon.svg" alt="calendar-icon">
                    </label>
                    <label class="registration-form_label">
                        <span class="label-title">Номер телефона</span>
                        <input type="tel" name="phoneNumber" placeholder="Номер телефона">
                    </label>
                    <label class="registration-form_label">
                        <span class="label-title">Электронная почта</span>
                        <input type="email" name="email" placeholder="Электронная почта">
                    </label>
                </div>

                <div class="registration-form_block">
                    <h3 class="registration-form_block-header">Введите данные о местонахождении</h3>

                    <label class="registration-form_label full">
                        <span class="label-title">Почтовый индекс</span>
                        <input type="text" name="postcode" placeholder="Почтовый индекс">
                    </label>
                    <label class="registration-form_label">
                        <span class="label-title">Страна</span>
                        <input type="text" name="country" placeholder="Страна">
                    </label>
                    <label class="registration-form_label">
                        <span class="label-title">Область</span>
                        <input type="text" name="cityArea" placeholder="Область">
                    </label>
                    <label class="registration-form_label date-label">
                        <span class="label-title">Район</span>
                        <input type="text" name="cityDistrict" placeholder="Район">
                    </label>
                    <label class="registration-form_label">
                        <span class="label-title">Город</span>
                        <input type="tel" name="city" placeholder="Город">
                    </label>
                </div>

                <div class="registration-form_block">
                    <h3 class="registration-form_block-header">Придумайте пароль</h3>

                    <label class="registration-form_label full">
                        <span class="label-title">Пароль</span>
                        <input type="text" name="password" placeholder="Пароль">
                    </label>
                    <label class="registration-form_label full">
                        <span class="label-title">Повторите пароль</span>
                        <input type="text" name="repeatPassword" placeholder="Повторите пароль">
                    </label>
                </div>

                <div class="registration-form_block">
                    <h3 class="registration-form_block-header">Заполните данные о работе</h3>

                    <label class="registration-form_label">
                        <span class="label-title">Стоимость консультации</span>
                        <input type="text" name="consultPrice" placeholder="Стоимость консультации">
                    </label>
                    <label class="registration-form_label date-label">
                        <span class="label-title">Дата начала юр. практики</span>
                        <input type="date" name="dateOfPractice" placeholder="Дата начала юр. практики">
                        <img src="/lawyers/images/icons/calendar-icon.svg" alt="calendar-icon">
                    </label>
                    <label class="registration-form_label">
                        <span class="label-title">Лицензионный номер</span>
                        <input type="number" name="licenseNumber" placeholder="Лицензионный номер">
                    </label>
                    <label class="registration-form_label">
                        <span class="label-title">Компания</span>
                        <input type="text" name="company" placeholder="Компания">
                    </label>
                </div>

                <div class="registration-form_block">
                    <h3 class="registration-form_block-header">Сертификаты</h3>

                    <label class="registration-form_label full file-label">
                        <span class="label-title">Выберите файл</span>
                        <input type="file">
                        <span class="choose-img _downloaded">
                            <span class="downloaded_text">
                                Загрузить файл
                            </span>

                            <span class="downloaded-img">
                                <span class="cross"></span>
                                <img src="/lawyers/images/main/lawyer-img.png" alt="lawyer-img">
                                <span class="title">pdf/12345678..</span>
                            </span>
                        </span>
                    </label>
                </div>

                <div class="registration-form_block">
                    <h3 class="registration-form_block-header">Аватар</h3>

                    <label class="registration-form_label full file-label">
                        <span class="label-title">Выберите файл</span>
                        <input type="file" name="avatar">
                        <span class="choose-img">
                            <span class="downloaded_text">
                                Загрузить файл
                            </span>
                        </span>
                    </label>
                </div>

                <button type="submit" class="main-btn">
                    <span class="first">Зарегистрироваться</span>
                    <span class="second">Зарегистрироваться</span>
                </button>
            </form>
        </div>
    </section>
@endsection
