<?php

namespace App\Models\CoreEngine\ProjectModels\Employee;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class EmployeeServicePrice extends BaseModel
{
    protected $table = 'user_employee_service_price';

    protected $fillable = [
        'price',
        'description',
        'employee_id',
        'service_id',
    ];
}
