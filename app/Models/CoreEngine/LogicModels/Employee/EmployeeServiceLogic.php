<?php

namespace App\Models\CoreEngine\LogicModels\Employee;

use App\Models\CoreEngine\Core\CoreEngine;
use App\Models\CoreEngine\ProjectModels\Employee\EmployeeService;
use App\Models\CoreEngine\ProjectModels\Service\Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmployeeServiceLogic extends CoreEngine
{
    private $helpEngine;

    public function __construct($params = [], $select = ['*'], $callback = null)
    {
        $this->engine = new EmployeeService();
        $this->query = $this->engine->newQuery();
        $this->params = array_merge($params, ['is_deleted' => '0']);
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

    protected function getFilter() {
        $tab = $this->engine->getTable();
        $this->filter = [
            [   'field' => $tab.'.user_id','params' => 'user_id',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => '=', 'concat' => 'AND',
            ],
            [   'field' => $tab.'.is_deleted','params' => 'is_deleted',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => '=', 'concat' => 'AND',
            ],
        ];

        return $this->filter = array_merge($this->filter, parent::getFilter());
    }

    protected function compileGroupParams() {
        $this->group_params = [
            'select' => [],
            'by' => [],
            'relatedModel' => [
                'Service' => [
                    'entity' => new Service(),
                    'relationship' => ['id', 'service_id'],
                    'field' => [
                        'name',
                    ],
                ],
            ]
        ];
        return $this->group_params;
    }
}
