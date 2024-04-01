<?php

namespace App\Models\CoreEngine\ProjectModels\Vacancy;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class VacancyOffer extends BaseModel
{
    protected $table = 'vacancy_offer';

    protected $fillable = [
        'payment',
        'is_deleted',
        'vacancy_id',
        'employee_response_id',
        'employee_user_id',
    ];
}
