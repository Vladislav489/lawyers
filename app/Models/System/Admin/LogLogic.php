<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 22.12.2022
 * Time: 21:11
 */

namespace App\Models\System\Admin;

use App\Models\CoreEngine\Core\CoreEngine;
use App\Models\System\SystemLog;
use Illuminate\Database\Eloquent\Model;

class LogLogic extends CoreEngine {
    public function __construct($params = [],$select = ['*'],$callback = null){
        $this->engine = new SystemLog();
        $this->query = $this->engine->newQuery();
        $this->getFilter();
        $this->compileGroupParams();
        parent::__construct($params,$select);
    }

    public function getLog(){
        $this->executeFilter();
        $result = $this->getSandartResultList();
        $listTypeLog = SystemLog::getListConst('rus');
        foreach ($result['result'] as $key => $item ){
            $result['result'][$key]['code'] = (isset($listTypeLog[$result['result'][$key]['code']]))?
                 $listTypeLog[$result['result'][$key]['code']]:$result['result'][$key]['code'];
            $result['result'][$key]['created_at'] = date('Y-m-d h:i:s',strtotime($result['result'][$key]['created_at']));
        }
        return $result;
    }
    protected function getFilter(){
        $tab = $this->engine->getTable();
        $this->filter = [
            [   'field'=>$tab.'.title','params' => 'title',
                'validate' =>['string'=>true,"empty"=>true],
                'type' => 'string|array',
                "action" => '%LIKE%', 'concat' => 'AND'
            ],
            [   'field'=>"REG(".$tab.'.title,"")','params' => 'title_reg',
                'validate' =>['string'=>true,"empty"=>true],
                'type' => 'string|array',
                "action" => '%LIKE%', 'concat' => 'AND'
            ],
            [
                'field'=>$tab.'.short_text','params' => 'description',
                'validate' =>['string'=>true,"empty"=>true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND'
            ],
            [
                'field'=>$tab.'.code','params' => 'type',
                'validate' =>['string'=>true,"empty"=>true],
                'type' => 'string|array',
                "action" => '%LIKE%', 'concat' => 'AND'
            ],
            [
                'field'=>$tab.'.created_at','params' => 'date_to',
                'validate' =>['datetime'=>true,'empty'=>true],
                'type'=>'datetime',
                'action'=>'>=','concat'=>'AND'
            ],
            [
                'field'=>$tab.'.created_at','params' => 'date_from',
                'validate' =>['datetime'=>true,'empty'=>true],
                'type'=>'datetime',
                'action'=>'<=','concat'=>'AND'
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

