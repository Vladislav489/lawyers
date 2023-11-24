<?php

namespace App\Models\CoreEngine\ProjectModels\Employee;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class EmployeeComment extends BaseModel
{
    protected $table = 'user_employee_comment';

    protected $fillable = [
        'comment_text',
        'is_deleted',
        'is_archive',
        'user_id',
        'vacancy_id',
    ];
}
