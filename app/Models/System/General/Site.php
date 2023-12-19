<?php

namespace App\Models\System\General;

use App\Models\CoreEngine\Core\CoreEngine;
use App\Models\CoreEngine\Model\SystemSite;
use App\Models\System\SessionServer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;

class Site extends CoreEngine {
    const ROOT_SITE_ID = 1;
    const ROOT_USER_ID = 1;
    const SITE_STATUS_ACTIVE = 1;
    const ROOT_SITE_NOT_ACTIVE = 0;
    private $site = null;
    public function __construct($params = [],$select = ['*'],$callback = null){
        $params['is_deleted'] = 0;
        if(is_null($this->site)){
            $this->site = self::getSite();
        }
        $this->engine = new SystemSite();
        $this->query = $this->engine->newQuery();
        $this->getFilter();
        $this->compileGroupParams();
        parent::__construct($params,$select);
    }

    public function getOwnerUserId(){return $this->site['user_main_id'];}
    public function getLangId(){return $this->site['lang_id'];}
    public function delete($id, $flagForeva = false){
        $site = new Site(['id'=>$id]);
        $res =$site->offPagination()->getOne();
        //чистет все сессии  на сервере связаные с этим доменом
        SessionServer::deleteSessionDomains($res);
        return parent::delete($id, $flagForeva);
    }
    public function save($data){
        $result = parent::save($data);
        if(isset($data['id'])){
            $site = new Site(['id'=>$data['id']]);
            //обновляет все сессии связанные с доменом
            SessionServer::updateSessionVariableDomains('site',$site->offPagination()->getOne());
        }
        return $result;
    }
    public function getList(){
        $this->executeFilter();
        $result = $this->getSandartResultList();
        if(isset($result['error'])) dd($result);
        return $result;
    }
    public function getOne(){
        $this->executeFilter();
        return $this->getSandartResultOne();
    }

    public static function SiteForSupperRoot($session,$domains){
        if(Auth::id() == self::ROOT_USER_ID){// если суппер рут то доступны любые сайты может под ними заходить
            if(!is_null($session) && isset($session['domain_name']) && $session['domain_name'] != $domains ){
                //Находим нужный домен
                try{
                    $site = SystemSite::query()->newQuery()
                        ->where('domain_name', '=', $domains, "AND")
                        ->get(['*'])->toArray()[0];
                }catch (\Throwable $e){
                    abort(500, 'Something went wrong!!!! Site table, check Db!!!!!!!');
                }
                $site['active'] = self::SITE_STATUS_ACTIVE; // Ставим в сесиию что он активный
                if(is_array($site) && isset($site['id'])){
                    //переписываем сессиию
                    Session::put('site',$site);
                    return $site;
                }
                return null; // сайта не существует
            }
            if(is_null($session)){ // грузим рутовый сайт по умолчанию что бы можно было пользоватся админкой и дефолтным сайтом
                return [ 'id' => self::ROOT_SITE_ID,
                    'active' => self::SITE_STATUS_ACTIVE,
                    'domain_name' => $domains
                ];
            }else{ // если находимся на на нужном сайте принудительно делаем активным
                $session['active'] = self::SITE_STATUS_ACTIVE;
                return $session;
            }
        }
        return $session;
    }
    public static function getSite(){
        $domains = request()->host();
        if((new SiteConfig())->getConfig('migration')) {
            $session = Session::get('site', null); // получение текущий сессии (данные о сайте)
            $session = self::SiteForSupperRoot($session, $domains); // если зашли под рутом то возвращаем сайт домена без проверо юзкра
            if(is_null($session)){
                //ищем сайт для обычного пользователя
                $site = SystemSite::query()->newQuery()
                    ->where("active", "=", "1", "AND")
                    ->where('domain_name', '=', $domains, "AND")
                    ->where("is_deleted", "=", "0")->limit(1)
                    ->get(['id', "domain_name", "active", "is_deleted", "lang_id", "user_main_id"])->toArray();
                $site = (isset($site[0]['id']))?$site[0]:null;
                $session = $site;
                Session::put('site',$site);
            }
            if (!is_null($session) && isset($session['active'])) {
                return ($session['active'] == self::SITE_STATUS_ACTIVE || Auth::id() == self::ROOT_USER_ID) ? $session : null;
            }
        }else{
            //если база не импортированна вернем дкфолтные настройки для рута  если пользователь не рут  вернем то что передали null
            $session = self::SiteForSupperRoot(null, $domains);
            return (!is_null($session))?$session : null;
        }

    }
    public static function getSiteId(){
        $site = self::getSite();
        return (!is_null($site))?$site['id']:null;
    }

    protected function defaultSelect(){
        $tab = $this->engine->tableName();
        $this->default = [];
        return $this->default;
    }
    protected function getFilter(){
        $tab = $this->engine->getTable();
        $this->filter = [
            [   'field'=>$tab.'.id','params' => 'id',
                'validate' =>['string'=>true,"empty"=>true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND'
            ],
            [   'field'=>$tab.'.domain_name','params' => 'name',
                'validate' =>['string'=>true,"empty"=>true],
                'type' => 'string|array',
                "action" => '=', 'concat' => 'AND'
            ],
            [   'field'=>$tab.'.active','params' => 'active',
                'validate' =>['string'=>true,"empty"=>true],
                'type' => 'string|array',
                "action" => '=', 'concat' => 'AND'
            ],
            [
                'field'=>$tab.'.user_main_id','params' => 'user_id',
                'validate' =>['string'=>true,"empty"=>true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND'
            ]
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