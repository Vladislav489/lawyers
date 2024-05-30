<?php

namespace App\Models\CoreEngine\ProjectModels\Chat;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class Chat extends BaseModel
{
    protected $table = 'chat';

    protected $fillable = [
        'name',
        'is_group',
        'is_deleted',
        'is_archive',
        'user_id',
        'updated_at'
    ];
}
