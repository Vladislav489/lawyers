<?php

namespace App\Models\CoreEngine\LogicModels\Service;

use App\Models\CoreEngine\Core\CoreEngine;
use App\Models\CoreEngine\ProjectModels\Service\Service;
use App\Models\CoreEngine\ProjectModels\Service\ServiceType;

class ServiceLogic extends CoreEngine
{
    public function __construct($params = [], $select = ['*'], $callback = null) {
        $this->engine = new Service();
        $this->params = $params;
        $this->query = $this->engine->newQuery();
        $this->getFilter();
        $this->compileGroupParams();
        parent::__construct($this->params, $select);
    }

    public function store(array $data): array|bool {
        try {
            $service = array_intersect_key($data, array_flip($this->engine->getFillable()));

            if (isset($data['id'])) {
                $service['id'] = $data['id'];
            }

            $data['id'] = $this->save($service);
            return $data;

        } catch (\Throwable $e) {
            return false;
        }
    }

    public function deleteService(array $data): bool {
        try {
            $service = $this->setModel(new Service());
            return $service->delete($data['id']);
        } catch (\Throwable $e) {
            return false;
        }
    }

    protected function getFilter() {
        $tab = $this->engine->getTable();
        $this->filter = [
            [   'field' => $tab.'.type_id','params' => 'type_id',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => '=', 'concat' => 'AND',
            ],
            [   'field' => $tab.'.is_deleted','params' => 'is_deleted',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => '=', 'concat' => 'AND',
            ],
            [   'field' => $tab.'.is_archive','params' => 'is_archive',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
            ],
            [   'field' => 'ServiceType.name','params' => 'service_name',
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
                'ServiceType' => [
                    'entity' => new ServiceType(),
                    'relationship' => ['id', 'type_id'],
                    'field' => ['*'],
                ],
            ]
        ];
        return $this->group_params;
    }

}
