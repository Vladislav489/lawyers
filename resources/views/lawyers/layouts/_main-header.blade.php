<header class="u-container main-bg">
    <nav class="container header-nav">
        <a class="image-container" href="{{ route__('actionIndex_controller') }}">Лого</a>
        <ul class="nav-ul ">
            <li>
                <span class="cool-underline select-btn">Найти специалиста</span>
                <img class="arrow-icon sub-icon" src="/lawyers/images/icons/arrow-icon-white.svg" alt="arrow-icon">
                <ul class="select-window">
                    <li>
                        <a href="{{ route('actionFindSpecialist_controller') }}" style="text-decoration: none;">
                            <p>Найти Юриста <span>placeholder</span></p>
                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11" fill="none">
                                <path d="M1 1L5 5.5L1 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('actionFindSpecialist_controller') }}" style="text-decoration: none;">
                            <p>Найти Юриста <span>placeholder</span></p>
                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11" fill="none">
                                <path d="M1 1L5 5.5L1 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="{{ route__('actionVacancyExchange_controllers_employee_employeecontroller') }}" class="cool-underline select-btn" style="text-decoration: none;">Биржа заказов</a>
            </li>
            <li>
                <a href="#" class="cool-underline select-btn" style="text-decoration: none;">Вопросы юристам</a>
            </li>
            <li>
                <a href="#" class="cool-underline select-btn" style="text-decoration: none;">Коллективные иски</a>
            </li>
        </ul>
        @guest
            <a class="signup-btn" href="{{ route__('actionLogin_controllers_site_usercontroller') }}" style="text-decoration: none;">Войти</a>
            <a class="signup-btn" href="{{ route__('actionSignupClient_controllers_site_usercontroller') }}" style="margin-left: initial; text-decoration: none;">Регистрация</a>
        @endguest

        @auth
            @if(session('type_id') == 1)
                <a class="signup-btn" href="{{ route__('actionClientCabinet_controllers_client_clientcontroller') }}" style="margin-left: auto; text-decoration: none;">Профиль</a>
            @elseif(session('type_id') == 2)
                <a class="signup-btn" href="{{ route__('actionEmployeeCabinet_controllers_employee_employeecontroller') }}" style="margin-left: auto; text-decoration: none;">Профиль</a>
            @endif
            <a class="signup-btn" href="{{ route__('actionUserLogout_logincontroller') }}" style="margin-left: auto; text-decoration: none;">Выход</a>
        @endauth

        <button class="burger-btn popup-btn" type="button" data-popup="mobile-menu-popup">
            <picture>
                <img src="/lawyers/images/icons/burger-icon.svg" alt="burger-icon">
            </picture>
        </button>
    </nav>
</header>
