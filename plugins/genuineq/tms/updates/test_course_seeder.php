<?php

namespace Genuineq\Tms\Updates;

use Genuineq\Tms\Models\Course;
use Genuineq\Tms\Models\CourseCategory;
use Genuineq\Tms\Models\CourseSkill;
use October\Rain\Database\Updates\Seeder;
use Illuminate\Support\Facades\App;
use Config;
use Faker;

class TestCourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* Check if the FAKE data should be added in DB. */
        if (env('TMS_ADD_FAKE_COURSES', false)) {
            $faker = Faker\Factory::create();

            $courseNumber = 100;
            $totalCategoriesNumber = 12;
            $totalSkillsNumber = 30;
            /**
             * Create courses
             */
            for ($i=0; $i < $courseNumber; $i++) {

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
            for ($i=1; $i <= $courseNumber; $i++) {
                $categoriesNumber = $faker->numberBetween($min = 1, $max = 5);

                $categories = array();
                for ($j=0; $j < $categoriesNumber; $j++) {
                    $category = $faker->numberBetween($min = 1, $max = $totalCategoriesNumber);

                    while (in_array($category, $categories)) {
                        $category = $faker->numberBetween($min = 1, $max = $totalCategoriesNumber);
                    }
                    $categories[] = $category;
                }

                for ($j=0; $j < $categoriesNumber; $j++) {
                    CourseCategory::create([
                        'course_id' => $i,
                        'category_id' => $categories[$j],
                    ]);
                }
            }

            /**
             * Add skills to courses
             */
            for ($i=1; $i <= $courseNumber; $i++) {
                $skillsNumber = $faker->numberBetween($min = 1, $max = 5);

                $skills = array();
                for ($j=0; $j < $skillsNumber; $j++) {
                    $skill = $faker->numberBetween($min = 1, $max = $totalSkillsNumber);

                    while (in_array($skill, $skills)) {
                        $skill = $faker->numberBetween($min = 1, $max = $totalSkillsNumber);
                    }
                    $skills[] = $skill;
                }

                for ($j=0; $j < $skillsNumber; $j++) {
                    CourseSkill::create([
                        'course_id' => $i,
                        'skill_id' => $skills[$j],
                    ]);
                }
            }
        }
    }
}
