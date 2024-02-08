<?php

namespace App\Http\Mainstay\HelpData;

use App\Models\CoreEngine\LogicModels\HelpData\CityLogic;
use App\Models\CoreEngine\LogicModels\HelpData\CountryLogic;
use App\Models\CoreEngine\LogicModels\HelpData\DistrictLogic;
use App\Models\CoreEngine\LogicModels\HelpData\StateLogic;
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

    public function actionGetDistricts(array $param = []) {
        $this->params = (empty($param)) ? $this->params : $param;
        $list = (new DistrictLogic($this->params))->getList();
        $return['result'] = HelperFunction::ArrayForSelectFomCodeEngine($list['result'],'id','name');
        return response()->json($return);
    }

    public function actionGetCountries(array $param = []) {
        $this->params = (empty($param)) ? $this->params : $param;
        $list = (new CountryLogic($this->params))->getList();
        $return['result'] = HelperFunction::ArrayForSelectFomCodeEngine($list['result'],'id','name');
        return response()->json($return);
    }

    public function actionGetStates2(array $param = []) {
        $this->params = (empty($param)) ? $this->params : $param;
        $list = (new StateLogic($this->params))->getList();
        $return['result'] = HelperFunction::ArrayForSelectFomCodeEngine($list['result'],'id','name');
        return response()->json($return);
    }
}
