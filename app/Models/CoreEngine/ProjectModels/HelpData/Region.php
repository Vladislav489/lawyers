<?php

namespace App\Models\CoreEngine\ProjectModels\HelpData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

    protected $table = 'regions';

    protected $fillable = [
        'name',
        'is_deleted'
    ];

    public $timestamps = false;
}
