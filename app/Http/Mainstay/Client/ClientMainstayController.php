<?php

namespace App\Http\Mainstay\Client;

use App\Http\Login\LoginController;
use App\Models\CoreEngine\LogicModels\User\UserLogic;
use App\Models\CoreEngine\ProjectModels\HelpData\City;
use App\Models\CoreEngine\ProjectModels\HelpData\Country;
use App\Models\CoreEngine\ProjectModels\HelpData\District;
use App\Models\CoreEngine\ProjectModels\HelpData\State;
use App\Models\CoreEngine\ProjectModels\User\UserEntity;
use App\Models\CoreEngine\ProjectModels\User\UserType;
use App\Models\System\ControllersModel\MainstayController;
use Illuminate\Support\Facades\Validator;

class ClientMainstayController extends MainstayController {
    public function actionStoreClient(array $param = []) {
        $this->params = (empty($param)) ? $this->params : $param;
        $rules = [
            'email' => 'required|string|max:128|email|unique:' . UserEntity::class . ',email',
            'phone_number' => 'required|string|max:128|unique:' . UserEntity::class . ',phone_number',
            'password' => 'required|string|confirmed',
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

        $validated = Validator::validate($this->params, $rules);
        if ($data = (new UserLogic())->save($validated)) {
            $credentials = ['phone_number' => $data['phone_number'], 'password' => $data['input_password']];
            return (new LoginController())->actionUserLogin($credentials);
        }
        return redirect()->back();
    }
}
