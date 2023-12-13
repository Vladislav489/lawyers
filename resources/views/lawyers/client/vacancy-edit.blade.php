@extends('lawyers.layouts.main')
@section('title', 'Редактировать вакансию')

@section('content')
    <section class="mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h1 class="fs-3">Редактировать вакансию</h1>
                    <form
                        id="signup-form"
                        class="mt-4 mb-5 p-3 bg-primary-subtle"
                        action=""
                        method="post"
                        enctype="application/x-www-form-urlencoded"
                        style="border: 1px dashed"
                        data-success-url="http://lawyers/vacancylist"
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

        fetch(`http://lawyers/mainstay/client/getvacancy?id=${params.id}`)
            .then((response) => response.json())
            .then((data) => {
                const url = `http://lawyers/mainstay/client/updatevacancy?id=${params.id}`;

                document.querySelector('form').dataset.requestUrl = url;
                document.getElementById('desc').textContent = data.description;
                document.getElementById('payment').value = data.payment;

                setSubmitHandler();
            });
    </script>
@endsection
