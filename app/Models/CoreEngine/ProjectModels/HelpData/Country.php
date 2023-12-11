<?php

namespace App\Models\CoreEngine\ProjectModels\HelpData;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class Country extends BaseModel
{
    protected $table = 'country';

    protected $fillable = [
        'name',
        'is_deleted',
    ];
}
