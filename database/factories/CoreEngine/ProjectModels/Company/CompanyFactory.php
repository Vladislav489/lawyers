<?php

namespace Database\Factories\CoreEngine\ProjectModels\Company;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CoreEngine\ProjectModels\Company\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => 'Компания ' . fake('ru')->company,
            'owner_id' => rand(1, 25)
        ];
    }
}
