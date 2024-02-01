<?php

namespace App\Models\CoreEngine\ProjectModels\Company;

use App\Models\CoreEngine\ProjectModels\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends BaseModel
{
    use HasFactory;
    protected $table = 'company';

    protected $fillable = [
        'name',
        'is_deleted',
        'is_archive',
        'owner_id',
    ];

    public $timestamps = false;
}
