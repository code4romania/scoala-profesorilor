<?php namespace Genuineq\Tms\Updates;

use Genuineq\Tms\Models\Specialization;
use October\Rain\Database\Updates\Seeder;
use Illuminate\Support\Facades\App;
use Faker;

class TestSpecializationsSeeder extends Seeder
{
    public function run()
    {
        /* Check if the environment is either local OR development. */
        if (App::environment(['local', 'development'])) {
            $faker = Faker\Factory::create();

            for ($i=0; $i < 23; $i++) {

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
