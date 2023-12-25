<?php

namespace App\Http\Controllers\Service;

use App\Models\System\ControllersModel\FrontController;
use Illuminate\Support\Facades\Auth;

class ServiceController extends FrontController
{
    public function getPageParams(): array
    {
        return [
            'actionServiceList' => [
                'name' => 'Service list',
                'template' => 'lawyers.contract.service-list'
            ],
            'actionServiceCreate' => [
                'name' => 'Service create',
                'template' => 'lawyers.contract.service-create'
            ],
            'actionServiceEdit' => [
                'name' => 'Service edit',
                'template' => 'lawyers.contract.service-edit'
            ],
        ];
    }

    // TODO:
    public function callAction($method, $parameters)
    {
        if (true) {
            // return response()->json(['message' => 'forbidden']);
        }

        return parent::callAction($method, $parameters);
    }

    public function actionServiceList()
    {
        return view('lawyers.service.service-list');
    }

    public function actionServiceCreate()
    {
        return view('lawyers.service.service-create');
    }

    public function actionServiceEdit()
    {
        return view('lawyers.service.service-edit');
    }
}
