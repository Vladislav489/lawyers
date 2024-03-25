<?php

namespace App\Http\Mainstay\Vacancy;

use App\Models\CoreEngine\LogicModels\Vacancy\VacancyLogic;
use App\Models\CoreEngine\ProjectModels\Vacancy\Vacancy;
use App\Models\System\ControllersModel\MainstayController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VacancyMainstayController extends MainstayController
{
    public function actionGetVacancy($param = []) {
        $this->params = empty($param) ? $this->params : $param;
        return response()->json((new VacancyLogic($this->params))->setJoin(['VacancyOffer', 'ChatMessage', 'VacancyGroup', 'VacancyGroupForApprove', 'Service', 'ServiceType'])->getOne());
    }

    public function actionGetVacancyList($param = []) {
        $this->params = empty($param) ? $this->params : $param;
        $select = [
            'id', 'title', 'description', 'payment', 'status', 'period_start', 'period_end',
//            DB::raw("IFNULL(period_end, 'Срок не установлен') as period_end"),
            DB::raw("CONCAT(Country.name, ', ', State.name, ', ', City.name) as location"),
            DB::raw("(CASE
        WHEN TIMESTAMPDIFF(MINUTE, vacancy.created_at, NOW()) < 60 THEN CONCAT(TIMESTAMPDIFF(MINUTE, vacancy.created_at, NOW()), ' минут назад')
        WHEN TIMESTAMPDIFF(HOUR, vacancy.created_at, NOW()) < 24 THEN CONCAT(TIMESTAMPDIFF(HOUR, vacancy.created_at, NOW()), ' часов назад')
        ELSE CONCAT(TIMESTAMPDIFF(DAY, vacancy.created_at, NOW()), ' дней назад')
        END) AS time_ago")
        ];
        return response()->json((new VacancyLogic($this->params, $select))->setJoin(['Country', 'State', 'City'])->getList());
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

        return response()->json((new VacancyLogic(['user_id' => $user->id]))->store($data));
    }

    public function actionVacancyUpdate($param = []) {
        $this->params = empty($param) ? $this->params : $param;
        $userId = \auth()->id();
        $rules = [
            'service_id' => 'required|integer|exists:service,id',
            'title' => 'required|string|min:5|max:150',
            'description' => 'required|string|min:5|max:1200',
            'payment' => 'required',
            'files' => 'array|nullable',
            'files.*' => 'file|max:5120',
            'id' => 'required|integer|exists:vacancy,id'
        ];
        $data = Validator::validate($this->params, $rules);
        $data['user_id'] = $userId;
        return response()->json((new VacancyLogic(['user_id' => $userId]))->store($data));
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
