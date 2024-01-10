<?php

namespace App\Models\CoreEngine\ProjectModels\Chat;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class ChatInvitation extends BaseModel
{
    protected $table = 'chat_invitation';

    protected $fillable = [
        'text',
        'is_deleted',
        'is_archive',
        'chat_id',
        'user_id',
        'target_user_id',
    ];
}
