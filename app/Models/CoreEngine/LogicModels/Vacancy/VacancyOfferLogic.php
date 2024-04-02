<?php

namespace App\Models\CoreEngine\LogicModels\Vacancy;

use App\Models\CoreEngine\Core\CoreEngine;
use App\Models\CoreEngine\ProjectModels\Employee\Employee;
use App\Models\CoreEngine\ProjectModels\Employee\EmployeeOfferResponse;
use App\Models\CoreEngine\ProjectModels\User\UserEntity;
use App\Models\CoreEngine\ProjectModels\Vacancy\VacancyOffer;

class VacancyOfferLogic extends CoreEngine
{
    public function __construct($params = [], $select = ['*'], $callback = null) {
        $this->engine = new VacancyOffer();
        $this->params = array_merge($params);
        $this->query = $this->engine->newQuery();
        $this->getFilter();
        $this->compileGroupParams();

        parent::__construct($this->params, $select);
    }

    protected function getFilter(): array {
        $tab = $this->engine->getTable();
        $this->filter = [
            [   'field' => $tab.'.vacancy_id','params' => 'vacancy_id',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => '=', 'concat' => 'AND',
            ],
            [   'field' => $tab.'.employee_user_id','params' => 'employee_id',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => '=', 'concat' => 'AND',
            ],

        ];

        return $this->filter = array_merge($this->filter, parent::getFilter());
    }

    protected function compileGroupParams(): array {
        $this->group_params = [
            'select' => [],
            'by' => [],
            'relatedModel' => [
                'Employee' => [
                    'entity' => new Employee(),
                    'relationship' => ['user_id', 'employee_user_id'],
                    'field' => [],
                ],
                'User' => [
                    'entity' => new UserEntity(),
                    'relationship' => ['id', 'employee_user_id'],
                    'field' => [],
                ],
                'Response' => [
                    'entity' => new EmployeeOfferResponse(),
                    'relationship' => ['id', 'employee_response_id'],
                    'field' => [],
                ],
            ]
        ];
        return $this->group_params;
    }
}
