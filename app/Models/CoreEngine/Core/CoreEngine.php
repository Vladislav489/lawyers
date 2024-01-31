<?php
namespace App\Models\CoreEngine\Core;

use App\Models\CoreEngine\Core\CoreParam;
use App\Models\CoreEngine\LogicModels\Forecast\InformationalDataLogic;
use App\Models\CoreEngine\Model\ForecastData;
use App\Models\CoreEngine\Model\InformationalData;
use App\Models\CoreEngine\Model\SystemSite;
use App\Models\System\HelperFunction;
use Illuminate\Database\Eloquent\Model;
use App\Models\System\SystemLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use mysql_xdevapi\Exception;

class CoreEngine{
    const INSERT = 1;
    const INSERT_IGNOR = 2;
    const INSERT_MULTI = 3;
    const INSERT_MULTI_IGNORY = 4;
    const DEFAULT_LIMI = 100;

    protected $engine = null;
    protected $select;
    protected $group_by = [];
    protected $join_by = [];
    protected $group_params;
    protected $query;
    protected $default = [];

    protected $lang_id = 1;

    protected $class;
    protected $coreParams;
    protected $filter;
    protected $params;

    protected $pagination = ['totalCount' => 0,'countPage' => 0,'pageSize' => false,'page' => 1];
    protected $paginationOff = false;
    protected $limit = self::DEFAULT_LIMI;
    protected $union = false;

    private   $joinTable = [];
    private   $checkJoin = [];
    private   $debug_sql = false;
    private   $query_debug_sql = true;
    protected $error = [];

    public function __destruct() {
        if(count($this->error) > 1 && !empty($this->error))
                SystemLog::query()->insert($this->error);
    }

    public function __construct($params,$select = [],$callback = null ){
        $this->setSelect((is_array($select) && count($select) > 0)?$select:$this->defaultSelect());
        if (Auth::id() == 1) {
            unset($params['site']);
            unset($params['site_id']);
        }
        $this->union = (key_exists('union',$params))? true:false;
        if (!isset($params['is_delete']))
            $params['is_delete'] = 0;
        $this->class = get_class($this);
        $this->setCoreParams($params,$callback);
    }

    public function setCoreParams($params = [] , $callback = null ){
        $this->params = $params;
        $this->coreParams = new CoreParam();
        $this->coreParams->setParams($this->params);
        if(!is_null($callback)) $this->coreParams->specialValueCallBack($callback);
        $this->coreParams->setRules($this->filter);
        return $this;
    }

    protected function LogError( \Throwable|null $e = null,$dataFrom = [],$title = null) {
        try {
            if (!is_null($e)) {
                $dataMessage = [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'code' => $e->getCode(),
                    'file' => $e->getFile() . " line:" . $e->getLine(),
                    'revious' => $e->getPrevious()
                ];
                $dataMessage = array_merge($dataMessage, $dataFrom);
            } else {
                $dataMessage = $dataFrom;
            }
            $title_ = get_class($this) . " => " . debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]['function'];
            $title = (is_null($title)) ? "Core engin " . $title_ : "Core engin " . $title;
            $this->error[] = ['title' => $title,
                    'log' => json_encode($dataMessage),
                    'short_text' => " Ошибка запроса ",
                    'code' => SystemLog::CODE_ERROR
                   ];
        } catch (\Throwable $e) {
           dd($e->getMessage());
        }
    }

    public function getRealExistFilter() {
        $return = [];
        foreach ($this->filter as $item)
            if (isset($this->params[$item['params']]))
                $return[$item['params']] = $this->params[$item['params']];

        return $return;
    }
    public function getParamsFilterAll() {
        if (!is_array($this->filter) && count($this->filter) == 0 )
            return [];
        $return = [];
        foreach ($this->filter as $item)
                $return[] = $item['params'];
        return $return;
    }
    //ДЛЯ ДЕБАГА
    public function OffQuery() {
        $this->query_debug_sql = false;
        return $this;
    }
    //ВКЛЮЧАЕТ ВЫВОД ДЕБАНА ТЕКСТ ЗАПРОСА И РЕЗУЛЬТАТ
    public function OnDebug() {
        $this->debug_sql = true;
        return $this;
    }
    public function offPagination() {
        $this->paginationOff = true;
        return $this;
    }
    public function onPagination() {
        $this->paginationOff = false;
        return $this;
    }
    //Управление данными
    public function save($data){
        try {
            $id = false;
            if(isset($data['id']) && !empty($data['id'])) {
                $id = $data['id'];
                unset($data['id']);
            }
            return ($id)?  $this->update($data, $id):$this->insert($data);
        } catch (\Throwable $e) {
            $this->LogError($e,['data'=>$data]);
            return false;
        }
    }
    //ПОЛУЧЕНИЕ ВНУТРЕННИХ ОБЪЕКТОВ
    public function getLable($cols,$lang,$ignory = []){
        try {
            $cols = array_combine($cols, $cols);
            $lable = [$this->engine->getLable($lang)];
            foreach ($this->joinTable as $item)
                $lable[] = $this->group_params['relatedModel'][$item]['entity']->getLable($lang);

            foreach ($lable as $key => $itemsLabel)
                $lable[$key] = (!is_null($itemsLabel)) ? HelperFunction::getLableForQuery($cols, $itemsLabel, $ignory) : $cols;

            $column = HelperFunction::joinColumLabel($lable);
            $column = HelperFunction::missingColumnLabel($column, $cols);
            $column = HelperFunction::ignoryColumnLable($column, $ignory);
            $column = HelperFunction::SortLableByArray($column, $cols);
            return $column;
        } catch (\Throwable $e) {
                $this->LogError($e,['cols' => $cols,'lang' => $lang,'ignory' => $ignory]);
                return false;
        }
    }

    public function insert($data, $type = self::INSERT) {
        try {
            if (empty($data)) return false;
            if (!$this->checkInsertColumn($data, $type)) {
                $this->LogError(null,['data' => $data,'type' => $type]," Поля не соответствуют");
                return false;
            }
            switch ($type) {
                case self::INSERT:
                    return (!isset($data[0]) && $id = $this->query->insertGetId($data)) ? $id : false;
                    break;
                case self::INSERT_IGNOR:
                    return (!isset($data[0]) && $this->query->insertOrIgnore($data));
                    break;
                case self::INSERT_MULTI:
                    return (is_array($data[0]) && $this->query->insert($data));
                    break;
                case self::INSERT_MULTI_IGNORY:
                    return (is_array($data[0]) && $this->query->insertOrIgnore($data));
                    break;
                default:
                    break;
            }
        }catch (\Throwable $e ){
            $this->LogError($e,['data' => $data , 'flag' => $type]);
            return false;
        }
    }

    public function delete($id, $key = NULL){
        try {
            if (empty($id)) return false;
            $id = (is_array($id)) ? $id:[$id];
            if (is_null($key))
                return $this->query->newQuery()->whereIn('id',$id)->update(['is_delete' => 1]);
            else
                return (in_array($key, $this->engine->getFillable()))? $this->query->newQuery()->whereIn($key, $id)->update(['is_delete' => 1]):false;
        } catch (\Throwable $e) {
            $this->LogError($e,['data' => $id]);
        }
    }

    public function deleteForeva($id, $key = NULL) {
        try {
            if (empty($id)) return false;
            $id = (!is_array($id)) ? [$id] : $id;
            if (is_null($key))
                return $this->query->newQuery()->whereIn('id', $id)->delete();
            else
                return (in_array($key, $this->engine->getFillable()))? $this->query->newQuery()->whereIn($key, $id)->delete():false;
        } catch (\Throwable $e) {
                $this->LogError($e,['data' => $id]);
                return false;
        }
    }

    public function update($data, $id, $key = null) {
        try {

            if (empty($id)) return false;
            if (!$this->checkInsertColumn($data)) {
                $this->LogError(null, ['data' => $data, 'id' => $id, 'key' => $key]);
                return false;
            }
            if (is_null($key)) {
                 $ids = ((is_array($id)) ? $id : [$id]);
                (DB::table($this->engine->getTable())->whereIn('id', $ids)->update($data)) ? $id : false;

               return $id;
            } else {
                if (in_array($key, $this->engine->getFillable())) {
                    $ids = ((is_array($id)) ? $id : [$id]);
                    return (DB::table($this->engine->getTable())->whereIn($key, $ids)->update($data)) ? $id : false;
                } else {
                    return false;
                }
            }
            return false;
        } catch (\Throwable $e) {
            $this->LogError($e,['data' => $id]);
            return false;
        }
    }

    public function updateByFilter($data){
        try {
            if (empty($data)) return false;
            if (!$this->checkInsertColumn($data)) {
                $this->LogError(null,['data' => $data]," Поля не соответствуют");
                return false;
            }
            $this->executeFilter();
            return $this->query->update($data);
        } catch (\Throwable $e){
            $this->LogError($e,['data' => $data]);
            return false;
        }
    }
    ///////////////////////////////////////
    public function setGroupBy($group_by) {
        $this->group_by = $this->prepareRequest($group_by);
        return $this;
    }


    public function get_first_common_parent($objects) {
        $common_ancestors = null;
        foreach($objects as $object) {
            $class_name = (is_object($object))?get_class($object):$object;
            $parent_class_names = array();
            $parent_class_name = $class_name;
            do {
                $parent_class_names[] = $parent_class_name;
            } while ($parent_class_name = get_parent_class($parent_class_name));
            $common_ancestors = ($common_ancestors === null)?$parent_class_names:array_intersect($common_ancestors, $parent_class_names);
        }
        return reset($common_ancestors);
    }

    public static function createTempLogic($model, $params = [], $select = []) {
        if(!array_search(Model::class,class_parents($model))) {
            (new self())->LogError(null,['model'=>get_class($model),'params' => $params,'select' => $select]," Это не Модель ");
            throw new \Exception("Это не Модель");
        }
        $ruls = (new self([]))->setModel($model)->getRuls();
        return (new CoreEngine($params,$select))->setCoreParamsRule($ruls)->setModel($model);
    }


    public function setModel($model) {
        if(!array_search(Model::class,class_parents($model))) {
            $this->LogError(null,['model'=>get_class($model)]," Это не Модель ");
            throw new \Exception("Это не Модель");
        }
        $this->engine = $model;
        $this->query = $this->engine->newQuery();
        return $this;
    }
    public function setQuery($query) {
        $this->query = $query;
        return $this;
    }
    //Входны данные для построения WHERE
    protected function setCoreParamsRule(array $filte) {
        $this->coreParams->setRules($filte);
        return $this;
    }
    public function setRequired($name) {
        $this->filter[$this->getFilterByName($name)['key']]['validate']['required'] = true;
        return $this;
    }
    public function setParams($params) {
        $this->coreParams = new CoreParam();
        $this->params = $this->prepareRequest($params);
        $this->coreParams->setParams($this->params)->setRules($this->filter);
        if($this->debug_sql) {
            var_dump("params",$this->params);
            echo "<br>";
        }
        return $this;
    }
    public function setLimit($limit) {
        $this->limit = $limit;
        return $this;
    }
    public function setSelect($select) {
        $this->select = $this->prepareRequest($select);
        return $this;
    }
    //ПРИНУДИТЕЛЬНО ПОДКЛЮЧЕНИЕ ПОДКЛЮЧАЕМЫХ ТАБЛИЦ.
    public function setJoin($join) {
        $join = (is_string($join))? [$join]:$join;
        $this->joinTable = $join;
        foreach ($join as $item)
            if (key_exists($item,$this->group_params['relatedModel']))
                if ($item && !in_array($item,$this->group_by))
                    $this->join_by[$item] = $item;
        return $this;
    }
    public function getcoreParamsClass() {return $this->coreParams;}
    public function getSchema() {
      if (is_object($this->engine))
          return get_class($this->engine)::getTableSchema();
      return [];
    }
    public function getRuls() {return  $this->getFilter();}
    public function getEngine() {return $this->engine;}
    public function getQuery() {return $this->query;}
    public function &getQueryLink() {return $this->query;}
    public function getParams() {return  $this->params;}
    public function getGroupBy() {return $this->group_by;}
    //ПОЛУЧЕНИЕ СВОЙСТВ ПО ИМЕНИ ПАРАМЕТРА
    public function getPropertyFilterByParam($name) {
        return ($res = $this->getFilterByName($name))? $res['value']:$res;
    }
    //УДАЛИТЬ ИЗ ФИЛЬТРОВ ОПРЕДЕЛЕННЫЙ ФИЛЬТ ПО ПАРАМЕТРУ
    public function removeFilterByParam($name) {
        unset($this->filter[$this->getFilterByName($name)['key']]);
    }
    //ПРИМИТИВНАЯ ВЫБОРКА БЕЗ УЧЕТА ФИЛЬРА
    public function getSandartResultOne() {
        try {
            $result = [];
            $result['pagination'] = $result['result'] = [];
            if (!$this->coreParams->getStatusValidate()) {
                $error = $this->coreParams->getErrorValidate();
                $this->LogError(null, ['error' => $error, 'params' => $this->params], 'Ошибка в параметрах');
                return $error;
            }
            if ($this->debug_sql) $this->debugQuery();
            if ($this->query_debug_sql) $result = $this->query->get()->toArray();
            return (count($result) > 0) ? $result[0] : [];
        } catch (\Throwable $e){
            $this->LogError($e,['params' => $this->params,'query' => $this->getSqlToStrFromQuery()],'Ошибка в Запросе');
            return  [];
        }
    }
    public function removeParams($nameParam) {
        unset($this->params[$nameParam]);
        $return = $this->coreParams->getParamsFilter($nameParam);
        $this->coreParams->removeParamertFiltrer($nameParam);
        return $return;
    }
    //ДЛЯ ГРУПОВОГО ПЕЗУЛЬТАТА
    public function getSandartResultGroup() {
        try {
            $result = [];
            $result['result'] = $result['pagination'] = [];
            if (!$this->coreParams->getStatusValidate()){
                $error = $this->coreParams->getErrorValidate();
                $this->LogError(null, ['error' => $error, 'params' => $this->params], 'Ошибка в параметрах');
                return $error;
            }
            if ($this->debug_sql) $this->debugQuery();
            if ($this->query_debug_sql) $result['result'] = $this->query->get()->toArray();
            $result['pagination'] = $this->pagination;
            return $result;
        } catch (\Throwable $e) {
            $this->LogError($e,['params' => $this->params,'query' => $this->getSqlToStrFromQuery()],'Ошибка в Запросе');
            return [];
        }
    }
    public function getSandartResultList() {
        try {
            $result = [];
            $result['result'] = $result['pagination'] = [];
            if (!$this->coreParams->getStatusValidate()) {
                $error = $this->coreParams->getErrorValidate();
                $this->LogError(null, ['error' => $error, 'params' => $this->params], 'Ошибка в параметрах');
                return $error;
            }
            if ($this->debug_sql) $this->debugQuery();
            $result['pagination'] = $this->pagination;
            $result['totalCount'] = ($this->pagination['totalCount'] != 0) ? (int)$this->pagination['totalCount'] : 0;
            if ($this->query_debug_sql)
                if ($this->union) {
                    if (!isset($this->params['union_type'])) $this->params['union_type'] = "UNION";
                    if (!isset($this->params['union_group'])) $this->params['union_group'] = null;
                    $result['result'] = $this->unionQueryOneObjectCore($this->params['union'], $this->params['union_group'], $this->params['union_type']);
                } else {
                    $result['result'] = $this->query->get()->toArray();
                }
            return $result;
        } catch (\Throwable $e) {
            $this->LogError($e,['params' => $this->params,'query' => $this->getSqlToStrFromQuery()],'Ошибка в Запросе');
            return ['result'=>['error'=>$e->getMessage()],'pagination' => $this->pagination];
        }
    }

    public function getFullQuery(){
        return (count($this->group_by))? $this->executeGroup():$this->executeFilter();
    }

    public function getSqlToStrFromQuery() {
        $data = $this->query->getBindings();
        foreach ($data as $key => $item) $data[$key] = "'" . $item . "'";
        return Str::replaceArray("?",$data,$this->query->toSql());
    }

    public function getSqlToStr(){
        (count($this->group_by))? $this->executeGroup():$this->executeFilter();
        $data = $this->query->getBindings();
        foreach ($data as $key => $item) $data[$key] = "'" . $item . "'";
        return Str::replaceArray("?",$data,$this->query->toSql());
    }

    //СТАНДАРТНЫЙ ВЫВОД ДАНЫХ
    public function getTotal() { return $this->executeFilter()->count();}
    public function getGroup() {
        $this->executeGroup();
        return $this->getSandartResultGroup();
    }
    public function getList() {
        $this->executeFilter();
        return $this->getSandartResultList();
    }
    public function getOne() {
        $this->executeFilter();
        return $this->getSandartResultOne();
    }
    public function Exist() {
        try {
            if (!$this->coreParams->getStatusValidate()) {
                $error = $this->coreParams->getErrorValidate();
                $this->LogError(null, ['error' => $error, 'params' => $this->params], 'Ошибка в параметрах');
                return $error;
            }
            $this->offPagination()->executeFilter();
            if ($this->debug_sql) $this->debugQuery();
            return $this->query->exists();
        } catch (\Throwable $e){
            $this->LogError($e,['params' => $this->params,'query' => $this->getSqlToStrFromQuery()],'Ошибка в Запросе');
            return false;
        }
    }
    // ОСНОВНЫЕ ФУНКЦИИ ДЛЯ  ВЫПОЛНЕНИЯ ЗАПРОСОВ
    public function executeFilter() {
        try {
            $this->selectQuery()->connectEntityJoin()->whereQuery($this->coreParams->getWhereSql());
            $this->pagination();
            $this->order();
            if ($this->debug_sql) $this->debugQuery();
            return $this->query;
        } catch (\Throwable $e) {
            $this->LogError($e,['params' => $this->params,'query' => $this->getSqlToStrFromQuery()],'Ошибка в Запросе');
            return $this->query;
        }
    }
    public function executeGroup() {
        try {
            $this->selectQueryGroup()->connectEntityJoin()->whereQuery($this->coreParams->getWhereSql());
            $this->pagination();
            $this->groupQuery()->order();
            if ($this->debug_sql) $this->debugQuery();
            return $this->query;
        } catch (\Throwable $e) {
            $this->LogError($e,['params' => $this->params,'query' => $this->getSqlToStrFromQuery()],'Ошибка в Запросе');
            return $this->query;
        }
    }
    // ПАГИНАЦИЯ ВКЛЮЧАЕТСЯ ТОЛЬКО ЕСЛИ ЕСТЬ PAGESIZE ИЛИ LIMIT
    public function pagination($page = null,$pageSize = null) {
        $query = clone $this->query;
        $totalCount = (!$this->paginationOff)?$query->count():1;
        if(is_null($page) && is_null($pageSize)){
            $pageSize = $this->coreParams->getParamsFilter('pageSize');
            $page = $this->coreParams->getParamsFilter('page');
            if (!$pageSize) $pageSize = (empty($this->limit))? false:$this->limit;
            if ($pageSize == -1) $pageSize = false;
            $page = ($page > 0 && !is_null($page))?($page-1):0;
        }
        if($pageSize) {
            $countPage = (int)($totalCount / $pageSize);
            $countPage += (($totalCount % $pageSize) == 0)?0:1;
            $this->pagination = ['totalCount' => $totalCount,'countPage' => $countPage,'pageSize' => $pageSize,'page' => $page+1];
            $this->limit = $pageSize;
            $this->query->offset($page * $this->limit)->limit($this->limit);
        } else {
            $this->pagination = ['totalCount' => $totalCount,'countPage' => 0,'pageSize' => 0,'page' => 1];
        }
        return $this->pagination;
    }
    //СОРТИРОВКА
    public function order($sort_dir = null, $sort_by = null) {
        if(is_null($sort_dir) && is_null($sort_by)) {
            $sort_dir = $this->coreParams->getParamsFilter('sort_dir') ?: 'asc';
            $sort_by = $this->coreParams->getParamsFilter('sort_by') ?: false;
        }
        if($sort_by) {
            $sort_by = (is_array($sort_by))? $sort_by:[$sort_by];
                foreach ($sort_by as $item_sort)
                    $this->query->orderBy($item_sort, $sort_dir);
        }
        return $this;
    }
    public function unionQueryOneObjectCore($queryParasm, $group = null, $typeUnion = "UNION") {
        $query = $listOject = [];
        $add_to_all_select = null;
        $class = get_class($this);

        $field = $this->getFilterByName($group);
        if($field && key_exists('field',$field['value']))
            $field = $field['value']['field'];
        else
            $field = ($field)?$group:$field;
        if (preg_match("/.*\..*/",$field)) {
            $group = explode(".", $field)[1];
            if (in_array($group, $this->engine->getFillable()))
                $this->select[] = $group;
            else
                $add_to_all_select = $group;
        } else {
            $add_to_all_select = $group;
        }

        foreach ($queryParasm as $key => $paramsOneWhere) {
            $selectUnion = $this->select;
            if (!empty($this->params))
                $paramsOneWhere =  array_merge($paramsOneWhere,$this->params);
            if (!is_null($add_to_all_select))
                $selectUnion[] = DB::raw("'" . $paramsOneWhere[$add_to_all_select] . "' as `" . $add_to_all_select . "`");

            $listOject[$key] = new $class($paramsOneWhere,$selectUnion);

            if (!empty($this->joinTable)) $listOject[$key]->setJoin($this->joinTable);

            (isset($paramsOneWhere['group']) && $paramsOneWhere['group'] == true) ? $listOject[$key]->executeGroup() : $listOject[$key]->executeFilter();
            $query[] = "(".$listOject[$key]->queryText().")";
        }
        $res = json_decode(json_encode(DB::select(implode("\n".$typeUnion."\n",$query))),true);

        if (!is_null($group)) {
            $group_list = [];
            foreach ($res as $item) $group_list[$item[$group]][] = $item;

            $res = $group_list;
            unset($group_list);
        }
        return $res;
    }
    public function queryText() {
        $dataWhere = $this->query->getBindings();
        foreach ($dataWhere as &$item)  $item = "'".str_replace(['"',"'"],['\"',"\'"],$item)."'";
        return Str::replaceArray("?", $dataWhere,$this->query->toSql());
    }
    // в перспективе
    public function unionQueryBuild($listObjct, $group = null, $groupQuery = false) {
        $select = [];
        foreach ($listObjct as $class){
            if(strpos(get_parent_class($class),"CoreEngine")  !== false ){
                ($groupQuery) ? $class->executeGroup() : $class->executeFilter();
                $select[] = $class->engine->getFillableTable();
            }
        }
    }
    //ДЛЯ ГРУПИРОВКИ ПОДКЛЮЧЕНИЕ ПОЛЕЙ ГЛАВНОЙ ТАБЛИЦЫ БЕЗ УЧЕВТА ГРУППИРОВКИ
    private function selectQueryGroup() {
        $this->select = $this->prepareRequest($this->select);
        foreach ($this->select as $value) {
            if (count($this->group_params['select']) > 0) {
                if (isset($this->group_params['select'][$value]))
                    $this->query->addSelect($this->group_params['select'][$value]);
            }else{
                if(!is_null($value))
                    $this->query->addSelect($value);
            }
        }
        return $this;
    }
    private function selectQuery() {
        if ($select = $this->coreParams->getParamsFilter('select')) {
            $this->select = $this->prepareRequest($select);
            $flagMainColum = true;
            foreach ($this->select as  $key => $value) {
                if (is_numeric($key)) {
                    $flagMainColum  = false;
                    $this->query->addSelect($this->engine->getTable() . "." . $value);
                } else {
                    if (is_array($this->group_params['relatedModel'][$key]['entity'])) {
                        $join = each($this->group_params['relatedModel'][$key]['entity']);
                        $tab = $join['key'];
                    } else if (is_string($this->group_params['relatedModel'][$key]['entity'])) {
                        $tab = $this->group_params['relatedModel'][$key]['entity'];
                    } else {
                        $tab = $this->group_params['relatedModel'][$key]['entity']::getTableName();
                    }
                    $this->setJoin([$key]);
                    if (is_array($value)) {
                        foreach ($value as $item) $this->query->addSelect($tab . "." . $item);
                    } else {
                        $this->query->addSelect($tab . "." . $value);
                    }
                }
            }
            if($flagMainColum)
                foreach ($this->defaultSelect() as $value) $this->query->addSelect($value);
        } else {
            if(!is_null($this->select)) {
                foreach ($this->select as $value)
                    ($value instanceof \Illuminate\Database\Query\Expression) ?
                        $this->query->addSelect($value) : $this->query->addSelect($this->engine->getTable() . "." . $value);
            }
        }
        return $this;
    }
    //ДЛЯ ГРУППИРОВКИ ПОДКЛЮЧЕНИЕ ДРУГИХ ТАБЛИ И ДОПОЛНИТЕЛЬНЫХ ПОЛЕЙ (ИСПОЛЬЗУЕТСЯ ТОЛЬКО ДЛЯ ГРУППИРОВКИ)
    private function groupQuery() {
        $this->group_by = $this->prepareRequest($this->group_by);
        foreach ($this->group_by as $key => $value) {
            if(isset($this->group_params['relatedModel'][$key])){
                if(!in_array($key,$this->checkJoin)) {
                    $this->checkJoin[] = $key;
                    $this->relatedData($this->group_params['relatedModel'][$key],$key);
                }
            } else if (isset($this->group_params['custom_select'][$value])){
                $this->customSelect($this->group_params['by'][$value], $this->group_params['custom_select'][$value]);
            } else if (isset($this->group_params['by'][$value])) {
                $this->standartField($this->group_params['by'][$value]);
            }
        }
        return $this;
    }
    // ДЛЯ РАБОТЫ С ФИЛЬТРАМИ ПОИСК ПО НАЗВАНИЮ ПАРАМЕТРА ФИЛЬТРА
    private function getFilterByName($name) {
        foreach ($this->filter as $key => $value)
            if($value['params'] == $name)
                return ["key" => $key,"value" => $value];
        return false;
    }
    //УСТАНОВКА ФИЛЬТРОВ WHERE
    private function whereQuery($where) {
        if($this->debug_sql) var_dump($where);
    //0 => полк 1 => действие 2 => значае 3=> AND OR
        foreach ($where as $item){
           switch ($item[1]){
               case 'IN':
                   $this->query->whereIn(DB::raw($item[0]),is_array($item[2])?$item[2]:[$item[2]]);
                   break;
               case "NOT IN":
                   $this->query->whereNotIn(DB::raw($item[0]),is_array($item[2])?$item[2]:[$item[2]]);
                   break;

               case "RAW":
                    if(preg_match("/~\?\d+~/",$item[0])){
                        $ar_tes = [];
                        preg_match_all("/~\?\d+~/",$item[0],$ar_tes);
                        if(is_array($item[2])){
                            if(count($ar_tes[0]) > 0){
                                $temp = [];
                                foreach ($ar_tes[0] as $item__){
                                    $data_ = str_replace(["?", '~'], '', $item__);
                                    $temp[$item__] = "'".$item[2][$data_ - 1]."'";
                                }
                                $item[0] = str_replace(array_keys($temp), $temp, $item[0]);
                                $this->query->whereRaw($item[0], null, $item[3]);
                            }else {
                                $data_ = str_replace(["?", '~'], '', $ar_tes[0][0]);
                                $item[0] = str_replace($ar_tes[0][0], '?', $item[0]);
                                $this->query->whereRaw($item[0], $item[2][$data_ - 1], $item[3]);
                            }
                        }else{
                            $item[0] = str_replace($ar_tes[0][0],'?',$item[0]);
                            $this->query->whereRaw($item[0], $item[2], $item[3]);
                        }
                    }else{

                        $this->query->whereRaw($item[0], $item[2], $item[3]);
                    }
                   break;
               case "CHECK_NULL":
                   if((int)$item[2] == 0)
                        $this->query->whereNull(DB::raw($item[0]));
                   else
                       $this->query->whereNotNull(DB::raw($item[0]));
                   break;
               default:
                   $this->query->where(DB::raw($item[0]),$item[1],$item[2],$item[3]);
                   break;
           }
       }
       return $this;
    }
    //ДЛЯ СПИСКА ПОДКЛЮЧЕНИЕ ДРУГИХ ТАБЛИ
    private function filterJionQuery(){
        foreach ($this->join_by as $key => $value) {
            if (isset($this->group_params['relatedModel'][$key])) {
                if (!in_array($key,$this->checkJoin)) {
                    $this->checkJoin[] = $key;
                    $this->relatedData($this->group_params['relatedModel'][$key],$key);
                }
            }
        }
        return $this;
    }
    //ЕСЛИ ПРИШЛО ЗНАЧЕНИЕ НЕ МАСИВ ПЕРЕВОДИМ В МАССИВ
    private function prepareRequest($data) {
        $data = ($data) ? $data:[];
        return (is_array($data)) ? $data:[$data];
    }
    // ВСПОМАГАТЕЛЬНЫ ФУНКЦИИ ДЛЯ ЗАПРОСОВ ОБЩИЕ ДЛЯ ГРУПП И СПИСКОВ
    private function customSelect($code, $value) {
        if(!empty($value)) $this->query->addSelect($value);
        $this->query->groupBy($code);
        return $this;
    }
    //ДЛЯ ГРУППИРОВКИ ПОЛЕЙ  (ВЫБОРКА И ГРУППИРОВКА ПО ПОЛЮ)

    private function  getTableSchema() {
        $list = DB::select("DESCRIBE ".$this->engine->getTable());
        $newList = [];
        foreach ($list as $item) $newList[$item->Field] = $item;
        return $newList;
    }

    private function standartField($field) {
        $colums = $this->getTableSchema();
        $keys = array_keys($colums);
        if(!is_array($field)) {
            if (in_array($field, $keys)) {
                if (in_array($colums[$field]->Type, array("datetime", "date", 'timestamp'))) {
                    $this->query->groupBy(DB::raw("DATE(" . $this->engine->getTable() . '.' . $field . ")"));
                    $this->query->addSelect(DB::raw("DATE(" . $this->engine->getTable() . '.' . $field . ")"));
                } else {
                    if (is_object($field) && $field::class == 'Illuminate\Database\Query\Expression') {
                        $this->query->groupBy($field);
                        if (!empty($field))
                            $this->query->addSelect(DB::raw($field));
                    } else {
                        $this->query->groupBy($this->engine->getTable() . '.' . $field);
                        if (!empty($field))
                            $this->query->addSelect($this->engine->getTable() . '.' . $field);
                    }
                }
            } else {
                $this->query->groupBy(DB::raw($field));
                if (!empty($field))
                    $this->query->addSelect(DB::raw($field));
            }
        } else {
            if (is_object($field['group']) && $field['group']::class == 'Illuminate\Database\Query\Expression') {
                $this->query->groupBy($field['group']);
                if (!empty($field['field']))
                    $this->query->addSelect(DB::raw($field['field']));
            } else {
                $this->query->groupBy($this->engine->getTable() . '.' . $field['group']);
                if (!empty($field['field']))
                    $this->query->addSelect($this->engine->getTable() . '.' . $field['field']);
            }
        }
        return $this;
    }
    // ДЕЛАЙЕТ ДЖОЙН ТАБЛИЦ ДЛЯ ПОДКЛЮЧЕНИЯ   ЕСЛИ НЕ ПОЛЯ ТО ГРУПИРОВКА И ЫЕЛЕК ПО ПОЛЮ НЕ ДЕЛАЕТСЯКАК
    private function relatedData($related,$nameJoin = ""){
        $table = "";
        $nameAs = (!empty($nameJoin))?" AS ".$nameJoin:"";
        if (is_string($related['entity'])) {
            $table = $related['entity'];
            $joinQuery = DB::raw( $table." $nameAs ON ".$this->relatedConfig($related,$nameJoin));
        } else if(is_array($related['entity'])) {
            $join = each($related['entity']);
            $table = $join['key'];
            $joinQuery = DB::raw( $table." $nameAs ON ".$this->relatedConfig($related,$nameJoin));
        } else if($related['entity']::class == 'Illuminate\Database\Query\Expression') {
            $joinQuery = $related['entity'];
        } else {
            $table = $related['entity']->getTable();
            $joinQuery = DB::raw( $table." $nameAs ON ".$this->relatedConfig($related,$nameJoin));
        }

       if (isset($related['type']) && $related['type'] === "right")
            $this->query->rightJoin( $joinQuery,function(){});
       else if (isset($related['type']) && $related['type'] === "inner")
            $this->query->join( $joinQuery,function(){});
       else if(isset($related['type']) && $related['type'] === "left")
            $this->query->leftJoin( $joinQuery,function(){});
       else
            $this->query->leftJoin( $joinQuery,function(){});


        if (isset($related['field']))
            foreach ($related['field'] as $item ) {
                if ($table != "") {
                    if (!empty($nameJoin)) {
                        $this->query->addSelect(DB::raw($nameJoin . "." .$item));
                    } else {
                        $this->query->addSelect(DB::raw($table . "." . $item));
                    }
                } else if (!empty($nameJoin)) {
                    $this->query->addSelect(DB::raw($nameJoin . "." . $item));
                } else {
                    $this->query->addSelect(DB::raw($item));
                }
            }
        return $this;
    }
    //КОНФИГУРАЦИИ СВЯЗ ТАБЛИЦ
    private function relatedConfig($config,$nameJoin = "") {
        $joinOn  = "";
        if(is_string($config['entity'])) {
            if (stripos($config['entity']," as ") != false) {
                $joinOn = sprintf("%s.%s",substr($config['entity'],stripos($config['entity']," as ")+strlen(" as ")),$config['relationship'][0]);
            } else {
                $joinOn = sprintf("%s.%s",$config['entity'],$config['relationship'][0]);
            }
        } else if (is_array($config['entity'])) {
            $join =  each($config['entity']);
            $joinOn = (empty($join['value']))?$config['relationship'][0]:sprintf("%s.%s",$join['value'] , $config['relationship'][0]);
        } else {
            if (!empty($nameJoin )) {
                $joinOn = sprintf("%s.%s", $nameJoin, $config['relationship'][0]);
            } else {
                $joinOn = sprintf("%s.%s", $config['entity']->getTable(), $config['relationship'][0]);
            }
        }
        $joinOnMore = "";
        if (isset($config['relationship_more'])) {
            if(is_array($config['relationship_more'])) {
                foreach ($config['relationship_more'] as $field => $value)
                    $joinOnMore .= (!is_array($value)) ?
                        sprintf(" AND %s = %s", $field, $value):sprintf(" AND %s %s %s", $value[0], $value[2], $value[1]);

            } else if(is_string($config['relationship_more'])) {
                $joinOnMore .= "AND ". $config['relationship_more'];
            }
        }
        //собирает условие в одну кучу джойна
        if (strpos($config['relationship'][1],'.') === false) {
            $return = sprintf("%s.%s  =  %s %s", $this->engine->getTable(), $config['relationship'][1], $joinOn, $joinOnMore);
        } else {
            $return = sprintf("%s  =  %s %s", $config['relationship'][1], $joinOn, $joinOnMore);
        }
        return  (isset($config['pаrams_filter']))?$this->replaceParamsFlag($return,$config['pаrams_filter']):$return;
    }
    private function replaceParamsFlag($strSql,$params) {
        foreach ($params as $key=>$value)
            $strSql = (is_callable($value))? str_replace("%f_".$key,$value($this),$strSql):str_replace("%f_".$key,$value,$strSql);
        return $strSql;
    }
    //ДЛЯ СПИСКА ПОДКЛЮЧЕНИЕ ДРУГИХ ТАБЛИЦ ПО ПАРАМЕТРАМ ФИЛЬТРОВ
    private function connectEntityJoin() {
        $existFilte = $this->coreParams->getAllParams();
        foreach ($existFilte as $key => $valFilter) {
            $optionFiltret = $this->getPropertyFilterByParam($key);
            if($optionFiltret) {
                // если массив запиывается нсколько
                $join = (key_exists('relatedModel', $optionFiltret)) ? $optionFiltret['relatedModel'] : false;
                if (is_array($join)) {
                    foreach ($join as $item)
                        if ($item && !in_array($item, $this->join_by))
                            $this->join_by[$item] = $item;
                } else {
                    if ($join && !in_array($join, $this->join_by))
                        $this->join_by[$join] = $join;
                }
            }
        }
        $this->filterJionQuery();
        return $this;
    }
    //СЕЛЕКТ ПО УМОЛЧАНИЮ
    protected function defaultSelect(){
        return $this->default;
    }
    //ПАРАМЕТРЫ ФИЛЬТРАЦИИ
    protected function getFilter(){
        $this->filter =[
            [   'field' => $this->engine->getTable().'.is_deleted','params' => 'is_deleted',
                'type' => 'int','action' => "=",'concat' => "AND",'validate' => ['string' => true,'empty' => true]],
            [   'field' => $this->engine->getTable().'.site_id','params' => 'site_id',
                'validate' => ['string' => true,"empty" => true], 'type' => 'string|array',
                "action" => '=', 'concat' => 'AND',
            ],
            [ 'field' => $this->engine->getTable().'.id', 'params' => 'id',
              'type' => 'string','action' => "=",'concat' => "AND",'validate' => ['number' => true,'empty' => true]
            ],
            [ 'field' => $this->engine->getTable().'.id', 'params' => 'ids',
              'type' => 'string|array','action' => "IN",'concat' => "AND",'validate' => ['number' => true,'empty' => true]
            ],
            [   'params' => 'page','type' => 'string','validate' => ['number' => true,'empty' => true]],
            [   'params' => 'pageSize','type' => 'string','validate' => ['string' => true,'empty' => true]],
            [   'params' => 'sort_by', 'type' => 'string|array','validate' => ["template" => "/.*/",'empty' => true]],
            [   'params' => 'sort_dir','type' => 'string','validate' =>['list' => ['asc','desc'],'empty' => true]],
            [   'params' => 'select',  'type' => 'array','validate' => ["array" => true,'empty' => true]],
            [   'params' => 'join',    'type' => 'array','validate' => ["array" => true,'empty' => true]],
        ];
        return  $this->filter;
    }




    public function getInfoAPI() {
       $class = $class_name =  get_called_class();
       $class = new $class();
       $property = $mathod = [];
       $property = get_object_vars(new $class());
       foreach (get_class_methods(new $class()) as $key => $item){
           $mathod[$item] = [
               'function_name' => $item,
               'description' =>'',
               'params' => (new \ReflectionMethod($class_name,$item))->getParameters(),
               'return' => '',
               'description_return' => ''
           ];
       }

       return  [
           'title'=>"",
           'description' => "",
           'filter' => $this->getFilter(),
           'field' => $this->engine->getFillable(),
           'join' => $this->group_params,
           'name_logic' => $class_name,
           'property' => $property,
           'mathod_logic' => $mathod
       ];
    }

    private function checkInsertColumn($data,$type = self::INSERT) {
          $column = $this->engine->getFillable();
          $column1 = array_keys($data);
          if($type == self::INSERT || $type == self::INSERT_IGNOR) {
              return (count(array_diff($column1,$column)) == 0)? true:false;
          } else {
              return (count(array_diff($column1[0],$column)) == 0)? true:false;
          }
    }

    public function getLangFromData($data) {
        return (isset($data['lang_id']) && !empty($data['lang_id']))? $data['lang_id']:$this->lang_id;
    }
    protected function claerQuery() {
        $this->coreParams = new CoreParam();
        $this->coreParams->setParams($this->params)->setRules($this->filter);
        $this->checkJoin = [];
        $this->query = $this->engine->newQuery();
        return $this;
    }

    public function debug() { $this->debugQuery(); }
    private function debugQuery() {
        var_dump($this->queryText());
        echo "<br>";
    }

    public static function getTable() {return (new static())->engine->getTable();}

    public function storeEntity(array $data): array|bool {
        try {
            $entity = array_intersect_key(
                $data,
                array_flip($this->engine->getFillable())
            );
            if ($data['id'] = $this->save($entity)) {
                return $data;
            }

        } catch (\Throwable $e) {
        }

        return false;
    }
}
