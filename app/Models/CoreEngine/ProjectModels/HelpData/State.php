<?php

namespace App\Models\CoreEngine\ProjectModels\HelpData;

use App\Models\CoreEngine\ProjectModels\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class State extends BaseModel
{
    use HasFactory;
    protected $table = 'state';

    protected $fillable = [
        'name',
        'is_deleted',
        'country_id',
    ];

    public $timestamps = false;
}
