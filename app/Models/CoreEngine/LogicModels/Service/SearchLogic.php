<?php

namespace App\Models\CoreEngine\LogicModels\Service;

use App\Models\CoreEngine\Core\CoreEngine;
use App\Models\CoreEngine\Model\InformationCategoryName;

class ServiceLogic extends CoreEngine
{
    private $helpEngine;

    public function __construct($params = [], $select = ['*'], $callback = null)
    {
        $this->helpEngine['InformationCategoryName'] = new InformationCategoryName();
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
}
