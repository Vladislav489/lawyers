<?php

namespace App\Models\CoreEngine\LogicModels\Employee;

use App\Models\CoreEngine\Core\CoreEngine;
use App\Models\CoreEngine\ProjectModels\Employee\EmployeeService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmployeeServiceLogic extends CoreEngine
{
    private $helpEngine;

    public function __construct($params = [], $select = ['*'], $callback = null)
    {
        $this->engine = new EmployeeService();
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

    public function storeEmployeeServices(array $data): array
    {
        $result = [];
        DB::table('user_employee_service')
            ->where('user_id', Auth::id())
            ->whereNotIn('service_id', $data)
            ->delete();

        foreach ($data as $service_id) {
            if (!EmployeeService::where(['user_id' => Auth::id(), 'service_id' => $service_id])->exists()) {
                $result[] = $this->store(compact(['service_id']));
            }
        }

        return $result;
    }

    public function store(array $data): array|bool
    {
        $data['is_main'] = boolval($data['is_main'] ?? null);
        $data['user_id'] = Auth::id();

        try {
            $service = array_intersect_key(
                $data,
                array_flip($this->engine->getFillable())
            );

            if (isset($data['id'])) {
                $service['id'] = $data['id'];
            }

            if ($data['id'] = $this->save($service)) {
                return $data;
            }

        } catch (\Throwable $e) {
        }

        return false;
    }
}
