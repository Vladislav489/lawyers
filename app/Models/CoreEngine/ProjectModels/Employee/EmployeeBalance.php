<?php

namespace App\Models\CoreEngine\ProjectModels\Employee;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class EmployeeBalance extends BaseModel
{
    protected $table = 'user_employee_balance';

    protected $fillable = [
        'balance',
        'is_deleted',
        'employee_id',
    ];
}
