<?php

namespace Database\Factories\CoreEngine\ProjectModels\HelpData;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CoreEngine\ProjectModels\HelpData\Country>
 */
class CountryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake('ru')->country
        ];
    }
}
