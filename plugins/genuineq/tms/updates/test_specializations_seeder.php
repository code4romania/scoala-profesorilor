<?php namespace Genuineq\Tms\Updates;

use Genuineq\Tms\Models\Specialization;
use October\Rain\Database\Updates\Seeder;
use Illuminate\Support\Facades\App;
use Config;
use Faker;

class TestSpecializationsSeeder extends Seeder
{
    public function run()
    {
        /* Check if the FAKE data should be added in DB. */
        if (env('TMS_ADD_FAKE_SPECIALIZATIONS', false)) {
            $faker = Faker\Factory::create();

            $totalSpecializationsNumber = 23;

            for ($i=0; $i < $totalSpecializationsNumber; $i++) {

                $name = $faker->sentence($nbWords = 2, $variableNbWords = true);

                Specialization::create([
                    'name' => $name,
                    'diacritic' => $name,
                    'description' => $faker->paragraph($nbSentences = 3, $variableNbSentences = true),
                ]);
            }
        }
    }
}
