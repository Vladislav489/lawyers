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

    public function actionVacancyStore($param = []) {
        $this->params = empty($param) ? $this->params : $param;
        $user = \auth()->user();
        $rules = [
            'service_id' => 'required|integer|exists:service,id',
            'title' => 'required|string|min:5|max:150',
            'description' => 'required|string|min:5|max:1200',
            'payment' => 'required',
            'files' => 'array|nullable',
            'files.*' => 'file|max:5120'
        ];
        $data = Validator::validate($this->params, $rules);
        $data['status'] = VacancyLogic::STATUS_NEW;
        $data['priority_id'] = 1;
        $data['user_id'] = $user->id;
        $data['country_id'] = $user->country_id;
        $data['state_id'] = $user->state_id;
        $data['city_id'] = $user->city_id;

        return response()->json((new VacancyLogic())->store($data));
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
