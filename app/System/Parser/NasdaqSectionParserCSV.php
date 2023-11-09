<?php

namespace App\System\Parser;
use Illuminate\Support\Facades\DB;

class NasdaqSectionParserCSV extends  ParserSystemCSV {

    private $listCategory = [];

    public function __construct($model){
        parent::__construct($model);
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

        foreach ($this->listCategory as $key => $item){
            if(empty($this->listCategory[$key])){
                unset($this->listCategory[$key]);
            }

        }
        $group = "('".implode("'),('",$this->listCategory)."')";

        if(is_array($this->addToInsertParams)){
            $colunInsert = implode(",",array_merge($this->columnToParse,array_keys($this->addToInsertParams)));
        }else{
            $colunInsert = implode(",",$this->columnToParse);
        }
        $insert = "INSERT INTO ".$this->model->getTable()."  (".$colunInsert.") VALUES ".$group;
        DB::beginTransaction();
        try {
            DB::insert($insert);
            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
        }
        $insert = "INSERT INTO ".$this->model->getTable()."  (id,".$colunInsert.") VALUES (0,'Other')";
        DB::insert($insert);
    }
}
