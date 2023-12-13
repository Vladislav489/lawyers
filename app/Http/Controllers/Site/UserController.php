<?php

namespace App\Http\Controllers\Site;

use App\Models\System\ControllersModel\CentralController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends CentralController
{
    const EMPLOYEE_TYPE_ID = 2;
    const MAX_FILE_SIZE = 5;

    public function getPageParams(): array
    {
        return [];
    }

    public function callAction($method, $parameters)
    {
        if (
            (Auth::check() && $method !== 'actionLogout')
            || (!Auth::check() && $method === 'actionLogout')
        ) {
            return redirect('/main');
        }

        return parent::callAction($method, $parameters);
    }

    public function actionLogin(Request $request)
    {
        if ($request->isMethod('post')) {
            $credentials = $request->validate([
                'phone_number' => 'required|string',
                'password' => 'required|string',
            ]);

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                return redirect()->intended('/main');
            }

            return back()->withErrors([
                'password' => 'Неправильный номер телефона или пароль'
            ])->onlyInput('phone_number', 'password');
        }

        return view('lawyers.user.login');
    }

    public function actionLogout()
    {
        Auth::logout();
        return redirect('/main');
    }

    public function actionSignupClient()
    {
        return view('lawyers.user.signup-client');
    }

    public function actionSignupEmployee()
    {
        return view('lawyers.user.signup-employee');
    }
}
