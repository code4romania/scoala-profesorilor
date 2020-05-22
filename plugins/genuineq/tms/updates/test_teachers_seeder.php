<?php namespace Genuineq\Tms\Updates;

use Genuineq\Tms\Models\Teacher;
use October\Rain\Database\Updates\Seeder;
use Illuminate\Support\Facades\App;
use Faker;

class TestTeachersSeeder extends Seeder
{
    public function run()
    {
        /* Check if the environment is either local OR development. */
        if (App::environment(['local', 'development'])) {
            $faker = Faker\Factory::create('ro_RO');

            for ($i=0; $i < 10; $i++) {

                $name = $faker->name();

                $teacher = Teacher::create([
                    'name' => $name,
                    'slug' => str_slug($name, '-'),
                    'phone' => $faker->tollFreePhoneNumber(),
                    'birth_date' => $faker->dateTimeThisCentury->format('Y-m-d'),
                    'address_id' => $faker->numberBetween($min = 1, $max = 13851),
                    'seniority_level_id' => $faker->numberBetween($min = 1, $max = 5),
                    'school_level_id' => $faker->numberBetween($min = 1, $max = 5),
                ]);
            }
        }
    }
}
