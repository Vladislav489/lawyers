<?php

namespace App\Http\Controllers\Login;

use App\Models\System\ControllersModel\CentralController;
use App\Models\System\General\Routs;
use App\Models\System\SystemLog;
use Illuminate\Routing\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class LoginController extends CentralController
{
    public function getPageParams(): array
    {
        return [
            'actionIndex' => ['name' => 'Главная', 'chpu' => []],
            'actionIn' => ['name' => 'Вход', 'chpu' => []],
            'actionLoguot' =>['name' => 'Выход', 'chpu' => []],
        ];
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
                return redirect()->intended('/registeruser');
            }

            return back()->withErrors([
                'password' => 'Неправильный номер телефона или пароль'
            ])->onlyInput('phone_number', 'password');
        }

        return view('lawyers.user.login');
    }

    // public function actionIn()
    // {
    //     $request = request();

    //     if ($request->post('api') == 1) {
    //         $credentials = $request->only('email', 'password');
    //         $res = Auth::attempt($credentials, true);
    //         return Response::json([$request->all(), $res], 200);
    //     }

    //     $credentials = $request->only('email', 'password');

    //     if (Auth::attempt($credentials, true)) {
    //         return redirect(url('/'));
    //     } else {
    //         return view('Site.Login.login', [
    //             'mess' => 'User or Password do not current'
    //         ]);
    //     }
    // }

    public function actionLogout()
    {
        Auth::logout();
        return redirect('/registeruser');
    }
}
