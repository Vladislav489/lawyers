<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 01.12.2022
 * Time: 18:43
 */

namespace App\Http\Controllers\Login;

use App\Models\System\ControllersModel\CentralController;
use App\Models\System\General\Routs;
use App\Models\System\SystemLog;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;


class LoginController extends CentralController{
    public function actionIndex(){
        return view('Site.Login.login');
    }
    public function actionIn(){
        $request = request();

        if($request->post('api') == 1){
            $credentials = $request->only('email','password');
            $res = Auth::attempt($credentials,true);
            return Response::json([$request->all(),$res],200);
        }

        $credentials = $request->only('email','password');


        if(Auth::attempt($credentials,true)){
            return redirect(url('/'));
        }else{
            return  view('Site.Login.login',['mess'=>"User or Password do not current"]);
        }
    }
   /* public function actionUserAdd(){

             dd(Hash::make('654321'));

    }*/

    public function actionLoguot(){
        Auth::logout();
        return redirect(route__("actionIndex_logincontroller"));
    }

    public  function getPageParams(){
        return [
            "actionIndex" =>['name'=>"Главная",'chpu'=>[]],
            "actionIn" =>['name'=>"Вход",'chpu'=>[]],
            "actionLoguot" =>['name'=>"Выход",'chpu'=>[]],
        ];
    }
}
