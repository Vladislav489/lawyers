<?php

namespace App\Http\Mainstay\Client;

use App\Http\Login\LoginController;
use App\Models\CoreEngine\LogicModels\Question\QuestionLogic;
use App\Models\CoreEngine\LogicModels\User\UserLogic;
use App\Models\CoreEngine\LogicModels\Vacancy\VacancyLogic;
use App\Models\CoreEngine\ProjectModels\HelpData\City;
use App\Models\CoreEngine\ProjectModels\HelpData\Country;
use App\Models\CoreEngine\ProjectModels\HelpData\District;
use App\Models\CoreEngine\ProjectModels\HelpData\State;
use App\Models\CoreEngine\ProjectModels\User\UserEntity;
use App\Models\CoreEngine\ProjectModels\User\UserType;
use App\Models\System\ControllersModel\MainstayController;
use Illuminate\Support\Facades\DB;
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

    public function actionUpdateClient($param = []) {
        $this->params = (empty($param)) ? $this->params : $param;
        (new UserLogic())->save($this->params);
        return $this->actionGetClient(['id' => (string)auth()->id()]);
    }

    public function actionGetClient($param = []) {
        $this->params = (empty($param)) ? $this->params : $param;
        $select = ['*', DB::raw("CONCAT(last_name, ' ', first_name, ' ', middle_name) as full_name"),
            DB::raw("City.name as city_name, Country.name as country_name"),
            DB::raw("IFNULL(Balance.balance, 0) as balance"),
            DB::raw("CONCAT('[', GROUP_CONCAT(JSON_OBJECT('id', Question.id, 'text', Question.text, 'status', Question.status)), ']') as questions"),
//            DB::raw("CONCAT('[', GROUP_CONCAT(JSON_OBJECT('id', Vacancy.id, 'text', Question.text, 'status', Question.status)), ']') as questions")
        ];
        return response()->json((new UserLogic($this->params, $select))->offPagination()->setLimit(false)
            ->setJoin(['City', 'Country', 'Balance', 'Question'])->getOne());
    }

    public function actionGetClientQuestions($param = []) {
        $this->params = (empty($param)) ? $this->params : $param;
        $select = ['*', DB::raw("IFNULL(COUNT(Answer.id), 0) as count_answers")];
        return response()->json((new QuestionLogic($this->params, $select))->setJoin('Answer')->getList());
    }

    public function actionGetVacancies($param = []) {
        $this->params = (empty($param)) ? $this->params : $param;
        $select = ['*',
//            DB::raw("JSON_ARRAYAGG(JSON_OBJECT('id', VacancyOffer.id, 'payment', VacancyOffer.payment, 'employee_response_id',
//            VacancyOffer.employee_response_id)) as lawyers_offers"),
//            DB::raw("IF(VacancyOffer.id IS NULL, NULL,
//            CONCAT('[', GROUP_CONCAT(DISTINCT
//            (JSON_OBJECT('id', VacancyOffer.id, 'payment', VacancyOffer.payment, 'employee_response_id',
//             VacancyOffer.employee_response_id)), ']'))) as lawyers_offers"),
//            DB::raw("IF(ChatMessage.id IS NULL, NULL, CONCAT('[', GROUP_CONCAT(DISTINCT(JSON_OBJECT('id', ChatMessage.id, 'message', ChatMessage.message, 'sender_user_id', ChatMessage.sender_user_id)), ']'))) as chat_messages"),
//            DB::raw("IF(GroupVacancy.id IS NULL, NULL, CONCAT('[', GROUP_CONCAT(DISTINCT(JSON_OBJECT('user_id', GroupVacancy.user_id, 'is_appruv', GroupVacancy.is_appruv)), ']'))) as vacancy_group"),
            ];
        return response()->json((new VacancyLogic($this->params, $select))->setJoin(['VacancyOffer', 'ChatMessage', 'VacancyGroup', 'VacancyGroupForApprove'])->getList());
    }
}

