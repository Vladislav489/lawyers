<?php

namespace App\Http\Mainstay\Employee;

use App\Models\CoreEngine\LogicModels\Employee\EmployeeLogic;
use App\Models\CoreEngine\LogicModels\Employee\EmployeeServiceLogic;
use App\Models\CoreEngine\ProjectModels\Company\Company;
use App\Models\CoreEngine\ProjectModels\Employee\Employee;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\File;
use Illuminate\Database\Query\JoinClause;

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
            ];

        $validated = Validator::validate($this->params, $rules);
        dd($validated);
        return response()->json((new EmployeeLogic())->store($validated));
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

    public function actionGetServices() {
        return response()->json(DB::table('service')
            ->select(['service.*', 'user_employee_service.is_main', 'user_employee_service.user_id'])
            ->leftJoin('user_employee_service', function (JoinClause $join) {
                $join->on('user_employee_service.service_id', '=', 'service.id');
                $join->where('user_employee_service.user_id', '=', Auth::id());
            })
            ->limit(100)
            ->get());
    }

    public function actionGetUserServiceIds() {
        return response()->json(
            array_column(Auth::user()->services->toArray(), 'service_id')
        );
    }

    public function actionGetEmployeeList() {
        // return response()->json((new EmployeeLogic())->getList());

        return response()->json(DB::table('user_employee')
            ->select('user_entity.first_name', 'user_employee.avatar_path')
            ->leftJoin('user_entity', function (JoinClause $join) {
                $join->on('user_entity.id', '=', 'user_employee.user_id');
            })
            ->limit(100)
            ->get());
    }
}
