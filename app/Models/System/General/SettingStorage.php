<?php
namespace App\Models\System\General;

use App\Models\CoreEngine\Core\CoreEngine;
use App\Models\CoreEngine\Model\SystemSettingStoreg;
use Illuminate\Support\Facades\Auth;

class SettingStorage extends CoreEngine {
    public static $ins = null;
    public function __construct($params = [],$select = ['*'],$callback = null){
        $this->engine = new SystemSettingStoreg(); //Модель таблицы
        $this->query = $this->engine->newQuery();
        $this->site_id = Site::getSiteId(); // Айди текущего домена под которым зашли
        $this->uise_id = Auth::id();
        $this->lang_id = 1; //реализовать по сле запуска
        $this->getFilter();
        parent::__construct($params,$select);
    }

    public static function getInstance(){
        if(is_null(self::$ins))
            self::$ins = new SettingStorage();
        return self::$ins;
    }

    public function getSetting($key){
        $result = $this->get($key,$this->site_id,null);
        if($result)
            return$result['value'];
        return false;
    }

    public function getSettingForAllSite($key){
        $result = $this->get($key,null,null);
        if($result)
            return$result['value'];
        return false;
    }

    public function getSettingUser($key){
      $result = $this->get($key,$this->site_id,$this->uise_id);
      if($result)
          return$result['value'];
      return false;
    }

    public function setSettingForAllSite($key,$value){
        return $this->set($key, $value,null,null);
    }
    public function setSetting($key,$value){
        return $this->set($key,$value,$this->site_id,null);
    }

    public function setSettingUser($key, $value){
      return $this->set($key, $value, $this->site_id, $this->uise_id);
    }


    private function set($key, $value, $site_id = null, $user_id = null){
        $params = [
            'key' => $key,
            'value' => serialize($value)
        ];
        if(!is_null($site_id))
            $params['site_id'] = $site_id;
        if(!is_null($user_id))
            $params['user_id'] = $user_id;
        $exist = $this->get($key,$site_id,$user_id);

        return (!$exist)?$this->insert($params):$this->update($params,$exist['id']);
    }

    private function get($key, $site_id = null, $user_id = null){
        $this->claerQuery();
        $query = $this->query;
        $query->where('key','=',$key,"AND");

        if(!is_null($site_id))
            $query->where('site_id',"=",$site_id,"AND");
        if(!is_null($user_id))
            $query->where('user_id',"=",$user_id,"AND");

        $result = $query->get(['id','value'])->toArray();

        if(empty($result))
            return false;
        return  ['id' => $result[0]['id'],"value" => unserialize($result[0]['value'])];
    }

    protected function getFilter(){
        $tab = $this->engine->getTable();
        $this->filter = [
            [   'field'=>$tab.'.site_id','params' => 'site',
                'validate' =>['string'=>true,"empty"=>true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND'
            ],
            [   'field'=>$tab.'.user_id','params' => 'user',
                'validate' =>['string'=>true,"empty"=>true],
                'type' => 'string|array',
                "action" => '=', 'concat' => 'AND'
            ],
            [   'field'=>$tab.'.key','params' => 'key',
                'validate' =>['string'=>true,"empty"=>true],
                'type' => 'string|array',
                "action" => '=', 'concat' => 'AND'
            ]
        ];
        $this->filter =  array_merge($this->filter,parent::getFilter());
        return $this->filter;
    }

}