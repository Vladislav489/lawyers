<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 30.01.2023
 * Time: 12:12
 */

namespace App\Models\System\General;


use App\Models\CoreEngine\Core\CoreEngine;
use App\Models\CoreEngine\Model\SystemSeo;
use App\Models\System\buildTreeFromDirectory;
use App\Models\System\LogicModulsInfo;
use Illuminate\Support\Facades\Cache;

class Seo extends CoreEngine {
    private $filedTransform = ['title','description','keywords'];
    private $clearParams = null;
    private $ListObject = null;

    public function __construct($params = [],$select = ['*'],$paramsObj = [],$callback = null){
        $this->clearParams = $this->checkRulesParasms($paramsObj);
        $params['is_delete'] = 0;
        $this->engine = new SystemSeo();
        $this->query = $this->engine->newQuery();
        $this->getFilter();
        $this->compileGroupParams();
        parent::__construct($params,$select);
    }
    public function getList(){
        $this->executeFilter();
        $result = $this->getSandartResultList();
        if(isset($result['error'])){dd($result);}
        return $result;
    }

    private function getDataClassByTemplate($data){
        $ObjectClassField = $ObjectClass = $return = [];
        foreach ($data as $key => $item){
               foreach ($item as $element){
                   $element = str_replace(['<<','>>'],'',$element);
                   $expTemplate = explode(".",$element);
                   $ObjectClass[$expTemplate[0]] = $expTemplate[0];
                   $ObjectClassField[$expTemplate[0]][$expTemplate[1]] = $expTemplate[1];
               }
        }

        if(count($ObjectClass) == 0)
            return $return;
            foreach ($ObjectClass as $key => $class) {
                if($class != "Date"){
                    $listParamsClass = LogicModulsInfo::getInstace()->findByClassName($class);
                    $obj = new $listParamsClass['namespace_class']($this->clearParams);
                    $obj->executeFilter();
                    if (count($obj->getRealExistFilter()) || true) {
                        $ObjectClass[$key] = $obj->getSandartResultOne();
                        foreach ($ObjectClass[$key] as $key__ => $item__) {
                            if (!isset($ObjectClassField[$class][$key__])) {
                                unset($ObjectClass[$key][$key__]);
                            } else {
                                $temp = $ObjectClass[$key][$key__];
                                unset($ObjectClass[$key][$key__]);
                                $return["<<" . $key . "." . $key__ . ">>"] = $temp;
                            }
                        }
                    }
                } else {
                    foreach ($ObjectClassField[$class] as $key__ => $item__)
                            $return["<<" . $key . "." . $key__ . ">>"] = date($item__);
                }
            }
        return $return;
    }

    public function getListModule(){
        return LogicModulsInfo::getInstace()->getParmasFromObject();
    }
    private function parserRules($data){
        $outRes = [];
        preg_match_all("/(\<<(.+?)\>>)/",$data,$outRes);
        return(isset($outRes[0]))? $outRes[0]: null;
    }

    public function buildText(){
       $result = $this->getOne();
       $parserTag = [];
       if (is_array($result) && count($result)) {
            foreach ($this->filedTransform as $item)
                $parserTag[$item] = $this->parserRules($result[$item]);

            $dataReplace = $this->getDataClassByTemplate($parserTag);
            foreach ($this->filedTransform as $item) {
                if(!is_null($result[$item]))
                    $result[$item] = str_replace(array_keys($dataReplace),$dataReplace,$result[$item]);
            }
            return $result;
       }
       return null;
    }
    public function getOne(){
        $this->executeFilter();
        return $this->getSandartResultOne();
    }

    protected function defaultSelect(){
        $tab = $this->engine->tableName();
        return $this->default = [];
    }
    protected function getFilter(){
        $tab = $this->engine->getTable();
        $this->filter = [
            [   'field'=>$tab.'.id','params' => 'id',
                'validate' =>['string'=>true,"empty"=>true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND'
            ],
            [   'field'=>$tab.'.site_id','params' => 'site_id',
                'validate' =>['string'=>true,"empty"=>true],
                'type' => 'string|array',
                "action" => '=', 'concat' => 'AND'
            ],
            [   'field'=>$tab.'.template_id','params' => 'template_id',
                'validate' =>['string'=>true,"empty"=>true],
                'type' => 'string|array',
                "action" => '=', 'concat' => 'AND'
            ],
            [   'field'=>$tab.'.route_id','params' => 'route_id',
                'validate' =>['string'=>true,"empty"=>true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND'
            ]
        ];
        $this->filter =  array_merge($this->filter,parent::getFilter());
        return $this->filter;
    }
    protected function compileGroupParams() {
        $this->group_params = [
            'select' => [], 'by' => [], 'relatedModel'=>[]
        ];
        return $this->group_params;
    }

    protected function checkRulesParasms($params){
        if(isset($params['code']) && strpos($params['code'],'-') !== false)
            $params['code'] = explode("-",$params['code'])[0];
        return $params;
    }
}