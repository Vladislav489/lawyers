@extends('lawyers.layouts.main')
@section('title', 'Регистрация аккаунта')

@push('bootstrap')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        ul {
            margin: 0 !important;
        }
    </style>
@endpush

@section('content')
    <section class="mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h1 class="fs-3">Регистрация аккаунта</h1>
                    <div class="d-grid gap-2">
                        <div class="btn-group btn-group-sm mt-3">
                            <a href="{{ route__('actionSignupClient_controllers_site_usercontroller') }}" class="btn btn-outline-primary">Клиент</a>
                            <a href="{{ route__('actionSignupEmployee_controllers_site_usercontroller') }}" class="btn btn-outline-primary active">Сотрудник</a>
                        </div>
                    </div>
                    <form
                        id="signup-form"
                        class="mt-3 mb-5 p-3 bg-primary-subtle"
                        action=""
                        method="post"
                        enctype="multipart/form-data"
                        style="border: 1px dashed"
                        data-request-url="{{ route__('actionEmployeeStore_mainstay_employee_employeemainstaycontroller') }}"
                        data-success-url="{{ route__('actionLogin_controllers_site_usercontroller') }}"
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

                        <div class="mb-3">
                            <label class="form-label" for="avatar">Аватар</label>
                            <input id="avatar" class="form-control" type="file" name="avatar" accept="image/*">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="license-number">Лицензионный номер</label>
                            <input id="license-number" class="form-control" type="text" name="license_number">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="dt-practice-start">Дата начала юр. практики</label>
                            <input id="dt-practice-start" class="form-control" type="date" name="dt_practice_start">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="consultation-price">Стоимость консультации</label>
                            <input id="consultation-price" class="form-control" type="number" name="consultation_price">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label for="company" class="form-label">Компания</label>
                            <select id="company" class="form-select" name="company_id">
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>

                        <input type="hidden" name="type_id" value="2">
                        <input type="hidden" name="modifier_id" value="1">
                        <input type="hidden" name="avatar_path" value="">

                        <button
                            type="submit"
                            class="btn btn-primary"
                            style="pointer-events: all;"
                            data-text="Зарегистрировать"
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
            'company',
        ];

        getDataArray([
            "{{ route__('actionGetCities_mainstay_helpdata_helpdatamainstaycontroller') }}",
            "{{ route__('actionGetCountries_mainstay_helpdata_helpdatamainstaycontroller') }}",
            "{{ route__('actionGetDistricts_mainstay_helpdata_helpdatamainstaycontroller') }}",
            "{{ route__('actionGetStates2_mainstay_helpdata_helpdatamainstaycontroller') }}",
            "{{ route__('actionGetCompanies_mainstay_company_companymainstaycontroller') }}",
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
