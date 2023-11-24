<?php

namespace App\Models\CoreEngine\ProjectModels\Service;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class Tag extends BaseModel
{
    protected $table = 'tag';

    protected $fillable = [
        'name',
    ];
}
