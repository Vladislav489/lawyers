<?php

namespace App\Http\Controllers\Client;

use App\Http\Mainstay\File\FileMainstayController;
use App\Models\CoreEngine\LogicModels\Vacancy\VacancyLogic;
use App\Models\System\ControllersModel\ClientController as BaseClientController;

class ClientController extends BaseClientController
{
    public function actionClientCabinet() {
        return view('lawyers.client.cabinet');
    }

    public function actionPaymentPage() {
        return view('lawyers.client.payment');
    }

    public function actionCreateVacancy() {
        $employeeId = request()->get('employee_id');
        $employeeName = request()->get('employee_name');
        $serviceId = request()->get('service_id');
        return view('lawyers.client.create-vacancy', compact('employeeId', 'serviceId', 'employeeName'));
    }

    public function actionMyOrders() {
        return view('lawyers.client.my-orders');
    }

    public function actionViewVacancy() {
        if ($this->checkUserVacancyAccess()) {
            if ($this->checkVacancyHasExecutor()) {
                return view('lawyers.client.vacancy-details');
            }
            return view('lawyers.client.vacancy-responses');
        } else {
            return redirect(route__('actionClientCabinet_controllers_client_clientcontroller'))->with('error', 'No access!');
        }
    }

    public function actionEditVacancy() {
        if ($this->checkUserVacancyAccess()) {
            return view('lawyers.client.edit-vacancy');
        } else {
            return redirect(route__('actionClientCabinet_controllers_client_clientcontroller'))->with('error', 'No access!');
        }
    }

    public function actionViewFile() {
        $filePath = request()->query('path');
        $fileName = request()->query('name');
        return (new FileMainstayController())->actionDownloadFile($filePath, $fileName);
    }

    public function getPageParams(): array {
        return [
            'actionEditVacancy' => [
                'name' => 'Редактировать заказ',
                'chpu' => ['vacancy_id'],
                'template' => 'lawyers.client.edit-vacancy'
            ],
            'actionViewVacancy' => [
                'name' => 'Страница заказа',
                'chpu' => ['vacancy_id'],
                'template' => 'lawyers.client.vacancy-details'
            ],
        ];
    }

    protected function checkUserVacancyAccess() {
        $vacancyId = request()->route('vacancy_id');
        return (new VacancyLogic(['user_id' => (string)auth()->id(), 'id' => $vacancyId]))->getOne();
    }

    protected function checkVacancyHasExecutor() {
        $vacancyId = request()->route('vacancy_id');
        return (new VacancyLogic(['user_id' => (string)auth()->id(), 'id' => $vacancyId, 'has_executor' => '1']))->Exist();
    }
}
