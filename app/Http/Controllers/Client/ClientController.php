<?php

namespace App\Http\Controllers\Client;

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

    public function getPageParams(): array {
        return [
            'actionClientCabinet' => [
                'name' => 'Сlient Profile',
                'template' => 'lawyers.client.cabinet'
            ],
        ];
    }
}
