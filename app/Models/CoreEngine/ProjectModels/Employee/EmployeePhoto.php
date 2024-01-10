<?php

namespace App\Models\CoreEngine\ProjectModels\Employee;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class EmployeePhoto extends BaseModel
{
    protected $table = 'user_employee_photo';

    protected $fillable = [
        'path',
        'employee_id',
    ];
}
