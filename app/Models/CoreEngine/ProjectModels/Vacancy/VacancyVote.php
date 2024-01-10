<?php

namespace App\Models\CoreEngine\ProjectModels\Vacancy;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class VacancyVote extends BaseModel
{
    protected $table = 'vacancy_vote';

    protected $fillable = [
        'grade',
        'vacancy_id',
        'employee_id',
    ];
}
