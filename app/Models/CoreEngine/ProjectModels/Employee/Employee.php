<?php

namespace App\Models\CoreEngine\ProjectModels\Employee;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class Employee extends BaseModel
{
    protected $table = 'user_employee';

    protected $fillable = [
        'avatar_path',
        'license_number',
        'dt_practice_start',
        'consultation_price',
        'is_deleted',
        'is_confirmed',
        'user_id',
        'company_id',
        'about',
        'location_coordinates',
        'location_address',
        'site_url'
    ];
}
