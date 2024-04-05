<?php

namespace App\Http\Mainstay\Employee;

use App\Http\Login\LoginController;
use App\Models\CoreEngine\LogicModels\Employee\EmployeeLogic;
use App\Models\CoreEngine\LogicModels\Employee\EmployeeServiceLogic;
use App\Models\CoreEngine\ProjectModels\Company\Company;
use App\Models\CoreEngine\ProjectModels\HelpData\City;
use App\Models\CoreEngine\ProjectModels\HelpData\Country;
use App\Models\CoreEngine\ProjectModels\HelpData\District;
use App\Models\CoreEngine\ProjectModels\HelpData\State;
use App\Models\CoreEngine\ProjectModels\User\UserEntity;
use App\Models\CoreEngine\ProjectModels\User\UserType;
use App\Models\System\ControllersModel\MainstayController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EmployeeMainstayController extends MainstayController
{
    const MAX_FILE_SIZE = 5;

    public function actionEmployeeStore(array $param = []) {
        $this->params = (empty($param)) ? $this->params : $param;
        $rules = [
            'first_name' => 'required|string|max:64',
            'last_name' => 'required|string|max:64',
            'middle_name' => 'required|string|max:64',
            'date_birthday' => 'required|date',
            'phone_number' => 'required|string|max:128|unique:' . UserEntity::class . ',phone_number',
            'email' => 'required|string|max:128|email|unique:' . UserEntity::class . ',email',
            'post_code' => 'required|string|max:7',
            'country_id' => 'required|integer|exists:' . Country::class . ',id',
            'state_id' => 'required|integer|exists:' . State::class . ',id',
            'district_id' => 'required|integer|exists:' . District::class . ',id',
            'city_id' => 'required|integer|exists:' . City::class . ',id',
            'password' => 'required|string|confirmed',
            'consultation_price' => 'required|integer',
            'dt_practice_start' => 'required|date',
            'license_number' => 'required|string|max:128',
            'company_id' => 'required|integer|exists:' . Company::class . ',id',
            'achievements.*' => 'nullable|image|max:' . self::MAX_FILE_SIZE * 1024,
            'avatar' => 'required|image|max:' . self::MAX_FILE_SIZE * 1024,
            'type_id' => 'required|integer|exists:' . UserType::class . ',id',
            ];

        $validated = Validator::validate($this->params, $rules);
        if ($data = (new EmployeeLogic())->save($validated)) {
            $credentials = ['phone_number' => $data['phone_number'], 'password' => $data['input_password']];
            return (new LoginController())->actionUserLogin($credentials);
        }
        return redirect()->back();
    }

    public function actionEmployeeServiceStore(array $param = []) {
        $this->params = (empty($param)) ? $this->params : $param;
        (new EmployeeServiceLogic())->store($this->params);
        return $this->actionGetServices(['user_id' => auth()->id()]);
    }

    public function actionGetServices(array $param = []) {
        $this->params = (empty($param)) ? $this->params : $param;
        return response()->json((new EmployeeServiceLogic($this->params))->setJoin(['Service'])->getList());
    }

    public function actionGetService(array $param = []) {
        $this->params = (empty($param)) ? $this->params : $param;
        return response()->json(['result' => (new EmployeeServiceLogic($this->params))->setJoin(['Service'])->getOne()]);
    }

    public function actionDeleteService(array $param = []) {
        $this->params = (empty($param)) ? $this->params : $param;
        $deleteResult = (new EmployeeServiceLogic())->deleteService($this->params);
        if ($deleteResult) {
            return $this->actionGetServices([
                'user_id' => (string)auth()->id(),
            ]);
        }
        return response()->json(['msg' => 'error',]);
    }

    public function actionGetEmployee(array $param = []) {
        $this->params = (empty($param)) ? $this->params : $param;
        $select = [
            '*',
            DB::raw("CONCAT('/storage', Employee.avatar_path) as avatar_full_path") ,
            DB::raw("CONCAT(last_name, ' ', first_name, ' ', middle_name) as full_name") ,
            DB::raw("TIMESTAMPDIFF(YEAR, Employee.dt_practice_start, DATE(NOW())) as practice_years"),
            DB::raw("IF(Achievements.path IS NULL, NULL, CONCAT('[', GROUP_CONCAT(DISTINCT(JSON_OBJECT('id', Achievements.id,'path',
            CONCAT('/storage', Achievements.path)))), ']')) as achievements"),
            DB::raw("IF(Photos.path IS NULL, NULL, CONCAT('[', GROUP_CONCAT(DISTINCT(JSON_OBJECT('id', Photos.id, 'path',
            CONCAT('/storage', Photos.path)))), ']')) as photos"),
            DB::raw("City.id as city_id, City.name as city_name, Country.id as country_id, Country.name as country_name"),
            DB::raw("CONCAT(Country.name, ', ', State.name, ', ', City.name) as location"),
            ];
//        dd((new EmployeeLogic($this->params, $select))->setJoin(['Employee', 'Achievements', 'City','Country', 'Photos'])->getOne());
        return response()->json(['result' => (new EmployeeLogic($this->params, $select))->setJoin(['Employee', 'Achievements', 'City','Country', 'Photos', 'State'])->getOne()]);
    }

    public function actionGetEmployeeList(array $param = []) {
        $this->params = (empty($param)) ? $this->params : $param;
        $select = [
            '*', DB::raw("TIMESTAMPDIFF(YEAR, Employee.dt_practice_start, DATE(NOW())) as practice_years"),
            DB::raw("IF(EmployeeService.id IS NULL, NULL, CONCAT('[', GROUP_CONCAT(JSON_OBJECT('description',
            EmployeeService.description, 'service_name', Service.name)), ']')) as service")
            ];
        $employees = (new EmployeeLogic($this->params, $select))->offPagination()->setJoin(['Employee', 'EmployeeService', 'Service']);
        $employees->getQueryLink()->groupBy('user_entity.id');
        $result = $employees->getList();

        $employees = (new EmployeeLogic($this->params))->setJoin(['Employee']);
        $pagination = $employees->getList();

        $result['pagination'] = $pagination['pagination'];
//        dd($result);
        return response()->json($result);
    }

    public function actionUpdateEmployeeInfo(array $param = []) {
        $this->params = (empty($param)) ? $this->params : $param;
        (new EmployeeLogic())->save($this->params);
        return $this->actionGetEmployee(['id' => (string)auth()->id()]);
    }

    public function actionDeleteImage(array $param = []) {
        $this->params = (empty($param)) ? $this->params : $param;
        (new EmployeeLogic())->deleteImage($this->params);
        return $this->actionGetEmployee(['id' => (string)auth()->id()]);
    }

    public function actionRespondToVacancy(array $param = []) {
        $this->params = (empty($param)) ? $this->params : $param;
        return response()->json((new EmployeeLogic())->respondToVacancy($this->params));
    }

    public function actionGetResponse(array $param = []) {
        $this->params = (empty($param)) ? $this->params : $param;
        return response()->json(['result' => (new EmployeeLogic())->getMyResponse($this->params)]);
    }

    public function actionDeleteVacancyResponse(array $param = []) {
        $this->params = empty($param) ? $this->params : $param;
        $rules = [
            'employee_response_id' => 'required|exists:employee_offer_responses,id',
            'id' => 'required|exists:vacancy_offer,id',
            'vacancy_id' => 'required|exists:vacancy,id',
            'employee_id' => 'required|exists:user_entity,id',
        ];
        $validata = Validator::validate($this->params, $rules);
        return response()->json((new EmployeeLogic())->deleteResponse($validata));
    }

}
