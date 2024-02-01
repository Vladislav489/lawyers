<?php

namespace App\Models\CoreEngine\LogicModels\User;

use App\Models\CoreEngine\Core\CoreEngine;
use App\Models\CoreEngine\ProjectModels\User\UserEntity;
use Illuminate\Support\Facades\Hash;

class UserLogic extends CoreEngine
{
    const EMPLOYEE_TYPE_ID = 2;

    public function __construct($params = [], $select = ['*'], $callback = null) {
        $this->engine = new UserEntity();
        $this->query = $this->engine->newQuery();
        $this->getFilter();
        $this->compileGroupParams();

        parent::__construct($params, $select);
    }

    protected function compileGroupParams(): array {
        $this->group_params = [
            'select' => [],
            'by' => [],
            'relatedModel' => []
        ];

        return $this->group_params;
    }

    protected function defaultSelect(): array {
        $tab = $this->engine->tableName();
        $this->default = [];

        return $this->default;
    }

    protected function getFilter(): array {
        $tab = $this->engine->getTable();
        $this->filter = [];
        $this->filter = array_merge($this->filter, parent::getFilter());

        return $this->filter;
    }

    public function storeUser(array $data): array|bool {
        $data['modifier_id'] = 1;
        $data['password'] = Hash::make($data['password']);
        return $this->save($data);
    }

    public function save($data)
    {
        $data['modifier_id'] = 1;
        $data['input_password'] = $data['password'];
        $data['password'] = Hash::make($data['password']);
        if (isset($data) && !empty($data)) {
            $user = array_intersect_key($data, array_flip($this->engine->getFillable()));
            if ($data['id'] = parent::save($user)) {
                return $data;
            } else return false;
        }
        return false;
    }

    public function storeEntity(array $data): array|bool {
        try {
            $entity = array_intersect_key(
                $data,
                array_flip($this->engine->getFillable())
            );
            if ($data['id'] = $this->save($entity)) {
                return $data;
            }

        } catch (\Throwable $e) {
        }

        return false;
    }


}
