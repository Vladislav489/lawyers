<?php

namespace App\Models\CoreEngine\ProjectModels\HelpData;

use App\Models\CoreEngine\ProjectModels\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Country extends BaseModel
{
    use HasFactory;
    protected $table = 'country';

    protected $fillable = [
        'name',
        'is_deleted',
    ];

    public $timestamps = false;
}
