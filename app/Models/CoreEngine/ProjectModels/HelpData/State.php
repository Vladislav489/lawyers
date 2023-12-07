<?php

namespace App\Models\CoreEngine\ProjectModels\HelpData;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class State extends BaseModel
{
    protected $table = 'state';

    protected $fillable = [
        'name',
        'is_deleted',
        'country_id',
    ];
}
