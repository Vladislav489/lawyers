<?php

namespace App\Http\Controllers\Vacancy;

use App\Models\System\ControllersModel\FrontController;
use Illuminate\Support\Facades\Auth;

class VacancyController extends FrontController
{
    public function getPageParams(): array
    {
        return [
            'actionVacancyList' => [
                'name' => 'List Vacancy',
                'template' => 'lawyers.vacancy.vacancy-list'
            ],
            'actionVacancyCreate' => [
                'name' => 'Сreate Vacancy',
                'template' => 'lawyers.vacancy.vacancy-create'
            ],
            'actionVacancyEdit' => [
                'name' => 'Edit Vacancy',
                'template' => 'lawyers.vacancy.vacancy-edit'
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

    public function actionVacancyList()
    {
        return view('lawyers.vacancy.vacancy-list');
    }

    public function actionVacancyCreate()
    {
        return view('lawyers.vacancy.vacancy-create');
    }

    public function actionVacancyEdit()
    {
        return view('lawyers.vacancy.vacancy-edit');
    }
}
