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

        /*
         * "actionIndex" =>[
         * 'name'=>"Home", название страницы
         * 'type_page'=>'home', тип страницы
         * 'chpu'=>["type","code"], это route/{type?}/{code?}
         * "template"=>'Site.Forecast.main' Это ткмплайт который дефолтный для этой страницы
         * ],
         *
         */
        return [
            'actionClientCabinet' => ['name'=>"Сlient Profile",'template' => 'lawyers.client.cabinet'],
            'actionCreateVacancy' => ['name'=>"Create Vacancy", 'template' => 'lawyers.client.vacancy-create'],
            'actionEditVacancy' => ['name'=>"Edit Vacancy",'template' => 'lawyers.client.vacancy-edit'],
            'actionVacancyList' => ['name'=>"List Vacancy",'template' => 'lawyers.client.vacancy-list'],
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

    public function actionCreateVacancy()
    {
        return view('lawyers.client.vacancy-create');
    }

    // Данные моменты должны быть в


    public function actionEditVacancy(Request $request)
    {
        $vacancy = Vacancy::findOrFail($request->input('id'));
        //Мы не дложны передавать во вью данные иначе будет не гибкий моналит
        return view('lawyers.client.vacancy-edit', [
            'vacancy' => $vacancy
        ]);
    }



    public function actionVacancyList()
    {
        return view('lawyers.client.vacancy-list');
    }
}
