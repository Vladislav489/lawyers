<?php

namespace App\Models\CoreEngine\ProjectModels\Chat;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class ChatUser extends BaseModel
{
    protected $table = 'chat';

    protected $fillable = [
        'is_read',
        'is_block',
        'is_archive',
        'is_deleted',
        'chat_id',
        'user_id',
    ];
}
