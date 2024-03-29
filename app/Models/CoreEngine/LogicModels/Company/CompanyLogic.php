<?php

namespace App\Models\CoreEngine\LogicModels\Company;

use App\Models\CoreEngine\Core\CoreEngine;
use App\Models\CoreEngine\ProjectModels\Company\Company;
use App\Models\CoreEngine\ProjectModels\User\UserEntity;

class CompanyLogic extends CoreEngine
{
    public function __construct($params = [], $select = ['*'], $callback = null) {
        $this->engine = new Company();
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

    protected function getFilter() {
        $tab = $this->engine->getTable();
        $this->filter = [
            [   'field' => $tab.'.is_deleted','params' => 'is_deleted',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
            ],
            [   'field' => $tab.'.is_archive','params' => 'is_archive',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
            ],
            [   'field' => $tab.'.owner_id','params' => 'owner_id',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
            ],
        ];

        return $this->filter = array_merge($this->filter, parent::getFilter());;
    }

    protected function compileGroupParams() {
        $this->group_params = [
            'select' => [],
            'by' => [],
            'relatedModel' => [
                'Owner' => [
                    'entity' => new UserEntity(),
                    'relationship' => ['owner_id', 'id'],
                    'field' => ['Owner.*']
                ]
            ]
        ];
        return $this->group_params;
    }
}
