<?php

namespace App\Models\CoreEngine\ProjectModels\Vacancy;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class Vacancy extends BaseModel
{
    protected $table = 'vacancy';

    protected $fillable = [
        'description',
        'payment',
        'defendant',
        'status',
        'lawsuit_number',
        'address_judgment',
        'period_start',
        'period_end',
        'is_group',
        'is_public',
        'is_deleted',
        'is_archive',
        'is_consultation',
        'priority_id',
        'chat_id',
        'user_id',
        'service_id',
        'executor_id',
        'country_id',
        'state_id',
        'city_id',
    ];
}
