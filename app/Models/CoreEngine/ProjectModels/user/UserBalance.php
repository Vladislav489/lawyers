<?php

namespace App\Models\CoreEngine\ProjectModels\User;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class UserBalance extends BaseModel
{
    protected $table = 'user_balance';

    protected $fillable = [
        'balance',
        'is_deleted',
        'user_id',
    ];
}
