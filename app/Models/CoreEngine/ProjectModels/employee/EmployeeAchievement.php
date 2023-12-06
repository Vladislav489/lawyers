<?php

namespace App\Models\CoreEngine\ProjectModels\Employee;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class EmployeeAchievement extends BaseModel
{
    protected $table = 'user_employee_achievement';

    protected $fillable = [
        'path',
        'employee_id',
    ];
}
