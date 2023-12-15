@extends('lawyers.layouts.main')
@section('title', 'Регистрация аккаунта')

@section('content')
    <section class="mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h1 class="fs-3">Регистрация аккаунта</h1>
                    <div class="d-grid gap-2">
                        <div class="btn-group btn-group-sm mt-3">
                            <a href="{{ route__('actionSignupClient_usercontroller') }}" class="btn btn-outline-primary active">Клиент</a>
                            <a href="{{ route__('actionSignupEmployee_usercontroller') }}" class="btn btn-outline-primary">Сотрудник</a>
                        </div>
                    </div>
                    <form
                        id="signup-form"
                        class="mt-3 mb-5 p-3 bg-primary-subtle"
                        action=""
                        method="post"
                        enctype="application/x-www-form-urlencoded"
                        style="border: 1px dashed"
                        data-request-url="{{ route__('actionStoreClient_clientmainstaycontroller') }}"
                        data-success-url="{{ route__('actionLogin_usercontroller') }}"
                    >
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="email">Электропочта</label>
                            <input id="email" class="form-control" type="email" name="email">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="phone">Номер телефона</label>
                            <input id="phone" class="form-control" type="tel" name="phone_number">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="password">Пароль</label>
                            <input id="password" class="form-control" type="password" name="password">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="first-name">Имя</label>
                            <input id="first-name" class="form-control" type="text" name="first_name">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="last-name">Фамилия</label>
                            <input id="last-name" class="form-control" type="text" name="last_name">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="middle-name">Отчество</label>
                            <input id="middle-name" class="form-control" type="text" name="middle_name">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label class="form-label" for="post-code">Почтовый индекс</label>
                                    <input id="post-code" class="form-control" type="text" name="post_code">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            <div class="col">
                                <div class="mb-3">
                                    <label class="form-label" for="date-birthday">Дата рождения</label>
                                    <input id="date-birthday" class="form-control" type="date" name="date_birthday">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="country" class="form-label">Страна</label>
                                    <select id="country" class="form-select" name="country_id">
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            <div class="col">
                                <div class="mb-3">
                                    <label for="state" class="form-label">Область</label>
                                    <select id="state" class="form-select" name="state_id">
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="district" class="form-label">Район</label>
                                    <select id="district" class="form-select" name="district_id">
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            <div class="col">
                                <div class="mb-3">
                                    <label for="city" class="form-label">Город</label>
                                    <select id="city" class="form-select" name="city_id">
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="type_id" value="1">
                        <input type="hidden" name="modifier_id" value="1">

                        <button
                            class="btn btn-primary"
                            type="submit"
                            style="pointer-events: all;"
                            data-text="Регистрирую"
                        >Зарегистрировать</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    @include('js.util')
    @include('js.validation')
    @include('js.async-api')
    <script>
        const entities = [
            'city',
            'country',
            'district',
            'state',
        ];

        getDataArray([
            "{{ route__('actionGetCities_helpdatamainstaycontroller') }}",
            "{{ route__('actionGetCountries_helpdatamainstaycontroller') }}",
            "{{ route__('actionGetDistricts_helpdatamainstaycontroller') }}",
            "{{ route__('actionGetStates2_helpdatamainstaycontroller') }}",
        ]).then(({data}) => {
            entities.forEach((entity, index) => {
                const selectElement = document.getElementById(entity);
                data[index].forEach(({id, name}) => {
                    const html = `<option value="${id}">${name}</option>`;
                    selectElement.insertAdjacentHTML('beforeend', html);
                });
            });

            setSubmitHandler();
        });

    </script>
@endsection
