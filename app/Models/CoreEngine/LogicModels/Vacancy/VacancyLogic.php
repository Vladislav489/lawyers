<?php

namespace App\Models\CoreEngine\LogicModels\Vacancy;

use App\Models\CoreEngine\Core\CoreEngine;
use App\Models\CoreEngine\ProjectModels\Vacancy\Vacancy;
use App\Models\CoreEngine\Model\InformationCategoryName;
use Illuminate\Support\Facades\Auth;

class VacancyLogic extends CoreEngine
{
    public function __construct($params = [], $select = ['*'], $callback = null)
    {
        $this->engine = new Vacancy();
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
            $vacancy = array_intersect_key(
                $data,
                array_flip($this->engine->getFillable())
            );

            if ($data['id'] = $this->save($vacancy)) {
                return $data;
            }

        } catch (\Throwable $e) {
        }

        return false;
    }

    public function updateVacancy(array $data): Vacancy|false
    {
        $vacancy = Vacancy::find($data['id']);
        $vacancy->description = $data['description'];
        $vacancy->payment = $data['payment'];

        if ($vacancy->save()) {
            return $vacancy;
        }

        return false;
    }

    public function deleteVacancy(int $vacancy_id): ?bool
    {
        return Vacancy::find($vacancy_id)->delete();
    }
}
