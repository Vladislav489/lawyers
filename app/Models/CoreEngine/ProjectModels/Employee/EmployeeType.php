<?php

namespace App\Models\CoreEngine\ProjectModels\Employee;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class EmployeeType extends BaseModel
{
    protected $table = 'user_employee_type';

    protected $fillable = [
        'name',
        'is_deleted',
    ];
}
