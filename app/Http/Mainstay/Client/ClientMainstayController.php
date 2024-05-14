<?php

namespace App\Http\Mainstay\Client;

use App\Http\Login\LoginController;
use App\Models\CoreEngine\LogicModels\Question\QuestionLogic;
use App\Models\CoreEngine\LogicModels\User\UserLogic;
use App\Models\CoreEngine\LogicModels\Vacancy\VacancyLogic;
use App\Models\CoreEngine\LogicModels\Vacancy\VacancyOfferLogic;
use App\Models\CoreEngine\ProjectModels\HelpData\City;
use App\Models\CoreEngine\ProjectModels\HelpData\Region;
use App\Models\CoreEngine\ProjectModels\User\UserEntity;
use App\Models\CoreEngine\ProjectModels\User\UserType;
use App\Models\CoreEngine\ProjectModels\Vacancy\Vacancy;
use App\Models\System\ControllersModel\MainstayController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ClientMainstayController extends MainstayController {

    public function actionStoreClient(array $param = []) {
        $this->params = (empty($param)) ? $this->params : $param;
//        dd($this->params, Region::class);
        $rules = [
            'email' => 'required|string|max:128|email|unique:' . UserEntity::class . ',email',
//            'phone_number' => 'required|string|max:128|unique:' . UserEntity::class . ',phone_number',
            'password' => 'required|string|confirmed',
            'first_name' => 'required|string|max:64',
            'last_name' => 'required|string|max:64',
            'middle_name' => 'nullable|string|max:64',
//            'post_code' => 'required|string|max:7',
            'date_birthday' => 'required|date',
            'city_id' => 'required|integer|exists:' . City::class . ',id',
            'region_id' => 'required|integer|exists:' . Region::class . ',id',
            'type_id' => 'required|integer|exists:' . UserType::class . ',id',
        ];

        $validated = Validator::validate($this->params, $rules);
//        $validated['modifier_id'] = 1;
        if ($data = (new UserLogic())->save($validated)) {
            $credentials = ['email' => $data['email'], 'password' => $data['input_password']];
            return (new LoginController())->actionUserLogin($credentials);
        }
        return redirect()->back()->withErrors($validated)->withInput();
    }

    public function actionUpdateClient($param = []) {
        $this->params = (empty($param)) ? $this->params : $param;
        (new UserLogic())->save($this->params);
        return $this->actionGetClient(['id' => (string)auth()->id()]);
    }

    public function actionGetClient($param = []) {
        $this->params = (empty($param)) ? $this->params : $param;
        $select = ['*', DB::raw("CONCAT(last_name, ' ', first_name, ' ', middle_name) as full_name"),
            DB::raw("City.name as city_name, Region.name as region_name"),
            DB::raw("CONCAT_WS(' ', last_name, first_name, middle_name) as full_name"),
            DB::raw("IFNULL(Balance.balance, 0) as balance"),
            DB::raw("CONCAT('[', GROUP_CONCAT(JSON_OBJECT('id', Question.id, 'text', Question.text, 'status', Question.status)), ']') as questions"),
//            DB::raw("CONCAT('[', GROUP_CONCAT(JSON_OBJECT('id', Vacancy.id, 'text', Question.text, 'status', Question.status)), ']') as questions")
        ];
//        dd((new UserLogic($this->params, $select))->offPagination()->setLimit(false)->setJoin(['City', 'Country', 'Balance', 'Question'])->getOne());
        return response()->json((new UserLogic($this->params, $select))->offPagination()->setLimit(false)
            ->setJoin(['City', 'Region', 'Balance', 'Question'])->getOne());
    }

    public function actionGetClientQuestions($param = []) {
        $this->params = (empty($param)) ? $this->params : $param;
        $select = ['*', DB::raw("IFNULL(COUNT(Answer.id), 0) as count_answers")];
        return response()->json((new QuestionLogic($this->params, $select))->setJoin('Answer')->getList());
    }

    public function actionGetVacancies($param = []) {
        $this->params = (empty($param)) ? $this->params : $param;
        $select = ['*',
            DB::raw("CASE WHEN status = 1 THEN 'создан'
                    WHEN status = 2 THEN 'на модерации'
                    WHEN status = 3 THEN 'оплачен'
                    WHEN status = 4 THEN 'в работе'
                    WHEN status = 5 THEN 'на проверке'
                    WHEN status = 6 THEN 'принят'
                    WHEN status = 7 THEN 'закрыт'
                    WHEN status = 8 THEN 'создан'
                    WHEN status = 9 THEN 'на доработке'
                    WHEN status = 10 THEN 'отменен'
                    END as status_text"),
            ];

        $result = (new VacancyLogic($this->params, $select))
            ->setJoin(['VacancyOffer', 'ChatMessage', 'VacancyGroup', 'VacancyGroupForApprove'])
            ->order('desc', 'id')->getList();

        $res = (new VacancyLogic(['user_id' => (string) auth()->id(), 'status' => '1'], $select))
            ->setJoin(['VacancyOffer', 'ChatMessage', 'VacancyGroup', 'VacancyGroupForApprove'])
            ->order('desc', 'id')->getList();

        $result['count_new'] = 0;

        foreach ($res['result'] as $item) {
            if ($item['status'] == 1) {
                if ($item['lawyers_offers']) {
                    $result['count_new']++;
                }
            }
        }

        return response()->json($result);
    }

    public function actionGetVacancy($param = []) {
        $this->params = empty($param) ? $this->params : $param;
        return response()->json((new VacancyLogic($this->params))->setJoin(['VacancyOffer', 'ChatMessage', 'VacancyGroup', 'VacancyGroupForApprove'])->getOne());
    }

    public function actionSetExecutorForVacancy($param = []) {
        $this->params = (empty($param)) ? $this->params : $param;
        $rules = [
            'vacancy_id' => 'required|integer|exists:vacancy,id',
            'executor_id' => 'required|integer|exists:user_entity,id',
            'payment' => 'required|integer'
        ];

        $data = Validator::validate($this->params, $rules);

        return response()->json((new VacancyLogic())->setExecutor($data));
    }

    public function actionSendToRework($param = []) {
        $this->params = (empty($param)) ? $this->params : $param;
        $rules = [
            'vacancy_id' => 'required|integer|exists:vacancy,id',
            'user_id' => 'required|integer|exists:user_entity,id'
        ];

        $data = Validator::validate($this->params, $rules);

        return response()->json((new VacancyLogic())->sendToRework($data));
    }

    public function actionAcceptAndRateWork($param = []) {
        $this->params = (empty($param)) ? $this->params : $param;
//        dd($this->params);
        $rules = [
            'vacancy_id' => 'required|integer|exists:vacancy,id',
            'employee_user_id' => 'required|integer|exists:user_entity,id',
            'text' => 'nullable|string',
            'rating' => 'required|integer',
            'files' => 'nullable|file'
        ];

        $data = Validator::validate($this->params, $rules);
        $data['user_id'] = auth()->id();
        return response()->json((new VacancyLogic())->rateWork($data));
    }
}

