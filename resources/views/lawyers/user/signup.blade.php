@extends('lawyers.layouts.main')
@section('title', 'Регистрация аккаунта')

@section('content')
    <section class="mt-5">
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <h1 class="fs-3">Регистрация аккаунта</h1>
                    <form
                        id="signup-form"
                        class="mt-3 p-3 bg-primary-subtle"
                        action=""
                        method="post"
                        enctype="application/x-www-form-urlencoded"
                    >
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="email">Электропочта</label>
                            <input
                                id="email"
                                class="form-control"
                                type="email"
                                name="email"
                            >
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="phone">Номер телефона</label>
                            <input
                                id="phone"
                                class="form-control"
                                type="tel"
                                name="phone_number"
                            >
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="password">Пароль</label>
                            <input
                                id="password"
                                class="form-control"
                                type="password"
                                name="password"
                            >
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="first-name">Имя</label>
                            <input
                                id="first-name"
                                class="form-control"
                                type="text"
                                name="first_name"
                            >
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="last-name">Фамилия</label>
                            <input
                                id="last-name"
                                class="form-control"
                                type="text"
                                name="last_name"
                            >
                            <div class="invalid-feedback"></div>
                        </div>

                        <button type="submit" class="btn btn-primary">Зарегистрировать</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script>
        const formElement = document.querySelector('#signup-form');

        const createUser = async (body) => {
            const url = 'http://lawyers/storeuser';
            const options = {
                method: 'POST',
                body
            };

            const response = await fetch(url, options);
            const data = await response.json();

            return {
                status: response.status,
                errors: data.errors
            };
        };

        const clearValidationErrors = (formElement) => {
            formElement.querySelectorAll('input:not([type=hidden])').forEach((input) => {
                input.classList.remove('is-invalid');
                input.nextElementSibling.textContent = '';
            });
        };

        const renderValidationErrors = (errors, formElement) => {
            Object.keys(errors).forEach((field) => {
                const inputElement = formElement.querySelector(`[name=${field}]`);
                inputElement.classList.add('is-invalid');
                inputElement.nextElementSibling.textContent = errors[field][0];
            });
        };

        formElement.addEventListener('submit', (evt) => {
            evt.preventDefault();
            createUser(new FormData(evt.target)).then((response) => {
                clearValidationErrors(formElement);

                if (response.errors) {
                    renderValidationErrors(response.errors, evt.target);
                }
            });
        });
    </script>
@endsection
