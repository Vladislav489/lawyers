@extends('lawyers.layouts.main')
@section('title', 'Настройки сотрудника')

@section('content')

    @include('js.util')
    @include('js.render')
    @include('js.validation')
    @include('js.async-api')

    <section class="mt-5">
        <div class="container">
            <div class="row">
                <div class="col">
                    <h1 class="fs-3">Настройки сотрудника</h1>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-lg-6">
                    <form
                        id="settings-form"
                        class="p-3 bg-primary-subtle"
                        action=""
                        method="post"
                        enctype="application/x-www-form-urlencoded"
                        data-request-url="{{ route__('actionStoreEmployeeServices_employeemainstaycontroller') }}"
                        data-success-url="{{ route__('actionEmployeeSettings_employeecontroller') }}"
                        style="border: 1px dashed #000;"
                    >
                        <div id="service-container" class="mb-3"></div>
                        <button
                            class="btn btn-primary"
                            type="submit"
                            style="pointer-events: all;"
                            data-text="Сохраняю"
                        >Сохранить</button>
                    </form>
                </div>
                <div class="col-lg-6">

                    @foreach (Auth::user()->services as $service)
                        <form
                            id="service-form-{{ $service->id }}"
                            class="service-form mb-3 p-3 bg-{{ $service->is_main ? 'primary' : 'secondary' }}-subtle"
                            action=""
                            method="post"
                            enctype="application/x-www-form-urlencoded"
                            data-request-url="{{ route__('actionUpdateEmployeeService_employeemainstaycontroller') }}"
                            data-success-url="{{ route__('actionEmployeeSettings_employeecontroller') }}"
                            style="border: 1px dashed #000;"
                        >
                            <div class="mb-3">
                                <input
                                    id="service-is_main-{{ $service->id }}"
                                    class="form-check-input"
                                    type="checkbox"
                                    name="is_main"
                                    value="1"
                                    role="switch"
                                    {{ $service->is_main ? 'checked' : '' }}
                                >
                                <label
                                    class="form-check-label"
                                    for="service-is_main-{{ $service->id }}"
                                    style="user-select: none; cursor: pointer;"
                                    name="is_main"
                                >{{ $service->entity->name }} (Выбрать в качестве основного)</label>
                            </div>

                            <fieldset style="display: {{ $service->is_main ? 'block' : 'none' }}">
                                <div class="mb-3">
                                    <label class="form-label" for="service-desc-{{ $service->id }}">Описание</label>
                                    <textarea
                                        id="service-desc-{{ $service->id }}"
                                        class="form-control"
                                        name="description"
                                    >{{ $service->description }}</textarea>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="service-price-{{ $service->id }}">Цена</label>
                                    <input
                                        id="service-price-{{ $service->id }}"
                                        class="form-control"
                                        type="number"
                                        name="price"
                                        value="{{ $service->price }}"
                                    >
                                    <div class="invalid-feedback"></div>
                                </div>

                                <input type="hidden" name="id" value="{{ $service->id }}">

                            </fieldset>
                            <button
                                class="btn btn-primary"
                                type="submit"
                                style="pointer-events: all;"
                                data-text="Сохраняю"
                            >Сохранить</button>
                        </form>

                        <script>
                            setSubmitHandler('#service-form-{{ $service->id }}');
                        </script>
                    @endforeach

                </div>
            </div>
        </div>
    </section>

    <script>
        const checkboxElements = document.querySelectorAll('[name=is_main]');
        const serviceContainer = document.querySelector('#service-container');
        const serviceTemplate = document.querySelector('#service')
            .content
            .querySelector('.form-check');

        getDataArray([
            "{{ route__('actionGetServices_employeemainstaycontroller') }}",
            "{{ route__('actionGetUserServiceIds_employeemainstaycontroller') }}",
        ]).then(({data}) => {
            renderServices(...data)
            setSubmitHandler('#settings-form', false);
        });

        checkboxElements.forEach((checkbox) => {
            const fieldsetElement = checkbox.parentElement.nextElementSibling;

            if (fieldsetElement.matches('fieldset')) {
                checkbox.addEventListener('change', (evt) => {
                    const isHidden = fieldsetElement.style.display === 'none';
                    fieldsetElement.style.display = isHidden ? 'block' : 'none';
                });
            }
        });
    </script>
@endsection
