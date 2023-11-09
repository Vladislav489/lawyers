<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 13.02.2023
 * Time: 17:08
 */

namespace App\Models\System;


use App\Models\System\General\Site;
use Illuminate\Support\Facades\Cache;

class LogicModulsInfo{
    const  CACHE_DATA_FIELD_OBJ = "data_obj_moduls";

    protected       $path_Logic = "";
    protected       $object = [];
    private static  $instace = null;
    private         $callBack = null;
    public static function getInstace(){
        if(is_null(self::$instace)){
            self::$instace = new self();
        }
        return self::$instace;
    }

    public function __construct(){
        $this->path_Logic = app_path("Models")."/CoreEngine/LogicModels/";
        if(empty(Cache::get(self::CACHE_DATA_FIELD_OBJ.'_'.Site::getSiteId()))){
            $this->object = $this->buildSeoDinamicParams();
        }else{
            $this->object = Cache::get(self::CACHE_DATA_FIELD_OBJ.'_'.Site::getSiteId());
        }
    }


    public function getParmasFromObjectByPath($path){
        if(is_dir($path)){
            $this->path_Logic = $path;
            return $this->buildSeoDinamicParams();
        }
    }


    public function findByClassName($className,$key = null){
        foreach ($this->object as $key__ => $item){
            if(is_null($key)){
              if($item['class_name'] == $className)
                  return $this->object[$key__];
            }else{
                if($item[$key] == $className)
                    return $this->object[$key__];
            }
        }
        return false;
    }

    public function getParmasFromObject(){
        return $this->object;
    }

    private function addClassDate(){
        $this->object[] = [
            'class_name'=>"Date",
            'field'=>[],
            'lable'=>[
                ['key'=>'a','name'=>'am или pm'],
                ['key'=>'A','name'=>'AM или PM'],
                ['key'=>'B','name'=>'Время 	От 000 до 999'],
                ['key'=>'c','name'=>'ISO 8601 2004-02-12T15:19:21+00:00'],
                ['key'=>'d','name'=>'День месяца от 01 до 31'],
                ['key'=>'D','name'=>'Сокращенное наименование дня недели от Mon до Sun'],
                ['key'=>'F','name'=>'Полное наименование месяца от January до December'],
                ['key'=>'g','name'=>'Часы без ведущих нулей От 1 до 12'],
                ['key'=>'G','name'=>'Часы без ведущих нулей От 0 до 23'],
                ['key'=>'h','name'=>'Часы с ведущими нулями От 01 до 12'],
                ['key'=>'H','name'=>'Часы с ведущими нулями От 00 до 23'],
                ['key'=>'i','name'=>'Минуты с ведущими нулями 00 to 59'],
                ['key'=>'I','name'=>'Признак летнего времени'],
                ['key'=>'j','name'=>'День месяца без ведущих нулей От 1 до 31'],
                ['key'=>'l','name'=>'Полное наименование дня недели От Sunday до Saturday'],
                ['key'=>'L','name'=>'Признак високосного года'],
                ['key'=>'m','name'=>'Порядковый номер месяца с ведущими нулями От 01 до 12'],
                ['key'=>'M','name'=>'Сокращенное наименование месяца От Jan до Dec'],
                ['key'=>'n','name'=>'Порядковый номер месяца без ведущих нулей От 1 до 12'],
                ['key'=>'O','name'=>'Разница с временем по Гринвичу в часах	Например: +0200'],
                ['key'=>'r','name'=>'RFC Thu, 21 Dec 2000 16:01:07 +0200'],
                ['key'=>'s','name'=>'Секунды с ведущими нулями От 00 до 59'],
                ['key'=>'S','name'=>'Английский суффикс порядкового числительного дня месяца'],
                ['key'=>'t','name'=>'Количество дней в месяце От 28 до 31'],
                ['key'=>'T','name'=>'Временная зона на сервере EST, MDT ...'],
                ['key'=>'U','name'=>'Количество секунд, прошедших с начала Эпохи'],
                ['key'=>'w','name'=>'Порядковый номер дня недели	От 0 (воскресенье) до 6 (суббота)'],
                ['key'=>'W','name'=>'Порядковый номер недели года по ISO-8601'],
                ['key'=>'Y','name'=>'Порядковый номер года'],
                ['key'=>'y','name'=>'Номер года Примеры: 99, 03'],
                ['key'=>'z','name'=>'Порядковый номер дня в году (нумерация с 0) От 0 до 365'],
                ['key'=>'Z','name'=>'Смещение временной зоны в секундах.От -43200 до 43200'],
            ],
            'params'=>['a','A','B','c','d','D','F','g','G','h','H','i','I','i','j','l','L','m',
                'M','n','O','r','s','S','t','T','U','w','W','Y','y','z','Z',]
        ];
    }

    private function buildSeoDinamicParams($path = null){
            $tree = new buildTreeFromDirectory($this->path_Logic);
            $tree->setFilter(['*'], [], ['layouts']);
            $this->rebuildTreeFolder($tree->getTree()['dirs']);
            $this->addClassDate();
        return $this->object;
    }

    public function setCallBack($callBackFunction){
        $this->callBack = $callBackFunction;
        return $this;
    }

    private function rebuildTreeFolder($dataTree){
        foreach ($dataTree as $key => $item){
            if(key_exists('files',$item)){
                foreach ($item['files'] as $file) {
                    $className =  implode("/", [
                        substr($this->path_Logic,0,strlen($this->path_Logic)-1),
                        $file['relative_path'],
                        str_replace([".php"], '', $file['filename'])
                    ]);
                    $className = str_replace(base_path(),'',$className);
                    $className = str_replace('/','\\',$className);
                    $className = str_replace('app\\','App\\',$className);
                    if(is_subclass_of((new  $className()),"\\App\\Models\\CoreEngine\\Core\\CoreEngine")) {
                        $obj = (new  $className());
                        $class = explode("\\", get_class($obj));
                        $class = $class[count($class) - 1];
                        if (is_subclass_of($obj, "\\App\\Models\\CoreEngine\\Core\\CoreEngine")) {

                                $filed = $obj->getSchema();
                                $column = array_keys($filed);
                                $lable = (count($column) > 0) ? $obj->getLable($column, 'rus') : [];
                                $param = $obj->getParamsFilterAll();
                                    $this->object[] = [
                                        'lable' => $lable,
                                        'class_name' => $class,
                                        'table' =>($obj->getEngine() != null)?$obj->getEngine()->getTable():null,
                                        'namespace_class' => $className,
                                        'params' => $param,
                                        'field' => $filed
                                    ];
                        } else {
                            if (is_null($this->callBack)) {
                                $this->object[] = [
                                    'class_name' => $class,
                                    'namespace_class' => $className,
                                ];
                            } else {
                                $this->object[] = array_merge($this->callBack($obj), [
                                    'class_name' => $class,
                                    'namespace_class' => $className,
                                ]);
                            }
                        }
                    }
                }
            }
            if(key_exists('dirs',$item)){
                $this->rebuildTreeFolder($item['dirs']);
            }
        }
        return $this;
    }
}
