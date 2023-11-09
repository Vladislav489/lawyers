<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 05.01.2023
 * Time: 14:07
 */

namespace App\Models\System\General;
use App\Models\CoreEngine\Core\CoreEngine;
use App\Models\CoreEngine\Model\SystemRouts;
use App\Models\CoreEngine\Model\SystemSite;
use App\Models\System\ControllersModel\FrontController;
use App\Models\System\LogicModulsInfo;
use App\Models\System\RouteBilder;
use App\Models\System\Securities;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class Routs extends CoreEngine {
    protected $systemRouts = null;
    protected $site_id = null;
    protected $lang_id = null;
    protected $routes = null;
    protected $url;
    protected $url_system;
    public function __construct($params = [],$select = ['*'],$callback = null){
        $params['is_delete'] = 0; //Дефолтный фильтер что бы не выбирпло удалленые записи
        $this->engine = new SystemRouts(); //Модель таблицы
        $this->systemRouts = RouteBilder::getRotesBuild();//Получаем все поуты для текущего сайта
        $this->query = $this->engine->newQuery();
        $this->site_id = Site::getSiteId(); // Айди текущего домена под которым зашли
        $this->getFilter();
        $this->compileGroupParams();
        parent::__construct($params,$select);
    }
    //Быстрый получение урлов урлу
    public static function getSystemRoutes(){
       return (new Routs())->getAllSystemUrl();
    }
    /*
     * Быстрый поик по урлу в laravel системе
     * @var url адресс в адресной строке
     * @var route_url дополнительн урл
     * @return array rout
     */
    public static function getSystemRoutByUrl($url,$route_url = null){
        $route = (new Routs())->getAllSystemUrl($url);
        if (!is_null($route))
            return $route['result'];

        $route = (new Routs())->getAllSystemUrl($route_url);
        return (!is_null($route))? $route['result']:null;
    }
    //Собирает данные из человекоподобного урла
    public static function parseUrlTemplateParams($route,$url = null){
        $params = [];
        $url = (is_null($url))? $route['url']:$url;
        //переделать
        $url = (strlen($url) <= 1)? RouteBilder::T_S_P:((strlen($url) <= 1)? $url:substr($url,1));

        if(is_null($route['template_url']))
            return [];

        //разбиваем урл на сегменты
        $url = explode(RouteBilder::T_S_P,(is_null($url))? $route['url']:$url);
        //разбиваем template_url на сегменты
        $templateUrl = explode(RouteBilder::T_S_P,$route['template_url']);

        try {
            //перебираем сигменты
            for ($index = 0; $index < count($url); $index++) {
                //ищем динамические параметры в урл
                $urlCheck = preg_match("/(\{(.+?)})/s", $url[$index]);
                //ищем динамические параметры в урл темплейте
                $templateCheck = preg_match("/(\{(.+?)})/s", $templateUrl[$index]);
                //формируем параметры ключ значение
                if ($url[$index] != $templateUrl[$index] && !$urlCheck) {
                    $params[str_replace(['{', '}', '?'], '', $templateUrl[$index])] = $url[$index];
                } else {
                    if ($urlCheck && $templateCheck && $url[$index] == $templateUrl[$index])
                        $params[str_replace(['{', '}', '?'], '', $templateUrl[$index])] = '';
                }
            }
        } catch (\Throwable $e){
            dd($e->getFile(),$e->getLine(),$templateUrl,$url,$route);
        }
        return $params;
    }
    //Тяжелый поиск пути с учетами различных ситуаций
    public function getRouteHardUrl($url){
        $urlExplode = (RouteBilder::T_S_P == $url)? [$url]:explode(RouteBilder::T_S_P,$url);
        $urlExplode = array_filter($urlExplode, function($value) { return !is_null($value) && $value !== ''; });
        $search = [];
        $query = $this->engine->newQuery();
        $query->select()->where("is_delete", "=",0, "AND")
                        ->where("active", "=",1, "AND");
        if(!is_null($this->site_id))
            $query->where("site_id", "=", $this->site_id, "AND");
        foreach ($urlExplode as $key => $value) {
            $search[] = $value;
            $query->where("url", "LIKE",
            implode(RouteBilder::T_S_P, $search) . "%",
            (count($search) == 1) ? "AND" : "OR"
            );
        }
        $list = $query->get()->toArray();
        $urlSearch = implode(RouteBilder::T_S_P,$urlExplode);
        $multiUrl = [];
        foreach ($list as $key => $url_){
            if(preg_match("/\{.*\}/",$url_['url'])){
                $multiUrl[] = $url_;
                unset($list[$key]);
            }
        }
        foreach ($list as $key_ => $value) {
           if ($urlSearch == $value['url'])
                return $value;
        }
        foreach ($multiUrl as $key_ => $value) {
           $searchUrl = str_replace("/","\/",
               preg_replace("/\{[\/\!]*?[^\{\}]*?}/",".*",$value['url']));
           if(preg_match("/".$searchUrl."/",$url))
               return $value;
        }
        return null;
    }
    //Проверяет подлиность урла ко все системе учтывая системны и пользовательские пути(ДБ)
    public function checkDinamicParams($routs,$params){
        $params['active'] = "1";
        if (isset($params['type']) && !is_int($params['type'])) {
            $params['type_alias'] = $params['type'];
            unset($params['type']);
        }
        if (isset($params['category']) && !is_int($params['category'])) {
            $params['category_alias'] = $params['category'];
            unset($params['category']);
        }
        if (isset($routs['check_module']) && !is_null($routs['check_module']) && !empty($routs['check_module'])) {
           $class = LogicModulsInfo::getInstace()->findByClassName($routs['check_module']);
           $tempParams = null;
           if (isset($params['code']) && strpos($params['code'],'-') !== false) {
               $expCode = explode('-',$params['code']);
               $params['code'] = $expCode[0];
               $tempParams = $params;
               $tempParams['code'] = $expCode[1];
           }

           $obj = new $class['namespace_class']($params);
           $result = $obj->offPagination()->executeFilter();
           $result = $obj->getSandartResultOne();

           if(isset($result['type_inf_id']) && $result['type_inf_id'] == Securities::TYPE_PHYSICAL){
                if (is_null($tempParams)) {
                        if(strpos($routs['name_view'],'details') !== false ){
                            return redirect(url()->current()."-USD");
                        }
                } else {
                    $obj = new $class['namespace_class']($tempParams,['id']);
                    $obj->offPagination();
                    if (!$obj->Exist())
                        return 0;
                }
           }
           return (!empty($result['id']))?1:0;
        }
        return null;
    }
    //Поиск пути по все системе
    public function getRoute($urlReal,$route_url = null){
        $this->url = $urlReal;// улр из риквеста
        $this->url_system = $route_url; //Урл системы то что мжет быит template_url или

        // если адресс домашней страницы делаем обработку  но всистеме храним урлы без слкша с переди
        $url = (strlen($urlReal) <= 1)? "/":((strlen($urlReal) <= 1)? $urlReal:substr($urlReal,1));
        $params = ["url" => $url, "open" => '1', "active" => '1'];

        //Для разных спйтов
        if (!is_null($this->site_id))
            $params['site_id'] = $this->site_id;

        //простой поиск и быстрый поиск на соотвецтвие стандартом ядра и косвенным урлам типа /{type}/{code}
        $data = $this->setJoin(['Template'])->setParams($params)->getOne();
        if (is_array($data) && count($data) > 0) {
            $data['url_system'] = (!empty($data['template_url']))? $data['template_url']:$route_url;
            $data['params_route'] = self::parseUrlTemplateParams($data,$urlReal);
            $this->routes = $data;
            return $data;
        }
        if (!is_null($route_url)) {
            $params['url_system'] = $route_url;
            unset($params['url']);
        }
        $data = $this->setJoin(['Template'])->setParams($params)->claerQuery()->getOne();
        if (is_array($data) && count($data) > 0) {
            $data['url_system'] = (!empty($data['template_url']))? $data['template_url']:$route_url;
            $data['params_route'] = self::parseUrlTemplateParams($data,$urlReal);
            $this->routes = $data;
            return $data;
        }
        //Болие сложный  поиск по урлу с статичным или косвенным параметром  /stock/{code} или прямой /stock/usd =>  /{type}/{code}

        $data = $this->getRouteHardUrl($urlReal);
        if (!is_null($data)) {
            $template = new Template(['route_id' => (string)$data['id']],['name_view']);
            $data = array_merge($data,$template->offPagination()->getOne());
            $data['url_system'] = (!empty($data['template_url']))? $data['template_url']:$route_url;
            $data['params_route'] = self::parseUrlTemplateParams($data,$urlReal);
            $this->routes = $data;
            return $data;
        }
        //Поиск по урлам что собранны из контроллеров
        $data = $this->getSystemRoutByUrl($url,$route_url);
        if(!is_null($data)) {
            $data['url'] = $url;
            $data['site_id'] = $this->site_id;
            $data['url_system'] = $route_url;
            $data['template_url'] = $route_url;
            $data['type_page'] = (isset($data['params']['type_page']))? $data['params']['type_page']:null;
            $data['name_title'] = (isset($data['params']['name']))? $data['params']['name']:$url;
            $data['params_route'] = self::parseUrlTemplateParams($data,$urlReal);
            $this->routes = $data;
            return $data;
        }
        return false;
    }
    //Получение системного пути по урлу или все (системные пути это те что создаются на основе контролеров)
    public function getAllSystemUrl($url = null){
        if (is_null($url))
            return ['result'=>$this->systemRouts];
        return (isset($this->systemRouts[$url]))? ['result' => $this->systemRouts[$url]]:null;
    }
    //Если Роут доступен
    public function routExist($url){
        return ($this->systemRouts[$url])? true:false;
    }
    //Выдает список экземпляров по запросу
    public function getList(){
        $this->executeFilter();
        $result = $this->getSandartResultList();
        if(isset($result['error'])){
            dd($result);
        }
        return $result;
    }
    //Выдает только один экземпляр по запросу
    public function getOne(){
        $this->executeFilter();
        return $this->getSandartResultOne();
    }
    //Список для полей для вывода в админ панели
    public static function getColumForAdmin(){
        $columsSort = [];
        $mainTabl = Routs::getTable();
        $colums = [
            ["table" => $mainTabl, "column" => 'id'],
            ["table" => Site::getTable(), "column" => 'domain_name'],
            ["table" => $mainTabl, "column" => 'url'],
            ["table" => $mainTabl, "column" => 'name_title'],
            ["table" => $mainTabl, "column" => 'alias_url'],
            ["table" => $mainTabl, "column" => 'active'],
            ["table" => $mainTabl, "column" => 'template_url'],
            ["table" => $mainTabl, "column" => 'open',
                "db" => DB::raw('IF('.$mainTabl.'.open > 0,"дa","нет") as open')],
            ["table" => $mainTabl, "column" => 'open_',
                "db" => DB::raw($mainTabl.'.open as open_')],
            ["table" => $mainTabl, "column" => 'physically',
                "db" => DB::raw('IF(LENGTH('.$mainTabl.'.physically) > 0,"дa","нет") as physically')],
            ["table" => $mainTabl, "column" => 'physically',
                "db" => 'physically as physically_val'],
            ["table" => $mainTabl, "column" => 'check_module'],
            ["table" => $mainTabl, "column" => 'updated_at'],
            ["table" => $mainTabl, "column" => 'lang_id'],
            ["table" => $mainTabl, "column" => 'site_id'],
        ];
        foreach ($colums as $item)
            $columsSort[] = $item['column'];

        return [ "select" => $colums,
                 "sort_col" => $columsSort,
                 "ignody_col" => [
                    'physically_val', 'site_id', 'open_',
                     'template_url', 'lang_id', 'active'
                 ]
               ];
    }
    //Поля по умолчанию
    protected function defaultSelect(){
        $tab = $this->engine->tableName();
        $this->default = [];
        return $this->default;
    }
    //Фильтры для объекта
    protected function getFilter(){
        $tab = $this->engine->getTable();
        $this->filter = [
            [   "field" => $tab.'.site_id', "params" => 'site',
                "validate" => ["string" => true,"empty" => true],
                "type" => 'string|array', "action" => 'IN', "concat" => 'AND'
            ],
            [   "field" => $tab.'.name_title', "params" => 'byname',
                "validate" => ["string" => true,"empty" => true],
                "type" => 'string|array', "action" => 'IN', "concat" => 'AND'
            ],


            [   "field" => $tab.'.alias_url',"params" => 'url_alias',
                "validate" => ["string" => true,"empty" => true],
                "type" => 'string|array', "action" => 'IN', "concat" => 'OR'
            ],
            [   "field" => $tab.'.url',"params" => 'url_system',
                "validate" => ["string" => true,"empty" => true],
                "type" => 'string|array', "action" => 'IN', "concat" => 'OR'
            ],

            [   "field" => $tab.'.url', "params" => 'url',
                "validate" => ["string" => true,"empty" => true],
                "type" => 'string|array', "action" => '=', "concat" => 'OR'
            ],

            [   "field" => $tab.'.open', "params" => 'open',
                "validate" => ["string" => true,"empty" => true],
                "type" => 'string|array', "action" => '=', "concat" => 'AND'
            ],

            [   "field" => $tab.'.active', "params" => 'active',
                "validate" => ['string' => true,"empty" => true],
                "type" => 'string|array', "action" => '=', "concat" => 'AND'
            ],

            [   "field" => $tab.'.id', "params" => 'id',
                "validate" => ["string" => true,"empty" => true],
                "type" => 'string|array', "action" => '=', "concat" => 'AND'
            ]
        ];
        //Объединяет с класом подителя допустимые параметры для фильтрации
        $this->filter = array_merge($this->filter,parent::getFilter());
        return $this->filter;
    }
    //Подключаемые таблицы по псевдонимама
    protected function compileGroupParams() {
        $this->group_params = [
            "select" => [],
            "by" => [],
            "relatedModel"=>[
                "Site"=>[
                    "entity" => new SystemSite(),
                    "relationship" => ['id', 'site_id'],
                ],
                "Template" => [
                    "entity" => new Template(),
                    "relationship" => ['route_id', 'id'],
                    "field" => ['name_view'],
                ]
            ]
        ];
        return $this->group_params;
    }
}
