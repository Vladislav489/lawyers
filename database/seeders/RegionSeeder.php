<?php

namespace Database\Seeders;

use App\Models\CoreEngine\ProjectModels\HelpData\Region;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Region::factory(16)->create();
    }
}
