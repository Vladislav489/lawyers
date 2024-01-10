<?php

namespace App\Models\CoreEngine\ProjectModels\Question;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class UserQuestion extends BaseModel
{
    protected $table = 'user_question';

    protected $fillable = [
        'name',
        'text',
        'status',
        'period_start',
        'period_end',
        'is_deleted',
        'is_donate',
        'user_id',
    ];
}
