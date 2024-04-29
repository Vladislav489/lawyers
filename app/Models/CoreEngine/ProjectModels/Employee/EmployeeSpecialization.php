<?php

namespace App\Models\CoreEngine\ProjectModels\Employee;

use Illuminate\Database\Eloquent\Model;

class EmployeeSpecialization extends Model
{
    protected $table = 'employee_specializations';

    protected $fillable = [
        'user_id',
        'service_id',
        'is_deleted'
    ];
}
