<?php

namespace App\Models\CoreEngine\ProjectModels\User;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class UserModifier extends BaseModel
{
    protected $table = 'user_modifier';

    protected $fillable = [
        'name',
    ];
}
