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
            'actionClientCabinet' => ['template' => '1'],
            'actionCreateVacancy' => ['template' => '1'],
            'actionDeleteVacancy' => ['template' => '1'],
            'actionEditVacancy' => ['template' => '1'],
            'actionStoreVacancy' => ['template' => '1'],
            'actionVacancyList' => ['template' => '1'],
        ];
    }

    public function callAction($method, $parameters)
    {
        if (!Auth::check() || Auth::user()->type !== 'client') {
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

    public function actionDeleteVacancy(Request $request)
    {
        if ($request->isMethod('delete')) {
            $vacancy_id = $request->input('id');
            $vacancy = Vacancy::find($vacancy_id);

            return response()->json($vacancy->delete());
        }
    }

    public function actionEditVacancy(Request $request)
    {
        $vacancy = Vacancy::findOrFail($request->input('id'));

        return view('lawyers.client.vacancy-edit', [
            'vacancy' => $vacancy
        ]);
    }

    public function actionStoreVacancy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required|string',
            'payment' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ]);
        }

        if ($request->isMethod('post')) {
            $vacancy = (new VacancyLogic())->store($this->params);
        } elseif ($request->isMethod('patch')) {
            $vacancy = (new VacancyLogic())->update2($request->input('id'), $this->params);
        }

        return response()->json($vacancy);
    }

    public function actionVacancyList()
    {
        return view('lawyers.client.vacancy-list');
    }
}
