<?php

namespace App\Models\CoreEngine\ProjectModels\Employee;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class EmployeeLike extends BaseModel
{
    protected $table = 'user_employee_like';

    protected $fillable = [
        'user_id',
        'question_id',
        'target_user_id',
    ];
}
