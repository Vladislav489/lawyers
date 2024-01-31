<?php

namespace App\Http\Mainstay\Company;

use App\Models\CoreEngine\LogicModels\Company\CompanyLogic;
use App\Models\System\ControllersModel\MainstayController;
use App\Models\System\HelperFunction;

class CompanyMainstayController extends MainstayController
{
    public function actionGetCompanies(array $param = []) {
        $this->params = (empty($param)) ? $this->params : $param;
        $list = (new CompanyLogic($this->params))->getSandartResultList();
        $return['result'] = HelperFunction::ArrayForSelectFomCodeEngine($list['result'],'id','name');
        return response()->json($return);
    }
}
