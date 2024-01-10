<?php

namespace App\Models\CoreEngine\ProjectModels\Search;

use App\Models\CoreEngine\ProjectModels\BaseModel;

class GlobalSearch extends BaseModel
{
    protected $table = 'global_search';

    protected $fillable = [
        'long_search',
        'table_name',
        'table_item_id',
    ];
}
