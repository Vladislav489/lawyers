<?php

namespace App\Models\CoreEngine\ProjectModels\Employee;

use Illuminate\Database\Eloquent\Model;

class EmployeeWorkingSchedule extends Model
{
    protected $table = 'employee_working_schedules';

    protected $fillable = [
        'user_id',
        'time_from',
        'time_to',
        'day_of_week'
    ];
}
