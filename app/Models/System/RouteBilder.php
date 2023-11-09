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

    private $listController     = [];
    private $listIgnoreFolder   = ["Middleware","TaskSystem",'.','..','Kernel.php'];

    private $nameKeyCache = null;
    private $site_id = null;

    public function __construct(){
        if(is_null($this->site_id))
            $this->site_id = Site::getSiteId();
        $this->nameKeyCache = self::KEY_CACHE_ROUTE.$this->site_id;
    }
    //Собитает пути из контролеров все функции контроллеров с преставкой action являются путями
    public function build(){
        $domains = Site::getSite();
        if((new SiteConfig())->getConfig('migration')) {
            if (is_null($domains) || empty(Cache::tags([$domains['domain_name']])->get($this->nameKeyCache))) {
                $controllerPath = app_path("Http");
                $controllerPathReplace = app_path('');
                $result = $this->getControllerFromDir($controllerPath); // Берем все папки за исключением те что в $listIgnoreFolde
                foreach ($result as $key => $item) {
                    if (file_exists($item . '.php')) { //просеряем что есть файл и он php
                        $item = str_replace($controllerPathReplace, '\App', $item);
                        $action = $this->getAction($item);
                        if (count($action) > 0)
                            $this->listController[$key] = $action;
                    }
                }
                if ((new SiteConfig())->getConfig('migration'))
                    Cache::tags([Site::getSite()['domain_name']])->add($this->nameKeyCache . "_old", $this->listController, 900000);
                $this->loadRoutFromDB();
                Cache::tags([Site::getSite()['domain_name']])->add($this->nameKeyCache, $this->listController, 900000);
            } else {
                $this->listController = Cache::tags([Site::getSite()['domain_name']])->get($this->nameKeyCache);
            }

            return $this->listController;
        }else{
            return [];
        }
    }
    //выдать все параметры по путям
    public function getParamsRotes(){
        return $this->build();
    }
    public function getParamsController($nameController){
        $result = $this->build();
        return isset($result[$nameController])?$result[$nameController]:false;
    }
    //Выдает дополнительные параметры по Action
    public function getParamsAction($fullNameAction){
        $result = $this->build();
        $return = [];
        foreach ($result as $key => $list){
            if(is_array($list) && count($list) > 0)
                foreach ($list as $value)
                    if($value['action'] == $fullNameAction)
                        $return[] = $value;
        }
        return (count($return) > 0)?$return:false;
    }
    //Возвращает все урлы одним списком
    public function getAllPublicRout(){
        $result = [];
        foreach ($this->listController as $controller)
            foreach ($controller as $action)
                $result[] = $action['url'];
        return $result;
    }
    public static function getCacheName(){
        return self::KEY_CACHE_ROUTE.Site::getSiteId();
    }
    public static function getRotesAllRouts(){
       return Cache::tags([Site::getSite()['domain_name']])->get(self::getCacheName());
    }
    public static function getRotesBuildWithOutDB(){
        $site_id = Site::getSiteId();
        $list = Cache::tags([Site::getSite()['domain_name']])->get(self::KEY_CACHE_ROUTE.$site_id."_old");
        $return = [];
        if(!is_null($list))
            foreach ($list as $items)
                foreach ($items as $item)
                    $return[$item['url']] = $item;

        return $return;
    }

    public static function getRotesBuild(){
        $site_id = Site::getSiteId();
        if(is_null($site_id) && empty($site_id))
            return [];

        $list = Cache::tags([Site::getSite()['domain_name']])->get(self::KEY_CACHE_ROUTE.$site_id);
        $return = [];
        if(!is_null($list))
            foreach ($list as $items)
                foreach ($items as $item)
                    $return[$item['url']] = $item;

        return $return;
    }

    public static function findByTemplate($templateName){
        $result = self::getRotesBuild();
        if(!is_null($result)) {
            foreach ($result as $key => $item) {
                if(isset($item['params']) && !is_null($item['params'])) {
                    if (isset($item['params']['type_page']) && $item['params']['template'] == $templateName) {
                        return $item;
                    }
                }
            }
        }
        return false;
    }

    public static function findByUrl($url,$route_url = null){
       $result = self::getRotesBuild();
       if(isset($result[$url]) || isset($result[$route_url])){
           return $result[$url];
       }
    }
    //Удвляет путь по урлу
    private function removeByUrl($url){
        if(is_array($this->listController) && count($this->listController) > 0)
            foreach ($this->listController as $key => $items){
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
    private function getControllerFromDir($paht){
        $dir = scandir($paht);
        $listController = [];

        foreach ($dir as $item){
            if(!in_array($item,$this->listIgnoreFolder)){
                if(file_exists($paht.self::T_S_P.$item)){
                    $key = strtolower(str_replace(".php",'',$item));
                    $listController[$key] = $paht.self::T_S_P.str_replace(".php",'',$item);
                }
                if(is_dir($paht.self::T_S_P.$item)){
                    $listController = array_merge($listController,$this->getControllerFromDir($paht.self::T_S_P.$item));
                }
            }
        }
        return $listController;
    }
    //Проходит по классу и соберает методы (благодаря наймспейс иля файла соотвецтвует названию класса, если будет разнится работать не будет)
    private function getAction($controller){
        $listFunctionReturn = [];
        $controllerNameSpace = str_replace(self::T_S_P, self::T_S_P_G, $controller);
        if (class_exists($controllerNameSpace)) {
            $listFunction = get_class_methods($controllerNameSpace);
            $helpParams  = (new $controllerNameSpace())->getPageParams();
            $controller = str_replace(self::T_S_P_G, self::T_S_P, $controller);
            $controller = str_replace(self::PATH_CONTROLLERS, '', $controller);
            foreach ($listFunction as $list) {
                if (stripos($list, 'action') !== false && 'callAction' != $list) {
                    $clearAction = str_replace('action', '', strtolower($list));
                    $index = explode(self::T_S_P, substr(strtolower($controller), 1));
                    $clearAction = ($clearAction == "index") ? self::T_S_P : $clearAction;
                    if ($index[0] == strtolower(self::MAIN_CONTROLLER) && count($index) == 2) {
                        $url = $clearAction;
                    } else {
                        if($clearAction == self::T_S_P && in_array(strtolower(self::MAIN_CONTROLLER),$index) && count($index) ==2 ){
                            $url = self::T_S_P . $index[0] . self::T_S_P;
                        }else{
                            if($clearAction == self::T_S_P){
                                if($index[0] == strtolower(self::MAIN_CONTROLLER)){
                                    $url = self::T_S_P . $index[1] . self::T_S_P;
                                }else{
                                    $url = self::T_S_P . $index[0] . self::T_S_P;
                                }
                            }else{

                                $tempIndex = $index;
                                if($tempIndex[0] == strtolower(self::MAIN_CONTROLLER)){
                                   unset($tempIndex[0]);
                                   array_pop($tempIndex);
                                }else{
                                    array_pop($tempIndex);
                                }
                                if(in_array(strtolower(self::MAIN_CONTROLLER),$tempIndex)){
                                    array_pop($tempIndex);
                                }
                                $url = self::T_S_P . implode(self::T_S_P,$tempIndex) . self::T_S_P .$clearAction ;
                            }
                        }

                    }

                    $checParams = (isset($helpParams[$list]) &&!is_null($helpParams[$list]));
                    $checParamsCHPU = (isset($helpParams[$list]['chpu']) && count($helpParams[$list]['chpu']) > 0);
                    if($checParams && $checParamsCHPU){
                        $newparam = [];
                        foreach ($helpParams[$list]['chpu'] as $item){
                            $newparam[] = "{".$item."?}";
                            $listFunctionReturn[] = [
                                'name' => $list . "_" . $index[(count($index) - 1)],
                                'controller_name'=> str_replace(strtolower(self::MAIN_CONTROLLER),'',$index[(count($index) - 1)]).implode("_",$newparam),
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
                        'name' => $list . "_" . $index[(count($index) - 1)],
                        'controller_name'=> str_replace(strtolower(self::MAIN_CONTROLLER),'',$index[(count($index) - 1)]),
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
