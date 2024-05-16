@extends('lawyers.layouts.main')
@section('title', 'Вход в систему')

@section('content')
    <section class="registration-section u-container">
        <div class="container">
            <h1 class="section_header">Вход в систему</h1>
            <form class="registration-form" id="login-form" action="{{ route__('actionUserLogin_logincontroller') }}" method="post" enctype="application/x-www-form-urlencoded">
                <div class="registration-form_block">
                    <h3 class="registration-form_block-header">Введите данные</h3>
                    <p style="color: lightcoral">{{ session('error') }}</p>
                        @csrf
                            <div class="registration-form_label full">
                                <label class="label-title" for="email">Электронная почта</label>
                                <input id="email" placeholder="email.com" class="form-control @error('email') is-invalid @enderror" type="tel" name="email" value="{{ old('email') }}">

                            </div>
                            <div class="registration-form_label full">
                                <label class="label-title" for="password">Пароль</label>
                                <input id="password" placeholder="Пароль" class="form-control @error('password') is-invalid @enderror" type="password" name="password" value="{{ old('password') }}">

                            </div>
                        <button type="submit" class="main-btn">Войти</button>
                        @guest
                        <a @class(['main-btn main-btn_border','active' => str_contains(Route::currentRouteName(), 'actionSignup')]) href="{{ route__('actionSignupClient_controllers_site_usercontroller') }}">Регистрация</a>
                        @endguest
                    </div>
            </form>
        </div>
    </section>
{{--
    <section class="mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h1 class="fs-3">Вход в систему</h1>
                    <form
                        id="login-form"
                        class="mt-3 p-3 bg-primary-subtle"
                        action="{{ route__('actionUserLogin_logincontroller') }}"
                        method="post"
                        enctype="application/x-www-form-urlencoded"
                        style="border: 1px dashed"
                    >
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="phone">Номер телефона</label>
                            <input
                                id="phone"
                                class="form-control @error('phone_number') is-invalid @enderror"
                                type="tel"
                                name="phone_number"
                                value="{{ old('phone_number') }}"
                            >
                            @error('phone_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="password">Пароль</label>
                            <input
                                id="password"
                                class="form-control @error('password') is-invalid @enderror"
                                type="password"
                                name="password"
                                value="{{ old('password') }}"
                            >
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Войти</button>
                    </form>
                </div>
            </div>
        </div>
    </section>--}}
@endsection
