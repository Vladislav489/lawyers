@extends('lawyers.layouts.main')
@section('title', 'Сервис (редактирование)')

@push('bootstrap')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
@endpush

@section('content')
    <section class="mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h1 class="fs-2">Сервис «<span></span>» (редактирование)</h1>
                    @include('lawyers.service._menu')

                    <form
                        id="vacancy-form"
                        class="mt-4 mb-5 p-3 bg-primary-subtle"
                        action=""
                        method="post"
                        enctype="application/x-www-form-urlencoded"
                        style="border: 1px dashed"
                        data-request-url="{{ route__('actionServiceStore_servicemainstaycontroller') }}"
                        data-success-url="{{ route__('actionServiceList_servicecontroller')}}"
                    >
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label class="form-label" for="name">Название</label>
                            <textarea
                                id="name"
                                class="form-control"
                                name="name"
                            ></textarea>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="desc">Описание</label>
                            <textarea
                                id="desc"
                                class="form-control"
                                name="description"
                            ></textarea>
                            <div class="invalid-feedback"></div>
                        </div>

                        <input type="hidden" name="type_id" value="1">

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

        fetch(`http://lawyers/mainstay/service/getservice?id=${params.id}`)
            .then((response) => response.json())
            .then((data) => {
                const url = `{{ route__('actionServiceStore_servicemainstaycontroller') }}?id=${params.id}`;

                document.querySelector('form').dataset.requestUrl = url;
                document.querySelector('form [name=name]').textContent = data.name;
                document.querySelector('form [name=description]').textContent = data.description;
                document.querySelector('h1 span').textContent = data.name;

                setSubmitHandler();
            });
    </script>
@endsection
