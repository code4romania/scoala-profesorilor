<?php

namespace Genuineq\Tms\Updates;

use Genuineq\Tms\Models\Skill;
use October\Rain\Database\Updates\Seeder;
use Illuminate\Support\Facades\App;
use Config;
use Faker;

class TestSkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* Check if the FAKE data should be added in DB. */
        if (env('TMS_ADD_FAKE_SKILLS', false)) {
            $faker = Faker\Factory::create();

            for ($i=0; $i < 30; $i++) {

                $name = $faker->sentence($nbWords = 6, $variableNbWords = true);

                Skill::create([
                    'name' => $name,
                    'slug' => str_slug($name, '-'),
                    'description' => $faker->paragraph($nbSentences = 3, $variableNbSentences = true),
                ]);
            }
        }
    }
}
