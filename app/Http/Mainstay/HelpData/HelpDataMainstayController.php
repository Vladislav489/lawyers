<?php

namespace App\Http\Mainstay\HelpData;

use App\Models\CoreEngine\LogicModels\HelpData\CityLogic;
use App\Models\CoreEngine\LogicModels\HelpData\RegionLogic;
use App\Models\System\ControllersModel\MainstayController;
use App\Models\System\HelperFunction;

class HelpDataMainstayController extends MainstayController
{
    public function actionGetCities(array $param = []) {
        $this->params = (empty($param)) ? $this->params : $param;
        $list = (new CityLogic($this->params))->getList();
        $return['result'] = HelperFunction::ArrayForSelectFomCodeEngine($list['result'],'id','name');
        return response()->json($return);
    }

    public function actionGetRegions(array $param = []) {
        $this->params = (empty($param)) ? $this->params : $param;
        $list = (new RegionLogic($this->params))->getList();
        $return['result'] = HelperFunction::ArrayForSelectFomCodeEngine($list['result'],'id','name');
        return response()->json($return);
    }
}
