<?php

namespace App\Models\CoreEngine\ProjectModels\File;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class FileSend extends BaseModel
{
    protected $table = 'file_send';

    protected $fillable = [
        'name',
        'virtual_path',
        'period_start',
        'period_end',
        'is_deleted',
        'is_archive',
        'file_id',
    ];
}
