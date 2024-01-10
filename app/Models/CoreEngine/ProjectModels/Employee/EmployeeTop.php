<?php

namespace App\Models\CoreEngine\ProjectModels\Employee;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class EmployeeTop extends BaseModel
{
    protected $table = 'user_employee_top';

    protected $fillable = [
        'period_start',
        'period_end',
        'is_deleted',
        'user_id',
    ];
}
