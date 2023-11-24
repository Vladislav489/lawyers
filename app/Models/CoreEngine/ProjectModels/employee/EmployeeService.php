<?php

namespace App\Models\CoreEngine\ProjectModels\Employee;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class EmployeeService extends BaseModel
{
    protected $table = 'user_employee_service';

    protected $fillable = [
        'description',
        'price',
        'is_deleted',
        'is_archive',
        'user_id',
        'service_id',
    ];
}
