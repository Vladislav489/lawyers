<?php

namespace App\Models\CoreEngine\ProjectModels\Contract;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class Contract extends BaseModel
{
    protected $table = 'contract';

    protected $fillable = [
        'description',
        'user_id',
    ];
}
