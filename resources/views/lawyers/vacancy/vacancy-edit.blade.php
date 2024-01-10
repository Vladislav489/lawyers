@extends('lawyers.layouts.main')
@section('title', 'Вакансия (редактирование)')

@section('content')
    <section class="mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h1 class="fs-3">Вакансия «<span></span>» (редактирование)</h1>
                    @include('lawyers.vacancy._menu')

                    <form
                        id="signup-form"
                        class="mt-3 mb-5 p-3 bg-primary-subtle"
                        action=""
                        method="post"
                        enctype="application/x-www-form-urlencoded"
                        style="border: 1px dashed"
                        data-success-url="{{ route__('actionVacancyList_vacancycontroller')}}"
                    >
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label class="form-label" for="desc">Описание</label>
                            <textarea
                                id="desc"
                                class="form-control"
                                name="description"
                            ></textarea>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="payment">Оплата</label>
                            <input
                                id="payment"
                                class="form-control"
                                type="number"
                                name="payment"
                                value=""
                            >
                            <div class="invalid-feedback"></div>
                        </div>

                        <input type="hidden" name="status" value="1">
                        <input type="hidden" name="lawsuit_number" value="1">
                        <input type="hidden" name="address_judgment" value="1">
                        <input type="hidden" name="period_start" value="2023-01-01">
                        <input type="hidden" name="period_end" value="2023-01-01">
                        <input type="hidden" name="chat_id" value="1">
                        <input type="hidden" name="city_id" value="1">
                        <input type="hidden" name="state_id" value="1">
                        <input type="hidden" name="country_id" value="1">
                        <input type="hidden" name="service_id" value="1">
                        <input type="hidden" name="priority_id" value="1">

                        <button
                            type="submit"
                            class="btn btn-primary"
                            style="pointer-events: all;"
                            data-text="Обновляю"
                        >Обновить</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    @include('js.util')
    @include('js.validation')
    @include('js.async-api')
    <script>
        const params = {};
        window.location.href.split('?')[1].split('&').forEach((param) => {
            const [key, value] = param.split('=');
            params[key] = value;
        });

        fetch(`http://lawyers/mainstay/vacancy/getvacancy?id=${params.id}`)
            .then((response) => response.json())
            .then((data) => {
                const url = `{{ route__('actionVacancyStore_vacancymainstaycontroller') }}?id=${params.id}`;

                document.querySelector('form').dataset.requestUrl = url;
                document.getElementById('desc').textContent = data.description;
                document.querySelector('h1 span').textContent = data.description;
                document.getElementById('payment').value = data.payment;

                setSubmitHandler();
            });
    </script>
@endsection
