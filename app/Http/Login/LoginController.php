<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 01.12.2022
 * Time: 18:43
 */
namespace App\Http\Login;

use App\Models\System\ControllersModel\CentralController;
use Illuminate\Support\Facades\Auth;


class LoginController extends CentralController {
    public function actionIndex(){
        return view('lawyers.Login.login');
    }
    public function actionIn(){
        $request = request();

        $credentials = $request->only('email','password');
        if(Auth::guard('admin')->attempt($credentials,true)){
            return redirect(url('/'));
        } else {
            return  view('lawyers.Login.login',['mess'=>"User or Password do not current"]);
        }
    }
    public function actionLoguot(){
        Auth::guard('admin')->logout();
        return redirect(url('/'));
    }

    public  function getPageParams(){
        return [
            "actionIndex" =>['name'=>"Логин",'chpu'=>[],'template' => 'lawyers.Login.login'],
            "actionIn" =>['name'=>"Вход",'chpu'=>[], 'template' => ''],
            "actionLoguot" =>['name'=>"Выход",'chpu'=>[], 'template' =>''],
        ];
    }
}
