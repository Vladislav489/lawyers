<?php

namespace App\Models\CoreEngine\ProjectModels\HelpData;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class City extends BaseModel
{
    protected $table = 'city';

    protected $fillable = [
        'name',
        'is_deleted',
        'district_id',
        'state_id',
        'country_id',
    ];
}
