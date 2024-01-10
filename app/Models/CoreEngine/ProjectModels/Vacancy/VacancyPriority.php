<?php

namespace App\Models\CoreEngine\ProjectModels\Vacancy;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class VacancyPriority extends BaseModel
{
    protected $table = 'vacancy_priority';

    protected $fillable = [
        'name',
        'is_deleted',
    ];
}
