<?php namespace Genuineq\Tms\Updates;

use Genuineq\Tms\Models\TaughtSubject;
use October\Rain\Database\Updates\Seeder;
use Illuminate\Support\Facades\App;
use Config;
use Faker;

class TestTaughtSubjectsSeeder extends Seeder
{
    public function run()
    {
        /* Check if the FAKE data should be added in DB. */
        if (env('TMS_ADD_FAKE_TAUGHT_SUBJECTS', false)) {
            $faker = Faker\Factory::create();

            for ($i=0; $i < 12; $i++) {

                $name = $faker->sentence($nbWords = 2, $variableNbWords = true);

                TaughtSubject::create([
                    'name' => $name,
                    'diacritic' => $name,
                    'description' => $faker->paragraph($nbSentences = 3, $variableNbSentences = true),
                ]);
            }
        }
    }
}
