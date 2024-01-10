<?php

namespace App\Models\CoreEngine\ProjectModels\Question;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class EmployeeAnswer extends BaseModel
{
    protected $table = 'employee_answer';

    protected $fillable = [
        'text',
        'is_deleted',
        'question_id',
        'employee_id',
    ];
}
