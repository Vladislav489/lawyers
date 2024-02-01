<?php

namespace Database\Factories\CoreEngine\ProjectModels\HelpData;

use App\Models\CoreEngine\ProjectModels\HelpData\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CoreEngine\ProjectModels\HelpData\State>
 */
class StateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => 'Область ' . fake('ru')->word,
            'country_id' => Country::inRandomOrder()->first()->id
        ];
    }
}
