<ul class="round-top_nav">
    <li class="{{ $lawyer == 0 ? 'active' : '' }}">
        <a href="{{ route__('actionSignupClient_controllers_site_usercontroller') }}">Клиент</a>
    </li>
    <li class="{{ $lawyer == 1 ? 'active' : '' }}">
        <a href="{{ route__('actionSignupEmployee_controllers_site_usercontroller') }}">Юрист</a>
    </li>
</ul>
