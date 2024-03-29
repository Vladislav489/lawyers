@extends('lawyers.layouts.main')
@section('title', 'Контракты (список)')

@section('content')
    <section class="mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h1 class="fs-2">Контракты (список)</h1>
                    @include('lawyers.contract._menu')

                    @include('component_build', [
                        'component' => 'component.gridComponent.simpleGrid',
                        'params_component' => [
                            'autostart' => 'true',
                            'name' => 'contract_list',
                            'url' => route__("actionGetContractList_contractmainstaycontroller"),

                            'template' => '<div>
                                <div v-for="item in data" class="card mt-3 border-primary" v-bind:data-id="item.id">
                                    <div class="card-body">
                                        <h5 class="card-title mb-4">Description: @{{ item.description }}</h5>
                                        <a
                                            v-bind:href=\'"' . route__('actionContractEdit_contractcontroller') . '?id=" + item.id\'
                                            class="btn btn-secondary"
                                        >Редактировать</a>
                                        <button
                                            type="button"
                                            class="btn btn-danger"
                                            style="pointer-events: all;"
                                            data-text="Удаляю"
                                            data-request-url="{{ route__("actionContractDelete_contractmainstaycontroller") }}?id="
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
    @include('js.delete-handler')
    <script>
        setDeleteBtnHandler();
    </script>
@endsection
