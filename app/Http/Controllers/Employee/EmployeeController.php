<?php

namespace App\Http\Controllers\Employee;

use App\Models\System\ControllersModel\EmployeeController as BaseEmployeeController;

class EmployeeController extends BaseEmployeeController {
    public function actionSignupEmployee() {
        return view('lawyers.user.bootstrap.signup-employee');
    }

    public function actionEmployeeCabinet() {
        return view('lawyers.employee.cabinet');
    }

    public function actionEmployeeProfile() {
        return view('lawyers.employee.profile');
    }

    public function actionEmployeeSettings() {
        return view('lawyers.employee.settings');
    }

    public function getPageParams(): array {
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
}
