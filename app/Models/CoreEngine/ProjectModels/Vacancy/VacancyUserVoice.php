<?php

namespace App\Models\CoreEngine\ProjectModels\Vacancy;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class VacancyUserVoice extends BaseModel
{
    protected $table = 'vacancy_user_voice';

    protected $fillable = [
        'user_id',
        'vacancy_id',
    ];
}
