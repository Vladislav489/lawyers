<?php

namespace App\Models\CoreEngine\ProjectModels\Service;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class Service extends BaseModel
{
    protected $table = 'service';

    protected $fillable = [
        'name',
        'is_deleted',
        'is_archive',
        'type_id',
    ];
}
