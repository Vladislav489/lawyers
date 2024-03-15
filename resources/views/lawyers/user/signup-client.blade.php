@extends('lawyers.layouts.main')
@section('title', 'Регистрация аккаунта | Клиент')

@section('content')
    <section class="registration-section u-container">
        <div class="container">
            <h1 class="section_header">Регистрация аккаунта</h1>

            @include('lawyers.user.includes.user-type-switch', ['lawyer' => 0])

            <form
                class="registration-form"
                action="{{route__('actionStoreClient_mainstay_client_clientmainstaycontroller')}}"
                method="post"
                enctype="application/x-www-form-urlencoded"
            >
                @csrf

                <input type="hidden" name="type_id" value="1">
                @include('lawyers.user.includes.general-info')

                <button type="submit" class="main-btn">
                    <span class="first">Зарегистрироваться</span>
                    <span class="second">Зарегистрироваться</span>
                </button>
            </form>
        </div>
    </section>

@endsection
