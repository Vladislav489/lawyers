<?php

namespace App\Http\Controllers\Employee;

use App\Models\System\ControllersModel\EmployeeController as BaseEmployeeController;

class EmployeeController extends BaseEmployeeController {

    public function getPageParams(): array {
        return [
            'actionEmployeeProfile' => [
                'name' => 'Мой профиль',
                'template' => 'lawyers.employee.edit-profile'
            ],
            'actionViewVacancy' => [
                'name' => 'Вакансия',
                'template' => 'lawyers.employee.vacancy-',
                'chpu' => ['vacancy_id']
            ],
        ];
    }

    public function actionSignupEmployee() {
        return view('lawyers.user.bootstrap.signup-employee');
    }

    public function actionEmployeeCabinet() {
        return view('lawyers.employee.cabinet');
    }

    public function actionEmployeeProfile() {
        return view('lawyers.employee.edit-profile');
    }

    public function actionEmployeeSettings() {
        return view('lawyers.employee.settings');
    }

    public function actionVacancyExchange() {
        return view('lawyers.employee.find-vacancy');
    }

    public function actionViewVacancy() {
        return view('lawyers.employee.vacancy-');
    }
}
