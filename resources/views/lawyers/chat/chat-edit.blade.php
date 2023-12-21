@extends('lawyers.layouts.main')
@section('title', 'Мои вакансии')

@section('content')
    <section class="mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h1 class="fs-2">Чат «<span></span>» (редактирование)</h1>
                    @include('lawyers.chat._menu')

                    <form
                        id="vacancy-form"
                        class="mt-4 mb-5 p-3 bg-primary-subtle"
                        action=""
                        method="post"
                        enctype="application/x-www-form-urlencoded"
                        style="border: 1px dashed"
                        data-request-url="{{ route__('actionChatStore_chatmainstaycontroller') }}"
                        data-success-url="{{ route__('actionChatList_chatcontroller')}}"
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

        fetch(`http://lawyers/mainstay/chat/getchat?id=${params.id}`)
            .then((response) => response.json())
            .then((data) => {
                const url = `{{ route__('actionChatStore_chatmainstaycontroller') }}?id=${params.id}`;

                document.querySelector('form').dataset.requestUrl = url;
                document.querySelector('form [name=name]').textContent = data.name;
                document.querySelector('h1 span').textContent = data.name;

                setSubmitHandler();
            });
    </script>
@endsection
