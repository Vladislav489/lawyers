<?php
namespace App\Http\Mainstay;
use App\Models\CoreEngine\LogicModels\ComboSite\BreadcrumbsLogic;
use App\Models\CoreEngine\LogicModels\ComboSite\CategoryLogic;
use App\Models\CoreEngine\LogicModels\ComboSite\TypeLogic;
use App\Models\CoreEngine\LogicModels\Forecast\CurreciesLogic;
use App\Models\CoreEngine\LogicModels\Forecast\ForecastLogic;
use App\Models\CoreEngine\LogicModels\Forecast\InformationalDataLogic;
use App\Models\CoreEngine\Model\InformationalDataLong;
use App\Models\System\ControllersModel\MainstayController;
use App\Models\System\Securities;
use App\Models\System\SystemLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class FrontController extends MainstayController {
    public function actionlistStocks($params = NULL){
        if(!is_null($params)){
            $this->params = $params;
        }
        $this->params = array_merge(["type" => (string)Securities::TYPE_NASDAQ,
            "price" => '0', "active" => '1', "sort_dir" => 'desc', "sort_by" => ['sort','short_name']],$this->params);
        $infoData = new InformationalDataLogic($this->params,[
            DB::raw('IF(up_down > 0,true,false) as up_down'),
            'price','price_s','full_name','id','short_name',
            'url_icon','updated_at','type_inf_id']);
        $infoData->setJoin(['Type','Category']);
        $infoData->setRound(['price']);
        return Response::json($infoData->getList());
    }
    public function actionlistCurrecies($params = NULL){
        if(!is_null($params)){
            $this->params = $params;
        }
        $this->params = array_merge($this->params,["type" => (string)Securities::TYPE_PHYSICAL,
            "price" => '0', "active" => '1', "sort_dir" => 'desc', "sort_by" => 'sort']);
        $currence = new CurreciesLogic($this->params,[
            DB::raw('IF(up_down > 0,true,false) as up_down'),
            'price','price_s','full_name','id','short_name',
            'url_icon','updated_at','type_inf_id']);
        $currence->setJoin(['Type','Category'])->setRound(['price']);
        return Response::json($currence->getCurreciesConvert());
    }
    public function actionlistCryptocurrencies($params = NULL){
        if(!is_null($params)){
            $this->params = $params;
        }
        $this->params = array_merge($this->params,["type" => (string)Securities::TYPE_DIGITAL,
            "price" => '0', "active" => '1', "sort_dir" => 'desc', "sort_by" => 'sort']);
        $infoData = new InformationalDataLogic($this->params,[
            DB::raw('IF(up_down > 0,true,false) as up_down'),
            'price','price_s','full_name','id','short_name',
            'url_icon','updated_at','type_inf_id']);
        $infoData->setJoin(['Type','Category'])->setRound(['price']);
        return Response::json($infoData->getList());
    }

    public function actiongroupCurrecies($params = NULL){
        if(!is_null($params)){
            $this->params = $params;
        }
        $this->params = array_merge($this->params,["type" => (string)Securities::TYPE_PHYSICAL,
            "price" => '0',"not_item"=>"USD", "active" => '1', "sort_dir" => 'desc', "sort_by" => 'sort']);
        $infoData = new InformationalDataLogic($this->params,['id','short_name','type_inf_id']);
        $infoData->setJoin(['Type','Category'])->setLimit('');
        return Response::json($infoData->getList());
    }
    public function actiongroupStocks($params = NULL){

        if(!is_null($params)){
            $this->params = $params;
        }
        $this->params['type'] = (string)Securities::TYPE_NASDAQ;
         $listGroup = new InformationalDataLogic($this->params);
         return Response::json($listGroup->getCategoryAllUse());
    }
    public function actiongroupCryptocurrencies($params = NULL){
        if(!is_null($params)){
            $this->params = $params;
        }
        $this->params['type'] = (string)Securities::TYPE_DIGITAL;
        $page = [];
        $page_ = [];
        for ($index = 1; $index <= 12; $index++)
            $page[] = ["page" => $index, "name" => ''];

        if ($this->params['pageSize'] != -1) {
            $page = array_chunk($page,$this->params['pageSize']);
            if(isset($page[$this->params['page']-1])){
                $page_ = $page[$this->params['page']-1];
            }

        }
        $res = [
            "pagination" => ["page" => $this->params['page'], "pageSize" => $this->params['pageSize'],
                "countPage" =>($this->params['pageSize'] != -1)? (count($page)):1, "totalCount" => 12
            ]
        ];
        $res['result'] = $page_;
        return Response::json($res);
    }
    public function actioncryptocurrenciesDatale($params = NULL){
        if(!is_null($params)){
            $this->params = $params;
        }
        $codeTarge = NULL;
        $this->params['active'] = '1';
        $id = TypeLogic::getTypeId($this->params['type']);
        $this->params['type'] = (is_null($id))? $this->params['type']:(string)$id;
        if ($this->params['type'] == Securities::TYPE_PHYSICAL) {
            $temp = explode("-",$this->params['code']);

            $codeTarge = (isset($temp[1]))? $temp[1]:false;
            $this->params['code'] = $temp[0];
            if ($codeTarge) {
                $Currence = new InformationalDataLogic(["code" => $codeTarge, "type" => $this->params['type']], ['*']);
                $target = $Currence->offPagination()->getOne();
            }
        }
        $table = (new InformationalDataLong())->getTable();
        $infoData = new InformationalDataLogic($this->params,['*', DB::raw($table.".profile")]);
        $infoData->setJoin(['InformationalDataLong']);
        $data = $infoData->setRound(['price', 'low', 'high', 'close', 'up_down'])->offPagination()->getOne();
        $data['change_price'] = round($data['price'] - $data['close'],3);
        $data['val_status'] = ($data['up_down'] > 0)? true:false;
        if ($id == Securities::TYPE_PHYSICAL && $codeTarge) {
            $data['target_icon'] = $data['url_icon'];
            $data['url_icon'] = $target['url_icon'];
            $data['price'] = CurreciesLogic::simpleConvert($data, $target);
        }
        return Response::json($data);
    }
    public function actioncgetCategoryByAliasa($params = NULL){
        if(!is_null($params)){
            $this->params = $params;
        }
        $category = new CategoryLogic($this->params);
        return Response::json($category->getOne());
    }
    public function actionforecastYear($params = NULL){
        if(!is_null($params)){
            $this->params = $params;
        }
        try {
            $this->params['type'] = (string)$this->params['type'];
            $result = ForecastLogic::forYear($this->params);
            foreach ($result['result'] as $key => $item) {
                if (date("Y", strtotime($key)) != date("Y"))
                    unset($result['result'][$key]);
            }
            return Response::json($result);
        } catch (\Throwable $e){
            SystemLog::addLog("Forecast Math Page Front Year",[$e->getMessage(),$e->getFile(),$e->getLine(),"params" => $this->params],"Forecast error build!",SystemLog::CODE_ERROR);
            return Response::json([]);
            //abort(500,"Critical error check Log Forecase");
        }
    }
    public function actionforecastWeek($params = NULL){
        if(!is_null($params)){
            $this->params = $params;
        }
        try {
            $this->params['type'] = (string)$this->params['type'];
            $result =  ForecastLogic::forAllForecast($this->params);

            $listDay = ["2 days" => 2, "3 days" => 3, "week" => 7, "2 week" => 14, "month" => 30];
            $result['result'] = (new ForecastLogic())->getForecasePeriodList($listDay, $result['result']);
            return Response::json($result);
        } catch (\Throwable $e) {
           SystemLog::addLog("Forecast Math Page Front Week",[$e->getMessage(),$e->getFile(),$e->getLine(),"params" => $this->params],"Forecast error build!",SystemLog::CODE_ERROR);
            return Response::json([]);
           //abort(500,"Critical error check Log forecase!!!");
        }
    }
    public function actionforecastMonth($params = NULL){
        if(!is_null($params)){
            $this->params = $params;
        }
        try {
            $this->params['type'] = (string)$this->params['type'];
            $result = ForecastLogic::forAllForecast($this->params);
            foreach ($result['result'] as $key => $item)
                if(strtotime($key) < strtotime(date("Y-m-d")) ||
                    strtotime($key) > strtotime(date("Y-m-t")) )
                        unset($result['result'][$key]);
            return Response::json($result);
        } catch (\Throwable $e) {
            SystemLog::addLog("Forecast Math Page Front Month",[$e->getMessage(),$e->getFile(),$e->getLine(),"params" => $this->params],"Forecast error build!",SystemLog::CODE_ERROR);
            return Response::json([]);
            // abort(500,"Critical error check Log forecase!!!");
        }
    }
    public function actionBreadcrambs($params = NULL){
        if(!is_null($params)){
            $this->params = $params;
        }
        $breadcrumbs = new BreadcrumbsLogic();
        if(!isset($params['route']) && empty($params['route'])){
            return Response::json(false);
        }else{
            $route = $params['route'];
        }

        unset($params['route']);
        $route['params'] = (isset($route['params']))? array_merge($route['params'],$params):$params;
        $breadcrumbs->setRoute($route);
        return Response::json(['result'=>$breadcrumbs->getBreadcrumbs()]);
    }

    public function actionRobotLog($params = NULL){
        if(!is_null($params)){
            $this->params = $params;
        }
        Storage::disk('local')->put('logrobot.txt', 'Contents');
    }

    public function getPageParams($params = NULL){
        if(!is_null($params)){
            $this->params = $params;
        }
        return [];
    }
}
