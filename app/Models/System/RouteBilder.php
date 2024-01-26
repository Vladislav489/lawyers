<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 02.01.2023
 * Time: 10:56
 */

namespace App\Models\System;
use App\Models\System\General\Routs;
use App\Models\System\General\Site;
use App\Models\System\General\SiteConfig;
use Illuminate\Support\Facades\Cache;
use function PHPUnit\Framework\returnArgument;


class RouteBilder {
    const T_S_P                 = "/";
    const T_S_P_G               = "\\";
    const PATH_CONTROLLERS      = "/App/Http";
    const MAIN_CONTROLLER       = 'Controllers';
    const CONTROLLER_KEY        = 'controller';
    const KEY_CACHE_ROUTE       = 'controllerList_site_';
    const TIME_LIFE_CACHE       = 1000000;

    private $listController     = [];
    private $listIgnoreFolder   = ["Middleware","TaskSystem",'.','..','Kernel.php'];
    private $AppPath = "";
    private $nameKeyCache = null;
    private $site_id = null;

    public function __construct() {
        $this->site_id = (is_null($this->site_id))? Site::getSiteId():$this->site_id;
        $this->AppPath = app_path('');
        $this->nameKeyCache = self::KEY_CACHE_ROUTE.$this->site_id;
    }
    //Собитает пути из контролеров все функции контроллеров с преставкой action являются путями
    public function build() {
        $domains = Site::getSite();
        if(!(new SiteConfig())->getConfig('migration'))
            return [];
        if (is_null($domains) || empty(Cache::get($this->nameKeyCache))) {
            $controllerPath = app_path("Http");
            $r = $result = $this->getControllerFromDir($controllerPath); // Берем все папки за исключением те что в $listIgnoreFolde
            $result = $this->keyForFile($result);
            foreach ($result as $key => $item) {
                $action = $this->getAction($item);
                    if (count($action) > 0)
                        $this->listController[$key] = $action;
            }
            if ((new SiteConfig())->getConfig('migration'))
            Cache::add($this->nameKeyCache . "_old", $this->listController, self::TIME_LIFE_CACHE);
            $this->loadRoutFromDB();
            Cache::add($this->nameKeyCache, $this->listController, self::TIME_LIFE_CACHE);
        } else {
            $this->listController = Cache::get($this->nameKeyCache);
        }
        return $this->listController;
    }
    //выдать все параметры по путям
    public function getParamsRotes() {
        return $this->build();
    }
    public function getParamsController($nameController) {
        $result = $this->build();
        return isset($result[$nameController])? $result[$nameController]:false;
    }
    //Выдает дополнительные параметры по Action
    public function getParamsAction($fullNameAction) {
        $result = $this->build();
        $return = [];
        foreach ($result as $key => $list){
            if(is_array($list) && count($list) > 0)
                foreach ($list as $value)
                    if($value['action'] == $fullNameAction)
                        $return[] = $value;
        }
        return (count($return) > 0)? $return:false;
    }
    //Возвращает все урлы одним списком
    public function getAllPublicRout() {
        $result = [];
        foreach ($this->listController as $controller)
            foreach ($controller as $action)
                $result[] = $action['url'];
        return $result;
    }
    public static function getCacheName() {
        return self::KEY_CACHE_ROUTE.Site::getSiteId();
    }
    public static function getRotesAllRouts() {
       return Cache::get(self::getCacheName());
    }
    public static function getRotesBuildWithOutDB() {
        $site_id = Site::getSiteId();
        $list = Cache::get(self::KEY_CACHE_ROUTE.$site_id."_old");
        $return = [];
        if(!is_null($list))
            foreach ($list as $items)
                foreach ($items as $item)
                    $return[$item['url']] = $item;

        return $return;
    }
    public static function getRotesBuild() {
        $site_id = Site::getSiteId();
        if(is_null($site_id) && empty($site_id))
            return [];

        $list = Cache::get(self::KEY_CACHE_ROUTE.$site_id);
        $return = [];
        if(!is_null($list))
            foreach ($list as $items)
                foreach ($items as $item)
                    $return[$item['url']] = $item;

        return $return;
    }
    public static function findByTemplate($templateName) {
        $result = self::getRotesBuild();
        if(!is_null($result))
            return false;
        foreach ($result as $key => $item) {
            if(isset($item['params']) && !is_null($item['params'])) {
                if (isset($item['params']['type_page']) && $item['params']['template'] == $templateName) {
                    return $item;
                }
            }
        }
    }
    public static function findByUrl($url,$route_url = null) {
       $result = self::getRotesBuild();
       if(isset($result[$url]) || isset($result[$route_url])){
           return $result[$url];
       }
    }


    public function getControllerFromFolder($folderPath) {
        $this->listIgnoreFolder[] = 'ApiInfoController.php';

        $r =$cotrollers = $this->getControllerFromDir(app_path("Http").self::T_S_P.$folderPath);
        $cotrollers = $this->keyForFile($cotrollers);
        $return = [];
       // dd(app_path("Http").self::T_S_P.$folderPath,$cotrollers);
        $controllerPathReplace = app_path('');
        foreach ($cotrollers as $item) {
            $item = str_replace('\App',$controllerPathReplace, $item);
            $item = str_replace(self::T_S_P_G, self::T_S_P, $item);
            if (file_exists($item . '.php')) { //просеряем что есть файл и он php
               $item = str_replace($controllerPathReplace, '\App', $item);
                $action = $this->getAction($item);
                if (count($action) > 0) $return[$item] = $action;
            } else {
                dd(base_path().$item . '.php');
            }
        }
        return $return;
    }

    private function removeByUrl($url) {
        if(is_array($this->listController) && count($this->listController) > 0)
            foreach ($this->listController as $key => $items) {
                foreach ($items as $key__=>$item) {
                    if( $item['url'] == $url){
                        unset($this->listController[$key][$key__]);
                    }
                }
            }
    }
    //Вернет допустимы данные по action
    private function find($nameAction){
        if(is_array($this->listController) && count($this->listController) > 0)
            foreach ($this->listController as $key => $items){
                foreach ($items as $item) {
                    if( $item['clearAction'] == $nameAction || $item['action'] ==  $nameAction){
                        return $item;
                    }
                }
            }
    }
    //Выгрузка из базы пользовательских путей
    private function loadRoutFromDB(){
        if((new SiteConfig())->getConfig('migration')) {
           $listRouts = (new Routs(['site_id' => (string)$this->site_id]))->getList();
           $customPage = $this->find("custompage");
           foreach ($listRouts['result'] as $key => $dbRout) {
               foreach ($this->listController[self::CONTROLLER_KEY] as $key__ => $rout_phisic) {
                   if ($rout_phisic['clearAction'] == $dbRout['url'] && $dbRout['physically'] == 1) {
                       $dataNew = array_merge($dbRout, $this->listController[self::CONTROLLER_KEY][$key__]);
                       if (is_null($dataNew['name_title'])) {
                           $dataNew['name_title'] = $dataNew['params']['name'];
                       }
                       $dataNew['route_id'] = $dbRout['id'];
                       $this->listController[self::CONTROLLER_KEY][$key__] = $dataNew;
                   }
               }
               if ($dbRout['physically'] == 0) {
                   $newData = array_merge($customPage, $dbRout);
                   $newData['name'] .= "_" . implode("_", explode(self::T_S_P, preg_replace("/[^A-z0-9\/]/", '', $dbRout['url'])));
                   $this->listController[self::CONTROLLER_KEY][] = $newData;
               }
           }
           $this->removeByUrl("custompage");
           return $this->listController;
       }else{
           return [];
       }
    }
    //Проходит по всем папкам и соберает контроллеры


    public function getRouteDir($path){
        $controller = $this->getControllerFromFolder($path);
        $controller = $this->keyForFile($controller);
        foreach ($controller as $key => $item) {
            foreach ($item as $subItem) {
                if ($subItem['clearAction'] == 'info') {
                    $controllerCall = $subItem['controller'];
                    $action = $subItem['action'];
                    $controller[$key] = array_merge(
                        ['name_ligic'=> array_slice(explode('\\',$subItem['controller']),-2,1)],
                        (new $controllerCall())->$action()->original);
                }
            }
        }
    }

    private function keyForFile($array) {
        $newArray = [];
        foreach ($array as $path) {

            $pathBuild = explode(self::T_S_P,str_replace(self::T_S_P_G,self::T_S_P,$path));
            $prefix = array_slice($pathBuild,3);
            if (in_array(self::MAIN_CONTROLLER, $prefix)) {
                //#777# Тут надо подумать
                $key = strtolower($pathBuild[count($pathBuild)-1]);
                if(array_key_exists($key,$newArray)){
                    $key =  strtolower( $pathBuild[0].'_'.$pathBuild[count($pathBuild)-1]);
                }
            } else {
                $key = strtolower(implode('_', $prefix));
            }
            $newArray[$key] = $path;
        }
        return $newArray;
    }
    private function getControllerFromDir($paht,$allPath = null){

        $dir = scandir($paht);
        $listController = [];
        $error = [];
        foreach ($dir as $item){
            if(!in_array($item,$this->listIgnoreFolder)) {
                if(file_exists($paht.self::T_S_P.$item) && strpos($item,".php") ) {
                    $listController[] = str_replace($this->AppPath, '\App', $paht.self::T_S_P.str_replace(".php",'',$item));
                } else {
                    $error[] = $paht.self::T_S_P.$item;
                }
                if (is_dir($paht.self::T_S_P.$item)) {
                    $allPath = $paht.self::T_S_P.$item;
                    $listController = array_merge(
                        $listController,
                        $this->getControllerFromDir($paht.self::T_S_P.$item,$allPath)
                    );
                } else {
                    $error[] = $paht.self::T_S_P.$item;
                }
            }
        }
        if(count($error) > 0  && empty($listController) )
            var_dump('error',$error);
        return $listController;
    }


    private function clearAction($functionName,$controller) {
        $clearAction = str_replace('action', '', strtolower($functionName));
        $clearAction = ($clearAction == "index") ? self::T_S_P : $clearAction;
        //Если в функции встречается `_` то меняем на слеш обратны
        if(strpos($clearAction,'_') !== false ){
            $clearAction = str_replace("_","/",$clearAction);
        }
        return $clearAction;
    }
    private function checkMainController($pathController){
        if(stripos($pathController,self::MAIN_CONTROLLER) == 0)
            return 'main';
        if(stripos($pathController,self::MAIN_CONTROLLER) != false)
            return 'sub_main';
        return ;
    }
    private function indexUrl($clearAction,$pathController,$ExplodePath){
        switch ($this->checkMainController($pathController)){
            case "sub_main":
                return self::T_S_P . strtolower($ExplodePath[0]) . self::T_S_P;
                break;
            case "main":
                if(count($ExplodePath) > 2)
                    return self::T_S_P . strtolower($ExplodePath[0]).self::T_S_P.strtolower($ExplodePath[1]) . self::T_S_P;
                else
                    return self::T_S_P . strtolower($ExplodePath[1]) . self::T_S_P;
                break;
            case  "other":
                array_pop($ExplodePath);
                return self::T_S_P . strtolower(implode("/", $ExplodePath)) . self::T_S_P;
                break;
            default:
                return false;
                break;
        }
        return false;
    }

    private function buildUrlAction($clearAction,$ExplodePath) {

        if($clearAction == self::T_S_P) {
            // Обработка для индексныв урлов для контролеров;
            if ($url = $this->indexUrl($clearAction, implode(self::T_S_P, $ExplodePath), $ExplodePath))
                return $url;
        }
        // Обработка для всех остальных
        if ($ExplodePath[0] == self::MAIN_CONTROLLER) {
            $ExplodePath = array_slice($ExplodePath,1,-1);
            return self::T_S_P . strtolower(implode(self::T_S_P,$ExplodePath)) . self::T_S_P .$clearAction ;
        }

        if(in_array(self::MAIN_CONTROLLER,$ExplodePath)) {
            $key = array_search(self::MAIN_CONTROLLER,array_reverse($ExplodePath));
            $key = ($key+1) * (-1);
            $ExplodePath = array_slice($ExplodePath,0,$key);
            return self::T_S_P . strtolower(implode(self::T_S_P,$ExplodePath)) . self::T_S_P .$clearAction ;
        }

        $last = $ExplodePath[count($ExplodePath)-1];

        $chankUrl_ = preg_split('/(?<=[a-z])(?=[A-Z])/u',$last);
        $chankUrl = array_slice($chankUrl_,0,-1);
        $chankUrl = implode(self::T_S_P,$chankUrl);
        array_pop($ExplodePath);
        if (stripos($last,'controller') != false && stripos($last,'controllers') == false) {
            $last = str_replace('Controller','',$last);
            if (stripos($ExplodePath[count($ExplodePath)-1],$last) === false) {
                if(count($chankUrl_) > 2)
                   $ExplodePath[count($ExplodePath)-1] = $chankUrl;
            }
       }
       return self::T_S_P . strtolower(implode(self::T_S_P,$ExplodePath)) . self::T_S_P .$clearAction ;
    }

    //Проходит по классу и соберает методы (благодаря наймспейс иля файла соотвецтвует названию класса, если будет разнится работать не будет)
    private function getAction($controller){
        $listFunctionReturn = [];
        $controllerNameSpace = str_replace(self::T_S_P, self::T_S_P_G, $controller);
        if (class_exists($controllerNameSpace)) {
            $listFunction = get_class_methods($controllerNameSpace);
            $object = (new $controllerNameSpace());
            if(method_exists($object,'getPageParams')) {
                $helpParams = (new $controllerNameSpace())->getPageParams();
            }
            $controller = str_replace(self::T_S_P_G, self::T_S_P, $controller);
            $controller = str_replace(self::PATH_CONTROLLERS, '', $controller);

            foreach ($listFunction as $list) {
                if (stripos($list, 'action') !== false && 'callAction' != $list) {
                    $clearAction = $this->clearAction($list,$controller);
                    $ExplodePath = explode(self::T_S_P, substr($controller, 1));
                    $ExplodePathLevel = count($ExplodePath);
                    //если это Index тогда чисто слеш

                    if ($ExplodePath[0] == self::MAIN_CONTROLLER && count($ExplodePath) == 2) {
                        $url = $clearAction;
                    } else {
                        $url = $this->buildUrlAction($clearAction,$ExplodePath);
                    }
                    $checParams = (isset($helpParams[$list]) && !is_null($helpParams[$list]));
                    $checParamsCHPU = (isset($helpParams[$list]['chpu']) && count($helpParams[$list]['chpu']) > 0);
                    $nameRoute = (count($ExplodePath) > 2 )?implode("_",$ExplodePath):$ExplodePath[(count($ExplodePath) - 1)];

                    if($checParams && $checParamsCHPU) {
                        $newparam = [];
                        foreach ($helpParams[$list]['chpu'] as $item) {
                            $newparam[] = "{".$item."?}";
                            $listFunctionReturn[] = [
                                'name' => $list . "_" .strtolower($nameRoute),
                                'controller_name'=> str_replace(strtolower(self::MAIN_CONTROLLER),'',$ExplodePath[(count($ExplodePath) - 1)]).implode("_",$newparam),
                                'controller' => $controllerNameSpace,
                                "pathController" => $controllerNameSpace . "@" . $list,
                                'action' => $list,
                                'clearAction' => $clearAction,
                                'url' => $url.self::T_S_P.implode(self::T_S_P,$newparam),
                                'params' =>(isset($helpParams[$list])?$helpParams[$list]:null)
                            ];
                        }
                    }

                    $listFunctionReturn[] = [
                        'name' =>$list . "_" .strtolower($nameRoute),
                        'controller_name'=> str_replace(strtolower(self::MAIN_CONTROLLER),'',$ExplodePath[(count($ExplodePath) - 1)]),
                        'controller' => $controllerNameSpace,
                        "pathController" => $controllerNameSpace . "@" . $list,
                        'action' => $list,
                        'clearAction' => $clearAction,
                        'url' => $url,
                        'params' =>(isset($helpParams[$list])?$helpParams[$list]:null)
                    ];
                }
            }
        }

        return $listFunctionReturn;
    }
}
