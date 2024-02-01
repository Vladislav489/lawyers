<?php

namespace Database\Factories\CoreEngine\ProjectModels\HelpData;

use App\Models\CoreEngine\ProjectModels\HelpData\Country;
use App\Models\CoreEngine\ProjectModels\HelpData\State;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CoreEngine\ProjectModels\HelpData\District>
 */
class DistrictFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => 'Район ' . fake('ru')->word,
            'state_id' => State::inRandomOrder()->first()->id,
            'country_id' => Country::inRandomOrder()->first()->id,
        ];
    }
}
