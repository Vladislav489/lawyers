<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 01.12.2022
 * Time: 18:43
 */
namespace App\Http\Login;

use App\Models\CoreEngine\ProjectModels\User\UserEntity;
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
            return redirect(route__('actionIndex_admin_controllers_admincontroller'));
        } else {
            return  view('lawyers.Login.login',['mess'=>"User or Password do not current"]);
        }
    }
    public function actionLoguot(){
        Auth::guard('admin')->logout();
        return redirect(url('/'));
    }

    public function actionUserLogin($credentials = []) {
        if (empty($credentials)) {
            $request = request();
            $credentials = $request->only('phone_number', 'password');
        }
        if (Auth::attempt($credentials)) {
            $typeId = Auth::user()->type_id;
            session()->put('type_id', $typeId);
            if ($typeId != 1) {
                return redirect(route__('actionEmployeeProfile_controllers_employee_employeecontroller'));
            } else {
                return redirect(route__('actionClientCabinet_controllers_client_clientcontroller'));
            }
        } else {
            return redirect(route__('actionLogin_controllers_site_usercontroller'));
        }
    }

    public function actionUserLogout()
    {
        Auth::logout();
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
