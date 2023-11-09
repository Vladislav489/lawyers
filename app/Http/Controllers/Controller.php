<?php
namespace App\Http\Controllers;

use App\Models\System\ControllersModel\FrontController;
use App\Models\System\General\Template;
class Controller extends FrontController {
  //  use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function actionIndex(){
       return $this->View();
    }
    public function getPageParams(){
        return[
            "actionIndex" =>['name'=>"Home",'type_page'=>'home','chpu'=>[],"template"=>'Site.Forecast.main'],
          ];

    }


    //public function actionTest(){return view('simpleSite.test');}
    //public function actionTest1(){return view('simpleSite.test1');}
}
