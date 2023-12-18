<?php

namespace App\Http\Mainstay\Client;

use App\Models\CoreEngine\LogicModels\User\UserLogic;
use App\Models\CoreEngine\LogicModels\Vacancy\VacancyLogic;
use App\Models\CoreEngine\ProjectModels\HelpData\City;
use App\Models\CoreEngine\ProjectModels\HelpData\Country;
use App\Models\CoreEngine\ProjectModels\HelpData\District;
use App\Models\CoreEngine\ProjectModels\HelpData\State;
use App\Models\CoreEngine\ProjectModels\User\UserEntity;
use App\Models\CoreEngine\ProjectModels\User\UserType;
use App\Models\CoreEngine\ProjectModels\Vacancy\Vacancy;
use App\Models\System\ControllersModel\MainstayController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ClientMainstayController extends MainstayController
{
    public function callAction($method, $parameters)
    {
        // TODO: rest-аутентификация
        if (!Auth::check() || Auth::user()->type->name !== 'client') {
            // return response()->json(['message' => 'forbidden']);
        }

        return parent::callAction($method, $parameters);
    }

    public function actionStoreClient(Request $request)
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

        if (($validator = Validator::make($request->all(), $rules))->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        }

        return response()->json(
            (new UserLogic())->store($request->all())
        );
    }

    public function actionDeleteVacancy(Request $request)
    {
        if ($request->isMethod('delete')) {
            return response()->json(
                (new VacancyLogic())->deleteVacancy($request->all())
            );
        }
    }

    public function actionStoreVacancy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required|string',
            'payment' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ]);
        }

        $request->merge([
            'user_id' => Auth::id(),
            'defendant' => json_encode([])
        ]);

        return response()->json(
            (new VacancyLogic())->store($request->all())
        );
    }

    public function actionGetVacancy(Request $request)
    {
        return response()->json(
            Vacancy::find($request->input('id'))
        );
    }
}
