<?php

namespace App\Http\Controllers;

use App\Models\System\ControllersModel\FrontController;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends FrontController
{
    public function getPageParams(): array
    {
        return [
            'actionEmployeeCabinet' => ['template' => '1'],
        ];
    }

    public function callAction($method, $parameters)
    {
        if (!Auth::check() || Auth::user()->type !== 'employee') {
            return redirect('/main');
        }

        return parent::callAction($method, $parameters);
    }

    public function actionEmployeeCabinet()
    {
        return view('lawyers.employee.cabinet');
    }
}
