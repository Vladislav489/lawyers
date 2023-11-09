<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 18.12.2022
 * Time: 6:31
 */

namespace App\Models\System\ControllersModel;
use Illuminate\Support\Facades\Auth;


class MainstayController extends CentralController {
    public function callAction($method, $parameters){

        return parent::callAction($method, $parameters);
    }
    public  function getPageParams(){
        return [];
    }

}