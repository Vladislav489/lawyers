<?php

namespace App\Http\Controllers\Client;

use App\Models\System\ControllersModel\FrontController;
use Illuminate\Support\Facades\Auth;

class ClientController extends FrontController
{
    public function getPageParams(): array
    {
        return [
            'actionClientCabinet' => [
                'name' => 'Сlient Profile',
                'template' => 'lawyers.client.cabinet'
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

    public function actionClientCabinet()
    {
        return view('lawyers.client.cabinet');
    }
}
