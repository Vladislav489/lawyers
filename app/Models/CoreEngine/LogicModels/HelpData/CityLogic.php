<?php

namespace App\Models\CoreEngine\LogicModels\HelpData;

use App\Models\CoreEngine\Core\CoreEngine;
use App\Models\CoreEngine\ProjectModels\HelpData\City;
use App\Models\CoreEngine\ProjectModels\HelpData\Country;
use App\Models\CoreEngine\ProjectModels\HelpData\District;
use App\Models\CoreEngine\ProjectModels\HelpData\State;

class CityLogic extends CoreEngine {
    public function __construct($params = [], $select = ['*'], $callback = null) {
        $this->engine = new City();
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
            [   'field' => $tab.'.country_id','params' => 'country_id',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
            ],
            [   'field' => $tab.'.state_id','params' => 'state_id',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
            ],
            [   'field' => $tab.'.district_id','params' => 'district_id',
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
                'Country' => [
                    'entity' => new Country(),
                    'relationship' => ['country_id','id'],
                    'field' => ['Country.*'],
                ],
                'State' => [
                    'entity' => new State(),
                    'relationship' => ['state_id','id'],
                    'field' => ['State.*'],
                ],
                'District' => [
                    'entity' => new District(),
                    'relationship' => ['district_id','id'],
                    'field' => ['District.*'],
                ],
            ]
        ];
        return $this->group_params;
    }
}
