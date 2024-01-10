<?php

namespace App\Models\CoreEngine\ProjectModels\Employee;

use App\Models\CoreEngine\ProjectModels\BaseModel;
use App\Models\CoreEngine\ProjectModels\Service\Service;

class EmployeeService extends BaseModel
{
    protected $table = 'user_employee_service';

    protected $fillable = [
        'description',
        'price',
        'is_main',
        'is_deleted',
        'is_archive',
        'user_id',
        'service_id',
    ];

    public function entity()
    {
        return $this->hasOne(Service::class, 'id', 'service_id');
    }
}
