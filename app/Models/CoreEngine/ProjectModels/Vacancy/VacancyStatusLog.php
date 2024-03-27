<?php

namespace App\Models\CoreEngine\ProjectModels\Vacancy;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class VacancyStatusLog extends BaseModel
{
    protected $table = 'vacancy_status_log';

    protected $fillable = [
        'vacancy_id',
        'status'
    ];
}
