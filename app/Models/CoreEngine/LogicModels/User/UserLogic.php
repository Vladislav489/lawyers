<?php

namespace App\Models\CoreEngine\LogicModels\User;

use App\Models\CoreEngine\Core\CoreEngine;
use App\Models\CoreEngine\ProjectModels\Chat\ChatMessage;
use App\Models\CoreEngine\ProjectModels\HelpData\City;
use App\Models\CoreEngine\ProjectModels\HelpData\Region;
use App\Models\CoreEngine\ProjectModels\Question\UserQuestion;
use App\Models\CoreEngine\ProjectModels\User\UserBalance;
use App\Models\CoreEngine\ProjectModels\User\UserEntity;
use App\Models\CoreEngine\ProjectModels\Vacancy\Vacancy;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\Console\Question\Question;

class UserLogic extends CoreEngine
{
    const EMPLOYEE_TYPE_ID = 2;

    public function __construct($params = [], $select = ['*'], $callback = null) {
        $this->params = array_merge($params);
        $this->engine = new UserEntity();
        $this->query = $this->engine->newQuery();
        $this->getFilter();
        $this->compileGroupParams();

        parent::__construct($this->params, $select);
    }

    protected function defaultSelect(): array {
        $tab = $this->engine->tableName();
        $this->default = [];

        return $this->default;
    }

    public function getUserName() {
        $this->select = [
            DB::raw("CONCAT_WS(' ', last_name, first_name, middle_name) as user_name")
        ];
        return (new self($this->params, $this->select))->getOne()['user_name'];
    }

    public function getNewMessagesForUser($data) {
        if (!isset($data['user_id'])) {
            return false;
        }

    }

    public function save($data)
    {
        if (isset($data['password'])) {
            $data['input_password'] = $data['password'];
            $data['password'] = Hash::make($data['password']);
            if (isset($data) && !empty($data)) {
                $user = array_intersect_key($data, array_flip($this->engine->getFillable()));
                if ($data['id'] = parent::insert($user)) {
                    return $data;
                } else return false;
            }
        } else {
            if (isset($data) && !empty($data)) {
                foreach ($data as $k => $v) {
                    if (empty($v)) {
                        unset($data[$k]);
                    }
                }
                $user = array_intersect_key($data, array_flip($this->engine->getFillable()));
                if (!empty($user)) {
                    if ($data['id'] = parent::update($user, auth()->id())) {
                        return $data;
                    }
                } else {
                    return $data;
                }
            }
        }
        return false;
    }

    public function setUserOnlineTimestamp() {
        $data['online'] = Carbon::now()->toDateTimeString();
        $data = setTimestamps($data, 'update');
        return $this->save($data);
    }

    protected function getFilter(): array {
        $tab = $this->engine->getTable();
        $this->filter = [
            [   'field' => $tab.'.id','params' => 'id',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
            ],
            [   'field' => $tab.'.type_id','params' => 'type_id',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
            ],
            [   'field' => $tab.'.is_deleted','params' => 'is_deleted',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => '=', 'concat' => 'AND',
            ],
            [   'field' => $tab.'.city_id','params' => 'city_id',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
            ],
            [   'field' => 'Vacancy.is_group','params' => 'group_vacancy',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
            ],
            [   'field' => 'Vacancy.is_public','params' => 'public_vacancy',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
            ],
        ];
        $this->filter = array_merge($this->filter, parent::getFilter());

        return $this->filter;
    }

    protected function compileGroupParams(): array {
        $this->group_params = [
            'select' => [],
            'by' => [],
            'relatedModel' => [
                'City' => [
                    'entity' => new City(),
                    'relationship' => ['id', 'city_id'],
                    'field' => [],
                ],
                'Region' => [
                    'entity' => new Region(),
                    'relationship' => ['id', 'region_id'],
                    'field' => [],
                ],
                'Balance' => [
                    'entity' => new UserBalance(),
                    'relationship' => ['user_id', 'id'],
                    'field' => [],
                ],
                'Vacancy' => [
                    'entity' => new Vacancy(),
                    'relationship' => ['user_id', 'id'],
                    'field' => [],
                ],
                'Question' => [
                    'entity' => new UserQuestion(),
                    'relationship' => ['user_id', 'id'],
                    'field' => [],
                ],
            ]
        ];

        return $this->group_params;
    }

}
