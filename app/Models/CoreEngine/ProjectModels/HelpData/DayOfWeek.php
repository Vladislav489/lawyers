<?php

namespace App\Models\CoreEngine\ProjectModels\HelpData;

use Illuminate\Database\Eloquent\Model;

class DayOfWeek extends Model
{
    protected $table = 'days_of_week';

    protected $fillable = [
        'name'
    ];
}
