<?php

namespace Database\Factories\CoreEngine\ProjectModels\HelpData;

use App\Models\CoreEngine\ProjectModels\HelpData\Region;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CoreEngine\ProjectModels\HelpData\City>
 */
class CityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake('ru')->city,
            'region_id' => Region::inRandomOrder()->first()->id,
        ];
    }
}
