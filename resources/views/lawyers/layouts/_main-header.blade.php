
    <nav class="container header-nav">
        <a class="image-container" href="{{ route__('actionIndex_controller') }}">Лого</a>
        <ul class="nav-ul js_nav">
            <li class="dropdown js_select-btn">
                <a href="{{ route('actionFindSpecialist_controller') }}" class="cool-underline select-btn">Найти специалиста</a>
                <ul class="select-window js_select-window">
                    <li>
                        <a href="{{ route('actionFindSpecialist_controller') }}">
                            <p>Юрист <span>placeholder</span></p>
                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11" fill="none">
                                <path d="M1 1L5 5.5L1 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                    </li>
                    <li class='noactive' name='noactive'>
                        <a  href="#">
                            <p>Адвокат <span>placeholder</span></p>
                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11" fill="none">
                                <path d="M1 1L5 5.5L1 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                    </li>
                    <li class='noactive' name='noactive'>
                        <a  href="#">
                            <p>Нотариус <span>placeholder</span></p>
                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11" fill="none">
                                <path d="M1 1L5 5.5L1 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                    </li>
                    <li class='noactive' name='noactive'>
                        <a  href="#">
                            <p>Детектив <span>placeholder</span></p>
                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11" fill="none">
                                <path d="M1 1L5 5.5L1 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                    </li>
                </ul>
            </li>
            @if(session('type_id') == 2)
            <li><a href="{{ route__('actionVacancyExchange_controllers_employee_employeecontroller') }}" class="cool-underline select-btn">Биржа заказов</a></li>
            @endif
            @guest
            <li>
                <a href="{{ route__('actionFindSpecialist_controller') }}" class="cool-underline select-btn">Биржа исполнителей</a>
            </li>
            @endguest
            <li class='noactive' name='noactive'><a href="#" class="cool-underline select-btn">Вопросы юристам</a></li>
            <li class='noactive' name='noactive'><a href="#" class="cool-underline select-btn">Коллективные иски</a></li>

            @if(session('type_id') == 1)
            <li class='noactive' name='noactive'><a href="#" class="cool-underline select-btn">Услуги сайта</a></li>
            <li><a href="{{ route__('actionMyOrders_controllers_client_clientcontroller') }}" class="cool-underline select-btn">Заказы</a></li>
            @elseif(session('type_id') == 2)
                <li><a href="{{ route__('actionViewOrders_controllers_employee_employeecontroller') }}" class="cool-underline select-btn">Мои заказы</a></li>
            @endif
        </ul>

        @guest
            <a class="signup-btn" href="{{ route__('actionLogin_controllers_site_usercontroller') }}">Войти</a>
        @endguest

        @auth
            @if(session('type_id') == 1)
                <a href="{{ route__('actionCreateVacancy_controllers_client_clientcontroller') }}" class="create-order cool-underline">Создать заказ</a>
            @endif
                <div class="payment-message js_open_profile_nav">
                    <div class="message-icon user_ico">
                        @include('component_build',[
	                    "component" => 'component.infoComponent.textInfo',
                        "params_component" => [
                            "autostart" => 'true',
                            "name" => "notification_counter",
                            "url" => route("actionGetNotificationsCount_mainstay_user_usermainstaycontroller"),
                            "params" => ['user_id' => auth()->id()],
				        	"callAfterloadComponent" => "function() {
				        	}",
                            "template" => "<span v-if=\"data\" class='count'>@{{ data }}</span>"
                            ]
                        ])
                    </div>
                        @include('component_build',[
	                    "component" => 'component.infoComponent.textInfo',
                        "params_component" => [
                            "autostart" => 'false',
                            "name" => "notification_list",
                            "url" => route("actionGetNotifications_mainstay_user_usermainstaycontroller"),
                            "params" => ['user_id' => auth()->id()],
				        	"callAfterloadComponent" => "function() {
				        	}",
                            "template" => "
                            <ul v-if='data.length > 0' data-name='notification-nav' class='notification_dropdown'>
                                <li v-for=\"notification in data\"
                                    v-bind:data-notification-id=\"notification.id\"
                                    v-bind:data-notification-status=\"notification.is_read\"
                                ><p>@{{ notification.message }}</p>
                                    <p>@{{ notification.date }}</p>
                                    <a v-if=\"notification.is_read === 0\" @click.prevent=\"readNotification(notification.id, notification.is_read)\">Отметить как прочитанное</a>
                                </li>
                            </ul>"
                            ]
                        ])
                    <div class="name">{{Auth::user()->first_name}}</div>
                    <ul class="profile_nav" name="profile_nav">
                        <li><a href="{{ route__('actionChatList_controllers_chat_chatcontroller') }}">Чат</a></li>
                        @if(session('type_id') == 1)
                            <li><a href="{{ route__('actionClientCabinet_controllers_client_clientcontroller') }}">Профиль</a></li>
                        @else
                            <li><a href="{{ route__('actionEmployeeCabinet_controllers_employee_employeecontroller') }}">Профиль</a></li>
                        @endif
                        <li class='noactive' name='noactive'><a href="#">Помощь</a></li>
                        <li><a href="{{ route__('actionUserLogout_logincontroller') }}">Выход</a></li>
                    </ul>
                </div>
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
        @if(!auth()->check())
        <ul class="mob_nav js_mob_nav">
            <li class="dropdown js_select-btn_mob">
                <span class="nav-item">Найти специалиста</span>
                <ul class="js_select-window">
                    <li><a href="{{ route('actionFindSpecialist_controller') }}">Найти Юриста</a></li>
                    <li class='noactive' name='noactive'><a href="{{ route('actionFindSpecialist_controller') }}">Найти Адвоката</a></li>
                    <li class='noactive' name='noactive'><a href="{{ route('actionFindSpecialist_controller') }}">Найти Нотариуса</a></li>
                    <li class='noactive' name='noactive'><a href="{{ route('actionFindSpecialist_controller') }}">Найти Детектива</a></li>
                </ul>
            </li>
            <li><a href="{{ route__('actionVacancyExchange_controllers_employee_employeecontroller') }}" class="cool-underline select-btn">Биржа исполнителей</a></li>
            <li class='noactive' name='noactive'><a href="#">Вопросы юристам</a></li>
            <li class='noactive' name='noactive'><a href="#">Коллективные иски</a></li>
        </ul>
        @elseif(session('type_id') == 1)
        <ul class="mob_nav js_nav">
            <li class="dropdown js_select-btn_mob">
                <span class="nav-item">Найти специалиста</span>
                <ul class="js_select-window">
                    <li><a href="{{ route('actionFindSpecialist_controller') }}">Найти Юриста</a></li>
                    <li class='noactive' name='noactive'><a href="{{ route('actionFindSpecialist_controller') }}">Найти Адвоката</a></li>
                    <li class='noactive' name='noactive'><a href="{{ route('actionFindSpecialist_controller') }}">Найти Нотариуса</a></li>
                    <li class='noactive' name='noactive'><a href="{{ route('actionFindSpecialist_controller') }}">Найти Детектива</a></li>
                </ul>
            </li>
            <li class='noactive' name='noactive'><a href="#">Услуги сайта</a></li>
            <li class='noactive' name='noactive'><a href="#">Вопросы юристам</a></li>
            <li class='noactive' name='noactive'><a href="#">Коллективные иски</a></li>
            <li><a href="{{ route__('actionMyOrders_controllers_client_clientcontroller') }}">Заказы</a></li>
        </ul>
        <a href="{{ route__('actionCreateVacancy_controllers_client_clientcontroller') }}" class="mob-create-order">Создать заказ</a>
        @else
        <ul class="mob_nav js_mob_nav">
            <li class="dropdown js_select-btn_mob">
                <span class="nav-item">Специалисты</span>
                <ul class="js_select-window">
                    <li><a href="{{ route('actionFindSpecialist_controller') }}">Найти Юриста</a></li>
                    <li class='noactive' name='noactive'><a href="{{ route('actionFindSpecialist_controller') }}">Найти Адвоката</a></li>
                    <li class='noactive' name='noactive'><a href="{{ route('actionFindSpecialist_controller') }}">Найти Нотариуса</a></li>
                    <li class='noactive' name='noactive'><a href="{{ route('actionFindSpecialist_controller') }}">Найти Детектива</a></li>
                </ul>
            </li>
            <li><a href="{{ route__('actionVacancyExchange_controllers_employee_employeecontroller') }}" class="cool-underline select-btn">Биржа заказов</a></li>
            <li class='noactive' name='noactive'><a href="#">Вопросы юристам</a></li>
            <li class='noactive' name='noactive'><a href="#">Коллективные иски</a></li>
            <li><a href="{{ route__('actionViewOrders_controllers_employee_employeecontroller') }}">Мои заказы</a></li>
        </ul>
        @endif
    <div class="support-phone">
        <span>Поддержка</span>
        <p>+7 (999) 999 99 99</p>
    </div>
    </div>
</div>

    @auth
    <script>
        document.querySelector('.message-icon').addEventListener('click', function() {
            page__.getElementsGroup('notification_list')[0]['obj'].setUrlParams({user_id: {{ auth()->id() }} })
        })

        function readNotification(notificationId, notificationStatus) {
            if (notificationStatus === 0) {
                page__.sendData(
                    '{{ route__('actionReadNotification_mainstay_user_usermainstaycontroller') }}',
                    {id: notificationId},
                    function(data) {
                        if (data) {
                            let notificationListObj = page__.getElementsGroup('notification_list')[0]['obj']
                            notificationListObj.data = notificationListObj.data.find(notification => notification.id === notificationId).is_read = 1
                            console.log(page__.getElementsGroup('notification_counter')[0]['obj'].data = --page__.getElementsGroup('notification_counter')[0]['obj'].data)
                        }
                    }
                )
            }
        }
    </script>
    @endauth
