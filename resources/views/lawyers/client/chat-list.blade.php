@extends('lawyers.layouts.layout')
@section('title', 'Мои вакансии')

@section('content')
    <section class="mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h1 class="fs-2">Чаты (список)</h1>
                    <div class="btn-group my-3">
                        <a
                            href="{{ route__('actionChatList_clientcontroller') }}"
                            class="btn btn-outline-primary active"
                        >Список</a>
                        <a
                            href="{{ route__('actionChatCreate_clientcontroller') }}"
                            class="btn btn-outline-primary"
                        >Создание</a>
                    </div>

                    @include('component_build', [
                        'component' => 'component.gridComponent.simpleGrid',
                        'params_component' => [
                            'autostart' => 'true',
                            'name' => 'vacancy_list',
                            'url' => route__("actionGetChatList_chatmainstaycontroller"),

                            'template' => '<div>
                                <div v-for="item in data" class="card mt-3 border-primary" v-bind:data-id="item.id">
                                <div class="card-body">
                                    <h5 class="card-title">Name: @{{ item.name }}</h5>
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
@endsection
