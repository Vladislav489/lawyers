<?php

namespace App\Http\Controllers;

use App\Models\CoreEngine\LogicModels\Vacancy\VacancyLogic;
use App\Models\System\ControllersModel\FrontController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ClientController extends FrontController
{
    const USER_TYPE_ID = 1;

    public function getPageParams(): array
    {
        return [
            'actionClientCabinet' => ['template' => '1'],
            'actionCreateVacancy' => ['template' => '1'],
            'actionStoreVacancy' => ['template' => '1'],
        ];
    }

    public function callAction($method, $parameters)
    {
        if (!Auth::check() || Auth::user()->type_id !== self::USER_TYPE_ID) {
            return redirect('/main');
        }

        return parent::callAction($method, $parameters);
    }

    public function actionClientCabinet()
    {
        return view('lawyers.client.cabinet');
    }

    public function actionCreateVacancy()
    {
        return view('lawyers.client.vacancy');
    }

    public function actionStoreVacancy()
    {
        $validator = Validator::make($this->params, [
            'description' => 'required|string',
            'payment' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ]);
        }

        $user = (new VacancyLogic())->store($this->params);

        return response()->json($user);
    }
}
