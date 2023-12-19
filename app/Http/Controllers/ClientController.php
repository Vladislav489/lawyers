<?php

namespace App\Http\Controllers;

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
        ];
    }

    public function callAction($method, $parameters)
    {
        if (!Auth::check() || Auth::user()->type->name !== 'client') {
            return redirect('/main');
        }

        return parent::callAction($method, $parameters);
    }

    public function actionClientCabinet()
    {
        return view('lawyers.client.cabinet');
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
