<?php namespace Genuineq\Tms\Updates;

use Genuineq\Tms\Models\School;
use Genuineq\Tms\Models\SchoolTeacher;
use October\Rain\Database\Updates\Seeder;
use Illuminate\Support\Facades\App;
use Config;
use Faker;

class TestSchoolsSeeder extends Seeder
{
    public function run()
    {
        /* Check if the FAKE data should be added in DB. */
        if (env('TMS_ADD_FAKE_SCHOOLS', false)) {
            $faker = Faker\Factory::create('ro_RO');

            $totalSchoolsNumber = 10;

            for ($i=0; $i < $totalSchoolsNumber; $i++) {

                $name = $faker->sentence($nbWords = 3, $variableNbWords = true);

                School::create([
                    'name' => $name,
                    'slug' => str_slug($name, '-'),
                    'phone' => $faker->tollFreePhoneNumber(),
                    'email' => $faker->companyEmail(),
                    'principal' => $faker->name(),
                    'inspectorate_id' => $faker->numberBetween($min = 1, $max = 47),
                    'address_id' => $faker->numberBetween($min = 1, $max = 13851),
                    'description' => $faker->paragraph($nbSentences = 3, $variableNbSentences = true),
                    'contact_name' => $faker->name(),
                    'contact_email' => $faker->companyEmail(),
                    'contact_phone' => $faker->tollFreePhoneNumber(),
                    'contact_role' => $faker->sentence($nbWords = 3, $variableNbWords = true),
                ]);
            }

            /**
             * Add teachers to schools
             */
            for ($i=1; $i <= $totalSchoolsNumber; $i++) {
                if ($i == $totalSchoolsNumber) {
                    SchoolTeacher::create([
                        'school_id' => $i,
                        'teacher_id' => $i,
                    ]);
                } else {
                    SchoolTeacher::create([
                        'school_id' => $i,
                        'teacher_id' => $i,
                    ]);

                    SchoolTeacher::create([
                        'school_id' => $i,
                        'teacher_id' => ($i + 1),
                    ]);
                }
            }
        }
    }
}
