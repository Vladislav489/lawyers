@extends('lawyers.layouts.main')
@section('title', 'Настройки сотрудника')

@section('content')
    <!-- spinner -->
    <template id="spinner">
        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
    </template>

    @include('js.validation')
    <section class="mt-5">
        <div class="container">
            <div class="row">
                <div class="col">
                    <h1 class="fs-3">Настройки сотрудника</h1>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-lg-6">
                    <form id="settings-form" class="p-3 bg-primary-subtle" action="">
                        <div class="mb-3">

                            @foreach ($services as $service)
                                <div class="form-check form-switch">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        name="service_ids[]"
                                        value="{{ $service->id }}"
                                        role="switch"
                                        id="example-{{ $service->id }}"
                                        {{ in_array($service->id, $user_service_ids) ? 'checked' : '' }}
                                    >
                                    <label
                                        class="form-check-label"
                                        for="example-{{ $service->id }}"
                                        style="user-select: none;"
                                    >{{ $service->name }}</label>
                                </div>
                            @endforeach

                        </div>

                        <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                        <button class="btn btn-primary" type="submit" style="pointer-events: all;">Сохранить</button>
                    </form>
                </div>
                <div class="col-lg-6">

                    @foreach (Auth::user()->services as $service)
                        <form id="service-form-{{ $service->id }}" class="mb-3 p-3 bg-primary-subtle" action="">
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
                                    style="user-select: none;"
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

                                <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                                <input type="hidden" name="service_id" value="{{ $service->id }}">

                            </fieldset>
                            <button class="btn btn-primary" type="submit" style="pointer-events: all;">Сохранить</button>
                        </form>

                        <script>
                            {
                                const url = 'http://lawyers/employeeserviceupdate';
                                const onSuccess = () => window.location.href = 'http://lawyers/employeesettings';

                                setSubmitHandler(url, onSuccess, 'Сохраняю', '#service-form-{{ $service->id }}');
                            }
                        </script>
                    @endforeach

                </div>
            </div>
        </div>
    </section>

    <script>
        const url = 'http://lawyers/employeestore';
        const checkboxElements = document.querySelectorAll('[name=is_main]');
        const onSuccess = () => window.location.href = 'http://lawyers/employeesettings';

        setSubmitHandler(url, onSuccess, 'Сохраняю', '#settings-form', false);

        checkboxElements.forEach((checkbox) => {
            const fieldsetElement = checkbox.parentElement.nextElementSibling;
            checkbox.addEventListener('change', (evt) => {
                const isHidden = fieldsetElement.style.display === 'none';
                fieldsetElement.style.display = isHidden ? 'block' : 'none';
            });
        });
    </script>
@endsection
