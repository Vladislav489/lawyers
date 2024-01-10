<?php

namespace App\Models\CoreEngine\ProjectModels\Employee;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class EmployeeTag extends BaseModel
{
    protected $table = 'user_employee_tag';

    protected $fillable = [
        'user_employee_id',
        'tag_id',
    ];
}
