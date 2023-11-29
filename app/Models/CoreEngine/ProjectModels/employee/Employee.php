<?php

namespace App\Models\CoreEngine\ProjectModels\Employee;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class Employee extends BaseModel
{
    protected $table = 'user_employee';

    protected $fillable = [
        'photo_path',
        'license_number',
        'is_deleted',
        'is_confirmed',
        'user_id',
        'company_id',
        'user_type_id',
    ];
}
