<?php

namespace App\Models\CoreEngine\ProjectModels\Company;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class Company extends BaseModel
{
    protected $table = 'company';

    protected $fillable = [
        'name',
        'is_deleted',
        'is_archive',
        'owner_id',
    ];
}
