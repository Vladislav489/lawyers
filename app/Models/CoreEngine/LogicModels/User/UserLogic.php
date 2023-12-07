<?php

namespace App\Models\CoreEngine\LogicModels\User;

use App\Models\CoreEngine\Core\CoreEngine;
use App\Models\CoreEngine\LogicModels\Employee\EmployeeLogic;
use App\Models\CoreEngine\ProjectModels\User\UserEntity;
use Illuminate\Support\Facades\Hash;

class UserLogic extends CoreEngine
{
    const EMPLOYEE_TYPE_ID = 2;

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
        $user->middle_name = $data['middle_name'];

        $user->post_code = $data['post_code'];
        $user->phone_number = $data['phone_number'];
        $user->date_birthday = $data['date_birthday'];

        $user->city_id = $data['city_id'];
        $user->state_id = $data['state_id'];
        $user->country_id = $data['country_id'];
        $user->district_id = $data['district_id'];

        $user->type_id = $data['type_id'];
        $user->modifier_id = 1;

        $user->save();

        if (intval($user->type_id) === self::EMPLOYEE_TYPE_ID) {
            (new EmployeeLogic())->storeEmployee($data, $user->id);
        }

        return $user;
    }
}
