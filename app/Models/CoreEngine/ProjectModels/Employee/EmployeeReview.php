<?php

namespace App\Models\CoreEngine\ProjectModels\Employee;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class EmployeeReview extends BaseModel
{
    protected $table = 'user_employee_review';

    protected $fillable = [
        'grade',
        'user_id',
        'target_user_id',
    ];
}
