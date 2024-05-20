<?php

namespace App\Models\CoreEngine\LogicModels\Vacancy;

use App\Models\CoreEngine\Core\CoreEngine;
use App\Models\CoreEngine\LogicModels\Another\FileSystemLogic;
use App\Models\CoreEngine\LogicModels\Employee\EmployeeLogic;
use App\Models\CoreEngine\LogicModels\File\FileLogic;
use App\Models\CoreEngine\ProjectModels\Chat\Chat;
use App\Models\CoreEngine\ProjectModels\Chat\ChatMessage;
use App\Models\CoreEngine\ProjectModels\Employee\EmployeeOfferResponse;
use App\Models\CoreEngine\ProjectModels\Employee\EmployeeRating;
use App\Models\CoreEngine\ProjectModels\File\File;
use App\Models\CoreEngine\ProjectModels\HelpData\City;
use App\Models\CoreEngine\ProjectModels\HelpData\Region;
use App\Models\CoreEngine\ProjectModels\Service\Service;
use App\Models\CoreEngine\ProjectModels\Service\ServiceType;
use App\Models\CoreEngine\ProjectModels\User\UserEntity;
use App\Models\CoreEngine\ProjectModels\Vacancy\Vacancy;
use App\Models\CoreEngine\Model\InformationCategoryName;
use App\Models\CoreEngine\ProjectModels\Vacancy\VacancyGroup;
use App\Models\CoreEngine\ProjectModels\Vacancy\VacancyOffer;
use App\Models\CoreEngine\ProjectModels\Vacancy\VacancyStatusLog;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class VacancyLogic extends CoreEngine
{
    private $helpEngine;

    CONST STATUS_NEW = 1;
    CONST STATUS_MODERATION = 2;
    CONST STATUS_LAWYER_ACCEPTANCE = 8;
    CONST STATUS_PAYED = 3;
    CONST STATUS_IN_PROGRESS = 4;
    CONST STATUS_INSPECTION = 5;
    CONST STATUS_REWORK = 9;
    CONST STATUS_ACCEPTED = 6;
    CONST STATUS_CLOSED = 7;
    CONST STATUS_CANCELLED = 10;

    public function __construct($params = [], $select = ['*'], $callback = null)
    {
        $this->params = $params;
        $this->engine = new Vacancy();
        $this->helpEngine['status_log'] = self::createTempLogic(new VacancyStatusLog());
        $this->helpEngine['closing_message'] = self::createTempLogic(new VacancyClosing());
        $this->helpEngine['employee_rating'] = self::createTempLogic(new EmployeeRating());
        $this->query = $this->engine->newQuery();
        $this->getFilter();
        $this->compileGroupParams();

        parent::__construct($params, $select);
    }

    protected function defaultSelect(): array
    {
        $tab = $this->engine->tableName();
        $this->default = [];

        return $this->default;
    }

    public function getOne() {
        $result = parent::getOne();
        if (isset($result['status_history'])) {
            $result['status_history'] = json_decode($result['status_history'], true);
        }
        return $result;
    }

    public function store(array $data): array|bool {
        if (empty($data)) return false;
        try {
            $vacancy = array_intersect_key(
                $data,
                array_flip($this->engine->getFillable())
            );
            $vacancy = setTimestamps($vacancy, 'update');
            if (isset($data['id'])) {
                $vacancy['id'] = $data['id'];
            }
            if ($data['id'] = $this->save($vacancy)) {
                $data['file_type'] = FileLogic::TYPE_VACANCY;
                $data = (new FileLogic())->store($data, FileLogic::FILE_VACANCY);

                if (isset($vacancy['id'])) {
                    if (isset($data['status'])) {
                        $this->addToStatusLog($data, $data['status']);
                    }
                } else {
                    $this->addToStatusLog($data, self::STATUS_NEW);
                    if (isset($data['executor_id'])) {
                        unset($data['files']);
                        $this->setExecutor($data);
                    }
                }


                return $data;
            }

        } catch (\Throwable $e) {
        }

        return false;
    }

    public function setExecutor($data) {
        if ($this->payToLawyer($data)) {
            if (!isset($data['id']) && isset($data['vacancy_id'])) {
                $data['id'] = $data['vacancy_id'];
            }
            $data['status'] = self::STATUS_LAWYER_ACCEPTANCE;
            return $this->store($data);
        }
        return false;
    }

    public function acceptWorkDone($vacancyId) {
        $data['id'] = $vacancyId;
        $data['status'] = self::STATUS_ACCEPTED;
        $data['period_end'] = Carbon::now()->toDateTimeString();
        $res = $this->store($data);
        if ($res) {
            // кинуть оповещение юристу о принятии
            return $res;
        }
        return false;
    }

    public function sendToRework($data) {
        $data['id'] = $data['vacancy_id'];
        $data['status'] = self::STATUS_REWORK;
        // Отправить юристу уведомление, что заказ отдан на доработку
        $data = $this->store($data);
        $data['status'] = self::STATUS_IN_PROGRESS;
        return $this->store($data);
    }

    protected function addToStatusLog($data, $status) {
        if (empty($data)) return false;
        if (!isset($data['vacancy_id'])) {
            $data['vacancy_id'] = $data['id'];
            unset($data['id']);
        }
        $data = setTimestamps($data, 'create');
        $logRow = array_intersect_key(
            $data,
            array_flip($this->helpEngine['status_log']->getEngine()->getFillable())
        );
        $logRow['status'] = $status;
        return $this->helpEngine['status_log']->insert($logRow);
    }

    public function deleteVacancy(array $data): bool {
        if (empty($data) || !isset($data['vacancy_id'])) {
            return false;
        }
        $vacancyId = $data['vacancy_id'];
        $vacancy = (new VacancyLogic(['id' => $vacancyId]))->getOne();
        if ($vacancy['status'] == self::STATUS_PAYED) {
            $this->makeRefund($vacancy['payment']);
        }
        $vacancyFiles = (new FileLogic(['path_start' => 'vacancy/' . $vacancyId]))->getList()['result'];
        if (!empty($vacancyFiles)) {
            foreach ($vacancyFiles as $vacancyFile) {
                Storage::delete($vacancyFile['path']);
            }
        }
        if (empty($vacancy)) {
            return false;
        }
        return $this->deleteForeva($vacancyId);
    }

    public function makeRefund($amount) {
        // возвращение денег на счет
    }

    public function sendClosingDocs($data) {
        $data = setTimestamps($data, 'update');
        $closingMessage = array_intersect_key(
            $data,
            array_flip($this->helpEngine['closing_message']->getEngine()->getFillable())
        );

        if ($data['closing_message_id'] = $this->helpEngine['closing_message']->save($closingMessage)) {
            $data['file_type'] = FileLogic::TYPE_VACANCY_CLOSING;
            $this->addToStatusLog($data, self::STATUS_INSPECTION);
            $this->setVacancyStatus($data['vacancy_id'], self::STATUS_INSPECTION);
            return (new FileLogic())->store($data, FileLogic::FILE_VACANCY);
        }
        return false;
    }

    public function payToLawyer($data)
    {
        // сама оплата
        if (!isset($data['id']) && isset($data['vacancy_id'])) {
            $data['id'] = $data['vacancy_id'];
        }
        $data['status'] = self::STATUS_PAYED;
        return $this->store($data);
    }

    protected function setVacancyStatus($vacancyId, $status) {
        $data['id'] = $vacancyId;
        $data['status'] = $status;
        return $this->store($data);
    }

    public function getVacancyList($data) {
        $select = [
            'id', 'title', 'description', 'payment', 'status', 'period_start', 'period_end',
            DB::raw("(DATEDIFF(period_end, NOW())) as days_to_end"),
            DB::raw("CONCAT(Region.name, ', ', City.name) as location"),
            DB::raw("(CASE
        WHEN TIMESTAMPDIFF(MINUTE, vacancy.created_at, NOW()) < 60 THEN CONCAT(TIMESTAMPDIFF(MINUTE, vacancy.created_at, NOW()), ' минут назад')
        WHEN TIMESTAMPDIFF(HOUR, vacancy.created_at, NOW()) < 24 THEN CONCAT(TIMESTAMPDIFF(HOUR, vacancy.created_at, NOW()), ' часов назад')
        ELSE CONCAT(TIMESTAMPDIFF(DAY, vacancy.created_at, NOW()), ' дней назад')
        END) AS time_ago"),
            DB::raw("CASE WHEN status = 1 THEN 'создан'
                    WHEN status = 2 THEN 'на модерации'
                    WHEN status = 3 THEN 'оплачен'
                    WHEN status = 4 THEN 'в работе'
                    WHEN status = 5 THEN 'на проверке'
                    WHEN status = 6 THEN 'принят'
                    WHEN status = 7 THEN 'закрыт'
                    WHEN status = 8 THEN 'ожидает подтверждения'
                    WHEN status = 9 THEN 'на доработке'
                    WHEN status = 10 THEN 'отменен'
                    END as status_text"),
            DB::raw("CONCAT(Executor.last_name, ' ', Executor.first_name, ' ', Executor.middle_name ) as executor_name"),
            DB::raw("CONCAT(Owner.last_name, ' ', Owner.first_name, ' ', Owner.middle_name ) as owner_name"),
        ];
        $countNew = 0;
        $list = (new VacancyLogic(['status' => '1'], $select))
            ->setJoin(['Region', 'City', 'Executor', 'Owner', 'VacancyOffer'])->order('desc', 'id')->getList()['result'];
        foreach ($list as $item) {
            if ($item['lawyers_offers']) {
                foreach (json_decode($item['lawyers_offers'], true) as $offer) {
                    if (\auth()->id() == $offer['employee_user_id']) {
                        $countNew++;
                    }
                }

            }
        }
        if (isset($data['status']) && $data['status'] == 1) {
            unset($data['executor_id']);
            $res = (new VacancyLogic($data, $select))
                ->setJoin(['Region', 'City', 'Executor', 'Owner', 'VacancyOfferFromExactEmployee', 'VacancyOffer'])->order('desc', 'id')->getList();;
        } else {
            $res = (new VacancyLogic($data, $select))
                ->setJoin(['Region', 'City', 'Executor', 'Owner', 'VacancyOffer'])->order('desc', 'id')->getList();
        }

        foreach ($res['result'] as $index => $value) {
            $res['result'][$index]['auth_id'] = \auth()->id();
        }

        $res['count_new'] = $countNew;
        return $res;

    }

    public function getVacancyForResponse($data) {
        $select = [
            'id', 'title', 'description', 'payment', 'status', 'period_start', 'period_end', 'created_at', 'updated_at',
            DB::raw("CASE WHEN status = 1 THEN 'создан'
                    WHEN status = 2 THEN 'на модерации'
                    WHEN status = 3 THEN 'оплачен'
                    WHEN status = 4 THEN 'в работе'
                    WHEN status = 5 THEN 'на проверке'
                    WHEN status = 6 THEN 'принят'
                    WHEN status = 7 THEN 'закрыт'
                    END as current_status_text"),
            DB::raw("Service.name as service_name"),
            DB::raw("CONCAT(Region.name,' ', City.name) as location"),
            DB::raw("(CASE
                                WHEN DATEDIFF(period_end, NOW()) < 0 THEN 0
                                ELSE DATEDIFF(period_end, NOW())
                             END) as days_to_end"),
            DB::raw("CONCAT(Owner.last_name, ' ', Owner.first_name) as owner_name"),
            DB::raw("Owner.online as owner_online"),
            DB::raw("Executor.id as executor_id"),
        ];
        $result = (new VacancyLogic($data, $select))->setJoin(['Service', 'Region', 'City', 'Owner', 'Status', 'Executor', 'Files'])->getOne();
        $result['files'] = json_decode($result['files'], true);
        Carbon::setLocale('ru');
        $result['time_ago'] = max(Carbon::make($result['created_at'])->diffForHumans(), 0);
        $result['time_left_to_accept'] = max(Carbon::now()->diffInHours(Carbon::make($result['updated_at'])->addHours(24), false), 0);
        return ['result' => $result];
    }

    public function getVacancyLastStatus($vacancyId) {
        $x = VacancyStatusLog::where('vacancy_id', $vacancyId)->get();
        $x->each(function($item) {
            if (in_array($item->status, [self::STATUS_LAWYER_ACCEPTANCE, self::STATUS_PAYED,])) {
                $item->delete();
            }
        });
        dd($x);
    }

    public function getClosingMessage($data) {
        $select = [
            DB::raw("ClosingMessage.*"),
            DB::raw("CONCAT(Executor.last_name, ' ', Executor.first_name) as executor_name"),
        ];

        $res = (new VacancyLogic(['id' => (string) $data['id']], $select))->setJoin(['ClosingMessage', 'ClosingMessageFiles', 'Executor'])->getOne();
        $res['files'] = json_decode($res['files'], true);
        $res['time'] = Carbon::make($res['updated_at'])->toTimeString('minutes');
        return $res;
    }

    public function rateWork($data) {
        if ($this->acceptWorkDone($data['vacancy_id'])) {
            $data = setTimestamps($data, 'update');
            $ratingRow = array_intersect_key(
                $data,
                array_flip($this->helpEngine['employee_rating']->getEngine()->getFillable()));
            if ($data['employee_rating_id'] = $this->helpEngine['employee_rating']->save($ratingRow)) {
                if (isset($data['files'])) {
                    $data['file_type'] = FileLogic::TYPE_RATING;
                    $data = (new FileLogic())->store($data, FileLogic::FILE_RATING);
                }
                $this->setVacancyStatus($data['vacancy_id'], self::STATUS_CLOSED);
                return $data;
            }
        }
        return false;
    }

    protected function getFilter(): array
    {
        $tab = $this->engine->getTable();
        $this->filter = [
            [   'field' => $tab.'.user_id','params' => 'user_id',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
            ],
            [   'field' => $tab.'.status','params' => 'status',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => '=', 'concat' => 'AND',
            ],
            [   'field' => $tab.'.status','params' => 'except_status',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => 'NOT IN', 'concat' => 'AND',
            ],
            [   'field' => $tab.'.executor_id','params' => 'executor_id',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => '=', 'concat' => 'AND',
            ],
            [   'field' => $tab.'.executor_id IS NOT NULL','params' => 'has_executor',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => '=', 'concat' => 'AND',
            ],
            [   'field' => $tab.'.executor_id IS NULL','params' => 'has_no_executor',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => '=', 'concat' => 'AND',
            ],
            [   'field' => $tab.'.is_group','params' => 'is_group',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => '=', 'concat' => 'AND',
            ],
            [   'field' => $tab.'.is_public','params' => 'is_public',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => '=', 'concat' => 'AND',
            ],
            [   'field' => $tab.'.period_start','params' => 'start_date',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => '>=', 'concat' => 'AND',
            ],
            [   'field' => $tab.'.period_end','params' => 'end_date',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => '<=', 'concat' => 'AND',
            ],
            [   'field' => $tab.'.region_id','params' => 'region_id',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
            ],
            [   'field' => $tab.'.city_id','params' => 'city_id',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
            ],
            [   'field' => $tab.'.service_id','params' => 'service_id',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
            ],
            [   'field' => $tab.'.title','params' => 'search_spec',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => '%LIKE%', 'concat' => 'AND',
            ],
            [   'field' => 'Vacancy.is_appruv','params' => 'is_approved',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => '=', 'concat' => 'AND',
            ],
        ];
        $this->filter = array_merge($this->filter, parent::getFilter());

        return $this->filter;
    }

    protected function compileGroupParams(): array
    {
        $userId = $this->params['user_id'] ?? '';
        $currentUserId = (string)\auth()->id();
        $vacancyId = $this->params['id'] ?? '';
        $this->group_params = [
            'select' => [],
            'by' => [],
            'relatedModel' => [
                'Executor' => [
                    'entity' => new UserEntity(),
                    'relationship' => ['id', 'executor_id'],
                    'field' => []
                ],
                'VacancyGroup' => [
                    'entity' => DB::raw("(SELECT JSON_ARRAYAGG(
                                JSON_OBJECT('id', id, 'is_appruv', is_appruv, 'user_id', user_id)) as group_users,
                                JSON_LENGTH(JSON_ARRAYAGG(JSON_OBJECT('id', id, 'is_appruv', is_appruv,
                                'user_id', user_id))) as count_group_users,
                                vacancy_id FROM vacancy_group GROUP BY vacancy_id) as VacancyGroup ON VacancyGroup.vacancy_id = vacancy.id"),
                    'field' => ['group_users', 'count_group_users']
                ],
                'VacancyGroupForApprove' => [
                    'entity' => DB::raw("(SELECT
                                JSON_LENGTH(JSON_ARRAYAGG(JSON_OBJECT('id', id, 'is_appruv', is_appruv,
                                'user_id', user_id))) as count_not_approved,
                                vacancy_id FROM vacancy_group WHERE is_appruv = 0 GROUP BY vacancy_id) as VacancyGroupForApprove ON VacancyGroupForApprove.vacancy_id = vacancy.id"),
                    'field' => ['count_not_approved']
                ],
//                'VacancyOffer' => [
//                    'entity' => new VacancyOffer(),
//                    'relationship' => ['vacancy_id', 'id'],
//                    'field' => []
//                ],
                'VacancyOffer' => [
                    'entity' => DB::raw("(SELECT JSON_ARRAYAGG(
                                JSON_OBJECT('id', id, 'payment', payment, 'employee_response_id',
                                employee_response_id, 'employee_user_id', employee_user_id)) as lawyers_offers,
                                JSON_LENGTH(JSON_ARRAYAGG(JSON_OBJECT('id', id, 'payment', payment,
                                'employee_response_id', employee_response_id))) as count_offers,
                                vacancy_id FROM vacancy_offer GROUP BY vacancy_id) as VacancyOffer ON VacancyOffer.vacancy_id = vacancy.id"),
                    'field' => ['lawyers_offers', 'count_offers']
                ],
                'VacancyOfferFromExactEmployee' => [
                    'entity' => DB::raw("(SELECT JSON_ARRAYAGG(
                                JSON_OBJECT('id', id, 'payment', payment, 'employee_response_id',
                                employee_response_id, 'employee_user_id', employee_user_id)) as my_offer,
                                vacancy_id FROM vacancy_offer WHERE employee_user_id = $currentUserId
                                 GROUP BY vacancy_id) as VacancyOfferFromExactEmployee ON VacancyOfferFromExactEmployee.vacancy_id = vacancy.id"),
                    'type' => 'inner',
                    'field' => ['my_offer']
                ],
                'Service' => [
                    'entity' => new Service(),
                    'relationship' => ['id', 'service_id'],
                    'field' => []
                ],
                'ServiceType' => [
                    'entity' => new ServiceType(),
                    'relationship' => ['id', 'Service.type_id'],
                    'field' => ['id as service_type_id']
                ],
                'Chat' => [
                    'entity' => new Chat(),
                    'relationship' => ['id', 'chat_id'],
                    'field' => []
                ],
//                'ChatMessage' => [
//                    'entity' => new ChatMessage(),
//                    'relationship' => ['chat_id', 'Chat.id'],
//                    'relationship_more' => ['target_user_id', $this->params['user_id']],
//                    'field' => []
//                ],
                'ChatMessage' => [
                    'entity' => DB::raw("(SELECT JSON_ARRAYAGG(
                    JSON_OBJECT('message', message, 'sender_user_id', sender_user_id, 'target_user_id',
                    target_user_id)) as messages, chat_id FROM chat_message WHERE target_user_id = {$userId}
                    GROUP BY chat_id) as ChatMessage ON ChatMessage.chat_id = vacancy.chat_id"),
                    'field' => ['messages']
                ],
                'Region' => [
                    'entity' => new Region(),
                    'relationship' => ['id', 'region_id'],
                    'field' => []
                ],
                'City' => [
                    'entity' => new City(),
                    'relationship' => ['id', 'city_id'],
                    'field' => []
                ],
                'Status' => [
                    'entity' => DB::raw("(SELECT JSON_ARRAYAGG(
                    JSON_OBJECT('status',
                    CASE WHEN status = 1 THEN 'создан'
                    WHEN status = 2 THEN 'на модерации'
                    WHEN status = 3 THEN 'оплачен'
                    WHEN status = 4 THEN 'в работе'
                    WHEN status = 5 THEN 'на проверке'
                    WHEN status = 6 THEN 'принят'
                    WHEN status = 7 THEN 'закрыт'
                    WHEN status = 8 THEN 'ожидает принятия'
                    END,
                    'id', id, 'status_code', status, 'time', DATE_FORMAT(created_at, '%H:%i'), 'date', DATE_FORMAT(created_at, '%e %M'))) as status_history, vacancy_id
                    FROM vacancy_status_log GROUP BY vacancy_id) as Status ON Status.vacancy_id = vacancy.id"),
                    'field' => ['status_history'],
                ],
                'Owner' => [
                    'entity' => new UserEntity(),
                    'relationship' => ['id', 'user_id'],
                    'field' => []
                ],
                'Files' => [
                    'entity' => DB::raw("(SELECT JSON_ARRAYAGG(
                                JSON_OBJECT('id', id, 'path', path, 'name',
                                name)) as files,
                                vacancy_id FROM file GROUP BY vacancy_id) as Files ON Files.vacancy_id = vacancy.id"),
                    'field' => ['files']
                ],
                'ClosingMessage' => [
                    'entity' => new VacancyClosing(),
                    'relationship' => ['vacancy_id', 'id'],
                    'field' => []
                ],
                'ClosingMessageFiles' => [
                    'entity' => DB::raw("(SELECT JSON_ARRAYAGG(
                                JSON_OBJECT('id', id, 'path', path, 'name',
                                name)) as files,
                                vacancy_id FROM file WHERE type = 2 GROUP BY vacancy_id) as ClosingMessageFiles ON ClosingMessageFiles.vacancy_id = vacancy.id"),
                    'field' => ['files']
                ],
            ]
        ];

        return $this->group_params;
    }

    public function triggerVacancyCancellationWhenLawyerNotAccept() {
        $params = [
            'status' => (string) self::STATUS_LAWYER_ACCEPTANCE,
        ];
        $vacancyList = (new VacancyLogic($params))->offPagination()->setLimit(false)->getList()['result'];
        if (!empty($vacancyList)) {
            foreach ($vacancyList as $vacancy) {
                if (Carbon::now()->diffInMinutes(Carbon::make($vacancy['updated_at'])->addHours(24), false) <= 0) {
//                (new EmployeeLogic())->declineWork([$vacancy['id'], $vacancy['executor_id']]);
                    $this->deleteVacancy(['vacancy_id' => (string) $vacancy['id']]);
                    Log::build([
                        'driver' => 'daily',
                        'path' => storage_path('/logs/vacancy_cron.log')
                    ])->info('Вакансия с id ' . $vacancy['id'] . ' удалена, так как юрист не принял ее в работу!');
                }
            }
        }
    }

    public function triggerVacancyApprovingWhenClientNotAccept() {
        $params = [
            'status' => (string) self::STATUS_INSPECTION,
        ];
        $vacancyList = (new VacancyLogic($params))->offPagination()->setLimit(false)->getList()['result'];
        if (!empty($vacancyList)) {
            foreach ($vacancyList as $vacancy) {
                if (Carbon::now()->diffInMinutes(Carbon::make($vacancy['updated_at'])->addHours(24), false) <= 0) {
                    $this->acceptWorkDone($vacancy['id']);
                    Log::build([
                        'driver' => 'daily',
                        'path' => storage_path('/logs/vacancy_cron.log')
                    ])->info('Вакансия с id ' . $vacancy['id'] . ' автоматически принята, так как пользователь не сделал этого сам!');
                }
            }
        }
    }
}
