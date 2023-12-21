<?php

namespace App\Http\Mainstay\Vacancy;

use App\Models\CoreEngine\LogicModels\Vacancy\VacancyLogic;
use App\Models\CoreEngine\ProjectModels\Vacancy\Vacancy;
use App\Models\System\ControllersModel\MainstayController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class VacancyMainstayController extends MainstayController
{
    public function callAction($method, $parameters)
    {
        if (true) {
            // return response()->json(['message' => 'forbidden']);
        }

        return parent::callAction($method, $parameters);
    }

    public function actionGetVacancy(Request $request)
    {
        return response()->json(
            Vacancy::find($request->input('id'))
        );
    }

    public function actionGetVacancyList()
    {
        return response()->json((new VacancyLogic())->getList());
    }

    public function actionVacancyStore(Request $request)
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

        $request->merge([
            'user_id' => Auth::id(),
            'defendant' => json_encode([])
        ]);

        return response()->json(
            (new VacancyLogic())->store($request->all())
        );
    }

    public function actionVacancyDelete(Request $request)
    {
        if ($request->isMethod('delete')) {
            return response()->json(
                (new VacancyLogic())->deleteVacancy($request->all())
            );
        }
    }
}
