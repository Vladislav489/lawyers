@extends('lawyers.layouts.main')
@section('title', 'Мои вакансии')

@section('content')
    <section class="mt-5">
        <div class="container">
            <div class="ro">
                <div class="col-6">
                    <h1 class="mb-4 fs-3">Мои вакансии</h1>

                    @forelse (Auth::user()->vacancies as $vacancy)
                        <div class="card mt-3 border-primary" data-id="{{ $vacancy->id }}">
                            <div class="card-body">
                                <h5 class="card-title">Description: {{ $vacancy->description }}</h5>
                                <p class="card-text">Payment: {{ $vacancy->payment }} &#8381;</p>
                                <a href="http://lawyers/editvacancy?id={{ $vacancy->id }}" class="btn btn-secondary btn-sm">Редактировать</a>
                                <a href="#" class="btn btn-danger btn-sm" data-delete>Удалить</a>
                            </div>
                        </div>
                    @empty
                    @endforelse

                </div>
            </div>
        </div>
    </section>

    @include('js.validation')
    <script>
        const deleteBtnElements = document.querySelectorAll('[data-delete]');

        deleteBtnElements.forEach((btnElement) => {
            btnElement.addEventListener('click', (evt) => {
                const cardElement = evt.target.closest('.card[data-id]');

                if (cardElement) {
                    const vacancyId = +cardElement.dataset.id;
                    const url = `http://lawyers/deletevacancy?id=${vacancyId}`;
                    sendDeleteRequest(url).then((response) => {
                        if (!response.errors) {
                            window.location.reload();
                        }
                    });
                }
            });
        });
    </script>
@endsection
