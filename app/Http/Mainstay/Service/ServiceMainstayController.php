<?php

namespace App\Http\Mainstay\Service;

use App\Models\CoreEngine\LogicModels\Service\ServiceLogic;
use App\Models\CoreEngine\LogicModels\Service\ServiceTypeLogic;
use App\Models\System\ControllersModel\MainstayController;
use App\Models\System\HelperFunction;

class ServiceMainstayController extends MainstayController
{

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
        return response()->json((new ServiceLogic($this->params))->getList());
    }

    public function actionServiceDelete(array $param = [])
    {
        $this->params = (empty($param)) ? $this->params : $param;
        return response()->json(
            (new ServiceLogic())->deleteService($this->params)
        );
    }

    public function actionGetServiceTypeListForSelect(array $param = [])
    {
        $this->params = (empty($param)) ? $this->params : $param;
        $list = (new ServiceTypeLogic())->getList();
        $return['result'] = HelperFunction::ArrayForSelectFomCodeEngine($list['result'],'id','name');
        return response()->json($return);
    }

    public function actionGetServiceTypeList(array $param = [])
    {
        $this->params = (empty($param)) ? $this->params : $param;
        $list = (new ServiceTypeLogic())->getList();
        return response()->json($list);
    }

    public function actionGetServiceListForSelect(array $param = [])
    {
        $this->params = (empty($param)) ? $this->params : $param;
        $list = (new ServiceLogic($this->params))->offPagination()->getList();
        $return['result'] = HelperFunction::ArrayForSelectFomCodeEngine($list['result'],'id','name');
        return response()->json($return);
    }
}
