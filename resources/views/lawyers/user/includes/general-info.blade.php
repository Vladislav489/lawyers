<div class="registration-form_block">
    <h3 class="registration-form_block-header">Заполните информацию о себе</h3>

    <label class="registration-form_label">
        <span class="label-title">Имя</span>
        <input type="text" name="first_name" placeholder="Имя">
        @error('first_name')
        <div>{{ $message }}</div>
        @enderror
    </label>
    <label class="registration-form_label">
        <span class="label-title">Фамилия</span>
        <input type="text" name="last_name" placeholder="Фамилия">
        @error('last_name')
        <div style="color: red">{{ $message }}</div>
        @enderror
    </label>
    <label class="registration-form_label">
        <span class="label-title">Отчество</span>
        <input type="text" name="middle_name" placeholder="Отчество">
        @error('middle_name')
        <div style="color: red">{{ $message }}</div>
        @enderror
    </label>
    <label class="registration-form_label date-label">
        <span class="label-title">Дата рождения</span>
        <input type="date" name="date_birthday" placeholder="Дата рождения">
        {{--                        <img src="/lawyers/images/icons/calendar-icon.svg" alt="calendar-icon">--}}
        @error('date_birthday')
        <div style="color: red">{{ $message }}</div>
        @enderror
    </label>
    <label class="registration-form_label">
        <span class="label-title">Номер телефона</span>
        <input type="tel" name="phone_number" placeholder="Номер телефона">
        @error('phone_number')
        <div style="color: red">{{ $message }}</div>
        @enderror
    </label>
    <label class="registration-form_label">
        <span class="label-title">Электронная почта</span>
        <input type="email" name="email" placeholder="Электронная почта">
        @error('email')
        <div style="color: red">{{ $message }}</div>
        @enderror
    </label>
</div>

<div class="registration-form_block">
    <h3 class="registration-form_block-header">Введите данные о местонахождении</h3>

    <label class="registration-form_label full">
        <span class="label-title">Почтовый индекс</span>
        <input type="text" name="post_code" placeholder="Почтовый индекс">
        @error('post_code')
        <div style="color: red">{{ $message }}</div>
        @enderror
    </label>

    @include('lawyers.user.includes.location-selector')

</div>

<div class="registration-form_block">
    <h3 class="registration-form_block-header">Придумайте пароль</h3>

    <label class="registration-form_label full">
        <span class="label-title">Пароль</span>
        <input type="password" name="password" placeholder="Пароль">
        @error('password')
        <div style="color: red">{{ $message }}</div>
        @enderror
    </label>
    <label class="registration-form_label full">
        <span class="label-title">Повторите пароль</span>
        <input type="password" name="password_confirmation" placeholder="Повторите пароль">
    </label>
</div>
