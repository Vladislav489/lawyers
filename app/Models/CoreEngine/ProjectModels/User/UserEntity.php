<?php

namespace App\Models\CoreEngine\ProjectModels\User;

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
        'region_id',
        'city_id',
        'type_id',
        'modifier_id',
    ];

    protected $hidden = [
        'password'
    ];

}
