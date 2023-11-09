<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 01.03.2023
 * Time: 14:00
 */

namespace App\System\Parser;
use App\Models\CoreEngine\LogicModels\ComboSite\CategoryLogic;
use Illuminate\Support\Facades\DB;

class CategoryParserCSV extends ParserSystemCSV {
    private $listCategory = [];
    private $model_ = null;
    public function __construct(){
        $this->model_ = new CategoryLogic();
        parent::__construct($this->model_->getEngine());
    }

    protected function multiData($data,$index){
        if($index == 0) {
            if ($this->fileColumn) {
                $EndCol = strpos($data, $this->fileSeparator)+strlen($this->fileSeparator);
                $data = substr($data, $EndCol);
            }
        }
        $list = explode($this->fileSeparator,trim($data));
        $arrayKeyColum = array_keys($this->columnToParse);
        $takeIndex = [];
        foreach ($arrayKeyColum as $column_){
            $takeIndex[] = array_search($column_,$this->fileColumn);
        }
        foreach ($list as $key => $item){
            $items = explode(',',$item);
            foreach ($takeIndex as $index__){
                if(count($items) == count($this->fileColumn)) {
                    if(!empty($items[$index__])) {
                        $this->listCategory[$items[$index__]] = $items[$index__];
                    }
                }
            }
        }
    }
    public function saveData(){
        $this->listCategory['Other'] = 'Other';
        foreach ($this->listCategory as $key => $item){
            if(empty($this->listCategory[$key])){
                unset($this->listCategory[$key]);
            }
        }
        foreach ($this->listCategory as $item ){
            $alias = strtolower(\Transliterator::create('Any-Latin; Latin-ASCII')->transliterate($item));
            $alias = preg_replace('/[^a-z0-9-_]/','',$alias);
                $this->model_->save([
                    'alias_url' => $alias,
                    'site_id' => 1,
                    'lang_id' => 1,
                    'name' => $item,
                ]);
        }
    }
}