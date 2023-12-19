<?php

namespace App\Models\CoreEngine\LogicModels\File;

use App\Models\CoreEngine\Core\CoreEngine;
use App\Models\CoreEngine\ProjectModels\File\File;

class FileLogic extends CoreEngine
{
    public function __construct($params = [], $select = ['*'], $callback = null)
    {
        $this->engine = new File();
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
            $file = array_intersect_key(
                $data,
                array_flip($this->engine->getFillable())
            );

            if (isset($data['id'])) {
                $file['id'] = $data['id'];
            }

            if ($data['id'] = $this->save($file)) {
                return $data;
            }

        } catch (\Throwable $e) {
        }

        return false;
    }
}
