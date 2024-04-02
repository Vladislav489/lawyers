<?php

namespace App\Http\Mainstay\Vacancy;

use App\Models\CoreEngine\LogicModels\Employee\EmployeeLogic;
use App\Models\CoreEngine\LogicModels\Vacancy\VacancyLogic;
use App\Models\CoreEngine\LogicModels\Vacancy\VacancyOfferLogic;
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

    public function actionGetVacancyForEmployeeRespond($param = []) {
        $this->params = empty($param) ? $this->params : $param;
        $select = [
            'id', 'title', 'description', 'payment', 'status', 'period_start', 'period_end',
            DB::raw("CASE WHEN status = 1 THEN 'создан'
                    WHEN status = 2 THEN 'на модерации'
                    WHEN status = 3 THEN 'оплачен'
                    WHEN status = 4 THEN 'в работе'
                    WHEN status = 5 THEN 'на проверке'
                    WHEN status = 6 THEN 'принят'
                    WHEN status = 7 THEN 'закрыт'
                    END as current_status_text"),
            DB::raw("Service.name as service_name"),
            DB::raw("CONCAT(Country.name, ', ', State.name, ', ', City.name) as location"),
            DB::raw("(CASE
        WHEN TIMESTAMPDIFF(MINUTE, vacancy.created_at, NOW()) < 60 THEN CONCAT(TIMESTAMPDIFF(MINUTE, vacancy.created_at, NOW()), ' минут назад')
        WHEN TIMESTAMPDIFF(HOUR, vacancy.created_at, NOW()) < 24 THEN CONCAT(TIMESTAMPDIFF(HOUR, vacancy.created_at, NOW()), ' часов назад')
        ELSE CONCAT(TIMESTAMPDIFF(DAY, vacancy.created_at, NOW()), ' дней назад')
        END) AS time_ago"),
            DB::raw("(DATEDIFF(NOW(), period_end)) as days_to_end"),
            DB::raw("CONCAT(Owner.last_name, ' ', Owner.first_name) as owner_name"),
            DB::raw("Owner.online as owner_online"),
        ];
        return response()->json(['result' => (new VacancyLogic($this->params, $select))->setJoin(['Service', 'Country', 'State', 'City', 'Owner', 'Status'])->getOne()]);
    }

    public function actionGetVacancyList($param = []) {
        $this->params = empty($param) ? $this->params : $param;
        $this->params['executor'] = '1';
        $select = [
            'id', 'title', 'description', 'payment', 'status', 'period_start', 'period_end',
            DB::raw("(DATEDIFF(NOW(), period_end)) as days_to_end"),
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

    public function actionGetEmployeeRespondsList($param = []) {
        $this->params = empty($param) ? $this->params : $param;
        $select = [
            '*', 'period',
            DB::raw("CONCAT(User.last_name, ' ', User.first_name, ' ', User.middle_name) as full_name"),
            DB::raw("CONCAT('/storage', Employee.avatar_path) as avatar"),
            DB::raw("TIMESTAMPDIFF(YEAR, Employee.dt_practice_start, DATE(NOW())) as practice_years"),
            DB::raw("Response.text as response_text"),
            DB::raw("(SELECT CONCAT(City.name) FROM city as City WHERE City.id = User.city_id) as location"),
        ];
        return response()->json((new VacancyOfferLogic($this->params, $select))->setJoin(['User', 'Employee', 'Response'])->getList());
    }
}
