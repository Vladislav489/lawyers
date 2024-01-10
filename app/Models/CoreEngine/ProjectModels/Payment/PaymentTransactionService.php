<?php

namespace App\Models\CoreEngine\ProjectModels\Payment;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class PaymentTransactionService extends BaseModel
{
    protected $table = 'payment_transaction_service';

    protected $fillable = [
        'amount',
        'status',
        'user_id',
        'target_user_id',
    ];
}
