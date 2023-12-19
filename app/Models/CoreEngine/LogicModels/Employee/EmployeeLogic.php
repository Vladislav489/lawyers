<?php

namespace App\Models\CoreEngine\LogicModels\Employee;

use App\Models\CoreEngine\Core\CoreEngine;
use App\Models\CoreEngine\LogicModels\User\UserLogic;
use App\Models\CoreEngine\ProjectModels\Employee\Employee;
use App\Models\CoreEngine\ProjectModels\Employee\EmployeeService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmployeeLogic extends UserLogic
{
    public function __construct($params = [], $select = ['*'], $callback = null)
    {
        $this->engine = new Employee();
        $this->query = $this->engine->newQuery();
        $this->params = $params;
        $this->getFilter();
        $this->compileGroupParams();
        $this->setCoreParams($callback);
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

    public function store(array $data): array|bool
    {
        if ($data['user_id'] = (new UserLogic())->store($data)['id']) {
            try {
                $employee = array_intersect_key(
                    $data,
                    array_flip($this->engine->getFillable())
                );

                if ($data['id'] = $this->save($employee)) {
                    return $data;
                }

            } catch (\Throwable $e) {
            }
        }

        return false;
    }
}
