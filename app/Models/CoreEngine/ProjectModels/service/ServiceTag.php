<?php

namespace App\Models\CoreEngine\ProjectModels\Service;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class ServiceTag extends BaseModel
{
    protected $table = 'service_tag';

    protected $fillable = [
        'tag_id',
        'service_id',
    ];
}
