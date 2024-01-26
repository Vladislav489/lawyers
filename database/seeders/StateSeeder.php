<?php

namespace Database\Seeders;

use App\Models\CoreEngine\ProjectModels\HelpData\Country;
use App\Models\CoreEngine\ProjectModels\HelpData\State;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        State::factory(20)->create();
    }
}
