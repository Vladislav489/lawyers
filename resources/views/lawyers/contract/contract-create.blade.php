@extends('lawyers.layouts.main')
@section('title', 'Мои вакансии')

@section('content')
    <section class="mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h1 class="fs-2">Контракты (создание)</h1>
                    @include('lawyers.contract._menu')

                    <form
                        id="vacancy-form"
                        class="mt-4 mb-5 p-3 bg-primary-subtle"
                        action=""
                        method="post"
                        enctype="application/x-www-form-urlencoded"
                        style="border: 1px dashed"
                        data-request-url="{{ route__('actionContractStore_contractmainstaycontroller') }}"
                        data-success-url="{{ route__('actionContractList_contractcontroller')}}"
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
