<?php

namespace App\Http\Controllers;

use App\Models\CoreEngine\ProjectModels\Employee\EmployeeService;
use App\Models\CoreEngine\ProjectModels\Service\Service;
use App\Models\CoreEngine\ProjectModels\User\UserEntity;
use App\Models\System\ControllersModel\FrontController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends FrontController
{
    public function getPageParams(): array
    {
        return [
            'actionEmployeeCabinet' => ['template' => '1'],
            'actionEmployeeSettings' => ['template' => '1'],
            'actionEmployeeStore' => ['template' => '1'],
            'actionEmployeeServiceUpdate' => ['template' => '1'],
        ];
    }

    public function callAction($method, $parameters)
    {
        if (!Auth::check() || Auth::user()->type->name !== 'employee') {
            return redirect('/main');
        }

        return parent::callAction($method, $parameters);
    }

    public function actionEmployeeCabinet()
    {
        return view('lawyers.employee.cabinet');
    }

    public function actionEmployeeSettings()
    {
        $services = Service::all();
        $user_service_ids = array_column(Auth::user()->services->toArray(), 'service_id');
        // не годится выходит моналит
        return view('lawyers.employee.settings', [
            'services' => $services,
            'user_service_ids' => $user_service_ids,
        ]);
    }




}
