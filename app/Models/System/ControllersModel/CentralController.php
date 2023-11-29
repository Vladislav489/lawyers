<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 18.12.2022
 * Time: 6:42
 */

namespace App\Models\System\ControllersModel;
use App\Models\CoreEngine\Model\SystemSite;
use App\Models\System\General\Site;
use App\Models\System\General\SiteConfig;
use App\Models\System\HelperFunction;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;


abstract class CentralController extends BaseController {
    protected $params;
    protected $site_id;
    public function __construct(){
        $this->params = \request()->all();
    }
    public function callAction($method, $parameters){
        $parameters = (empty($parameters) || (isset($parameters[0]) && empty($parameters[0]) ))?[]:$parameters;
        $this->params = $GLOBALS["params"] = array_merge(\request()->all(),$parameters);
        if(isset($GLOBALS["params"]['template']['body_view']))
            unset($GLOBALS["params"]['template']['body_view']);
        if(isset($GLOBALS["params"]['template']['view']))
            unset($GLOBALS["params"]['template']['view']);

        $this->site_id = Site::getSiteId();
        \view()->composer('*', function ($view) {
            $view->with(['route_global' => [],'params_global'=>$GLOBALS["params"]]);
        });
        $check = (new SiteConfig())->getConfig('migration');

        if(!$check){
            header("Location: ".route('install'));
            die();
        }
        return parent::callAction($method, $parameters);
    }
    public abstract function getPageParams();

}
