<?php namespace Genuineq\Tms\Updates;

use Genuineq\Tms\Models\Appraisal;
use Genuineq\Tms\Models\School;
use Genuineq\Tms\Models\Course;
use Genuineq\Tms\Models\Teacher;
use Genuineq\Tms\Models\LearningPlan;
use Genuineq\Tms\Models\SchoolTeacher;
use Genuineq\Tms\Models\LearningPlansCourse;
use Genuineq\Tms\Models\Semester;
use October\Rain\Database\Updates\Seeder;
use Illuminate\Support\Facades\App;
use Genuineq\Tms\Classes\PeriodicTasks;
use Config;
use Faker;
use Log;

class TestSemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* Check if the FAKE data should be added in DB. */
        if (env('TMS_ADD_FAKE_SEMESTERS', false)) {
           $this->addFakeSemesters();
        } else {
            /**
             * Semester 1: August - January
             * Semester 2: February - June
             */

            /** Create the current semester. */
            Semester::create([
                'semester' => ((1 == date('n')) || (8 <= date('n'))) ? (1) : (2)
            ]);
        }
    }


    /**
     * Funtion that populates the Db with FAKE data.
     */
    private function addFakeSemesters()
    {
        $faker = Faker\Factory::create('ro_RO');
        $totalYearsNumber = 5;
        $totalSemestersNumber = $totalYearsNumber * 2;
        $totalSchoolsNumber = 10;
        $totalTeachersNumber = 10;
        $totalCoursesNumber = 100;
        $totalSpecializationsNumber = 23;
        $totalSkillsNumber = 30;

        $year = $totalYearsNumber;
        $semester = 1;
        for ($semesterNumber=1; $semesterNumber <= $totalSemestersNumber; $semesterNumber++) {
            /** In first semester we create schools and teachers. */
            if (1 == $semesterNumber) {
                /** Create the semester. */
                $activeSemester = Semester::create([ 'semester' => $semester, ]);
                $activeSemester->year = intval(date('Y')) - $year;
                $activeSemester->save();


                /** Create schools. This will also create a budget entry for each school. */
                for ($i=0; $i < $totalSchoolsNumber; $i++) {

                    $name = $faker->sentence($nbWords = 2, $variableNbWords = true);

                    $school = School::create([
                        'name' => $name,
                        'slug' => str_slug($name, '-'),
                        'phone' => $faker->tollFreePhoneNumber(),
                        'principal' => $faker->name(),
                        'inspectorate_id' => $faker->numberBetween($min = 1, $max = 47),
                        'address_id' => $faker->numberBetween($min = 1, $max = 13851),
                        'description' => $faker->paragraph($nbSentences = 3, $variableNbSentences = true),
                        'contact_name' => $faker->name(),
                        'contact_email' => $faker->companyEmail(),
                        'contact_phone' => $faker->tollFreePhoneNumber(),
                        'contact_role' => $faker->sentence($nbWords = 3, $variableNbWords = true),
                    ]);

                    $budget = $school->active_budget;
                    $budget->budget = $faker->numberBetween($min = 1, $max = 50000);
                    $budget->save();
                }

                /** Create teachers. This will also create a budget entry and a learning plan for each teacher. */
                for ($i=0; $i < $totalTeachersNumber; $i++) {

                    $name = $faker->name();

                    $teacher = Teacher::create([
                        'name' => $name,
                        'slug' => str_slug($name, '-'),
                        'phone' => $faker->tollFreePhoneNumber(),
                        'birth_date' => $faker->dateTimeThisCentury->format('Y-m-d'),
                        'address_id' => $faker->numberBetween($min = 1, $max = 13851),
                        'seniority_level_id' => $faker->numberBetween($min = 1, $max = 5),
                    ]);

                    $budget = $teacher->active_budget;
                    $budget->budget = $faker->numberBetween($min = 1, $max = 5000);
                    $budget->save();
                }

                /** Add teachers to schools */
                for ($i=1; $i <= $totalSchoolsNumber; $i++) {
                    for ($j=1; $j <= $faker->numberBetween($min = 1, $max = $totalTeachersNumber); $j++) {
                        SchoolTeacher::create([
                            'school_id' => $i,
                            'teacher_id' => $j,
                            'contract_type_id' => $faker->numberBetween($min = 1, $max = 3),
                            'school_level_id' => $faker->numberBetween($min = 1, $max = 7),
                            'grade_id' => $faker->numberBetween($min = 1, $max = 3),
                            'specialization_1_id' => $faker->numberBetween($min = 1, $max = $totalSpecializationsNumber),
                            'specialization_2_id' => $faker->numberBetween($min = 1, $max = $totalSpecializationsNumber),
                        ]);
                    }
                }
            } else {
                if (1 == $semester) {
                    PeriodicTasks::closeFirstSemester();
                } else {
                    PeriodicTasks::closeSecondSemester();
                }

                if (1 == $semester) {
                    $semester = 2;
                } else {
                    $semester = 1;
                    $year--;
                }

                /** Extract latest semester and update year. */
                $activeSemester = Semester::getLatest();
                $activeSemester->semester = $semester;
                $activeSemester->year = intval(date('Y')) - $year;
                $activeSemester->save();
            }

            /** Populate teacher learning plans */
            for ($teacherNr=1; $teacherNr <= $totalTeachersNumber; $teacherNr++) {
                /** Extract the teacher */
                $teacher = Teacher::find($teacherNr);

                /** Parse all teacher schools. */
                foreach ($teacher->schools as $key => $school) {
                    /** Create a number of different courses. */
                    $coursesNumber = $faker->numberBetween($min = 1, $max = 6);
                    $courses = array();
                    for ($j=0; $j < $coursesNumber; $j++) {
                        $course = $faker->numberBetween($min = 1, $max = $totalCoursesNumber);

                        while (in_array($course, $courses)) {
                            $course = $faker->numberBetween($min = 1, $max = $totalCoursesNumber);
                        }
                        $courses[] = $course;
                    }

                    /** Add courses to learning plan */
                    for ($j=0; $j < $coursesNumber; $j++) {
                        /** Extract the course */
                        $course = Course::find($courses[$j]);
                        /** Calculate the covered costs */
                        $schoolCoveredCosts = $faker->numberBetween($min = 0, $max = $course->price);
                        $teacherCoveredCosts = $course->price - $schoolCoveredCosts;

                        /** Add thr courses */
                        switch ($j) {
                            case 1:
                                LearningPlansCourse::create([
                                    'learning_plan_id' => $teacher->active_learning_plan->id,
                                    'course_id' => $courses[$j],
                                    'school_budget_id' => $school->active_budget_id,
                                    'school_covered_costs' => $schoolCoveredCosts,
                                    'teacher_budget_id' => $teacher->active_budget_id,
                                    'teacher_covered_costs' => $teacherCoveredCosts,
                                    'mandatory' => 0,
                                    'requestable_id' => $school->id,
                                    'requestable_type' => 'Genuineq\Tms\Models\School',
                                    'status' => 'proposed',
                                ]);
                                break;

                            case 2:
                                LearningPlansCourse::create([
                                    'learning_plan_id' => $teacher->active_learning_plan->id,
                                    'course_id' => $courses[$j],
                                    'school_budget_id' => $school->active_budget_id,
                                    'school_covered_costs' => $schoolCoveredCosts,
                                    'teacher_budget_id' => $teacher->active_budget_id,
                                    'teacher_covered_costs' => $teacherCoveredCosts,
                                    'mandatory' => 0,
                                    'requestable_id' => $school->id,
                                    'requestable_type' => 'Genuineq\Tms\Models\School',
                                    'status' => 'accepted',
                                ]);
                                break;

                            case 3:
                                LearningPlansCourse::create([
                                    'learning_plan_id' => $teacher->active_learning_plan->id,
                                    'course_id' => $courses[$j],
                                    'school_budget_id' => $school->active_budget_id,
                                    'school_covered_costs' => $schoolCoveredCosts,
                                    'teacher_budget_id' => $teacher->active_budget_id,
                                    'teacher_covered_costs' => $teacherCoveredCosts,
                                    'mandatory' => 0,
                                    'requestable_id' => $school->id,
                                    'requestable_type' => 'Genuineq\Tms\Models\School',
                                    'status' => 'declined',
                                ]);
                                break;

                            case 2:
                                LearningPlansCourse::create([
                                    'learning_plan_id' => $teacher->active_learning_plan->id,
                                    'course_id' => $courses[$j],
                                    'school_budget_id' => $school->active_budget_id,
                                    'school_covered_costs' => $schoolCoveredCosts,
                                    'teacher_budget_id' => $teacher->active_budget_id,
                                    'teacher_covered_costs' => $teacherCoveredCosts,
                                    'mandatory' => 0,
                                    'requestable_id' => $teacher->id,
                                    'requestable_type' => 'Genuineq\Tms\Models\Teacher',
                                    'status' => 'proposed',
                                ]);
                                break;

                            case 3:
                                LearningPlansCourse::create([
                                    'learning_plan_id' => $teacher->active_learning_plan->id,
                                    'course_id' => $courses[$j],
                                    'school_budget_id' => $school->active_budget_id,
                                    'school_covered_costs' => $schoolCoveredCosts,
                                    'teacher_budget_id' => $teacher->active_budget_id,
                                    'teacher_covered_costs' => $teacherCoveredCosts,
                                    'mandatory' => 0,
                                    'requestable_id' => $teacher->id,
                                    'requestable_type' => 'Genuineq\Tms\Models\Teacher',
                                    'status' => 'accepted',
                                ]);
                                break;

                            case 4:
                                LearningPlansCourse::create([
                                    'learning_plan_id' => $teacher->active_learning_plan->id,
                                    'course_id' => $courses[$j],
                                    'school_budget_id' => $school->active_budget_id,
                                    'school_covered_costs' => $schoolCoveredCosts,
                                    'teacher_budget_id' => $teacher->active_budget_id,
                                    'teacher_covered_costs' => $teacherCoveredCosts,
                                    'mandatory' => 0,
                                    'requestable_id' => $teacher->id,
                                    'requestable_type' => 'Genuineq\Tms\Models\Teacher',
                                    'status' => 'declined',
                                ]);
                                break;

                            case 5:
                                LearningPlansCourse::create([
                                    'learning_plan_id' => $teacher->active_learning_plan->id,
                                    'course_id' => $courses[$j],
                                    'school_budget_id' => $school->active_budget_id,
                                    'school_covered_costs' => $schoolCoveredCosts,
                                    'teacher_budget_id' => $teacher->active_budget_id,
                                    'teacher_covered_costs' => $teacherCoveredCosts,
                                    'mandatory' => 1,
                                    'status' => 'accepted',
                                ]);
                                break;

                            case 6:
                                LearningPlansCourse::create([
                                    'learning_plan_id' => $teacher->active_learning_plan->id,
                                    'course_id' => $courses[$j],
                                    'school_budget_id' => $school->active_budget_id,
                                    'school_covered_costs' => $schoolCoveredCosts,
                                    'teacher_budget_id' => $teacher->active_budget_id,
                                    'teacher_covered_costs' => $teacherCoveredCosts,
                                    'mandatory' => 0,
                                    'status' => 'accepted',
                                ]);
                                break;
                        }
                    }

                    /** In first semester we create appraisals. */
                    if (1 == $semesterNumber) {
                        /** Prepare 3 skills */
                        $skills = array();
                        for ($j=0; $j < 3; $j++) {
                            $skill = $faker->numberBetween($min = 1, $max = $totalSkillsNumber);

                            while (in_array($skill, $skills)) {
                                $skill = $faker->numberBetween($min = 1, $max = $totalSkillsNumber);
                            }
                            $skills[] = $skill;
                        }

                        /** Add an appraisal */
                        $appraisal = Appraisal::create([
                            'school_id' => $school->id,
                            'teacher_id' => $teacher->id,
                            'semester_id' => $activeSemester->id,
                            'objectives' => $faker->paragraph($nbSentences = 3, $variableNbSentences = true),
                            'skill_1_id' => $skills[0],
                            'grade_1' => $faker->numberBetween($min = 1, $max = 10),
                            'skill_2_id' => $skills[1],
                            'grade_2' => $faker->numberBetween($min = 1, $max = 10),
                            'skill_3_id' => $skills[2],
                            'grade_3' => $faker->numberBetween($min = 1, $max = 10),
                            'notes_objectives_set' => $faker->paragraph($nbSentences = 2, $variableNbSentences = true),
                            'notes_objectives_approved' => $faker->paragraph($nbSentences = 2, $variableNbSentences = true),
                            'notes_skills_set' => $faker->paragraph($nbSentences = 2, $variableNbSentences = true),
                            'notes_evaluation_opened' => $faker->paragraph($nbSentences = 2, $variableNbSentences = true),
                        ]);
                    } else {
                        $appraisal = $school->getActiveAppraisal($teacher->id);

                        $appraisal->objectives = $faker->paragraph($nbSentences = 3, $variableNbSentences = true);
                        $appraisal->skill_1_id = $skills[0];
                        $appraisal->grade_1 = $faker->numberBetween($min = 1, $max = 10);
                        $appraisal->skill_2_id = $skills[1];
                        $appraisal->grade_2 = $faker->numberBetween($min = 1, $max = 10);
                        $appraisal->skill_3_id = $skills[2];
                        $appraisal->grade_3 = $faker->numberBetween($min = 1, $max = 10);
                        $appraisal->notes_objectives_set = $faker->paragraph($nbSentences = 2, $variableNbSentences = true);
                        $appraisal->notes_objectives_approved = $faker->paragraph($nbSentences = 2, $variableNbSentences = true);
                        $appraisal->notes_skills_set = $faker->paragraph($nbSentences = 2, $variableNbSentences = true);
                        $appraisal->notes_evaluation_opened = $faker->paragraph($nbSentences = 2, $variableNbSentences = true);

                        $appraisal->save();
                    }
                }
            }
        }
    }
}
