@extends('lawyers.layouts.layout')
@section('title', 'Мои вакансии')

@section('content')
    <section class="mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h1 class="mb-4 fs-3">Вакансии (список)</h1>

                    @include('component_build', [
                        'component' => 'component.gridComponent.simpleGrid',
                        'params_component' => [
                            'autostart' => 'true',
                            'name' => 'vacancy_list',
                            'url' => route__("actionGetVacancyList_clientmainstaycontroller"),

                            'template' => '<div>
                                <div v-for="item in data" class="card mt-3 border-primary" v-bind:data-id="item.id">
                                <div class="card-body">
                                    <h5 class="card-title">Description: @{{ item.description }}</h5>
                                    <p class="card-text">Payment: @{{ item.payment }} &#8381;</p>
                                    <a
                                        v-bind:href=\'"' . route__('actionEditVacancy_clientcontroller') . '?id=" + item.id\'
                                        class="btn btn-secondary"
                                    >Редактировать</a>
                                    <button
                                        type="button"
                                        class="btn btn-danger"
                                        style="pointer-events: all;"
                                        data-text="Удаляю"
                                    >Удалить</button>
                                </div>
                                </div>
                            </div>',

                            'autostart' => 'true',
                            'pagination' => [
                                'page' => 1,
                                'pageSize' => 14,
                                'countPage' => 1,
                                'typePagination' => 0,
                                'showPagination' => 1,
                                'showInPage' => 14,
                                'count_line' => 1,
                                'all_load' => 0,
                                'physical_presence' => 0
                            ],
                        ]
                    ])

                </div>
            </div>
        </div>
    </section>

    @include('js.util')
    @include('js.validation')
    @include('js.async-api')
    <script>
        setTimeout(() => {
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
        }, 1000);
    </script>
@endsection
