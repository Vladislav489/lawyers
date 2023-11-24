<?php

namespace App\Models\CoreEngine\ProjectModels\Company;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class CompanyRating extends BaseModel
{
    protected $table = 'company_rating';

    protected $fillable = [
        'grade',
        'is_deleted',
        'user_id',
        'company_id',
    ];
}
