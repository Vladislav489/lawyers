<?php

namespace App\Models\CoreEngine\LogicModels\User;

use App\Models\CoreEngine\Core\CoreEngine;
use App\Models\CoreEngine\ProjectModels\User\UserEntity;
use Illuminate\Support\Facades\Hash;

class UserLogic extends CoreEngine
{
    public function __construct($params = [], $select = ['*'], $callback = null)
    {
        $this->engine = new UserEntity();
        $this->query = $this->engine->newQuery();
        $this->getFilter();
        $this->compileGroupParams();

        parent::__construct($params, $select);
    }

    protected function compileGroupParams(): array
    {
        $this->group_params = [
            'select' => [],
            'by' => [],
            'relatedModel' => []
        ];

        return $this->group_params;
    }

    protected function defaultSelect(): array
    {
        $tab = $this->engine->tableName();
        $this->default = [];

        return $this->default;
    }

    protected function getFilter(): array
    {
        $tab = $this->engine->getTable();
        $this->filter = [];
        $this->filter = array_merge($this->filter, parent::getFilter());

        return $this->filter;
    }

    public function store(array $data): UserEntity
    {
        $user = new UserEntity();

        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];

        $user->post_code = '1';
        $user->phone_number = $data['phone_number'];
        $user->date_birthday = '2023-01-01';

        $user->country_id = 1;
        $user->state_id = 1;
        $user->city_id = 1;
        $user->user_type_id = 1;
        $user->user_modifier_id = 1;

        $user->save();

        return $user;
    }
}
