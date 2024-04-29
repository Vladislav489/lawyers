<?php

namespace App\Models\CoreEngine\LogicModels\Employee;

use App\Models\CoreEngine\Core\CoreEngine;
use App\Models\CoreEngine\ProjectModels\Employee\EmployeeService;
use App\Models\CoreEngine\ProjectModels\Employee\EmployeeSpecialization;
use App\Models\CoreEngine\ProjectModels\Service\Service;
use App\Models\CoreEngine\ProjectModels\Service\ServiceType;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class EmployeeSpecializationLogic extends CoreEngine
{
    public function __construct($params = [], $select = ['*'], $callback = null) {
        $this->engine = new EmployeeSpecialization();
        $this->query = $this->engine->newQuery();
        $this->getFilter();
        $this->compileGroupParams();

        parent::__construct($params, $select);
    }

    public function store(array $data): array|bool {
        if ($data['user_id'] != Auth::id()) {
            return false;
        }

        try {
            $specialization = array_intersect_key(
                $data,
                array_flip($this->engine->getFillable())
            );

            if (isset($data['id'])) {
                $specialization['id'] = $data['id'];
            }

            if ($data['id'] = $this->save($specialization)) {
                return $data;
            }

        } catch (\Throwable $e) {
        }

        return false;
    }

    public function updateSpecialization(array $data) {
        $currentList = (new EmployeeSpecializationLogic(['user_id' => $data['user_id']]))->offPagination()->setLimit(false)->getList()['result'];
        $currentList = Arr::pluck($currentList, 'service_id');

        $recordsToAdd = array_diff($data['service_ids'], $currentList);
        $recordsToDelete = array_diff($currentList, $data['service_ids']);

        if (!empty($recordsToAdd)) {
            foreach ($recordsToAdd as $item) {
                $record['service_id'] = $item;
                $record['user_id'] = $data['user_id'];
                $this->store($record);
            }
        }
        if (!empty($recordsToDelete)) {
            $service_ids = explode(',', implode(', ', $recordsToDelete));
            $list = (new EmployeeSpecializationLogic(['user_id' => $data['user_id'], 'service_id' => $service_ids]))->getList()['result'];
            $ids = Arr::pluck($list, 'id');
            foreach ($ids as $id) {
                $this->deleteForeva($id);
            }
        }
    }

    protected function getFilter() {
        $tab = $this->engine->getTable();
        $this->filter = [
            [   'field' => $tab.'.user_id','params' => 'user_id',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => '=', 'concat' => 'AND',
            ],
            [   'field' => $tab.'.service_id','params' => 'service_id',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
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
