<div class="registration-form_block">
    <h3 class="registration-form_block-header">Заполните информацию о себе</h3>

    <div class="registration-form_label">
        <label class="label-title">Имя</label>
        <input type="text" name="first_name" placeholder="Имя">
        @error('first_name')
        <div>{{ $message }}</div>
        @enderror
    </div>
    <div class="registration-form_label">
        <label class="label-title">Фамилия</label>
        <input type="text" name="last_name" placeholder="Фамилия">
        @error('last_name')
        <div style="color: red">{{ $message }}</div>
        @enderror
    </div>
    <div class="registration-form_label">
        <label class="label-title">Отчество</label>
        <input type="text" name="middle_name" placeholder="Отчество">
        @error('middle_name')
        <div style="color: red">{{ $message }}</div>
        @enderror
    </div>
    <div class="registration-form_label date-label">
        <label class="label-title">Дата рождения</label>
        <input type="date" name="date_birthday" placeholder="Дата рождения">
        {{--                        <img src="/lawyers/images/icons/calendar-icon.svg" alt="calendar-icon">--}}
        @error('date_birthday')
        <div style="color: red">{{ $message }}</div>
        @enderror
    </div>
    <div class="registration-form_label">
        <label class="label-title">Номер телефона</label>
        <input type="tel" name="phone_number" placeholder="Номер телефона">
        @error('phone_number')
        <div style="color: red">{{ $message }}</div>
        @enderror
    </div>
    <div class="registration-form_label">
        <label class="label-title">Электронная почта</label>
        <input type="email" name="email" placeholder="Электронная почта">
        @error('email')
        <div style="color: red">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="registration-form_block">
    <h3 class="registration-form_block-header">Введите данные о местонахождении</h3>

    <div class="registration-form_label full">
        <label class="label-title">Почтовый индекс</label>
        <input type="text" name="post_code" placeholder="Почтовый индекс">
        @error('post_code')
        <div style="color: red">{{ $message }}</div>
        @enderror
    </div>

    @include('lawyers.user.includes.location-selector')

</div>

<div class="registration-form_block">
    <h3 class="registration-form_block-header">Придумайте пароль</h3>

    <div class="registration-form_label full">
        <label class="label-title">Пароль</label>
        <input type="password" name="password" placeholder="Пароль">
        @error('password')
        <div style="color: red">{{ $message }}</div>
        @enderror
    </div>
    <div class="registration-form_label full">
        <label class="label-title">Повторите пароль</label>
        <input type="password" name="password_confirmation" placeholder="Повторите пароль">
    </div>
</div>
