<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 01.12.2022
 * Time: 18:43
 */
namespace App\Http\Admin\Login;

use App\Models\System\ControllersModel\CentralController;
use Illuminate\Support\Facades\Auth;


class LoginController extends CentralController {
    public function actionIndex(){
        return view('Site.Login.login');
    }
    public function actionIn(){
        $request = request();
        $credentials = $request->only('email','password');
        if(Auth::attempt($credentials,true)){
            return redirect(url('/'));
        } else {
            return  view('Site.Login.login',['mess'=>"User or Password do not current"]);
        }
    }
    public function actionLoguot(){
        Auth::logout();
        return redirect(route__());
    }

    public  function getPageParams(){
        return [
            "actionIndex" =>['name'=>"Главная",'chpu'=>[]],
            "actionIn" =>['name'=>"Вход",'chpu'=>[]],
            "actionLoguot" =>['name'=>"Выход",'chpu'=>[]],
        ];
    }
}