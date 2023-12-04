<?php

namespace App\Http\Controllers\Site;

use App\Models\CoreEngine\LogicModels\User\UserLogic;
use App\Models\CoreEngine\ProjectModels\User\UserEntity;
use App\Models\System\ControllersModel\CentralController;
use App\Models\System\General\Routs;
use App\Models\System\SystemLog;
use Illuminate\Routing\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class UserController extends CentralController
{
    public function getPageParams(): array
    {
        return [];
    }

    public function actionLogin(Request $request)
    {
        if (Auth::check()) {
            return redirect('/main');
        }

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
        if (!Auth::check()) {
            return redirect('/main');
        }

        Auth::logout();
        return redirect('/main');
    }

    public function actionSignup()
    {
        if (Auth::check()) {
            return redirect('/main');
        }

        return view('lawyers.user.signup');
    }

    public function actionStore()
    {
        if (Auth::check()) {
            return redirect('/main');
        }

        $validator = Validator::make($this->params, [
            'email' => 'required|string|max:128|email|unique:' . UserEntity::class . ',email',
            'phone_number' => 'required|string|max:128|unique:' . UserEntity::class . ',phone_number',
            'password' => 'required|string',
            'first_name' => 'required|string|max:64',
            'last_name' => 'required|string|max:64',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ]);
        }

        $user = (new UserLogic())->store($this->params);

        return response()->json($user);
    }
}
