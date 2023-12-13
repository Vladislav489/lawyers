@extends('lawyers.layouts.main')
@section('title', 'Создать вакансию')

@section('content')
    <section class="mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h1 class="fs-3">Создать вакансию</h1>
                    <form
                        id="vacancy-form"
                        class="mt-4 mb-5 p-3 bg-primary-subtle"
                        action=""
                        method="post"
                        enctype="application/x-www-form-urlencoded"
                        style="border: 1px dashed"
                        data-request-url="http://lawyers/mainstay/client/storevacancy"
                        data-success-url="http://lawyers/vacancylist"
                    >
                        @csrf
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
                            >
                            <div class="invalid-feedback"></div>
                        </div>

                        <button
                            type="submit"
                            class="btn btn-primary"
                            style="pointer-events: all;"
                            data-text="Создаю"
                        >Создать</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    @include('js.util')
    @include('js.validation')
    @include('js.async-api')
    <script>
        setSubmitHandler();
    </script>
@endsection
