<?php

namespace App\Models\CoreEngine\ProjectModels\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeRating extends Model
{
    protected $table = 'employee_ratings';

    protected $fillable = [
        'created_at',
        'updated_at',
        'vacancy_id',
        'text',
        'user_id',
        'employee_user_id',
        'rating'
    ];
}
