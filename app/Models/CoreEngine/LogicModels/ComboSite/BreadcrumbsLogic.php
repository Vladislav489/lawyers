<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 16.03.2023
 * Time: 14:21
 */

namespace App\Models\CoreEngine\LogicModels\ComboSite;


use App\Models\System\General\Routs;
use App\Models\System\RouteBilder;
use Illuminate\Routing\Route;
use function PHPUnit\Framework\returnArgument;

class BreadcrumbsLogic {

    protected $url;
    protected $route;
    protected $ListLinks = [];
    protected $rules = ['home','type','category','end'];
    protected $addText = ['code'];

    public function __construct(){}

    public function setRoute($route){
        $this->ListLinks = $route;
        return $this;
    }

    public function setRoutes($url_list){
        $route = new Routs();
        if (!is_null($url_list))
            foreach ($url_list as $item) {
                $this->ListLinks[] = $route->getRout($item[0],$item[1]);
            }
        return $this;
    }

    private function ifTypePageEmpty($url,$template_url){
        $paramsUrlDefault = Routs::getSystemRoutByUrl($url);
        if (empty($paramsUrlDefault['type_page']) || is_null($paramsUrlDefault['type_page']) ||
                !isset($paramsUrlDefault['params']) &&  !isset($paramsUrlDefault['params']['type_page'])){
            $oldRout = RouteBilder::getRotesBuildWithOutDB();
            if (key_exists($url,$oldRout)) {
                $paramsUrlDefault = $oldRout[$url];
            } else {
                $template_url = str_replace("}","?}",$template_url);
                if (key_exists($template_url,$oldRout))
                    $paramsUrlDefault = $oldRout[$template_url];
            }
        }
        if (isset($paramsUrlDefault['params']) && isset($paramsUrlDefault['params']['type_page']))
            return $paramsUrlDefault['params']['type_page'];
        return null;
    }
    private function parserOne($route){
       $urlParsTemplate = $urlPars = $checkParams = $returnHref = $list = [];
       $urlPars[] = "/";
       $urlParsTemplate[] = "/";

       $urlPars = array_merge($urlPars,explode("/",$route['url']));
       $urlParsTemplate = array_merge($urlParsTemplate,explode("/",$route['url_system']));

        $urlParsTemplate = array_merge(['/'],array_diff($urlPars,$urlParsTemplate));
        if (strlen($route['url']) == 1)
           return false;

       foreach ($urlParsTemplate as $item) {
            $url = (strlen($item) <= 1)? "/":"/".$item;
            $breadckramsUrl = (new Routs())->getRoute($url,$url);
            if($breadckramsUrl) {
                if (empty($breadckramsUrl['type_page'])) {
                    $breadckramsUrl['type_page'] = $this->ifTypePageEmpty($breadckramsUrl['url'], $breadckramsUrl['template_url']);
                    $checkParams[] = $breadckramsUrl['type_page'];
                }
                $list[$breadckramsUrl['name_title'] . $breadckramsUrl['type_page']] = $breadckramsUrl;
            }
       }

       $url = (strlen($route['url']) <=1 )? "/":"/".$route['url'];
       $breadckramsUrl = (new Routs())->getRoute($url,$route['url_system']);
       $breadckramsUrl['type_page'] = 'end';
       $oldName =  $breadckramsUrl['name_title'];
            if(isset($route['params_route'])) {
                foreach ($route['params_route'] as $key_ => $item) {
                    if (!in_array($key_, $checkParams))
                        if(in_array($key_,$this->addText)) {
                            if (stripos($breadckramsUrl['name_title'], " " . $item) === false) {
                                $breadckramsUrl['name_title'] = $breadckramsUrl['name_title'] . " " . ucfirst($item);
                            }
                        }
                }
            }

       $list[$oldName.$breadckramsUrl['type_page']] = $breadckramsUrl;
       foreach ($this->rules as $item) {
           foreach ($list as $key => $page) {
               if ($page['type_page'] == $item) {
                   if ($page['url'] !== "/")
                       $page['url'] = "/" . $page['url'];

                   $returnHref[] = [
                       "name_title" => $page['name_title'],
                       "url"=> $page['url'],
                       "type" => $page['type_page']
                   ];
               }
           }
       }
       return $returnHref;
    }
    private function parserMulti($routeList){
    }
    private function parserRouts(){
        return (is_array($this->ListLinks) && isset($this->ListLinks['url']))? $this->parserOne($this->ListLinks):$this->parserMulti($this->ListLinks);
    }

    public function getBreadcrumbs(){
           return $this->parserRouts();
    }
}