<?php

namespace App\Models\CoreEngine\LogicModels\Service;

use App\Models\CoreEngine\Core\CoreEngine;
use App\Models\CoreEngine\ProjectModels\Service\Service;

class ServiceLogic extends CoreEngine
{
    public function __construct($params = [], $select = ['*'], $callback = null)
    {
        $this->engine = new Service();
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

    public function store(array $data): array|bool
    {
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

    public function deleteService(array $data): bool
    {
        try {
            $service = $this->setModel(new Service());
            return $service->delete($data['id']);
        } catch (\Throwable $e) {
        }

        return false;
    }
}
