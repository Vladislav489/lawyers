<?php

namespace App\Http\Controllers;

use App\Models\CoreEngine\LogicModels\User\UserLogic;
use App\Models\CoreEngine\ProjectModels\User\UserEntity;
use App\Models\System\ControllersModel\FrontController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Controller extends FrontController
{
    public function actionDefault()
    {
        return $this->view('lawyers.site.index');
    }

    public function getPageParams()
    {
        return [
            'actionDefault' => [
                'name' => 'Home',
                'type_page' => 'home',
                'chpu' => [],
                'template' => 'Site.Forecast.main'
            ],
            'actionRegisterUser' => [
                'name' => 'Регистрация',
                'type_page' => 'registration',
                'chpu' => [],
                'template' => 'Site.Forecast.main'
            ],
            'actionStoreUser' => [
                'name' => 'Регистрация',
                'type_page' => 'registration',
                'chpu' => [],
                'template' => 'Site.Forecast.main'
            ],
        ];
    }

    public function actionRegisterUser()
    {
        return view('lawyers.user.signup');
    }

    public function actionRegisterEmployee()
    {
        return view();
    }

    public function actionStoreUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
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
