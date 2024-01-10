<?php

namespace App\Models\CoreEngine\ProjectModels\Vacancy;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class VacancyGroup extends BaseModel
{
    protected $table = 'vacancy_group';

    protected $fillable = [
        'is_appruv',
        'user_id',
        'vacancy_id',
    ];
}
