
    <nav class="container header-nav">
        <a class="image-container" href="{{ route__('actionIndex_controller') }}">Лого</a>
        @if(auth()->check() == false)
        <ul class="nav-ul js_nav">
            <li class="dropdown js_select-btn">
                <span class="cool-underline select-btn">Найти специалиста</span>
                <ul class="select-window js_select-window">
                    <li>
                        <a href="{{ route('actionFindSpecialist_controller') }}">
                            <p>Найти Юриста <span>placeholder</span></p>
                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11" fill="none">
                                <path d="M1 1L5 5.5L1 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('actionFindSpecialist_controller') }}">
                            <p>Найти Юриста <span>placeholder</span></p>
                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11" fill="none">
                                <path d="M1 1L5 5.5L1 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="{{ route__('actionVacancyExchange_controllers_employee_employeecontroller') }}" class="cool-underline select-btn">Биржа исполнителей</a>
            </li>
            <li>
                <a href="#" class="cool-underline select-btn">Вопросы юристам</a>
            </li>
            <li>
                <a href="#" class="cool-underline select-btn">Коллективные иски</a>
            </li>
        </ul>
        @elseif(session('type_id') == 1)
        <ul class="nav-ul js_nav">
            <li class="dropdown js_select-btn">
                <span class="cool-underline select-btn">Найти специалиста</span>
                <ul class="select-window js_select-window">
                    <li>
                        <a href="{{ route('actionFindSpecialist_controller') }}">
                            <p>Найти Юриста <span>placeholder</span></p>
                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11" fill="none">
                                <path d="M1 1L5 5.5L1 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('actionFindSpecialist_controller') }}">
                            <p>Найти Юриста <span>placeholder</span></p>
                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11" fill="none">
                                <path d="M1 1L5 5.5L1 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                    </li>
                </ul>
            </li>
            <li><a href="№" class="cool-underline select-btn">Услуги сайта</a></li>
            <li><a href="#" class="cool-underline select-btn">Вопросы юристам</a></li>
            <li><a href="#" class="cool-underline select-btn">Коллективные иски</a></li>
            <li><a href="#" class="cool-underline select-btn">Заказы</a></li>
        </ul>
        @else
        <ul class="nav-ul js_nav">
            <li class="dropdown js_select-btn">
                <span class="cool-underline select-btn">Специалисты</span>
                <ul class="select-window js_select-window">
                    <li>
                        <a href="{{ route('actionFindSpecialist_controller') }}">
                            <p>Найти Юриста <span>placeholder</span></p>
                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11" fill="none">
                                <path d="M1 1L5 5.5L1 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('actionFindSpecialist_controller') }}">
                            <p>Найти Юриста <span>placeholder</span></p>
                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11" fill="none">
                                <path d="M1 1L5 5.5L1 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="{{ route__('actionVacancyExchange_controllers_employee_employeecontroller') }}" class="cool-underline select-btn">Биржа заказов</a>
            </li>
            <li><a href="#" class="cool-underline select-btn">Вопросы юристам</a></li>
            <li><a href="#" class="cool-underline select-btn">Коллективные иски</a></li>
            <li><a href="#" class="cool-underline select-btn">Мои заказы</a></li>
        </ul>
        @endif

        @guest
            <a class="signup-btn" href="{{ route__('actionLogin_controllers_site_usercontroller') }}">Войти</a>
            {{--<a class="signup-btn" href="{{ route__('actionSignupClient_controllers_site_usercontroller') }}">Регистрация</a>--}}
        @endguest

        @auth
            @if(session('type_id') == 1)
                <a href="#" class="create-order cool-underline">Создать заказ</a>
                <div class="payment-message js_open_profile_nav">
                    <div class="message-icon user_ico">{{--<span class="count">1</span>--}}</div>
                    <div class="name">{{Auth::user()->first_name}}</div>
                    <ul class="profile_nav" name="profile_nav">
                        <li><a href="">Чат</a></li>
                        <li><a href="{{ route__('actionClientCabinet_controllers_client_clientcontroller') }}">Профиль</a></li>
                        <li><a href="">Помощь</a></li>
                        <li><a href="{{ route__('actionUserLogout_logincontroller') }}">Выход</a></li>
                    </ul>
                </div>
            @elseif(session('type_id') == 2)
                <div class="payment-message js_open_profile_nav">
                    <div class="message-icon user_ico">{{--<span class="count">1</span>--}}</div>
                    <div class="name">{{Auth::user()->first_name}}</div>
                    <ul class="profile_nav" name="profile_nav">
                        <li><a href="">Чат</a></li>
                        <li><a href="{{ route__('actionEmployeeCabinet_controllers_employee_employeecontroller') }}">Профиль</a></li>
                        <li><a href="">Помощь</a></li>
                        <li><a href="{{ route__('actionUserLogout_logincontroller') }}">Выход</a></li>
                    </ul>
                </div>
            @endif

        @endauth

        <button class="burger-btn popup-btn" type="button" data-popup="mobile-menu-popup"></button>
    </nav>
</header>

<div id="mobile-menu-popup" class="mobile-menu-popup popup mobile popup_hide">
    <div class="popup_shadow"></div>
    <div class="step step1 mobile-step_top step_hide" data-step="1">
    <div class="mob_header">
        <button class="popup-close"></button>
        <a href="{{ route__('actionIndex_controller') }}" class="logo image-container">Лого</a>
    </div>
    <h2 class="mob-header_title">Личный кабинет</h2>
    <ul class="mob_nav js_nav">
        <li class="js_select-btn">
            <span class="nav-item">Найти специалиста</span>
            <ul class="js_select-window">
                <li><a href="{{ route('actionFindSpecialist_controller') }}">Найти Юриста</a></li>
                <li><a href="{{ route('actionFindSpecialist_controller') }}">Найти Адвоката</a></li>
                <li><a href="{{ route('actionFindSpecialist_controller') }}">Найти Нотариуса</a></li>
                <li><a href="{{ route('actionFindSpecialist_controller') }}">Найти Детектива</a></li>
            </ul>
        </li>
        <li><a href="{{ route__('actionVacancyExchange_controllers_employee_employeecontroller') }}">Биржа заказов</a></li>
        <li><a href="">Вопросы юристам</a></li>
        <li><a href="">Коллективные иски</a></li>
    </ul>
    <a href="#" class="mob-create-order">Создать заказ</a>
    <div class="support-phone">
        <span>Поддержка</span>
        <p>+7 (999) 999 99 99</p>
    </div>
    </div>
</div>
