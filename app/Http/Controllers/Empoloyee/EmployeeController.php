<?php

namespace App\Http\Controllers\Empoloyee;

use App\Models\System\ControllersModel\FrontController;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends FrontController
{
    public function getPageParams(): array
    {
        return [
            'actionEmployeeCabinet' => [
                'name' => '1',
                'template' => '1'
            ],
            'actionEmployeeSettings' => [
                'name' => '1',
                'template' => '1'
            ],
        ];
    }

    public function callAction($method, $parameters)
    {
        if (!Auth::check() || Auth::user()->type->name !== 'employee') {
            // return redirect('/main');
        }

        return parent::callAction($method, $parameters);
    }

    public function actionEmployeeCabinet()
    {
        return view('lawyers.employee.cabinet');
    }

    public function actionEmployeeSettings()
    {
        return view('lawyers.employee.settings');
    }
}
