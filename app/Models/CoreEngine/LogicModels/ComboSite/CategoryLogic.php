<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 27.02.2023
 * Time: 16:56
 */

namespace App\Models\CoreEngine\LogicModels\ComboSite;
use App\Models\CoreEngine\Core\CoreEngine;
use App\Models\CoreEngine\Model\InformationCategory;
use App\Models\CoreEngine\Model\InformationCategoryName;
use App\Models\CoreEngine\Model\SystemSite;
use App\Models\System\General\Site;
use Illuminate\Database\Eloquent\Model;

class CategoryLogic extends CoreEngine{
    private $helpEngine;
    public function __construct($params = [],$select =['*'],$callback = null){
        $this->engine = new InformationCategory();
        $this->helpEngine['InformationCategoryName'] = new InformationCategoryName();
        $this->query = $this->engine->newQuery();
        $this->getFilter();
        $this->compileGroupParams();
        parent::__construct($params,$select);
    }

    public function getList(){
        $this->setJoin(['CategoryName','Site']);
        $this->executeFilter();
        $result = $this->getSandartResultList();
        if(isset($result['error'])){
            dd($result);
        }
        return $result;
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
        if(!isset($typeName['category_id'] )){
            $typeName['category_id'] = $idType;
        }
        foreach ((new InformationCategoryName())->getFillable() as $key => $item){
            if(isset($data[$item])) {
                $typeName[$item] = $data[$item];
            }
        }
        if(isset($type['id']))
            $typeName['category_id'] = $type['id'];
        if (isset($data['id_name'])) {
            $result = (new InformationCategoryName())->newQuery()->whereIn('id', [$data['id_name']])->update($typeName);
        } else {
            $result = (new InformationCategoryName())->newQuery()->insert($typeName);
        }
        return  $result;
    }

    public function delete($id, $flagForeva = false){
        $result = false;
        $result = parent::delete($id, $flagForeva);
        if($flagForeva) {
            $result = $this->helpEngine['InformationCategoryName']->newQuery()->whereIn('category_id', [$id])->delete();
        }else{
            $result = $this->helpEngine['InformationCategoryName']->newQuery()->whereIn('category_id', [$id])->update(['is_deleted'=>1]);
        }
        return $result;
    }


    public static function getCategoryId($nameAlias){
        $categoryObj = new CategoryLogic([
            'alias_url'=>$nameAlias,
            'site'=>(string)Site::getSiteId()
        ],['id']);
        $result = $categoryObj->getOne();
        unset($categoryObj);
        return $result['id'];
    }
    public function getOne(){
        $this->setJoin(['CategoryName','Site']);
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
            [   'field'=>$this->helpEngine['InformationCategoryName']->getTable().'.name','params' => 'name',
                'validate' =>['string'=>true,"empty"=>true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
                'relatedModel'=>"CategoryName"
            ],
            [   'field'=>$this->helpEngine['InformationCategoryName']->getTable().'.group','params' => 'group',
                'validate' =>['string'=>true,"empty"=>true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
                'relatedModel'=>"CategoryName"
            ],
            [   'field'=>$this->helpEngine['InformationCategoryName']->getTable().'.lang_id','params' => 'lang',
                'validate' =>['string'=>true,"empty"=>true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
                'relatedModel'=>"CategoryName"
            ],
            [   'field'=>$tab.'.id','params' => 'ids',
                'validate' =>['string'=>true,"empty"=>true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND'
            ],
            [   'field'=>$tab.'.alias_url','params' => 'alias',
                'validate' =>['string'=>true,"empty"=>true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND'
            ],
            [   'field'=>$tab.'.alias_url','params' => 'category_alias',
                'validate' =>['string'=>true,"empty"=>true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
                'relatedModel'=>"CategoryName"
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
                'CategoryName'=>[
                    'entity'=>new InformationCategoryName(),
                    'relationship' => ['category_id','id'],
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