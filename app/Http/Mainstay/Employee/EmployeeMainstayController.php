<?php

namespace App\Http\Mainstay\Employee;

use App\Http\Login\LoginController;
use App\Models\CoreEngine\LogicModels\Employee\EmployeeLogic;
use App\Models\CoreEngine\LogicModels\Employee\EmployeeServiceLogic;
use App\Models\CoreEngine\ProjectModels\Company\Company;
use App\Models\CoreEngine\ProjectModels\Employee\EmployeeService;
use App\Models\CoreEngine\ProjectModels\HelpData\City;
use App\Models\CoreEngine\ProjectModels\HelpData\Country;
use App\Models\CoreEngine\ProjectModels\HelpData\District;
use App\Models\CoreEngine\ProjectModels\HelpData\State;
use App\Models\CoreEngine\ProjectModels\Service\Service;
use App\Models\CoreEngine\ProjectModels\User\UserEntity;
use App\Models\CoreEngine\ProjectModels\User\UserType;
use App\Models\System\ControllersModel\MainstayController;
use Illuminate\Http\Request;
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


    public function actionEmployeeServicesStore(Request $request) {
        $rules = [
            'service_ids.*' => 'required|integer|exists:' . Service::class . ',id',
        ];

        if (($validator = Validator::make($request->all(), $rules))->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ]);
        }

        $data = $request->all()['service_ids'] ?? [];

        return response()->json(
            (new EmployeeServiceLogic())->storeEmployeeServices($data)
        );
    }

    public function actionEmployeeServiceUpdate(Request $request) {
        $rules = [
            'is_main' => 'boolean',
            'price' => "exclude_if:is_main,'1'|required|integer",
            'description' => "exclude_if:is_main,'1'|required|string",
            'id' => 'required|integer|exists:' . EmployeeService::class . ',id',
        ];

        if (($validator = Validator::make($request->all(), $rules))->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ]);
        }

        return response()->json(
            (new EmployeeServiceLogic())->store($request->all())
        );
    }

    public function actionGetServices(array $param = []) {
        $this->params = (empty($param)) ? $this->params : $param;
        return (new EmployeeServiceLogic())->setJoin('Service')->getList();

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
        return response()->json($result);
    }
}
