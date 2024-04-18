<?php

namespace App\Http\Controllers\Employee;

use App\Models\CoreEngine\LogicModels\Vacancy\VacancyLogic;
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
        if ($this->checkChosenExecutor()) {
            return view('lawyers.employee.vacancy-details');
        } elseif ($this->hasExecutor()) {
            return redirect()->back();
        }
        return view('lawyers.employee.vacancy-');
    }

    public function actionViewOrders() {
        return view('lawyers.employee.all-orders');
    }

    public function checkChosenExecutor()
    {
        return (new VacancyLogic([
            'id' => $this->params['vacancy_id'],
            'executor_id' => (string)auth()->id(),
//            'status' => (string) VacancyLogic::STATUS_LAWYER_ACCEPTANCE
        ]))->Exist();
    }

    public function hasExecutor()
    {
        return (new VacancyLogic([
            'id' => $this->params['vacancy_id'],
            'has_executor' => '1',
        ]))->Exist();
    }
}
