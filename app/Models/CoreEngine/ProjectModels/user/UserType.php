<?php

namespace App\Models\CoreEngine\ProjectModels\User;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class UserType extends BaseModel
{
    protected $table = 'user_type';

    protected $fillable = [
        'name',
    ];
}
