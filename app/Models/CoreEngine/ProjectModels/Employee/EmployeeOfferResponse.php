<?php

namespace App\Models\CoreEngine\ProjectModels\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeOfferResponse extends Model
{
    protected $table = 'employee_offer_responses';

    protected $fillable = [
        'vacancy_id',
        'user_id',
        'text',
        'created_at',
        'updated_at',
    ];
}
