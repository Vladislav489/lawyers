<?php

namespace App\Models\CoreEngine\ProjectModels\Vacancy;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class VacancyChaffer extends BaseModel
{
    protected $table = 'vacancy_chaffer';

    protected $fillable = [
        'status',
        'description',
        'chat_id',
        'user_id',
        'winner_id',
        'vacancy_id',
        'initiator_id',
        'support_user_id',
        'user_employee_id',
    ];
}
