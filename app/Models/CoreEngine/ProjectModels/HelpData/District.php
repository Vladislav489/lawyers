<?php

namespace App\Models\CoreEngine\ProjectModels\HelpData;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class District extends BaseModel
{
    protected $table = 'district';

    protected $fillable = [
        'name',
        'is_deleted',
        'state_id',
        'country_id',
    ];
}
