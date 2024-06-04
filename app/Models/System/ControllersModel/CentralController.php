<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 18.12.2022
 * Time: 6:42
 */

namespace App\Models\System\ControllersModel;
use App\Models\CoreEngine\LogicModels\User\UserLogic;
use App\Models\System\General\Site;
use App\Models\System\General\SiteConfig;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cache;


abstract class CentralController extends BaseController {
    protected $params;
    protected $site_id;
    public function __construct(){
        $this->params = \request()->all();
    }
    public function callAction($method, $parameters){
        $cachedOnlineUserIds = Cache::get('online') ?? [];
        if (!in_array(\auth()->id(), $cachedOnlineUserIds)) {
            $cachedOnlineUserIds[] = \auth()->id();
            Cache::put('online', $cachedOnlineUserIds, 120);
        }
//        if (auth()->check()) {
//            /*
//             * Если медленно, используем очередь RecordLastActivityJob.
//             * Соответственно, нужно установить драйвер очередей. Сама джоба не тестировалась
//             */
//            (new UserLogic())->setUserOnlineTimestamp();
//        }
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
