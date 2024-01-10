<?php

namespace App\Models\CoreEngine\ProjectModels\Contract;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class EmployeeContract extends BaseModel
{
    protected $table = 'employee_contract';

    protected $fillable = [
        'user_id',
        'employee_id',
        'contract_id',
    ];
}
