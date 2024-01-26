<?php

namespace App\Models\System\ControllersModel;

use Illuminate\Support\Facades\Auth;

class ClientController extends CentralController {
    public function callAction($method, $parameters) {
        if (!Auth::guard('web')->check()) {
            return redirect(route__('actionLogin_controllers_site_usercontroller'));
        }
        return parent::callAction($method, $parameters);
    }

    public function getPageParams() {
        return [];
    }
}
