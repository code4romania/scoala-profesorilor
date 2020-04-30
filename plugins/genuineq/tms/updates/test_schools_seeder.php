<?php namespace Genuineq\Tms\Updates;

use Genuineq\Tms\Models\School;
use Genuineq\Tms\Models\SchoolTeacher;
use October\Rain\Database\Updates\Seeder;
use Illuminate\Support\Facades\App;
use Faker;

class TestSchoolsSeeder extends Seeder
{
    public function run()
    {
        /* Check if the environment is either local OR development. */
        if (App::environment(['local', 'development'])) {
            $faker = Faker\Factory::create('ro_RO');

            $totalTeachersNumber = 77;
            $totalSchoolsNumber = 33;

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
                $teachersNumber = $faker->numberBetween($min = 1, $max = 7);

                $teachers = array();
                for ($j=0; $j < $teachersNumber; $j++) {
                    $teacher = $faker->numberBetween($min = 1, $max = $totalTeachersNumber);

                    while (in_array($teacher, $teachers)) {
                        $teacher = $faker->numberBetween($min = 1, $max = $totalTeachersNumber);
                    }
                    $teachers[] = $teacher;
                }

                for ($j=0; $j < $teachersNumber; $j++) {
                    SchoolTeacher::create([
                        'school_id' => $i,
                        'teacher_id' => $teachers[$j],
                    ]);
                }
            }
        }
    }
}
