<?php

use Illuminate\Support\Facades\Route;

?>
<nav class="navbar navbar-expand-lg bg-dark" data-bs-theme="dark">
    <div class="container">
        <a class="navbar-brand" href="{{ route__('actionMain_controller') }}">{{ env('APP_NAME') }}</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

                @guest
                    <li class="nav-item">
                        <a @class([
                        'nav-link',
                        'active' => str_contains(Route::currentRouteName(), 'actionSignup')
                        ]) href="{{ route__('actionSignupClient_controllers_site_usercontroller') }}">Регистрация</a>
                    </li>
                    <li class="nav-item">
                        <a @class([
                        'nav-link',
                        'active' => Route::currentRouteName() === 'actionLogin_controllers_site_usercontroller'
                        ]) href="{{ route__('actionLogin_controllers_site_usercontroller') }}">Вход</a>
                    </li>
                @endguest

                @auth
                    <?php $user = Auth::user(); ?>
                    <li class="nav-item dropdown" data-bs-theme="light">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ $user->first_name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @if ($user->type->name === 'client')
                                <li><a class="dropdown-item" href="{{ route__('actionClientCabinet_clientcontroller') }}">Кабинет клиента</a></li>
                                <li><a class="dropdown-item" href="{{ route__('actionCreateVacancy_clientcontroller') }}">Вакансии (создание)</a></li>
                                <li><a class="dropdown-item" href="{{ route__('actionVacancyList_clientcontroller') }}">Вакансии (список)</a></li>
                                <li><a class="dropdown-item" href="{{ route__('actionChatList_clientcontroller') }}">Чаты</a></li>
                                <li><a class="dropdown-item" href="{{ route__('actionContractList_clientcontroller') }}">Контракты</a></li>
                            @else
                                <li><a class="dropdown-item" href="{{ route__('actionEmployeeCabinet_employeecontroller') }}">Кабинет сотрудника</a></li>
                                <li><a class="dropdown-item" href="{{ route__('actionEmployeeSettings_employeecontroller') }}">Настройки сотрудника</a></li>
                            @endif

                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route__('actionLogout_usercontroller') }}">Выход</a></li>
                        </ul>
                    </li>
                @endauth

            </ul>
        </div>
    </div>
</nav>
