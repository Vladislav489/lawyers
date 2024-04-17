<?php

namespace Database\Factories\CoreEngine\ProjectModels\HelpData;

use App\Models\CoreEngine\ProjectModels\HelpData\Region;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CoreEngine\ProjectModels\HelpData\Country>
 */
class RegionFactory extends Factory
{
    private static $order = 1;

    protected $model = Region::class;

    public function definition()
    {
        return [
            'name' => 'регион ' . self::$order++
        ];
    }
}
