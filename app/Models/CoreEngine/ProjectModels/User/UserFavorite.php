<?php

namespace App\Models\CoreEngine\ProjectModels\User;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class UserFavorite extends BaseModel
{
    protected $table = 'user_favorite';

    protected $fillable = [
        'user_id',
        'employee_id',
    ];
}
