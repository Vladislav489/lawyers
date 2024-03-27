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
        return view('lawyers.client.create-vacancy');
    }

    public function actionEditVacancy() {
        $vacancyId = request()->route('vacancy_id');
        if ((new VacancyLogic(['user_id' => (string)auth()->id(), 'id' => $vacancyId]))->getOne()) {
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
        ];
    }
}
