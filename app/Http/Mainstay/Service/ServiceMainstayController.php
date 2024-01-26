<?php

namespace App\Http\Mainstay\Service;

use App\Models\CoreEngine\LogicModels\Service\ServiceLogic;
use App\Models\CoreEngine\LogicModels\Service\ServiceTypeLogic;
use App\Models\CoreEngine\ProjectModels\Service\Service;
use App\Models\CoreEngine\ProjectModels\Service\ServiceType;
use App\Models\System\ControllersModel\MainstayController;
use App\Models\System\HelperFunction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ServiceMainstayController extends MainstayController
{
    public function callAction($method, $parameters)
    {
        if (!Auth::check()) {
            // return response()->json(['message' => 'forbidden']);
        }

        return parent::callAction($method, $parameters);
    }

    public function actionServiceStore(array $param = [])
    {
        $this->params = (empty($param)) ? $this->params : $param;
        return response()->json(
            (new ServiceLogic())->store($this->params)
        );
    }

    public function actionGetService(array $param = [])
    {
        $this->params = (empty($param)) ? $this->params : $param;
        return response()->json(
            (new ServiceLogic($this->params))->getSandartResultOne()
        );
    }

    public function actionGetServiceList(array $param = [])
    {
        $this->params = (empty($param)) ? $this->params : $param;
        $this->params = array_merge($this->params, ['is_deleted' => 0]);
        return response()->json((new ServiceLogic($this->params))->getList());
    }

    public function actionServiceDelete(array $param = [])
    {
        $this->params = (empty($param)) ? $this->params : $param;
        return response()->json(
            (new ServiceLogic())->deleteService($this->params)
        );
    }

    public function actionGetServiceTypeList(array $param = [])
    {
        $this->params = (empty($param)) ? $this->params : $param;
        $list = (new ServiceTypeLogic())->getSandartResultList();
        $return['result'] = HelperFunction::ArrayForSelectFomCodeEngine($list['result'],'id','name');
        return response()->json($return);
    }
}
