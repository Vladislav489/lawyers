@extends('lawyers.layouts.main')
@section('title', 'Редактировать вакансию')

@section('content')
    <section class="mt-5">
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <h1 class="fs-3">Редактировать вакансию</h1>
                    <form
                        id="signup-form"
                        class="mt-4 mb-5 p-3 bg-primary-subtle"
                        action=""
                        method="post"
                        enctype="application/x-www-form-urlencoded"
                        style="border: 1px dashed"
                    >
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label class="form-label" for="desc">Описание</label>
                            <textarea
                                id="desc"
                                class="form-control"
                                name="description"
                            >{{ $vacancy->description }}</textarea>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="payment">Оплата</label>
                            <input
                                id="payment"
                                class="form-control"
                                type="number"
                                name="payment"
                                value="{{ $vacancy->payment }}"
                            >
                            <div class="invalid-feedback"></div>
                        </div>

                        <button type="submit" class="btn btn-primary">Обновить</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    @include('js.validation')
    <script>
        const url = 'http://lawyers/storevacancy?id={{ $vacancy->id }}';
        const onSuccess = () => window.location.href = 'http://lawyers/vacancylist';

        setSubmitHandler(url, onSuccess);
    </script>
@endsection
