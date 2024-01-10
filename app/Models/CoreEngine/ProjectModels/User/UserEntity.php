<?php

namespace App\Models\CoreEngine\ProjectModels\User;

use App\Models\CoreEngine\ProjectModels\Vacancy\Vacancy;
use App\Models\CoreEngine\ProjectModels\Employee\EmployeeService;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserEntity extends Authenticatable
{
    protected $table = 'user_entity';

    protected $fillable = [
        'email',
        'password',
        'first_name',
        'last_name',
        'middle_name',
        'post_code',
        'phone_number',
        'date_birthday',
        'online',
        'is_block',
        'is_public',
        'is_deleted',
        'country_id',
        'state_id',
        'district_id',
        'city_id',
        'type_id',
        'modifier_id',
    ];

    // FIXME:
    public function type()
    {
        return $this->hasOne(UserType::class, 'id', 'type_id');
    }

    public function services()
    {
        return $this->hasMany(EmployeeService::class, 'user_id', 'id');
    }

    public function vacancies()
    {
        return $this->hasMany(Vacancy::class, 'user_id', 'id');
    }
}
