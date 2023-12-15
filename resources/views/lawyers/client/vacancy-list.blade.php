@extends('lawyers.layouts.main')
@section('title', 'Мои вакансии')

@section('content')
    <section class="mt-5">
        <div class="container">
            <div class="ro">
                <div class="col-lg-6">
                    <h1 class="mb-4 fs-3">Мои вакансии</h1>

                    @forelse (Auth::user()->vacancies as $vacancy)
                        <div class="card mt-3 border-primary" data-id="{{ $vacancy->id }}">
                            <div class="card-body">
                                <h5 class="card-title">Description: {{ $vacancy->description }}</h5>
                                <p class="card-text">Payment: {{ $vacancy->payment }} &#8381;</p>
                                <a href="{{ route__('actionEditVacancy_clientcontroller') }}?id={{ $vacancy->id }}" class="btn btn-secondary">Редактировать</a>
                                <button
                                    type="button"
                                    class="btn btn-danger"
                                    style="pointer-events: all;"
                                    data-text="Удаляю"
                                >Удалить</button>
                            </div>
                        </div>
                    @empty
                    @endforelse

                </div>
            </div>
        </div>
    </section>

    @include('js.util')
    @include('js.validation')
    @include('js.async-api')
    <script>
        const deleteBtnElements = document.querySelectorAll('.btn-danger');

        deleteBtnElements.forEach((btnElement) => {
            btnElement.addEventListener('click', (evt) => {
                const cardElement = evt.target.closest('.card[data-id]');

                if (cardElement) {
                    const vacancyId = +cardElement.dataset.id;
                    const url = `{{ route__('actionDeleteVacancy_clientmainstaycontroller') }}?id=${vacancyId}`;
                    blockButton(btnElement);
                    setTimeout(() => {
                        sendDeleteRequest(url).then((response) => {
                            if (!response.errors) {
                                window.location.reload();
                            }
                        }).finally(() => {
                            unblockButton(btnElement);
                        });
                    }, 2000);
                }
            });
        });
    </script>
@endsection
