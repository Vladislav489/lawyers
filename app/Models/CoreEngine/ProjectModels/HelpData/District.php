<?php

namespace App\Models\CoreEngine\ProjectModels\HelpData;

use App\Models\CoreEngine\ProjectModels\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class District extends BaseModel
{
    use HasFactory;
    protected $table = 'district';

    protected $fillable = [
        'name',
        'is_deleted',
        'state_id',
        'country_id',
    ];

    public $timestamps = false;
}
