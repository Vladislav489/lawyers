<?php
namespace  App\Models\System\ControllersModel;
use App\Models\System\General\Routs;
use App\Models\System\General\Site;
use Illuminate\Support\Facades\Auth;

class AdminController extends CentralController {
    public function callAction($method, $parameters){
        if (!Auth::check()) {
            if($_SERVER['SERVER_ADDR'] != "127.0.0.1"){
                return redirect(route__("actionIndex_logincontroller"));
            }
        }
        $routs =  (new  Routs([],['id','name_title','url','open','site_id','physically','active']))->getRoute(
            request()->getPathInfo(),
            \Illuminate\Support\Facades\Route::current()->uri());
        $GLOBALS["route"] = $routs;
        $GLOBALS["params"] = array_merge(\request()->all(),\request()->route()->parameters());
        \view()->composer('*', function ($view) {
            $view->with(['route_global' => $GLOBALS["route"],'params_global'=>$GLOBALS["params"]]);
        });
        if(Auth::id() != Site::ROOT_USER_ID){
            $this->params['site_id'] = $this->site_id;
        }
        return parent::callAction($method, $parameters);
    }

    public  function getPageParams(){
        return [];
    }
}

