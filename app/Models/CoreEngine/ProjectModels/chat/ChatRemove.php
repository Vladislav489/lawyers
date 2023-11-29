<?php

namespace App\Models\CoreEngine\ProjectModels\Chat;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class ChatRemove extends BaseModel
{
    protected $table = 'chat_remove';

    protected $fillable = [
        'chat_id',
        'user_id',
    ];
}
