<?php

namespace App\Models\CoreEngine\ProjectModels\Vacancy;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class VacancyDocumentationAccess extends BaseModel
{
    protected $table = 'vacancy_documentation_access';

    protected $fillable = [
        'period_start',
        'period_end',
        'is_deleted',
        'user_id',
        'documentation_id',
    ];
}
