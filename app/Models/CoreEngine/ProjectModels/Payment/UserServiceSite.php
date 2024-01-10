<?php

namespace App\Models\CoreEngine\ProjectModels\Payment;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class UserServiceSite extends BaseModel
{
    protected $table = 'user_service_site';

    protected $fillable = [
        'name',
        'price',
    ];
}
