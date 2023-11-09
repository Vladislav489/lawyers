<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 16.12.2022
 * Time: 15:14
 */

namespace App\Models\CoreEngine\LogicModels\ComboSite;

use App\Models\CoreEngine\Core\CoreEngine;
use App\Models\System\General\TreeBuild;
use Illuminate\Database\Eloquent\Model;

class MenuLogic extends CoreEngine {
    public function __construct($engine = NULL,$params = [],$select =['*'],$callback = null){
        if($engine instanceof Model){
            $this->limit = false;
            $this->engine = $engine;
            $this->query = $this->engine->newQuery();
            $this->getFilter();
            $this->compileGroupParams();
            parent::__construct($params,$select);
        }
    }

    public function getMemuItem(){
        $this->executeFilter();
        $result = $this->getSandartResultList();
        $tree = new TreeBuild();
        $result['result'] = $tree->setData($result['result'])->getTree();
        return $result;
    }
    protected function getFilter(){
        $tab = $this->engine->getTable();
        $this->filter = [
            [   'field'=>$tab.'.site_id','params' => 'site',
                'validate' =>['string'=>true,"empty"=>true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND'
            ],
            [
                'field'=>$tab.'.id','params' => 'menu_id',
                'validate' =>['string'=>true,"empty"=>true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND'
            ],
            [
                'field'=>$tab.'.parent_id','params' => 'parent_id',
                'validate' =>['string'=>true,"empty"=>true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND'
            ],
            [
                'field'=>$tab.'.active','params' => 'active',
                'validate' =>['string'=>true,"empty"=>true],
                'type' => 'int',
                "action" => '=', 'concat' => 'AND'
            ],
            [
                'field'=>$tab.'.is_delete','params' => 'is_delete',
                'validate' =>['string'=>true,"empty"=>true],
                'type' => 'int',
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
            'relatedModel'=>[]
        ];
        return $this->group_params;
    }
}