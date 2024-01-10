<?php

namespace App\Models\CoreEngine\ProjectModels\User;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class UserIgnory extends BaseModel
{
    protected $table = 'user_ignory';

    protected $fillable = [
        'user_id',
        'target_user_id',
    ];
}
