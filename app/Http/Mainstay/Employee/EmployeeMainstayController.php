<?php

namespace App\Http\Mainstay\Employee;

use App\Models\CoreEngine\LogicModels\Employee\EmployeeLogic;
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

class EmployeeMainstayController extends MainstayController
{
    const MAX_FILE_SIZE = 5;

    public function callAction($method, $parameters)
    {
        // TODO: rest-аутентификация
        if (!Auth::check() || Auth::user()->type->name !== 'client') {
            // return response()->json(['message' => 'forbidden']);
        }

        return parent::callAction($method, $parameters);
    }

    public function actionStoreEmployee(Request $request)
    {
        $rules = [
            'email' => 'required|string|max:128|email|unique:' . UserEntity::class . ',email',
            'phone_number' => 'required|string|max:128|unique:' . UserEntity::class . ',phone_number',
            'password' => 'required|string',
            'first_name' => 'required|string|max:64',
            'last_name' => 'required|string|max:64',
            'middle_name' => 'required|string|max:64',
            'post_code' => 'required|string|max:7',
            'date_birthday' => 'required|date',
            'city_id' => 'required|integer|exists:' . City::class . ',id',
            'state_id' => 'required|integer|exists:' . State::class . ',id',
            'country_id' => 'required|integer|exists:' . Country::class . ',id',
            'district_id' => 'required|integer|exists:' . District::class . ',id',
            'type_id' => 'required|integer|exists:' . UserType::class . ',id',

            'avatar' => File::types(['jpg', 'png'])->max(1024 * self::MAX_FILE_SIZE),
            'avatar_path' => 'required|string|max:128|unique:' . Employee::class . ',avatar_path',
            'license_number' => 'required|string|max:128',
            'dt_practice_start' => 'required|date',
            'consultation_price' => 'required|integer',
            'company_id' => 'required|integer|exists:' . Company::class . ',id',
        ];

        if ($request->hasFile('avatar')) {
            $dir = 'public';
            $path = mb_substr($request->avatar->store($dir), strlen("$dir/"));
            $request->merge(['avatar_path' => $path]);
        }

        if (($validator = Validator::make($request->all(), $rules))->fails()) {
            if (isset($dir, $path)) {
                Storage::delete("$dir/$path");
            }

            return response()->json([
                'errors' => $validator->errors(),
            ]);
        }

        return response()->json(
            (new EmployeeLogic())->storeEmployee($request->all())
        );
    }

    public function actionStoreEmployeeService(Request $request)
    {
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
            (new EmployeeLogic())->storeEmployeeServices($data)
        );
    }

    public function actionUpdateEmployeeService(Request $request)
    {
        $rules = [
            'is_main' => 'boolean',
            'price' => "exclude_if:is_main,'1'|required|integer",
            'description' => "exclude_if:is_main,'1'|required|string",
            'service_id' => 'required|integer|exists:' . EmployeeService::class . ',id',
        ];

        if (($validator = Validator::make($request->all(), $rules))->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ]);
        }

        return response()->json(
            (new EmployeeLogic())->updateEmployeeService($request->all())
        );
    }

    public function actionGetServices()
    {
        return response()->json(
            DB::table('service')->limit(100)->get()
        );
    }

    public function actionGetUserServiceIds()
    {
        return response()->json(
            array_column(Auth::user()->services->toArray(), 'service_id')
        );
    }
}
