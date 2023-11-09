<?php
namespace App\System\Parser;

use App\Models\CoreEngine\Model\NasdaqSection;
use Illuminate\Support\Facades\DB;

class NasdaqParserCSV extends ParserSystemCSV {
    private $category = [];

    public function __construct($model){
        parent::__construct($model);
        $this->getCatalog();
    }

    private function getCatalog(){
        $category = NasdaqSection::query()->newQuery()->get("*")->toArray();
        foreach($category as $item){
            $this->category[$item['id']] = $item['section_name'];
        }
    }

    protected function multiData($data,$index){
        if($index == 0) {
            if ($this->fileColumn) {
                $EndCol = strpos($data, $this->fileSeparator)+strlen($this->fileSeparator);
                $data = substr($data, $EndCol);
            }
        }
        $insert ="";
        $list = explode($this->fileSeparator,trim($data));
        $arrayKeyColum = array_keys($this->columnToParse);
        $takeIndex = [];
        foreach ($arrayKeyColum as $column_){
            $takeIndex[] = array_search($column_,$this->fileColumn);
        }
        foreach ($list as $key => $item){
            $items = explode(',',$item);
            $newItems = [];
            foreach ($takeIndex as $index__){
                if(count($items) == count($this->fileColumn)) {
                    if($index__ ==  array_search('Sector',$this->fileColumn)) {
                        $category = array_search($items[$index__],$this->category);
                        if($category){
                            $newItems[] = $category;
                        }else{
                            $newItems[] = 0;
                        }
                    }else{
                        $newItems[] = $items[$index__];
                    }
                }
            }
            if($this->addToInsertParams){
                $newItems = array_merge($newItems,array_values($this->addToInsertParams));
            }
            $list[$key] = '"'.implode("\",\"",$newItems).'"';
        }

        $colunInsert = implode(",",array_merge($this->columnToParse,array_keys($this->addToInsertParams)));
        $insert = "INSERT INTO ".$this->model->getTable()."  (".$colunInsert.") VALUES (".implode("),(",$list).")";
        DB::beginTransaction();
        try {
            DB::insert($insert);
            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
        }
    }
}
