<?php

namespace App\Models\CoreEngine\ProjectModels\User;

use App\Models\CoreEngine\ProjectModels\BaseModel;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserEntity extends Authenticatable
{
    protected $table = 'user_entity';

    protected $fillable = [
        'email',
        'password',
        'first_name',
        'last_name',
        'post_code',
        'phone_number',
        'date_birthday',
        'online',
        'is_block',
        'is_public',
        'is_deleted',
        'country_id',
        'state_id',
        'city_id',
        'user_type_id',
        'user_modifier_id',
    ];
}
