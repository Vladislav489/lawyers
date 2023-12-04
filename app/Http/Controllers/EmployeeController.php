<?php

namespace App\Http\Controllers;

use App\Models\System\ControllersModel\FrontController;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends FrontController
{
    const EMPLOYEE_TYPE_ID = 2;

    public function getPageParams(): array
    {
        return [
            'actionEmployeeCabinet' => ['template' => '1'],
        ];
    }

    public function callAction($method, $parameters)
    {
        if (!Auth::check() || Auth::user()->type_id !== self::EMPLOYEE_TYPE_ID) {
            return redirect('/main');
        }

        return parent::callAction($method, $parameters);
    }

    public function actionEmployeeCabinet()
    {
        return view('lawyers.employee.cabinet');
    }
}
