<?php

namespace App\Models\CoreEngine\ProjectModels\Vacancy;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class VacancyDocumentation extends BaseModel
{
    protected $table = 'vacancy_documentation';

    protected $fillable = [
        'description',
        'is_deleted',
        'user_id',
        'vacancy_id',
    ];
}
