<?php

namespace App\Models\CoreEngine\ProjectModels\HelpData;

use App\Models\CoreEngine\ProjectModels\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class City extends BaseModel
{
    use HasFactory;
    protected $table = 'city';

    protected $fillable = [
        'name',
        'is_deleted',
        'district_id',
        'state_id',
        'country_id',
    ];

    public $timestamps = false;
}
