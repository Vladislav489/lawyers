<?php

namespace App\Http\Mainstay\Vacancy;

use App\Models\CoreEngine\LogicModels\Employee\EmployeeLogic;
use App\Models\CoreEngine\LogicModels\Vacancy\VacancyLogic;
use App\Models\CoreEngine\LogicModels\Vacancy\VacancyOfferLogic;
use App\Models\CoreEngine\ProjectModels\Vacancy\Vacancy;
use App\Models\System\ControllersModel\MainstayController;
use Carbon\Carbon;
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
        return response()->json((new VacancyLogic())->getVacancyForResponse($this->params));
    }

    public function actionGetVacancyList($param = []) {
        $this->params = empty($param) ? $this->params : $param;
        return response()->json((new VacancyLogic())->getVacancyList($this->params));
    }

    public function actionVacancyStore($param = []) {
        $this->params = empty($param) ? $this->params : $param;
        $user = \auth()->user();
        $messages = [
            'required' => 'Поле обязательно к заполнению',
            'integer'    => 'Введите целочисленное значение',
            'max' => 'Максимальная длина :max символов',
            'min'      => 'Минимальная длина :min символов',
            'files.*.max' => 'Максимальный размер файла 5 МБ'
        ];
        $rules = [
            'service_id' => 'required|integer|exists:service,id',
            'title' => 'required|string|min:5|max:150',
            'description' => 'required|string|min:5|max:1200',
            'payment' => 'required',
            'files' => 'array|nullable',
            'files.*' => 'file|max:5120',
            'executor_id' => 'nullable|integer|exists:user_entity,id',
            'period' => 'nullable|integer'
        ];
        $data = Validator::validate($this->params, $rules, $messages);
        $data['status'] = VacancyLogic::STATUS_NEW;
        $data['priority_id'] = 1;
        $data['user_id'] = $user->id;
        $data['region_id'] = $user->region_id;
        $data['city_id'] = $user->city_id;

        if ($data['period'] != 0) {
            $currentTimeStamp = Carbon::now();
            $data['period_start'] = $currentTimeStamp->toDateTimeString();
            $data['period_end'] = $currentTimeStamp->addDays($data['period'])->toDateTimeString();
        }
        unset($data['period']);
//        dd($data);
        return response()->json((new VacancyLogic(['user_id' => $user->id]))->store($data));
    }

    public function actionVacancyUpdate($param = []) {
        $this->params = empty($param) ? $this->params : $param;
        $userId = \auth()->id();
        $messages = [
            'required' => 'Поле обязательно к заполнению',
            'integer'    => 'Введите целочисленное значение',
            'max' => 'Максимальная длина :max символов',
            'min'      => 'Минимальная длина :min символов',
            'files.*.max' => 'Максимальный размер файла 5 МБ'
        ];
        $rules = [
            'service_id' => 'required|integer|exists:service,id',
            'title' => 'required|string|min:5|max:150',
            'description' => 'required|string|min:5|max:1200',
            'payment' => 'required',
            'files' => 'array|nullable',
            'files.*' => 'file|max:5120',
            'id' => 'required|integer|exists:vacancy,id',
            'period' => 'nullable|integer'
        ];
        $data = Validator::validate($this->params, $rules ,$messages);
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
        return response()->json((new VacancyOfferLogic())->getOffersList($this->params));
    }

    public function actionGetGroupVacancyList($param = []) {
        $this->params = empty($param) ? $this->params : $param;
        $this->params['is_group'] = '1';
        dd((new VacancyLogic($this->params))->setJoin(['VacancyGroup'])->getList());
    }

    public function actionGetClosingMessage($param = []) {
        $this->params = empty($param) ? $this->params : $param;
        return response()->json(['result' => (new VacancyLogic())->getClosingMessage($this->params)]);
    }
}
