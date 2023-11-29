<?php

namespace App\Models\CoreEngine\ProjectModels\Employee;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class EmployeeServiceTag extends BaseModel
{
    protected $table = 'user_employee_service_tag';

    protected $fillable = [
        'employee_service_id',
        'tag_id',
    ];
}
