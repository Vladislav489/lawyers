<?php

namespace Database\Factories\CoreEngine\ProjectModels\HelpData;

use App\Models\CoreEngine\ProjectModels\HelpData\District;
use App\Models\CoreEngine\ProjectModels\HelpData\State;
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
            'district_id' => District::inRandomOrder()->first()->id,
            'state_id' => State::inRandomOrder()->first()->id,
            'Country_id' => District::inRandomOrder()->first()->id,
        ];
    }
}
