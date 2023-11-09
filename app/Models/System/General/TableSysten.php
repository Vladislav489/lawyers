<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 24.03.2023
 * Time: 13:01
 */

namespace App\Models\System\General;


use Illuminate\Support\Facades\DB;

class TableSysten{

    private $ignory = [];
    private $list = [];

    public function __construct(){
      $this->list = json_decode(json_encode(DB::select('SHOW TABLES')), true);
      foreach ($this->list as $key => $value){
          $copy = $value;
          unset($this->list[$key]);
          $this->list[$key] = ['id' => $value['Tables_in_laravel_sys'],'name' => $value['Tables_in_laravel_sys']];
      }
      $this->ignory = [
            'client','failed_jobs','migrations','password_resets','personal_access_tokens',
            'setting_global','system_component','system_lang','system_log','system_menu',
            'system_route','system_seo','system_site','system_status_import_start',
            'system_style','system_type_page','system_view','users'
      ];
    }

    public function addIgnory($listIgnory = []){
        foreach ($listIgnory as $value)
            array_push($this->ignory,$value);
        return $this;
    }

    public function getListTableByUser(){
        $return = [];
        foreach ($this->list as $key => $value){
               if(!in_array($value['id'],$this->ignory)){
                   $return[] = $value;
               }
        }
        return $return;
    }
    public function getListTable(){
        return $this->list;
    }

    public function getIgnoryList(){
        return $this->ignory;
    }

}