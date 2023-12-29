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
            'actionFindSpecialist' => [
                'name' => '1',
                'template' => '1'
            ],
            'actionEmployeeProfile' => [
                'name' => '1',
                'template' => '1'
            ],
            'actionEmployeeSettings' => [
                'name' => '1',
                'template' => '1'
            ],
        ];
    }

    // TODO
    public function callAction($method, $parameters)
    {
        if (true) {
            // return response()->json(['message' => 'forbidden']);
        }

        return parent::callAction($method, $parameters);
    }

    public function actionEmployeeCabinet()
    {
        return view('lawyers.employee.cabinet');
    }

    public function actionFindSpecialist()
    {
        return view('lawyers.employee.find-specialist');
    }

    public function actionEmployeeProfile()
    {
        return view('lawyers.employee.profile');
    }

    public function actionEmployeeSettings()
    {
        return view('lawyers.employee.settings');
    }
}
