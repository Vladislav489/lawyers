<?php

namespace App\Models\CoreEngine\LogicModels\Employee;

use App\Models\CoreEngine\Core\CoreEngine;
use App\Models\CoreEngine\LogicModels\User\UserLogic;
use App\Models\CoreEngine\ProjectModels\Employee\Employee;

class EmployeeLogic extends UserLogic
{
    public function __construct($params = [], $select = ['*'], $callback = null)
    {
        $this->engine = new Employee();
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

    public function storeEmployee(array $data, int $user_id): Employee
    {

        /*
         * public function store(array $data)
         *$data = parent::store($data);
         * if($this->save($data)){
         *      return $data;
         * }else{
         *      return false;
         * }
         * возвращаем массивы   их дегче обрабатывать
         *
         */
        $employee = new Employee();

        $employee->avatar_path = $data['avatar_path'];
        $employee->license_number = $data['license_number'];
        $employee->dt_practice_start = $data['dt_practice_start'];
        $employee->consultation_price = $data['consultation_price'];
        $employee->user_id = $user_id;
        $employee->company_id = $data['company_id'];

        $employee->save();

        return $employee;
    }
}
