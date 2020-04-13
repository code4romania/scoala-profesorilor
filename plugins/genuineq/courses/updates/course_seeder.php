<?php

namespace Genuineq\Courses\Updates;

use Genuineq\Courses\Models\Course;
use Genuineq\Courses\Models\CourseCategory;
use Genuineq\Courses\Models\CourseSkill;
use October\Rain\Database\Updates\Seeder;
use Faker;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        /**
         * Create courses
         */
        for ($i=0; $i < 100; $i++) {

            $name = $faker->sentence($nbWords = 6, $variableNbWords = true);
            $accredited = $faker->numberBetween($min = 0, $max = 1);
            $credits = ($accredited) ? ($faker->numberBetween($min = 1, $max = 50)) : (0);

            Course::create([
                'name' => $name,
                'slug' => str_slug($name, '-'),
                'description' => $faker->paragraph($nbSentences = 80, $variableNbSentences = true),
                'supplier_id' => $faker->numberBetween($min = 1, $max = 10),
                'accredited' => $accredited,
                'credits' => $credits,
                'address' => $faker->address(),
                'duration' => $faker->randomFloat($nbMaxDecimals = 1, $min = 1, $max = 100),
                'price' => $faker->randomFloat($nbMaxDecimals = 2, $min = 100, $max = 3000),
                'start_date' => $faker->dateTimeBetween($startDate = 'now', $endDate = '+6 months', $timezone = null),
                'end_date' => $faker->dateTimeBetween($startDate = '+6 months', $endDate = '+1 year', $timezone = null),
            ]);
        }

        /**
         * Add courses to categories
         */
        for ($i=1; $i <= 100; $i++) {
            $sp1 = $faker->numberBetween($min = 1, $max = 30);
            $sp2 = $faker->numberBetween($min = 1, $max = 30);
            $sp3 = $faker->numberBetween($min = 1, $max = 30);

            while ($sp2 == $sp1) {
                $sp2 = $faker->numberBetween($min = 1, $max = 30);
            }

            while (($sp3 == $sp1) || ($sp3 == $sp2)) {
                $sp3 = $faker->numberBetween($min = 1, $max = 30);
            }

            CourseCategory::create([
                'course_id' => $i,
                'category_id' => $sp1,
            ]);

            CourseCategory::create([
                'course_id' => $i,
                'category_id' => $sp2,
            ]);

            CourseCategory::create([
                'course_id' => $i,
                'category_id' => $sp3,
            ]);
        }

        /**
         * Add skills to courses
         */
        for ($i=1; $i <= 100; $i++) {
            $sp1 = $faker->numberBetween($min = 1, $max = 30);
            $sp2 = $faker->numberBetween($min = 1, $max = 30);
            $sp3 = $faker->numberBetween($min = 1, $max = 30);

            while ($sp2 == $sp1) {
                $sp2 = $faker->numberBetween($min = 1, $max = 30);
            }

            while (($sp3 == $sp1) || ($sp3 == $sp2)) {
                $sp3 = $faker->numberBetween($min = 1, $max = 30);
            }

            CourseSkill::create([
                'course_id' => $i,
                'skill_id' => $sp1,
            ]);

            CourseSkill::create([
                'course_id' => $i,
                'skill_id' => $sp2,
            ]);

            CourseSkill::create([
                'course_id' => $i,
                'skill_id' => $sp3,
            ]);
        }
    }
}