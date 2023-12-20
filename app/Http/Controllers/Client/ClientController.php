<?php

namespace App\Http\Controllers\Client;

use App\Models\CoreEngine\LogicModels\Vacancy\VacancyLogic;
use App\Models\CoreEngine\ProjectModels\Vacancy\Vacancy;
use App\Models\System\ControllersModel\FrontController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ClientController extends FrontController
{
    public function getPageParams(): array
    {
        return [
            'actionClientCabinet' => [
                'name' => 'Сlient Profile',
                'template' => 'lawyers.client.cabinet'
            ],
            'actionCreateVacancy' => [
                'name' => 'Сreate Vacancy',
                'template' => 'lawyers.client.vacancy-create'
            ],
            'actionEditVacancy' => [
                'name' => 'Edit Vacancy',
                'template' => 'lawyers.client.vacancy-edit'
            ],
            'actionVacancyList' => [
                'name' => 'List Vacancy',
                'template' => 'lawyers.client.vacancy-list'
            ],
            'actionChatList' => [
                'name' => 'List Chat',
                'template' => 'lawyers.client.chat-list'
            ],
            'actionChatCreate' => [
                'name' => 'List Chat',
                'template' => 'lawyers.client.chat-create'
            ],
            'actionContractList' => [
                'name' => 'List Contract',
                'template' => 'lawyers.client.contract-list'
            ],
            'actionContractCreate' => [
                'name' => 'List Contract',
                'template' => 'lawyers.client.contract-create'
            ],
        ];
    }

    public function callAction($method, $parameters)
    {
        if (!Auth::check() || Auth::user()->type->name !== 'client') {
            // return redirect('/main');
        }
        return parent::callAction($method, $parameters);
    }

    public function actionClientCabinet()
    {
        return view('lawyers.client.cabinet');
    }

    public function actionChatList()
    {
        return view('lawyers.client.chat-list');
    }

    public function actionChatCreate()
    {
        return view('lawyers.client.chat-create');
    }

    public function actionContractList()
    {
        return view('lawyers.client.contract-list');
    }

    public function actionContractCreate()
    {
        return view('lawyers.client.contract-create');
    }

    public function actionVacancyList()
    {
        return view('lawyers.client.vacancy-list');
    }

    public function actionCreateVacancy()
    {
        return view('lawyers.client.vacancy-create');
    }

    public function actionEditVacancy()
    {
        return view('lawyers.client.vacancy-edit');
    }
}
