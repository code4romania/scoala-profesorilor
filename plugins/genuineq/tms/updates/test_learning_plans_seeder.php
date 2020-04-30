<?php namespace Genuineq\Tms\Updates;

use Genuineq\Tms\Models\LearningPlan;
use Genuineq\Tms\Models\LearningPlansCourse;
use Genuineq\Tms\Models\TeacherLearningPlan;
use October\Rain\Database\Updates\Seeder;
use Illuminate\Support\Facades\App;
use Faker;

class TestLearningPlansSeeder extends Seeder
{
    public function run()
    {
        /* Check if the environment is either local OR development. */
        if (App::environment(['local', 'development'])) {
            $faker = Faker\Factory::create('ro_RO');

            $totalTeachersNumber = 77;
            $totalCoursesNumber = 100;

            /**
             * Creating learning plans.
             */
            for ($i=0; $i < $totalTeachersNumber; $i++) {
                LearningPlan::create([
                    'year' => date('Y'),
                    'semester' => 1,
                ]);
            }

            /**
             * Add courses to learning plans.
             */
            for ($i=1; $i <= $totalTeachersNumber; $i++) {
                $coursesNumber = $faker->numberBetween($min = 1, $max = 5);

                $courses = array();
                for ($j=0; $j < $coursesNumber; $j++) {
                    $course = $faker->numberBetween($min = 1, $max = $totalCoursesNumber);

                    while (in_array($course, $courses)) {
                        $course = $faker->numberBetween($min = 1, $max = $totalCoursesNumber);
                    }
                    $courses[] = $course;
                }

                for ($j=0; $j < $coursesNumber; $j++) {
                    LearningPlansCourse::create([
                        'learning_plan_id' => $i,
                        'course_id' => $courses[$j],
                    ]);
                }
            }

            /**
             * Add learning plans to teachers.
             */
            for ($j=0; $j < $totalTeachersNumber; $j++) {
                TeacherLearningPlan::create([
                    'teacher_id' => $j,
                    'learning_plan_id' => $j,
                ]);
            }
        }
    }
}
