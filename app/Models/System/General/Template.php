<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 03.01.2023
 * Time: 15:49
 */

namespace App\Models\System\General;
use App\Models\CoreEngine\Core\CoreEngine;
use App\Models\CoreEngine\Model\SystemRouts;
use App\Models\CoreEngine\Model\SystemSite;
use App\Models\CoreEngine\Model\SystemViwe;
use App\Models\System\buildTreeFromDirectory;
use App\Models\System\Component\ComponentBuilder;
use App\System\Parser\UrlTargetParser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use function Symfony\Component\Console\Helper\render;

class Template extends CoreEngine {
   CONST _PATH_             = "/views/Site/";
   CONST _MAIN_PATHP_       = "Site";
   CONST _INCLUDE_SCRIPT    = "Include_Component";
   private $allow_ext       = array('php');
   private $prefix          = "";
   private $template        = null;
   private $route           = null;

   public function __construct($params = [],$select = ["*"],$callback = null){
        $params['is_deleted'] = 0;
        $this->engine = new SystemViwe();
        $this->query = $this->engine->newQuery();
        $this->getFilter();
        $this->compileGroupParams();
        $this->getAllTemplates();
        parent::__construct($params,$select);
   }
   public function parserToSegment($textTemplate){
       $scriptIncludeComponent = [];
       $SeqrchTarget = [
           "body_view" => ["target" => 'section', "takeData" => UrlTargetParser::TAKE_DATE_HTML],
           "body_script_view" => ["target" => "head > script", "takeData" => UrlTargetParser::TAKE_DATE_HTML_OUT],
           "body_link_view" => ["target" => "head > link", "takeData" => UrlTargetParser::TAKE_DATE_HTML_OUT],
           "body_title_view" => ["target" => 'head > title', "takeData" => UrlTargetParser::TAKE_DATE_HTML_OUT],
           "body_meta_view" => ["target" => "head > meta", "takeData" => UrlTargetParser::TAKE_DATE_HTML_OUT],
        ];
        preg_match("/<!--".self::_INCLUDE_SCRIPT.".*?-->/s",$textTemplate,$scriptIncludeComponent );
        $dom = new UrlTargetParser();
        $dom->setGroupByKey(true);
        $dom->setTargets($SeqrchTarget);
        $res = $dom->parserFromText($textTemplate);
        $res['body_bottom_script_view'] = str_replace(['<!--'.self::_INCLUDE_SCRIPT,'-->'],"",$scriptIncludeComponent[0]);
        return $res;
   }
   public function getUseViewByParams($admin = false, $file = false){
      $this->executeFilter();
      $result = $this->getSandartResultOne();
       if($admin){
           $routs = [
               "id" => $result['route_id'], "url" => $result['url'],
               "template_url" => isset($result['template_url'])?$result['template_url']:null,
               "site_id" => $result['site_id'], "template_id" => $result['id'],
           ];
           $GLOBALS["route"] = $routs;
           $GLOBALS["params"] =\request()->all();
           \view()->composer('*', function ($view) {
               $view->with(["route_global" => $GLOBALS["route"],"params_global" => $GLOBALS["params"]]);
           });
       }
       if(!$file) {
           $res = $this->parserToSegment(self::getUseView($result['name_view'], [], $admin, $file));
           foreach ($res as $key => $data) {
               if ($key == 'body_view') {
                   if (is_null($result['body_view']) || empty($result['body_view']))
                       $result[$key] = $data[0];
               } else {
                   $result[$key] = $data;
               }
           }
       }else{
           if (empty($result['body_view'])) {
               $result['body_view'] = self::getUseView($result['name_view'], [], $admin, $file);
           } else {
               $result['body_view'] = self::removeAnchorComponent($result['body_view']);
               $result['body_view'] = self::buildTemplateFromBody($result);
           }
       }
       return $result;
   }

   public static function removeAnchorComponent($body_view){
       return str_replace([ComponentBuilder::START_COMPONENT,ComponentBuilder::END_COMPONENT],"",$body_view);
   }

   public static function removeAnchorFromView($tagStart,$tagEnd,$body_view){
       $start = strpos($body_view,$tagStart);
       $end = strpos($body_view,$tagEnd);
       if($start !== false && $end !== false ){
          $cut = substr($body_view,$start,$end+strlen($tagEnd) - $start );
          $body_view = str_replace($cut,'',$body_view);
       }
       return $body_view;
   }

   public static function getAnchor($nameSection = null){
       $list = [
           'meta' => ['<!--meta-start-->','<!--meta-end-->'],
           'style'=> ['<!--style-start-->','<!--style-end-->'],
           'script_lib' => ['<!--js-lib-start-->','<!--js-lib-end-->'],
           'js-lib-component' => ['<!--js-lib-component-start-->','<!--js-lib-component-end-->'],
           'js-code-component' => ['<!--js-code-component-start-->','<!--js-code-component-end-->'],
           'head-page' => ['<!--head-page-start-->','<!--head-page-end-->'],
           'footer-page' => ['<!--footer-page-start-->','<!--footer-page-end-->'],
           'body-page' => ['<!--body-page-start-->','<!--body-page-end-->'],
           'head' => ['<!--head-start-->','<!--head-end-->'],
           'body' => ['<!--body-start-->','<!--body-end-->'],
        ];

        return (is_null($nameSection))?$list:$list[$nameSection];
   }
   // Для спец тегов удаление их
   public static function removeTagFromView($tag,$view){
       $startTag = "<".$tag." (.*)+>";
       $EndtTag = "</".$tag.">";
       $EndtTagReg = "<\/".$tag.">";
       $positionTag = [];
       $result = preg_match_all("/".$startTag."|".$EndtTagReg."/",$view,$positionTag,PREG_OFFSET_CAPTURE);
       if($result == 2) {
           $positionTag = $positionTag[0];
           $listCallInclude = [];
           for ($index = 0; $index < count($positionTag); $index += 2) {
               $listCallInclude[] = trim(substr($view,
                   $positionTag[$index][1] ,
                   $positionTag[$index + 1][1] - $positionTag[$index][1] + strlen($EndtTag)));
           }
       $view = str_replace($listCallInclude,'',$view);
       }
       return $view;
   }

   public static function getBladeCode($resourcePath){
       $code = null;
       $startTag = "<chank-page>";
       $EndtTag = "</chank-page>";
       $EndtTagReg = "<\/chank-page>";
       if(file_exists($resourcePath)){
          //(@include(\/?[^)]+)\))
          $code = file_get_contents($resourcePath);
          $positionTag = [];
          $listCallInclude = [];
          $result = preg_match_all("/".$startTag."|".$EndtTagReg."/",$code,$positionTag,PREG_OFFSET_CAPTURE);
          if($result > 2) {
              $positionTag = $positionTag[0];
              for ($index = 0; $index < count($positionTag); $index += 2) {
                  $listCallInclude[] = trim(substr($code,
                      $positionTag[$index][1] + strlen($startTag),
                      $positionTag[$index + 1][1] - $positionTag[$index][1] - strlen($EndtTag)));
              }
              foreach ($listCallInclude as $item) {
                  $item_ = str_replace('@include(', '', $item);
                  $item_ = substr($item_, 0, strlen($item_) - 1);
                  $item_ = explode(",", $item_);
                  $item_[0] = str_replace(['"', "'"], '', $item_[0]);
                  $chank_code = self::getBladeCode(self::getTemplatePathFile($item_[0]));
                  $chank_code = str_replace(["@extends('Site.Forecast.layouts.layout')",'@extends("Site.Forecast.layouts.layout")'],
                  '',$chank_code);
                  $code = str_replace($item,$chank_code, $code);
              }
          }
          return $code;
      }
   }

   public static function getTemplatePathFile($PathView){
       $pathView = Config::get('view.paths')[0];
       $pathView .= "/" . str_replace('.',"/",$PathView) . ".blade.php";
       return $pathView;
   }

   public static function getUseView($template,$data = [],$admin = false,$file = false){
       $listTemplate = (new Template())->getAllTemplates()->getListTemplateSystem();
       $pathView = self::getTemplatePathFile($template);

       if (in_array($template, $listTemplate) && $file)
            return self::getBladeCode($pathView);

       if (!$admin) {//для фронта
           if($template instanceof RedirectResponse ) return $template;
           return (in_array($template, $listTemplate))? \view()->make($template,$data)->render():Blade::render($template,$data);
       } else {// для админки
           return (in_array($template, $listTemplate))? \view()->make($template)->with('admin',$admin)->render():$template;
       }
   }
   // Получает чисты  view из базы без измений и приврашает в стандартный view с layouts
   public static function buildTemplateFromBody($template,$seo = null){
       $view = "";
       $view .= "@extends('Site.Forecast.layouts.layout')\n";
       $view .= "@pushOnce('css-style')\n";
       if (!is_null($seo)) {
           $view .= "<title>".$seo['title']."</title>";
           $view .= "<meta name='description' content='".$seo['description']."'>";
       }
       if(isset($template['body_link_view']))
            $view .= $template['body_link_view'];

       $view .= "@endpushOnce\n";

       if(isset($template['body_bottom_script_view'])) {
           $view .= "@pushOnce('js-lib-component-head')\n";
           $view .= $template['body_bottom_script_view'];
           $view .= "@endpushOnce\n";
       }

       $view .= "@push('content')\n";
       $template['body_view'] = self::equlsVueVaqriable($template['body_view']);
       $view .= "\n".$template['body_view'];
       $view .= "@endpush\n";

       return $view;
   }
   public static function getTemplate($route,$seo = null,$template = null,$default = []){
       if ($route instanceof  Route) {
           $route = Routs::getSystemRoutByUrl($route->uri())['result'];
           return(isset($default[$route[$route->uri()]]))?$default[$route[$route->uri()]]['template']:false;
       } else {
           if (isset($route['id'])) {
               if (is_null($template)) {
                   $template = (new Template([
                       'site_id' => (string)$route['site_id'],
                       'route_id' => (string)$route['id'],
                       'lang_id' => '1','active' => '1',
                   ],['*']))->getOne();
               }

               if (!empty($template)) {
                   if(!empty($template['body_view'])){
                       return self::buildTemplateFromBody($template,$seo);
                   }else{
                       if(!empty($template['name_view'])){
                           return $template['name_view'];
                       }else{
                           $route_ = Routs::getSystemRoutByUrl($route['url']);
                           $logFlag = isset($default[$route_['result'][$route['url']]['action']]);
                           return($logFlag)?$default[$route_['result'][$route['url']]['action']]['template']:false;
                       }
                   }
               } else {
                   $route_ = Routs::getSystemRoutByUrl($route['url']);
                       $logFlag = isset($default[$route_['result'][$route['url']]['action']]);
                       return ($logFlag) ? $default[$route_['result'][$route['url']]['action']]['template'] : false;
               }
           } else {
               $route['url'] = (strlen($route['url'])==0)?"/":$route['url'];
               if (isset($route['result'])) {
                   $logFlag = isset($default[$route['result'][$route['url']]['action']]);
                   return ($logFlag) ? $default[$route['result'][$route['url']]['action']]['template'] : false;
               } else {
                   $logFlag = isset($default[$route['action']]);
                   return ($logFlag) ? $default[$route['action']]['template'] : false;
               }
           }
       }
   }
   public static function equlsRemoveLaravelVaqriable($body_view){
        $res = [];
        preg_match_all("(@\{\{(.+?)\}\})",$body_view,$res);
        $replace = [];
        foreach($res[0] as $item)
            $replace[$item] = str_replace("@",'',$item);

        $body_view = str_replace(array_keys($replace),$replace,$body_view);
        return $body_view;
   }
   // преабразует фигурные скобки для vue  что бы ларавел не подумал что это скобки для view
   public static function equlsVueVaqriable($body_view){
        $res = [];
        preg_match_all("(\{\{(.+?)\}\})",$body_view,$res);
        $replace = [];
        foreach($res[0] as $item)
            $replace[$item] = "@".$item;

        $body_view = str_replace(array_keys($replace),$replace,$body_view);
        return $body_view;
   }
   public static function getColumForAdmin(){
        $columsSort = [];
        $colums = [
            ["table"=>Template::getTable(),"column"=>"id"],
            ["table"=>Routs::getTable(),"column"=>"name_title"],
            ["table"=>Routs::getTable(),"column"=>"url"],
            ["table"=>Site::getTable(),"column"=>"domain_name"],
            ["table"=>Template::getTable(),"column"=>"name_view"],
            ["table"=>Template::getTable(),"column"=>"type_page_id"],
            ["table"=>Template::getTable(),"column"=>"physically",
                "db"=>DB::raw('IF('.Template::getTable().'.physically > 0,"дa","нет") as physically')],
            ["table"=>Template::getTable(),"column"=>"body_view",
                "db"=>DB::raw('IF(LENGTH('.Template::getTable().'.body_view) > 0,"есть","нет") as body_view')],
            ["table"=>Template::getTable(),"column"=>"user_id"],
            ["table"=>Template::getTable(),"column"=>"lang_id"],
            ["table"=>Template::getTable(),"column"=>"route_id"],
            ["table"=>Template::getTable(),"column"=>"site_id"],
            ["table"=>Template::getTable(),"column"=>'active'],
        ];
        foreach ($colums as $item)
            $columsSort[] = $item['column'];
        return ['select'=>$colums,"sort_col"=>$columsSort,'ignody_col'=>['active','site_id','route_id','lang_id','user_id']];
    }

   public function getOne(){
        $this->executeFilter();
        return $this->getSandartResultOne();
   }
   public function getListTemplateSystem(){
       return $this->Template;
   }
   public function getPresentListTemplate(){
       $return = [];
       foreach ($this->Template as $key =>$item){
           $newTitle = str_replace(".","->",str_replace(self::_MAIN_PATHP_.".",'',$item)."()");
           $return[$item] = $newTitle;
       }
       $return['Empty'] = "Пустой";
       return $return;
   }

   protected function getFilter(){
        $tab = $this->engine->getTable();
        $this->filter = [
            [   'field'=>$tab.'.route_id' ,'params'=> 'route_id',
                'validate' =>['string'=>true,"empty"=>true],
                'type' => 'string|array',
                "action" => '=', 'concat' => 'AND'
            ],
            [   'field'=>$tab.'.site_id' ,'params'=> 'site_id',
                'validate' =>['string'=>true,"empty"=>true],
                'type' => 'string|array',
                "action" => '=', 'concat' => 'AND'
            ],
            [   'field'=>$tab.'.user_id' ,'params' => 'user_id',
                'validate' =>['string'=>true,"empty"=>true],
                'type' => 'string|array',
                "action" => '=', 'concat' => 'AND'
            ],
            [   'field'=>$tab.'.lang_id' ,'params' => 'lang_id',
                'validate' =>['string'=>true,"empty"=>true],
                'type' => 'string|array',
                "action" => '=', 'concat' => 'AND'
            ],
            [   'field'=>$tab.'.active' ,'params' => 'active',
                'validate' =>['string'=>true,"empty"=>true],
                'type' => 'string|array',
                "action" => '=', 'concat' => 'AND'
            ],
            [   'field'=>$tab.'.id' ,'params'=> 'id',
                'validate' =>['string'=>true,"empty"=>true],
                'type' => 'string|array',
                "action" => '=', 'concat' => 'AND'
            ],
            [   'field'=>$tab.'.physically' ,'params' => 'physically',
                'validate' =>['string'=>true,"empty"=>true],
                'type' => 'string|array',
                "action" => '=', 'concat' => 'AND'
            ],
        ];
        $this->filter =  array_merge($this->filter,parent::getFilter());
        return $this->filter;
    }
   protected function compileGroupParams() {
        $this->group_params = [
            'select' => [],
            'by' => [],
            'relatedModel'=>[
                'Route'=>[
                    'entity'=>new SystemRouts(),
                    'relationship' => ['id','route_id'],
                ],
                'Site'=>[
                    'entity'=>new SystemSite(),
                    'relationship' => ['id','site_id'],
                ]
            ]
        ];
        return $this->group_params;
    }

   private function getAllTemplates(){
        $tree = new buildTreeFromDirectory(resource_path().self::_PATH_);
        $tree->setFilter($this->allow_ext, $this->prefix,['layouts']);
        $dirs = $tree->getTree();
        if(isset($dirs['dirs']))
            $this->rebuildTreeFolder($dirs['dirs']);
        return $this;
    }
   private function rebuildTreeFolder($dataTree) {
        foreach ($dataTree as $key => $item){
            if(key_exists('files',$item)){
                foreach ($item['files'] as $file) {
                    $this->Template[] = implode(".", [
                        self::_MAIN_PATHP_,
                        str_replace(['/', '\\'], '.', $file['relative_path']),
                        str_replace(".blade.php", '', $file['filename'])
                    ]);
                }
            }
            if(key_exists('dirs',$item))
                $this->rebuildTreeFolder($item['dirs']);
        }
        return $this;
   }
}
