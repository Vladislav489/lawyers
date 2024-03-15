<?php

namespace App\Models\CoreEngine\LogicModels\Employee;

use App\Models\CoreEngine\Core\CoreEngine;
use App\Models\CoreEngine\ProjectModels\Employee\EmployeeService;
use App\Models\CoreEngine\ProjectModels\Service\Service;
use Illuminate\Support\Facades\Auth;

class EmployeeServiceLogic extends CoreEngine
{
    private $helpEngine;

    public function __construct($params = [], $select = ['*'], $callback = null) {
        $this->engine = new EmployeeService();
        $this->params = array_merge($params, ['is_deleted' => '0']);
        $this->query = $this->engine->newQuery();
        $this->getFilter();
        $this->compileGroupParams();

        parent::__construct($params, $select);
    }


    protected function defaultSelect(): array {
        $tab = $this->engine->tableName();
        $this->default = [];

        return $this->default;
    }

    public function store(array $data): array|bool {
        if ($data['user_id'] != Auth::id()) {
            return false;
        }

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

    public function deleteService(array $data) {
        if (empty($data)) {
            return false;
        }
        $id = $data['id'];
        $userId = $data['user_id'];

        if ($userId == \auth()->id()) {
            return $this->delete($id);
        }
        return false;
    }

    protected function getFilter() {
        $tab = $this->engine->getTable();
        $this->filter = [
            [   'field' => $tab.'.is_deleted','params' => 'is_deleted',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => '=', 'concat' => 'AND',
            ],
            [   'field' => $tab.'.user_id','params' => 'user_id',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => '=', 'concat' => 'AND',
            ],
            [   'field' => $tab.'.id','params' => 'id',
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
