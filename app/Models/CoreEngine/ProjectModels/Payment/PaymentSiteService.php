<?php

namespace App\Models\CoreEngine\ProjectModels\Payment;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class PaymentSiteService extends BaseModel
{
    protected $table = 'payment_site_service';

    protected $fillable = [
        'amount',
        'user_id',
    ];
}
