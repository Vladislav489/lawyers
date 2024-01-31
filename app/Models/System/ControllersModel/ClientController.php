<?php

namespace App\Models\System\ControllersModel;

use Illuminate\Support\Facades\Auth;

class ClientController extends CentralController {
    public function callAction($method, $parameters) {
        if (Auth::check() && request()->session()->get('type_id') == 1) {
            return parent::callAction($method, $parameters);
        }
        return redirect(route__('actionLogin_controllers_site_usercontroller'));
    }

    public function getPageParams() {
        return [];
    }
}
