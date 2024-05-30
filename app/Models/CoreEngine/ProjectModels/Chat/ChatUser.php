<?php

namespace App\Models\CoreEngine\ProjectModels\Chat;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class ChatUser extends BaseModel
{
    protected $table = 'chat_user';

    protected $fillable = [
        'created_at',
        'updated_at',
        'is_read',
        'is_block',
        'is_archive',
        'is_deleted',
        'chat_id',
        'user_id',
    ];
}
