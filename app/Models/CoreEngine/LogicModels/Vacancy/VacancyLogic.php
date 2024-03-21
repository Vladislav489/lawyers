<?php

namespace App\Models\CoreEngine\LogicModels\Vacancy;

use App\Models\CoreEngine\Core\CoreEngine;
use App\Models\CoreEngine\LogicModels\Another\FileSystemLogic;
use App\Models\CoreEngine\LogicModels\File\FileLogic;
use App\Models\CoreEngine\ProjectModels\Chat\Chat;
use App\Models\CoreEngine\ProjectModels\Chat\ChatMessage;
use App\Models\CoreEngine\ProjectModels\File\File;
use App\Models\CoreEngine\ProjectModels\Service\Service;
use App\Models\CoreEngine\ProjectModels\User\UserEntity;
use App\Models\CoreEngine\ProjectModels\Vacancy\Vacancy;
use App\Models\CoreEngine\Model\InformationCategoryName;
use App\Models\CoreEngine\ProjectModels\Vacancy\VacancyGroup;
use App\Models\CoreEngine\ProjectModels\Vacancy\VacancyOffer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VacancyLogic extends CoreEngine
{
    CONST STATUS_NEW = 1;
    CONST STATUS_MODERATION = 2;
    CONST STATUS_OPEN = 3;
    CONST STATUS_CLOSE = 4;
    CONST STATUS_DISPUTE = 5;

    public function __construct($params = [], $select = ['*'], $callback = null)
    {
        $this->params = $params;
        $this->engine = new Vacancy();
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

    public function store(array $data): array|bool {
        if (empty($data)) return false;
        try {
            $vacancy = array_intersect_key(
                $data,
                array_flip($this->engine->getFillable())
            );

            if (isset($data['id'])) {
                $vacancy['id'] = $data['id'];
            }

            if ($data['id'] = $this->save($vacancy)) {
                $data = (new FileLogic())->store($data, FileLogic::FILE_VACANCY);
                return $data;
            }

        } catch (\Throwable $e) {
        }

        return false;
    }

    public function deleteVacancy(array $data): bool
    {
        try {
            // return Vacancy::find($data['id'])->delete();
            $vacancy = $this->setModel(new Vacancy());
            return $vacancy->delete($data['id']);
        } catch (\Throwable $e) {
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
            [   'field' => $tab.'.executor_id','params' => 'executor_id',
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
        $this->group_params = [
            'select' => [],
            'by' => [],
            'relatedModel' => [
                'Executor' => [
                    'entity' => new UserEntity(),
                    'relationship' => ['executor_id', 'id'],
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
                                employee_response_id)) as lawyers_offers,
                                JSON_LENGTH(JSON_ARRAYAGG(JSON_OBJECT('id', id, 'payment', payment,
                                'employee_response_id', employee_response_id))) as count_offers,
                                vacancy_id FROM vacancy_offer ) as VacancyOffer ON VacancyOffer.vacancy_id = vacancy.id"),
                    'field' => ['lawyers_offers', 'count_offers']
                ],
                'Service' => [
                    'entity' => new Service(),
                    'relationship' => ['id', 'service_id'],
                    'field' => ['name as service_name']
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
                    target_user_id)) as messages, chat_id FROM chat_message WHERE target_user_id = {$this->params['user_id']}
                    GROUP BY chat_id) as ChatMessage ON ChatMessage.chat_id = vacancy.chat_id"),
                    'field' => ['messages']
                ]
            ]
        ];

        return $this->group_params;
    }
}
