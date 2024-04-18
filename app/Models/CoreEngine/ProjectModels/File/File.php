<?php

namespace App\Models\CoreEngine\ProjectModels\File;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class File extends BaseModel
{
    protected $table = 'file';

    protected $fillable = [
        'name',
        'path',
        'is_deleted',
        'is_archive',
        'is_private',
        'user_id',
        'vacancy_id'
    ];
}
