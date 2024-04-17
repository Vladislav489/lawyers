<?php

namespace App\Models\CoreEngine\ProjectModels\Company;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class CompanyBranch extends BaseModel
{
    protected $table = 'company_branch';

    protected $fillable = [
        'contact_info',
        'address_map',
        'is_deleted',
        'company_id',
        'region_id',
        'city_id',
    ];
}
