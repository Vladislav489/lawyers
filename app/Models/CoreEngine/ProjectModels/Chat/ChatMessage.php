<?php

namespace App\Models\CoreEngine\ProjectModels\Chat;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class ChatMessage extends BaseModel
{
    protected $table = 'chat_message';

    protected $fillable = [
        'message',
        'recipients',
        'is_archive',
        'is_deleted',
        'chat_id',
        'message_type_id',
        'sender_user_id',
        'target_user_id',
        'is_read',
        'updated_at'
    ];
}
