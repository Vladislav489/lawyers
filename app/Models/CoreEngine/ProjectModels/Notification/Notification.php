<?php

namespace App\Models\CoreEngine\ProjectModels\Notification;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        'updated_at',
        'message',
        'user_id',
        'is_read',
        'is_deleted'
    ];
}
