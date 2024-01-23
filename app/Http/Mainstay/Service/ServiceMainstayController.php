<?php

namespace App\Http\Mainstay\Service;

use App\Models\CoreEngine\LogicModels\Service\ServiceLogic;
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

    public function actionServiceStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ]);
        }
        return response()->json(
            (new ServiceLogic())->store($request->all())
        );
    }

    public function actionGetService(Request $request)
    {
        return response()->json(
            Service::find($request->input('id'))
        );
    }

    public function actionGetServiceList()
    {
        return response()->json((new ServiceLogic())->getList());
    }

    public function actionServiceDelete(Request $request)
    {
        return response()->json(
            (new ServiceLogic())->deleteService($request->all())
        );
    }

    public function actionGetServiceTypeList()
    {
        $return['result'] = ServiceType::all()->toArray();
        $return['result'] = HelperFunction::ArrayForSelectFomCodeEngine($return['result'],'id','name');
        return response()->json($return);
    }
}
