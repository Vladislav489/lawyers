<?php

namespace App\Http\Mainstay\Employee;

use App\Http\Login\LoginController;
use App\Models\CoreEngine\LogicModels\Employee\EmployeeLogic;
use App\Models\CoreEngine\LogicModels\Employee\EmployeeServiceLogic;
use App\Models\CoreEngine\LogicModels\Employee\EmployeeSpecializationLogic;
use App\Models\CoreEngine\LogicModels\File\FileLogic;
use App\Models\CoreEngine\LogicModels\Vacancy\VacancyLogic;
use App\Models\CoreEngine\ProjectModels\Company\Company;
use App\Models\CoreEngine\ProjectModels\HelpData\City;
use App\Models\CoreEngine\ProjectModels\HelpData\Region;
use App\Models\CoreEngine\ProjectModels\User\UserEntity;
use App\Models\CoreEngine\ProjectModels\User\UserModifier;
use App\Models\CoreEngine\ProjectModels\User\UserType;
use App\Models\System\ControllersModel\MainstayController;
use App\Models\System\HelperFunction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EmployeeMainstayController extends MainstayController
{
    const MAX_FILE_SIZE = 5;

    public function actionGetModifiersForSelect($param = []) {
        $this->params = (empty($param)) ? $this->params : $param;
        return response()->json((new EmployeeLogic())->getModifiersList());
    }

    public function actionEmployeeStore(array $param = []) {
        $this->params = (empty($param)) ? $this->params : $param;
        $rules = [
            'first_name' => 'required|string|max:64',
            'last_name' => 'required|string|max:64',
            'middle_name' => 'nullable|string|max:64',
            'date_birthday' => 'required|date',
            'email' => 'required|string|max:128|email|unique:' . UserEntity::class . ',email',
            'city_id' => 'required|integer|exists:' . City::class . ',id',
            'region_id' => 'required|integer|exists:' . Region::class . ',id',
            'password' => 'required|string|confirmed',
            'dt_practice_start' => 'required|date',
            'license_number' => 'nullable|string|max:128',
            'cert_file' => 'required|image|max:' . self::MAX_FILE_SIZE * 1024,
            'cert_description' => 'required|string|max:128',
            'avatar' => 'required|image|max:' . self::MAX_FILE_SIZE * 1024,
            'type_id' => 'required|integer|exists:' . UserType::class . ',id',
            'modifier_id' => 'required|integer|exists:' . UserModifier::class . ',id',
            ];

        $messages = [
            'required' => 'Это обязательное поле',
            'max' => 'Максимальная длина :max символов',
            'image' => 'Допустимые форматы: jpg, jpeg, png, bmp, svg или webp',
            'email.email' => 'Введите email в формате xxx@xxx.xx',
            'email.unique' => 'Пользователь с таким email уже зарегистрирован',
            'password.confirmed' => 'Пароль не совпадает',
            'cert_file.max' => 'Максимальный размер файла :max байт',
            'avatar.max' => 'Максимальный размер файла :max байт',
        ];

        $validated = Validator::validate($this->params, $rules, $messages);
        if ($data = (new EmployeeLogic())->save($validated)) {
            $credentials = ['email' => $data['email'], 'password' => $data['input_password']];
            return (new LoginController())->actionUserLogin($credentials);
        }
        return redirect()->back()->withErrors($validated)->withInput();
    }

    public function actionEmployeeServiceStore(array $param = []) {
        $this->params = (empty($param)) ? $this->params : $param;
        $rules = [
            'id' => 'nullable|exists:user_employee_service,id',
            'type_id' => 'required|integer|exists:service_type,id',
            'service_id' => 'required|integer|exists:service,id',
            'user_id' => 'required|integer|exists:user_entity,id',
            'title' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|integer',
        ];
        $data = Validator::validate($this->params, $rules);
        (new EmployeeServiceLogic())->store($data);
        return $this->actionGetServices(['user_id' => auth()->id()]);
    }

    public function actionGetServices(array $param = []) {
        $this->params = (empty($param)) ? $this->params : $param;
        return response()->json((new EmployeeServiceLogic($this->params, ['*', DB::raw("ServiceType.id as type_id")]))->setJoin(['Service', 'ServiceType'])->getList());
    }

    public function actionGetSpecialization(array $param = []) {
        $this->params = (empty($param)) ? $this->params : $param;
        return response()->json((new EmployeeSpecializationLogic($this->params))->setJoin(['Service'])->getList());
    }

    public function actionUpdateSpecialization(array $param = []) {
        $this->params = (empty($param)) ? $this->params : $param;
        (new EmployeeSpecializationLogic())->updateSpecialization($this->params);
        return $this->actionGetEmployee(['id' => (string)auth()->id()]);
    }

    public function actionUpdateLocation(array $param = []) {
        $this->params = (empty($param)) ? $this->params : $param;
        return response()->json((new EmployeeLogic())->save($this->params));
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
            DB::raw("CONCAT_WS(' ', last_name, first_name, middle_name) as full_name") ,
            DB::raw("TIMESTAMPDIFF(YEAR, Employee.dt_practice_start, DATE(NOW())) as practice_years"),
            DB::raw("IF(Photos.path IS NULL, NULL, CONCAT('[', GROUP_CONCAT(DISTINCT(JSON_OBJECT('id', Photos.id, 'path',
            CONCAT('/storage', Photos.path)))), ']')) as photos"),
            DB::raw("City.id as city_id, City.name as city_name, Region.id as region_id, Region.name as region_name"),
            DB::raw("CONCAT(Region.name, ' ', City.name) as location"),
            DB::raw("Employee.about as about"),
            DB::raw("Employee.location_coordinates as location_coordinates"),
            DB::raw("Employee.location_address as location_address"),
            DB::raw("Employee.site_url as site_url"),
            DB::raw("Employee.thesis as thesis"),
            DB::raw("Employee.is_confirmed as is_confirmed"),
            ];
        return response()->json(['result' => (new EmployeeLogic($this->params, $select))
            ->setJoin(['Employee', 'Achievements', 'City','Region', 'Photos', 'WorkingSchedule', 'Specialization'])->getOne()]);
    }

    public function actionGetEmployeeList(array $param = []) {
        $this->params = (empty($param)) ? $this->params : $param;
        $select = [
            '*', DB::raw("TIMESTAMPDIFF(YEAR, Employee.dt_practice_start, DATE(NOW())) as practice_years"),
            DB::raw("CONCAT_WS(' ', last_name, first_name, middle_name) as full_name") ,
            DB::raw('Employee.user_id as user_id, Employee.avatar_path as avatar_path, Employee.consultation_price as consultation_price, Employee.about as about'),
            DB::raw("CONCAT(Region.name, ' ', City.name) as location"),
            ];
        return response()->json((new EmployeeLogic($this->params, $select))->setJoin(['Employee', 'Specialization', 'Region', 'City'])->getList());
    }

    public function actionUpdateEmployeeInfo(array $param = []) {
        $this->params = (empty($param)) ? $this->params : $param;
//        dd($this->params);
//        $rules = [
//            'first_name' => 'required|string|max:64',
//            'last_name' => 'required|string|max:64',
//            'middle_name' => 'required|string|max:64',
//            'phone_number' => 'required|string',
//            'city_id' => 'required|integer|exists:' . City::class . ',id',
//            'region_id' => 'required|integer|exists:' . Region::class . ',id',
//            'working_days' => 'nullable|array',
//            'working_days.*' => 'nullable|integer|exists:days_of_week,id',
//            'time_from' => 'nullable|integer',
//            'time_to' => 'nullable|integer',
//        ];
//        $data = Validator::validate($this->params, $rules);

        (new EmployeeLogic())->updateEmployeeInfo($this->params);
        return $this->actionGetEmployee(['id' => (string)auth()->id()]);
    }

    public function actionUpdateEmployeeCertificates(array $param = []) {
        $this->params = (empty($param)) ? $this->params : $param;
        (new EmployeeLogic())->saveAchievements($this->params);
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

    public function actionAcceptWork($param = []) {
        $this->params = empty($param) ? $this->params : $param;
        $rules = [
            'vacancy_id' => 'required|exists:vacancy,id',
            'employee_user_id' => 'required|exists:user_entity,id',
        ];
        $data = Validator::validate($this->params, $rules);
        return response()->json((new EmployeeLogic())->acceptWork($data));
    }

    public function actionDeclineWork($param = []) {
        $this->params = empty($param) ? $this->params : $param;
        $rules = [
            'vacancy_id' => 'required|exists:vacancy,id',
            'employee_user_id' => 'required|exists:user_entity,id',
        ];
        $data = Validator::validate($this->params, $rules);
        return response()->json((new EmployeeLogic())->declineWork($data));
    }

    public function actionSendWorkToInspection($param = []) {
        $this->params = empty($param) ? $this->params : $param;
        $rules = [
            'text' => 'required|string',
            'files' => 'required|array',
            'vacancy_id' => 'required|integer|exists:vacancy,id',
        ];

        $data = Validator::validate($this->params, $rules);
        $data['employee_user_id'] = auth()->id();

        return response()->json((new VacancyLogic())->sendClosingDocs($data));
    }

    public function actionGetEmployeeAchievements($param = []) {
        $this->params = empty($param) ? $this->params : $param;
        return response()->json((new EmployeeLogic())->getEmployeeAchievements($this->params));

    }

}
