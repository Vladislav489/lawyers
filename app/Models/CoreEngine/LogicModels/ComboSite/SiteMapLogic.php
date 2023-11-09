<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 05.06.2023
 * Time: 11:01
 */

namespace App\Models\CoreEngine\LogicModels\ComboSite;

use App\Models\CoreEngine\LogicModels\ComboSite\TypeLogic;
use App\Models\CoreEngine\LogicModels\ComboSite\CategoryLogic;
use App\Models\CoreEngine\Core\CoreEngine;
use App\Models\CoreEngine\Model\SystemSitemap;
use App\Models\System\General\Routs;
use App\Models\System\General\Site;
use App\Models\System\HelperFunction;
use App\Models\System\LogicModulsInfo;
use App\Models\System\RouteBilder;
use App\Models\System\Securities;
use http\Env\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use stringEncode\Exception;

class SiteMapLogic extends  CoreEngine {
    const  SITEMAP_NAME = "sitemap.xml";
    private $urlList = null;
    private $dirSitemap = null;
    private $dirDomains = null;
    private $site_id = null;
    public function __construct($params = [],$select =['*'],$callback = null){
        $this->engine = new SystemSitemap();
        $this->query = $this->engine->newQuery();
        $this->getFilter();
        $this->compileGroupParams();
        $this->site_id = Site::getSiteId();
        $params['site'] = (isset($params['site'])) ?$params['site']:$this->site_id;
        if(is_numeric($params['site']) && $params['site'] ==-1)
            unset($params['site']);

        if(is_null($this->site_id)){
            throw  new Exception("Site id is not undefined... (null)");
        }
        $this->dirDomains = public_path("sitemap_")."/".Site::getSite()['domain_name'];
        $this->dirSitemap = $this->dirDomains.'/'.'sitemap';

        parent::__construct($params,$select);
    }

    private function fillUrlSiteMap(){
      $listRout = RouteBilder::getRotesAllRouts();
      $newList = [];
      //взять все доступные пути
      foreach ($listRout['controller'] as $key => $item){
                $newList[$item['url']]  = $item;
      }
      //убрать все неактивные закрытые и дкфолтные
      foreach ($newList as $key => $item){
          if (isset($item['open']) && isset($item['active'])) {
              if($item['open'] == 0 ||$item['active'] == 0)
                  unset($newList[$key]);
          } else {
              unset($newList[$key]);
          }
      }
      $group = [];
      foreach ($newList as $key => $item){
          if ($key != "/") {
              $url = explode("/",$key);
              $group[$url[0]][$key] = $item;
          } else {
              $group[$key][$key] =$item;
          }
      }
      foreach ($group as $key => $item){
          if (count($item) == 1){
              $group['main'][$key] = $item;
              unset($group[$key]);
          }
      }
      $group = array_reverse($group);
      foreach ($group as $key => $item ){
        if ($key != 'main') {
            $newSubItem = [];
            foreach ($item as $key_sub => $item_sub){
                    if(preg_match("/\{[\/\!]*?[^\{\}]*?}/",$key_sub))
                        $newSubItem[$key_sub] = $item_sub;
            }
            $group[$key] = $newSubItem;
        }
      }
      return $group;
    }

    private function AddToDb($data,$route){
        $sqlQueryText = "";
        $insertone = [];
        foreach ($data as $key => $item){
            $insert = "";
            $url = $item;
            $item = [];
            $item['url'] = $url;
            $item['route_id'] = $route['id'];
            $item['site_id'] = $this->site_id;
            $item['lang_id'] = "1";
            $item['is_delete'] = "0";
            $item['cache'] = "0";


            $insert = explode("~~~~~~", addslashes(implode("~~~~~~",$item)));
            $insertone[] = "('".implode("','",$insert)."')";
        }
        $sqlQueryText = " INSERT INTO ".self::getTable()." (".implode(",",['url','route_id','site_id','lang_id','is_delete','cache']).") VALUES".implode(",",$insertone);
        DB::statement($sqlQueryText);
    }

    private function BuildListUrl($routeParasm){
            $returnListUrl = [];
            $class = LogicModulsInfo::getInstace()->findByClassName($routeParasm['check_module']);
            $params_url = $params = Routs::parseUrlTemplateParams($routeParasm);

            foreach ($params as $key_param => $item_param) {
                if ($params[$key_param] == "")
                         unset($params[$key_param]);
            }

            if(isset($params['type']) && $params['type']){
                if(!is_numeric($params['type']))
                    $params['type'] = (string)TypeLogic::getTypeId($params['type']);
            }

            if(isset($params['category']) && $params['category']){
                if(!is_numeric($params['category']))
                    $params['category'] = (string)CategoryLogic::getCategoryId($params['category']);
            }
            $list = new $class['namespace_class']($params);
            $params['pageSize'] = (string)$list->getTotal();
            $params['page'] = "1";
            $params['active'] = "1";
            $list = new $class['namespace_class']($params);
            $filter = $list->getFilter();
            $forUrlTabField = [];
            foreach ($filter as $item) {
                foreach ($params_url as $key_url => $item_url) {
                    if ($item['params'] == $key_url) {
                        $forUrlTabField[$item['params']] = explode(".", $item['field'])[1];
                    }
                }
            }

            preg_match_all("/\{[\/\!]*?[^\{\}]*?}/",$routeParasm['url'],$seqrch);
            $seqrch = implode("|", $seqrch[0]);
            $list = $list->getList();
            if(isset($params['type']) && $params['type'] == Securities::TYPE_PHYSICAL &&
                strpos($routeParasm['url'],'forecast') !== false ) {
                $buffer = ['result'=>[]];
                foreach ($list['result'] as $key => $item) {
                    foreach ($list['result'] as $key_sub => $item_sub) {
                        if($item['short_name'] != $item_sub['short_name']) {
                            $short_name = $item['short_name'] . "-" . $item_sub['short_name'];
                            $newData = $item;
                            $newData['short_name'] = $short_name;
                            $buffer['result'][] = $newData;
                        }
                    }
                }
                $list['result'] = $buffer['result'];
            }

            foreach ($list['result'] as $key => $item) {
                $paramsForUrl = [];
                foreach ($forUrlTabField as $key_params => $field) {
                    if (strpos($seqrch, $key_params) !== false) {
                        $paramsForUrl[$key_params] = $item[$field];
                    }
                }
                $returnListUrl[] = route($routeParasm['name'], $paramsForUrl);
            }


            return $returnListUrl;
    }

    public function CreateSiteMapSub($target,$file,$ListObject){
       $res = false;
       if($target == "main"){
           $urlset = new \SimpleXMLElement('<urlset/>') ;
            foreach ($ListObject[$target] as $key => $item) {
               if(count($item) == 1) {
                     $item__ =  current($item);
                     if(!is_null($item__['check_module'])){
                         if(!preg_match("/\{[\/\!]*?[^\{\}]*?}/",$item__['url'])){
                             $this->AddToDb([url($item__['url'])],$item__);
                             $url = $urlset->addChild("url");
                             $url->addChild("loc",url($item__['url']));
                             $url->addChild('lastmod',date("Y-m-d"));
                         } else {
                             $dataUrl = $this->BuildListUrl($item__);
                             $this->AddToDb($dataUrl,$item__);
                             foreach ($dataUrl as $url_full){
                                 $url = $urlset->addChild("url");
                                 $url->addChild("loc",url($url_full));
                                 $url->addChild('lastmod',date("Y-m-d"));
                             }
                         }
                     } else {
                        $this->AddToDb([url($item__['url'])],$item__);
                        $url = $urlset->addChild("url");
                        $url->addChild("loc",url($item__['url']));
                        $url->addChild('lastmod',date("Y-m-d"));
                     }
               }
           }

           $xml = str_replace('<?xml version="1.0"?>',"<?xml version=\"1.0\" encoding=\"UTF-8\"?>",$urlset->asXML());
           $xml = simplexml_load_string($xml);
           $res =  $xml->saveXML($file);
       } else {
           $index = 0;
           $dataSave = [];
           foreach ($ListObject[$target] as $item__){
               $urlset = new \SimpleXMLElement('<urlset/>') ;
                    if (!is_null($item__['check_module'])) {
                        $dataUrl =  $this->BuildListUrl($item__);
                        $this->AddToDb($dataUrl,$item__);
                        foreach ($dataUrl as $url_full){
                            $url = $urlset->addChild("url");
                            $url->addChild("loc",url($url_full));
                            $url->addChild('lastmod',date("Y-m-d"));
                        }
                        $xml = str_replace('<?xml version="1.0"?>',"<?xml version=\"1.0\" encoding=\"UTF-8\"?>",$urlset->asXML());
                        $xml = simplexml_load_string($xml);
                        $res = $xml->saveXML($file[$index]);
                    }

               $index++;
           }
       }
        return $res;
    }

    public function createSiteMap(){
        $this->urlList = $this->fillUrlSiteMap();
        $listFile = [];
        $result = false;
        DB::statement("DELETE FROM ".self::getTable()." WHERE site_id ='{$this->site_id}'");
        if(is_dir($this->dirSitemap)) {
            File::deleteDirectory($this->dirDomains);
        }
        if(!is_dir($this->dirSitemap)){
            if(!mkdir($this->dirSitemap, 0777, true)){
                throw  new Exception("Failed to create directories...");
            }
        }

        $sitemapindex = new \SimpleXMLElement('<sitemapindex/>') ;
        $sitemapindex->addAttribute("xmlns","http://www.sitemaps.org/schemas/sitemap/0.9");

        foreach ($this->urlList as $key_g => $group){
            if($key_g != 'main'){
                foreach ($group as $key => $item){
                    $key_ = preg_replace("/\{[\/\!]*?[^\{\}]*?}/","",str_replace("/",'-',$key));
                    $listFile[$key_g][] =  $nameFileSub = $this->dirSitemap."/".$key_.self::SITEMAP_NAME;
                    $sitemap = $sitemapindex->addChild("sitemap");
                    $sitemap->addChild("loc",url($key_.self::SITEMAP_NAME));
                    $sitemap->addChild("lastmod",date("Y-m-d"));
                }
            } else {
                $listFile[$key_g] =  $nameFileSub =  $this->dirSitemap."/".$key_g.self::SITEMAP_NAME;
                $sitemap = $sitemapindex->addChild("sitemap");
                $sitemap->addChild("loc",url($key_g.self::SITEMAP_NAME));
                $sitemap->addChild("lastmod",date("Y-m-d"));
            }
        }
        $xml = str_replace('<?xml version="1.0"?>',"<?xml version=\"1.0\" encoding=\"UTF-8\"?>",$sitemapindex->asXML());
        $xml = simplexml_load_string($xml);
        $result =  $xml->saveXML($this->dirSitemap."/".self::SITEMAP_NAME);
        foreach ($listFile as $target => $item){
            $result = $this->CreateSiteMapSub($target,$item,$this->urlList);
        }

        return $result;
    }

    public function getListFileSiteMap(){
      $listFile = [];
      $t = new \DirectoryIterator($this->dirSitemap);

        foreach ((new \DirectoryIterator($this->dirSitemap)) as $file) {
            if($file->isDot()) continue;
            $listFile[] =  [
                'name'=>$file->getFilename(),
                'datetime'=>date("Y-m-d H:i:s",$file->getCTime()),
                'size'=>HelperFunction::size_format($file->getSize())
            ];
        }
      return ['result'=>$listFile];
    }

    protected function getFilter(){
        $tab = $this->engine->getTable();
        $this->filter = [
            [   'field'=>$tab.'.id','params' => 'id',
                'validate' =>['string'=>true,"empty"=>true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND'
            ],
            [   'field'=>$tab.'.url','params' => 'url',
                'validate' =>['string'=>true,"empty"=>true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND'
            ],
            [   'field'=>$tab.'.site_id','params' => 'site',
                'validate' =>['string'=>true,"empty"=>true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND'
            ],
            [   'field'=>$tab.'.lang_id','params' => 'lang',
                'validate' =>['string'=>true,"empty"=>true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND'
            ],
            [   'field'=>$tab.'.route_id','params' => 'route',
                'validate' =>['string'=>true,"empty"=>true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND'
            ],
            [   'field'=>$tab.'.cache','params' => 'status_cache',
                'validate' =>['string'=>true,"empty"=>true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND'
            ],
            [   'field'=>$tab.'.is_delete','params' => 'delete',
                'validate' =>['string'=>true,"empty"=>true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND'
            ],
        ];
        $this->filter =  array_merge($this->filter,parent::getFilter());
        return $this->filter;
    }
    protected function compileGroupParams() {
        $this->group_params = [
            'select' => [],
            'by' => [],
            'relatedModel'=>[]
        ];

        return $this->group_params;
    }

}