<?php

namespace App\Models\CoreEngine\ProjectModels\Service;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class ServiceType extends BaseModel
{
    protected $table = 'service_type';

    protected $fillable = [
        'name',
        'is_deleted',
    ];
}
