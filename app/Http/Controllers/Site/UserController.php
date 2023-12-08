<?php

namespace App\Http\Controllers\Site;

use App\Models\CoreEngine\LogicModels\User\UserLogic;
use App\Models\CoreEngine\ProjectModels\Company\Company;
use App\Models\CoreEngine\ProjectModels\Employee\Employee;
use App\Models\CoreEngine\ProjectModels\HelpData\City;
use App\Models\CoreEngine\ProjectModels\HelpData\District;
use App\Models\CoreEngine\ProjectModels\HelpData\State;
use App\Models\CoreEngine\ProjectModels\HelpData\Country;
use App\Models\CoreEngine\ProjectModels\User\UserEntity;
use App\Models\CoreEngine\ProjectModels\User\UserType;
use App\Models\System\ControllersModel\CentralController;
use App\Models\System\General\Routs;
use App\Models\System\SystemLog;
use Illuminate\Routing\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\File;

class UserController extends CentralController
{
    const EMPLOYEE_TYPE_ID = 2;
    const MAX_FILE_SIZE = 5;

    const ENTITIES = [
        'city',
        'state',
        'country',
        'district',
    ];

    public function getPageParams(): array
    {
        return [];
    }

    public function callAction($method, $parameters)
    {
        if (
            (Auth::check() && $method !== 'actionLogout')
            || (!Auth::check() && $method === 'actionLogout')
        ) {
            return redirect('/main');
        }

        return parent::callAction($method, $parameters);
    }

    public function actionLogin(Request $request)
    {
        if ($request->isMethod('post')) {
            $credentials = $request->validate([
                'phone_number' => 'required|string',
                'password' => 'required|string',
            ]);

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                return redirect()->intended('/main');
            }

            return back()->withErrors([
                'password' => 'Неправильный номер телефона или пароль'
            ])->onlyInput('phone_number', 'password');
        }

        return view('lawyers.user.login');
    }

    public function actionLogout()
    {
        Auth::logout();
        return redirect('/main');
    }

    // FIXME: dry
    public function actionSignupClient()
    {
        $plural_entities = [];

        foreach (self::ENTITIES as $entity) {
            $plural_entities[] = $plural_entity = Str::plural($entity);
            $class_name = 'App\Models\CoreEngine\ProjectModels\HelpData\\' . ucfirst($entity);
            $$plural_entity = $class_name::all();
        }

        return view('lawyers.user.signup-client', compact($plural_entities));
    }

    // FIXME: dry
    public function actionSignupEmployee()
    {
        $plural_entities = [];

        foreach (self::ENTITIES as $entity) {
            $plural_entities[] = $plural_entity = Str::plural($entity);
            $class_name = 'App\Models\CoreEngine\ProjectModels\HelpData\\' . ucfirst($entity);
            $$plural_entity = $class_name::all();
        }

        $companies = Company::all();
        $plural_entities[] = 'companies';

        return view('lawyers.user.signup-employee', compact($plural_entities));
    }

    public function actionStore(Request $request)
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
        ];

        $data = array_merge($request->all(), ['avatar_path' => null]);

        if (intval($request->input('type_id')) === self::EMPLOYEE_TYPE_ID) {
            $rules = array_merge($rules, [
                'avatar' => File::types(['jpg', 'png'])->max(1024 * self::MAX_FILE_SIZE),
                'avatar_path' => 'required|string|max:128|unique:' . Employee::class . ',avatar_path',
                'company_id' => 'required|integer',
                'license_number' => 'required|string|max:128',
                'dt_practice_start' => 'required|date',
                'consultation_price' => 'required|integer',
                'company_id' => 'required|integer|exists:' . Company::class . ',id',
            ]);

            if ($request->hasFile('avatar')) {
                $dir = 'public';
                $path = mb_substr($request->avatar->store($dir), strlen("$dir/"));
                $data['avatar_path'] = $path;
            }
        }

        if (($validator = Validator::make($data, $rules))->fails()) {
            Storage::delete("$dir/$path");

            return response()->json([
                'errors' => $validator->errors(),
            ]);
        }

        $user = (new UserLogic())->store($data);

        return response()->json($user);
    }
}
