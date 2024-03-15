<?php

namespace App\Models\CoreEngine\LogicModels\User;

use App\Models\CoreEngine\Core\CoreEngine;
use App\Models\CoreEngine\ProjectModels\HelpData\City;
use App\Models\CoreEngine\ProjectModels\HelpData\Country;
use App\Models\CoreEngine\ProjectModels\Question\UserQuestion;
use App\Models\CoreEngine\ProjectModels\User\UserBalance;
use App\Models\CoreEngine\ProjectModels\User\UserEntity;
use App\Models\CoreEngine\ProjectModels\Vacancy\Vacancy;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\Console\Question\Question;

class UserLogic extends CoreEngine
{
    const EMPLOYEE_TYPE_ID = 2;

    public function __construct($params = [], $select = ['*'], $callback = null) {
        $this->params = array_merge($params, ['is_deleted' => '0']);
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

    public function save($data)
    {
        if (isset($data['password'])) {
            $data['modifier_id'] = 1;
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
                if ($data['id'] = parent::update($user, auth()->id())) {
                    return $data;
                } else return false;
            }
        }
        return false;
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
                "action" => 'IN', 'concat' => 'AND',
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
                'Country' => [
                    'entity' => new Country(),
                    'relationship' => ['id', 'country_id'],
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
