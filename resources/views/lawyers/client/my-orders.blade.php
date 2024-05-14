@extends('lawyers.layouts.main')


@section('title', 'Все заказы')

@section('content')

    @include('component_build', [
                            'component' => 'component.gridComponent.simpleGrid',
                            'params_component' => [
                                'autostart' => 'true',
                                'name' => 'offers',
								'url' => route__('actionGetVacancies_mainstay_client_clientmainstaycontroller'),
								'params' => ['user_id' => auth()->id(), 'is_group' => 0],
                                'template' =>
                            "
<section class='gradient-bg u-container request-table-section'>
    <div class='container'>

        <div class='request-table_container'>
            <nav class='lawsuit_nav'>

                <h2 class='lawyer-wrapper_title'>Мои заказы <span></span></h2>

                <form action='#' class='lawsuit_search'>
                    <label>
                        <input type='search' name='search_requests' placeholder='Найти по имени...'>
                        <input type='image' src='/lawyers/images/icons/search-icon-gray.svg' alt='search-icon'>
                    </label>
                </form>

                <ul>
                    <li mark='line_mark' class='active'>
                        <button type='button' @click.prevent=\"switchCategory(null)\">Все</button>
                    </li>
                    <li mark='line_mark' class='has_new'>
                        <button type='button' @click.prevent=\"switchCategory(1)\">Отклики <span>@{{ count_new_items }}</span></button>
                    </li>
                    <li mark='line_mark'>
                        <button type='button' @click.prevent=\"switchCategory(4)\">В работе</button>
                    </li>
                    <li mark='line_mark'>
                        <button type='button' @click.prevent=\"switchCategory(7)\">Выполненные</button>
                    </li>
                    <li mark='line_mark'>
                        <button type='button' @click.prevent=\"switchCategory(10)\">Отмененные</button>
                    </li>
                </ul>
            </nav>

            <table class='request-table mobile-hidden'>
                <tr>
                    <th>Описание задачи</th>
                    <th>Юрист</th>
                    <th>Дата начала</th>
                    <th>Выполнен</th>
                    <th>Стоимость</th>
                    <th>Статус</th>
                </tr>

                <tr v-for=\"vacancy in data\" @click.prevent=\"goToVacancyPage(vacancy.id)\">
                    <td class='description'>@{{ vacancy.title }}</td>
                    <td>@{{ vacancy.executor_name }}</td>
                    <td>@{{ vacancy.period_start ?? '---' }}</td>
                    <td>@{{ vacancy.period_end ?? '---' }}</td>
                    <td>@{{ vacancy.payment }} &#8381;</td>
                    <td class='status _moderation' :class=\"{'_success': vacancy.status == 7, '_error': vacancy.status == 10},\">@{{ vacancy.status_text }}</td>
                </tr>
            </table>

            <div class='request-table_mobile mobile'>
                <div class='request_unit' v-for=\"vacancy in data\">
                    <h3 class='request_header'>
                        @{{ vacancy.title }}
                    </h3>

                    <div class='request-row'>
                        <span class='request-row_left'>Статус</span>
                        <span class='request-row_right status'
                        :class=\"{'_success': vacancy.status == 7}\"
                        >@{{ vacancy.status_text }}</span>
                    </div>

                    <div class='request-row'>
                        <span class='request-row_left'>клиент</span>
                        <span class='fs-text'>@{{ vacancy.executor_name }}</span>
                    </div>

                    <div class='request-row'>
                        <span class='request-row_left'>Дата начала</span>
                        <span class='fs-text'>@{{ vacancy.at_work_from ?? '---' }}</span>
                    </div>

                    <div class='request-row'>
                        <span class='request-row_left'>Выполнен</span>
                        <span class='fs-text'>@{{ vacancy.at_work_to ?? '---' }}</span>
                    </div>

                    <div class='request-row'>
                        <span class='request-row_left'>Стоимость</span>
                        <span class='fs-text'>@{{ vacancy.payment }} &#8381;</span>
                    </div>
                </div>
                <button class='read-more'>Показать еще</button>
            </div>
        </div>
    </div>
</section>
                            ",
                            'pagination' => [
                                        'page' => 1,
                                        'pageSize' => 100,
                                        'countPage' => 1,
                                        'typePagination' => 2,
                                        'showPagination' => 1,
                                        'showInPage' => 2,
                                        'count_line' => 1,
                                        'all_load' => 0,
                                        'physical_presence' => 0
                                    ],
                            ]
                        ])
    {{--<section class="gradient-bg u-container request-table-section">--}}
    {{--    <div class="container">--}}
    {{--        <ul class="breadcrumbs mobile-hidden">--}}
    {{--            <li class="cool-underline"><a href="#">Юрист</a></li>--}}
    {{--            <li class="cool-underline"><a href="#">Город</a></li>--}}
    {{--        </ul>--}}

    {{--        <div class="request-table_container">--}}
    {{--            <nav class="lawsuit_nav">--}}

    {{--                <h2 class="lawyer-wrapper_title">Мои заказы <span>1/2</span></h2>--}}

    {{--                <form action="#" class="lawsuit_search">--}}
    {{--                    <label>--}}
    {{--                        <input type="search" name="search_requests" placeholder="Найти по имени...">--}}
    {{--                        <input type="image" src="/lawyers/images/icons/search-icon-gray.svg" alt="search-icon">--}}
    {{--                    </label>--}}
    {{--                </form>--}}

    {{--                <ul>--}}
    {{--                    <li class="has_new"><button type="button">Новые <span>12</span></button></li>--}}
    {{--                    <li><button type="button">Выполненные</button></li>--}}
    {{--                    <li><button type="button">Отмененные</button></li>--}}
    {{--                    <li class="active"><button type="button">Все</button></li>--}}
    {{--                </ul>--}}
    {{--            </nav>--}}

    {{--            <table class="request-table mobile-hidden">--}}
    {{--                <tr>--}}
    {{--                    <th>Описание задачи</th>--}}
    {{--                    <th>Юрист</th>--}}
    {{--                    <th>Дата начала</th>--}}
    {{--                    <th>Выполнен</th>--}}
    {{--                    <th>Стоимость</th>--}}
    {{--                    <th>Статус</th>--}}
    {{--                </tr>--}}

    {{--                <tr>--}}
    {{--                    <td class="description">Оглавление вопроса, который задал юрист... Оглавление вопроса, который задал--}}
    {{--                        юрист... Оглавление вопроса...</td>--}}
    {{--                    <td>Соколовский Владимир Александрович</td>--}}
    {{--                    <td>9 сен 2024</td>--}}
    {{--                    <td>---</td>--}}
    {{--                    <td>1300 &#8381;</td>--}}
    {{--                    <td class="status _moderation">модерация</td>--}}
    {{--                </tr>--}}

    {{--                <tr>--}}
    {{--                    <td class="description">Оглавление вопроса, который задал юрист... Оглавление вопроса, который--}}
    {{--                        задал юрист... Оглавление вопроса...</td>--}}
    {{--                    <td>Соколовский Владимир Александрович</td>--}}
    {{--                    <td>9 сен 2024</td>--}}
    {{--                    <td>10 окт 2024</td>--}}
    {{--                    <td>1300 &#8381;</td>--}}
    {{--                    <td class="status _success">решен</td>--}}
    {{--                </tr>--}}

    {{--                <tr>--}}
    {{--                    <td class="description">Оглавление вопроса, который задал юрист... Оглавление вопроса, который задал--}}
    {{--                        юрист... Оглавление вопроса...</td>--}}
    {{--                    <td>Соколовский Владимир Александрович</td>--}}
    {{--                    <td>9 сен 2024</td>--}}
    {{--                    <td>---</td>--}}
    {{--                    <td>1300 &#8381;</td>--}}
    {{--                    <td class="status _moderation">модерация</td>--}}
    {{--                </tr>--}}

    {{--                <tr>--}}
    {{--                    <td class="description">Оглавление вопроса, который задал юрист... Оглавление вопроса, который задал--}}
    {{--                        юрист... Оглавление вопроса...</td>--}}
    {{--                    <td>Соколовский Владимир Александрович</td>--}}
    {{--                    <td>9 сен 2024</td>--}}
    {{--                    <td>---</td>--}}
    {{--                    <td>1300 &#8381;</td>--}}
    {{--                    <td class="status _moderation">модерация</td>--}}
    {{--                </tr>--}}

    {{--                <tr>--}}
    {{--                    <td class="description">Оглавление вопроса, который задал юрист... Оглавление вопроса, который--}}
    {{--                        задал юрист... Оглавление вопроса...</td>--}}
    {{--                    <td>Соколовский Владимир Александрович</td>--}}
    {{--                    <td>9 сен 2024</td>--}}
    {{--                    <td>10 окт 2024</td>--}}
    {{--                    <td>1300 &#8381;</td>--}}
    {{--                    <td class="status _error">отменен</td>--}}
    {{--                </tr>--}}

    {{--                <tr>--}}
    {{--                    <td class="description">Оглавление вопроса, который задал юрист... Оглавление вопроса, который--}}
    {{--                        задал юрист... Оглавление вопроса...</td>--}}
    {{--                    <td>Соколовский Владимир Александрович</td>--}}
    {{--                    <td>9 сен 2024</td>--}}
    {{--                    <td>10 окт 2024</td>--}}
    {{--                    <td>1300 &#8381;</td>--}}
    {{--                    <td class="status _error">отменен</td>--}}
    {{--                </tr>--}}
    {{--            </table>--}}

    {{--            <div class="request-table_mobile mobile">--}}
    {{--                <div class="request_unit">--}}
    {{--                    <h3 class="request_header">--}}
    {{--                        Оглавление вопроса, который задал юрист... Оглавление вопроса, который задал юрист...--}}
    {{--                        Оглавление вопроса, который задал юрист...--}}
    {{--                    </h3>--}}

    {{--                    <div class="request-row">--}}
    {{--                        <span class="request-row_left">Статус</span>--}}
    {{--                        <span class="request-row_right status _moderation">модерация</span>--}}
    {{--                    </div>--}}

    {{--                    <div class="request-row">--}}
    {{--                        <span class="request-row_left">юрист</span>--}}
    {{--                        <span class="fs-text">Соколовский Владимир Александрович</span>--}}
    {{--                    </div>--}}

    {{--                    <div class="request-row">--}}
    {{--                        <span class="request-row_left">Дата начала</span>--}}
    {{--                        <span class="fs-text">9 сен 2024</span>--}}
    {{--                    </div>--}}

    {{--                    <div class="request-row">--}}
    {{--                        <span class="request-row_left">Выполнен</span>--}}
    {{--                        <span class="fs-text">---</span>--}}
    {{--                    </div>--}}

    {{--                    <div class="request-row">--}}
    {{--                        <span class="request-row_left">Стоимость</span>--}}
    {{--                        <span class="fs-text">1300 &#8381;</span>--}}
    {{--                    </div>--}}
    {{--                </div>--}}

    {{--                <div class="request_unit">--}}
    {{--                    <h3 class="request_header">--}}
    {{--                        Оглавление вопроса, который задал юрист... Оглавление вопроса, который задал юрист...--}}
    {{--                        Оглавление вопроса, который задал юрист...--}}
    {{--                    </h3>--}}

    {{--                    <div class="request-row">--}}
    {{--                        <span class="request-row_left">Статус</span>--}}
    {{--                        <span class="request-row_right status _error">отменен</span>--}}
    {{--                    </div>--}}

    {{--                    <div class="request-row">--}}
    {{--                        <span class="request-row_left">юрист</span>--}}
    {{--                        <span class="fs-text">Соколовский Владимир Александрович</span>--}}
    {{--                    </div>--}}

    {{--                    <div class="request-row">--}}
    {{--                        <span class="request-row_left">Дата начала</span>--}}
    {{--                        <span class="fs-text">9 сен 2024</span>--}}
    {{--                    </div>--}}

    {{--                    <div class="request-row">--}}
    {{--                        <span class="request-row_left">Выполнен</span>--}}
    {{--                        <span class="fs-text">---</span>--}}
    {{--                    </div>--}}

    {{--                    <div class="request-row">--}}
    {{--                        <span class="request-row_left">Стоимость</span>--}}
    {{--                        <span class="fs-text">1300 &#8381;</span>--}}
    {{--                    </div>--}}
    {{--                </div>--}}

    {{--                <div class="request_unit">--}}
    {{--                    <h3 class="request_header">--}}
    {{--                        Оглавление вопроса, который задал юрист... Оглавление вопроса, который задал юрист...--}}
    {{--                        Оглавление вопроса, который задал юрист...--}}
    {{--                    </h3>--}}

    {{--                    <div class="request-row">--}}
    {{--                        <span class="request-row_left">Статус</span>--}}
    {{--                        <span class="request-row_right status _success">решен</span>--}}
    {{--                    </div>--}}

    {{--                    <div class="request-row">--}}
    {{--                        <span class="request-row_left">юрист</span>--}}
    {{--                        <span class="fs-text">Соколовский Владимир Александрович</span>--}}
    {{--                    </div>--}}

    {{--                    <div class="request-row">--}}
    {{--                        <span class="request-row_left">Дата начала</span>--}}
    {{--                        <span class="fs-text">9 сен 2024</span>--}}
    {{--                    </div>--}}

    {{--                    <div class="request-row">--}}
    {{--                        <span class="request-row_left">Выполнен</span>--}}
    {{--                        <span class="fs-text">---</span>--}}
    {{--                    </div>--}}

    {{--                    <div class="request-row">--}}
    {{--                        <span class="request-row_left">Стоимость</span>--}}
    {{--                        <span class="fs-text">1300 &#8381;</span>--}}
    {{--                    </div>--}}
    {{--                </div>--}}

    {{--                <button class="read-more">Показать еще</button>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}
    {{--</section>--}}

    <script>
        function switchCategory(categoryId) {
            // STATUS_NEW = 1;
            // STATUS_MODERATION = 2;
            // STATUS_PAYED = 3;
            // STATUS_IN_PROGRESS = 4;
            // STATUS_INSPECTION = 5;
            // STATUS_ACCEPTED = 6;
            // STATUS_CLOSED = 7;
            // STATUS_CANCELLED = 8;
            let component = page__.getElementsGroup('offers')[0]['obj']
            $('nav[class = lawsuit_nav] > ul > li[mark=line_mark]').removeClass('active')
            event.currentTarget.parentElement.classList.add('active')
            component.setUrlParams(Object.assign({}, component.params, {status: categoryId}))
        }

        function goToVacancyPage(vacancyId) {
            window.location.href = `{{ route__('actionViewVacancy_controllers_client_clientcontroller') }}/${vacancyId}`
        }
    </script>
@endsection

