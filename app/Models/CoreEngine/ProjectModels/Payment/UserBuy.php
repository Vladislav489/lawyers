<?php

namespace App\Models\CoreEngine\ProjectModels\Payment;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class UserBuy extends BaseModel
{
    protected $table = 'user_buy';

    protected $fillable = [
        'amount',
        'user_id',
        'type_service_id',
    ];
}
