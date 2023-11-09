<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 17.08.2022
 * Time: 20:27
 */

namespace App\Models\CoreEngine\Model;

use Illuminate\Database\Eloquent\Model;
use App\Models\CoreEngine\Model\InterfacModel\InterfaceModyfiModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ModyfiModel extends Model implements InterfaceModyfiModel {
    private static $instace = null;
    protected $engine;
    protected $table  = "";
    public static function getInstace(){
        if(is_null(self::$instace))
            self::$instace = new self();
        return self::$instace;
    }
    public function getLable(){
        return null;
    }

    public function removeNotExistCol($array){
        return self::removeNotExistCol_($array);
    }
    public static function  removeNotExistCol_($array){
        $arrayCol = (new static())->getFillable();
        $arrayCol[] = 'id';
        foreach ($array as $key => $item){
            if(!in_array($key,$arrayCol)){
                unset($array[$key]);
            }
        }
        return $array;
    }

    public function getFillableTable(){
      $list = parent::getFillable();
      foreach ($list as $key => $item){
          $list[$key] = $this->getTable().".".$item;
      }
      return $list;
    }

    public static function  getTableSchema(){
       $table = (new static)->table;
       $list = DB::select("DESCRIBE ".$table);
       $newList = [];
       foreach ($list as $item){
           $newList[$item->Field] = $item;
       }
       return $newList;
    }
}
