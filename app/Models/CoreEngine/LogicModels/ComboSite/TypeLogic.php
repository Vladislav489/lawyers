<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 27.02.2023
 * Time: 16:56
 */

namespace App\Models\CoreEngine\LogicModels\ComboSite;
use App\Models\CoreEngine\Core\CoreEngine;
use App\Models\CoreEngine\Model\InformationType;
use App\Models\CoreEngine\Model\InformationTypeName;
use App\Models\CoreEngine\Model\SystemSite;
use App\Models\System\General\Site;
use Illuminate\Database\Eloquent\Model;

class TypeLogic extends CoreEngine{
    private $helpEngine = null;
    public function __construct($params = [],$select =['*'],$callback = null){
        $this->engine = new InformationType();
        $this->helpEngine['InformationTypeName'] = new InformationTypeName();
        $this->query = $this->engine->newQuery();
        $this->getFilter();
        $this->compileGroupParams();
        parent::__construct($params,$select);
    }

    public function getList(){
        $this->setJoin(['TypeName','Site']);
        $this->executeFilter();
        $result = $this->getSandartResultList();
        if(isset($result['error'])){
            dd($result);
        }
        return $result;
    }

    public function getHelpEngine(){
        return $this->helpEngine;
    }
    public function save($data){
        $result = false;
        $type = [];
        $typeName = [];
        foreach ($this->engine->getFillable() as $key => $item){
            if(isset($data[$item])) {
                $type[$item] = $data[$item];
            }
        }
        if(isset($data['id'])) {
            $type['id'] = $data['id'];
        }
        $result = $idType = parent::save($type);
        if(!isset($typeName['type_id'] )){
            $typeName['type_id'] = $idType;
        }
        foreach ($this->helpEngine['InformationTypeName']->getFillable() as $key => $item){
            if(isset($data[$item])) {
                $typeName[$item] = $data[$item];
            }
        }
        if(isset($type['id']))
            $typeName['type_id'] = $type['id'];
        if (isset($data['id_name'])) {
            $result = $this->helpEngine['InformationTypeName']->newQuery()->whereIn('id', [$data['id_name']])->update($typeName);
        } else {
            $result = $this->helpEngine['InformationTypeName']->newQuery()->insert($typeName);
        }
        return  $result;
    }

    public function delete($id, $flagForeva = false){
        $result = false;
        $result = parent::delete($id, $flagForeva);
        if($flagForeva) {
            $result = $this->helpEngine['InformationTypeName']->newQuery()->whereIn('type_id', [$id])->delete();
        }else{
            $result = $this->helpEngine['InformationTypeName']->newQuery()->whereIn('type_id', [$id])->update(['is_deleted'=>1]);
        }
        return $result;
    }

    public static function getTypeId($nameAlias){
        $typeObj = new TypeLogic([
            'alias'=>$nameAlias,
            'site'=>(string)Site::getSiteId()
        ],['id']);
        $result = $typeObj->getOne();
        unset($typeObj);
        return (isset($result['id']) && !is_null($result['id']))?$result['id']:null;
    }
    public function getOne(){
        $this->executeFilter();
        return $this->getSandartResultOne();
    }
    protected function defaultSelect(){
        $tab = $this->engine->tableName();
        $this->default = [];
        return $this->default;
    }
    protected function getFilter(){
        $tab = $this->engine->getTable();
        $this->filter = [
            [   'field'=>$this->helpEngine['InformationTypeName']->getTable().'.name','params' => 'name',
                'validate' =>['string'=>true,"empty"=>true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
                'relatedModel'=>"TypeName"
            ],
            [   'field'=>$this->helpEngine['InformationTypeName']->getTable().'.group','params' => 'group',
                'validate' =>['string'=>true,"empty"=>true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
                'relatedModel'=>"TypeName"
            ],
            [   'field'=>$this->helpEngine['InformationTypeName']->getTable().'.lang_id','params' => 'lang',
                'validate' =>['string'=>true,"empty"=>true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
                'relatedModel'=>"TypeName"
            ],
            [   'field'=>$tab.'.alias_url','params' => 'alias',
                'validate' =>['string'=>true,"empty"=>true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND'
            ],
            [   'field'=>$tab.'.site_id','params' => 'site',
                'validate' =>['string'=>true,"empty"=>true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND'
            ],
            [   'field'=>$tab.'.parent_id','params' => 'parent_id',
                'validate' =>['string'=>true,"empty"=>true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND'
            ],
            [   'field'=>$tab.'.phone','params' => 'phone',
                'validate' =>['string'=>true,"empty"=>true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND'
            ],
            [
                'field'=>$tab.'.active','params' => 'active',
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
            'relatedModel'=>[
                'TypeName'=>[
                    'entity'=>new InformationTypeName(),
                    'relationship' => ['type_id','id'],
                    'field' => ['name','sort','id as id_name','lang_id'],
                ],
                'Site'=>[
                            'entity'=>new SystemSite(),
                            'relationship' => ['id','site_id'],
                            'field' => ['domain_name'],
                ]
            ]
        ];

        return $this->group_params;
    }

}
