<?php

namespace App\Models\CoreEngine\LogicModels\Vacancy;

use Illuminate\Database\Eloquent\Model;

class VacancyClosing extends Model
{
    protected $table = 'vacancy_closings';

    protected $fillable = [
        'vacancy_id',
        'employee_user_id',
        'text',
        'created_at',
        'updated_at',
    ];
}
