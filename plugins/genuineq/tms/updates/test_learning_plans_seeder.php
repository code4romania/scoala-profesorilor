<?php namespace Genuineq\Tms\Updates;

use Genuineq\Tms\Models\Course;
use Genuineq\Tms\Models\Teacher;
use Genuineq\Tms\Models\LearningPlan;
use Genuineq\Tms\Models\LearningPlansCourse;
use October\Rain\Database\Updates\Seeder;
use Illuminate\Support\Facades\App;
use Config;
use Faker;

class TestLearningPlansSeeder extends Seeder
{
    public function run()
    {
        /* Check if the FAKE data should be added in DB. */
        if (env('TMS_ADD_FAKE_LEARNING_PLANS', false)) {
            $faker = Faker\Factory::create('ro_RO');

            $totalYearsNumber = 5;
            $totalSemestersNumber = $totalYearsNumber * 2;
            $totalTeachersNumber = 10;
            $totalCoursesNumber = 100;

            /**
             * Creating learning plans.
             */
            for ($teacherNr=1; $teacherNr <= $totalTeachersNumber; $teacherNr++) {
                for ($semesterNr=1; $semesterNr <= $totalSemestersNumber ; $semesterNr++) {
                    if ($semesterNr == $totalSemestersNumber) {
                        $learningPlan = LearningPlan::create([
                            'teacher_id' =>  Teacher::find($teacherNr)->id,
                            'semester_id' => $semesterNr,
                            'status' => 1,
                        ]);
                    } else {
                        $learningPlan = LearningPlan::create([
                            'teacher_id' =>  Teacher::find($teacherNr)->id,
                            'semester_id' => $semesterNr,
                            'status' => 0,
                        ]);
                    }

                    $schoolNr = ($teacherNr == $totalTeachersNumber) ? (1) : (2);

                    for ($school=0; $school < $schoolNr ; $school++) {

                        $coursesNumber = 6;
                        $courses = array();
                        for ($j=0; $j < $coursesNumber; $j++) {
                            $course = $faker->numberBetween($min = 1, $max = $totalCoursesNumber);

                            while (in_array($course, $courses)) {
                                $course = $faker->numberBetween($min = 1, $max = $totalCoursesNumber);
                            }
                            $courses[] = $course;
                        }

                        for ($j=0; $j < $coursesNumber; $j++) {

                            switch ($j) {
                                case 1:
                                    LearningPlansCourse::create([
                                        'learning_plan_id' => $learningPlan->id,
                                        'course_id' => $courses[$j],
                                        'mandatory' => 0,
                                        'requestable_id' => ($teacherNr + $school),
                                        'requestable_type' => 'Genuineq\Tms\Models\School',
                                        'covered_costs' => $faker->numberBetween($min = 0, $max = Course::find($courses[$j])->price),
                                        'status' => 'proposed',
                                    ]);
                                    break;

                                case 2:
                                    LearningPlansCourse::create([
                                        'learning_plan_id' => $learningPlan->id,
                                        'course_id' => $courses[$j],
                                        'mandatory' => 0,
                                        'requestable_id' => ($teacherNr + $school),
                                        'requestable_type' => 'Genuineq\Tms\Models\School',
                                        'covered_costs' => $faker->numberBetween($min = 0, $max = Course::find($courses[$j])->price),
                                        'status' => 'accepted',
                                    ]);
                                    break;

                                case 3:
                                    LearningPlansCourse::create([
                                        'learning_plan_id' => $learningPlan->id,
                                        'course_id' => $courses[$j],
                                        'mandatory' => 0,
                                        'requestable_id' => ($teacherNr + $school),
                                        'requestable_type' => 'Genuineq\Tms\Models\School',
                                        'covered_costs' => $faker->numberBetween($min = 0, $max = Course::find($courses[$j])->price),
                                        'status' => 'declined',
                                    ]);
                                    break;

                                case 2:
                                    LearningPlansCourse::create([
                                        'learning_plan_id' => $learningPlan->id,
                                        'course_id' => $courses[$j],
                                        'mandatory' => 0,
                                        'requestable_id' => $teacherNr,
                                        'requestable_type' => 'Genuineq\Tms\Models\Teacher',
                                        'covered_costs' => $faker->numberBetween($min = 0, $max = Course::find($courses[$j])->price),
                                        'status' => 'proposed',
                                    ]);
                                    break;

                                case 3:
                                    LearningPlansCourse::create([
                                        'learning_plan_id' => $learningPlan->id,
                                        'course_id' => $courses[$j],
                                        'mandatory' => 0,
                                        'requestable_id' => $teacherNr,
                                        'requestable_type' => 'Genuineq\Tms\Models\Teacher',
                                        'covered_costs' => $faker->numberBetween($min = 0, $max = Course::find($courses[$j])->price),
                                        'status' => 'accepted',
                                    ]);
                                    break;

                                case 4:
                                    LearningPlansCourse::create([
                                        'learning_plan_id' => $learningPlan->id,
                                        'course_id' => $courses[$j],
                                        'mandatory' => 0,
                                        'requestable_id' => $teacherNr,
                                        'requestable_type' => 'Genuineq\Tms\Models\Teacher',
                                        'covered_costs' => $faker->numberBetween($min = 0, $max = Course::find($courses[$j])->price),
                                        'status' => 'declined',
                                    ]);
                                    break;

                                case 5:
                                    LearningPlansCourse::create([
                                        'learning_plan_id' => $learningPlan->id,
                                        'course_id' => $courses[$j],
                                        'mandatory' => 1,
                                        'school_id' => ($teacherNr + $school),
                                        'covered_costs' => $faker->numberBetween($min = 0, $max = Course::find($courses[$j])->price),
                                        'status' => 'accepted',
                                    ]);
                                    break;

                                case 6:
                                    LearningPlansCourse::create([
                                        'learning_plan_id' => $learningPlan->id,
                                        'course_id' => $courses[$j],
                                        'mandatory' => 0,
                                        'status' => 'accepted',
                                    ]);
                                    break;
                            }
                        }
                    }
                }
            }
        }
    }
}
