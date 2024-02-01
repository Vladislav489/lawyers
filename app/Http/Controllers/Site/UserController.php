<?php

namespace App\Http\Controllers\Site;

use App\Models\System\ControllersModel\CentralController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends CentralController {
    public function actionLogin() {
        return view('lawyers.user.login');
    }

    public function actionSignupClient() {
        return view('lawyers.user.signup-client');
    }

    public function actionSignupEmployee() {
        return view('lawyers.user.signup-employee');
    }

    public function getPageParams(): array {
        return [];
    }
}
