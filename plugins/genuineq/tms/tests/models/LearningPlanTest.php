<?php namespace Genuineq\Tms\Tests\Models;

use Log;
use PluginTestCase;
use Genuineq\Tms\Models\Semester;
use Genuineq\Tms\Models\Teacher;
use Genuineq\Tms\Models\LearningPlan;
use System\Classes\PluginManager;

class LearningPlanTest extends PluginTestCase
{
    public function test__createLearningPlan()
    {
        /** Create new semester */
        $semester_1 = $this->helper__createSemester(1);
        /** Create a new teacher */
        $teacher_1 = $this->helper__createTeacher(1);
        /** Create new learningPlan */
        $learningPlan = $this->helper__createLearningPlan($teacher_1->id, $semester_1->id);

        /* Validate created learningPlan */
        $this->assertEquals($teacher_1->id, $learningPlan->teacher_id);
        $this->assertEquals($semester_1->id, $learningPlan->semester_id);
        $this->assertEquals(1, $learningPlan->status);
    }

    public function test__updateLearningPlan()
    {
        /** Create new semester */
        $semester_1 = $this->helper__createSemester(2);
        /** Create a new teacher */
        $teacher_1 = $this->helper__createTeacher(2);
        /** Create new learningPlan */
        $learningPlan = $this->helper__createLearningPlan($teacher_1->id, $semester_1->id);

        /* Validate created learningPlan */
        $this->assertEquals($teacher_1->id, $learningPlan->teacher_id);
        $this->assertEquals($semester_1->id, $learningPlan->semester_id);
        $this->assertEquals(1, $learningPlan->status);

        /** Create new semester */
        $semester_2 = $this->helper__createSemester(3);
        /** Create a new teacher */
        $teacher_2 = $this->helper__createTeacher(3);

        /** Update learningPlan */
        $learningPlan->teacher_id = $teacher_2->id;
        $learningPlan->semester_id = $semester_2->id;
        $learningPlan->status = 0;
        $learningPlan->save();

        /** Check learningPlan new values */
        $this->assertEquals($teacher_2->id, $learningPlan->teacher_id);
        $this->assertEquals($semester_2->id, $learningPlan->semester_id);
        $this->assertEquals(0, $learningPlan->status);
    }

    public function test__deleteLearningPlan()
    {
        /** Create new semester */
        $semester_1 = $this->helper__createSemester(4);
        /** Create a new teacher */
        $teacher_1 = $this->helper__createTeacher(4);
        /** Create new learningPlan */
        $learningPlan = $this->helper__createLearningPlan($teacher_1->id, $semester_1->id);

        /** Save the ID */
        $learningPlanId = $learningPlan->id;

        /** Delete learningPlan. */
        $learningPlan->delete();

        /** Search for deleted learningPlan */
        $_learningPlan = LearningPlan::where('id', $learningPlanId)->first();

        $this->assertEquals(null, $_learningPlan);
    }

    public function test__teacherName()
    {
        /** Create new semester */
        $semester_1 = $this->helper__createSemester(5);
        /** Create a new teacher */
        $teacher_1 = $this->helper__createTeacher(5);
        /** Create new learningPlan */
        $learningPlan = $this->helper__createLearningPlan($teacher_1->id, $semester_1->id);

        /* Validate created learningPlan */
        $this->assertEquals($teacher_1->id, $learningPlan->teacher_id);
        $this->assertEquals($semester_1->id, $learningPlan->semester_id);
        $this->assertEquals(1, $learningPlan->status);

        /** Validate teacher name */
        $this->assertEquals($teacher_1->name, $learningPlan->teacher_name);
    }

    public function test__year()
    {
        /** Create new semester */
        $semester_1 = $this->helper__createSemester(6);
        /** Create a new teacher */
        $teacher_1 = $this->helper__createTeacher(6);
        /** Create new learningPlan */
        $learningPlan = $this->helper__createLearningPlan($teacher_1->id, $semester_1->id);

        /* Validate created learningPlan */
        $this->assertEquals($teacher_1->id, $learningPlan->teacher_id);
        $this->assertEquals($semester_1->id, $learningPlan->semester_id);
        $this->assertEquals(1, $learningPlan->status);

        /** Validate teacher name */
        $this->assertEquals($semester_1->year, $learningPlan->year);
    }

    public function test__realSemester()
    {
        /** Create new semester */
        $semester_1 = $this->helper__createSemester(7);
        /** Create a new teacher */
        $teacher_1 = $this->helper__createTeacher(7);
        /** Create new learningPlan */
        $learningPlan = $this->helper__createLearningPlan($teacher_1->id, $semester_1->id);

        /* Validate created learningPlan */
        $this->assertEquals($teacher_1->id, $learningPlan->teacher_id);
        $this->assertEquals($semester_1->id, $learningPlan->semester_id);
        $this->assertEquals(1, $learningPlan->status);

        /** Validate teacher name */
        $this->assertEquals($semester_1->semester, $learningPlan->real_semester);
    }

    public function test__name()
    {
        /** Create new semester */
        $semester_1 = $this->helper__createSemester(8);
        /** Create a new teacher */
        $teacher_1 = $this->helper__createTeacher(8);
        /** Create new learningPlan */
        $learningPlan = $this->helper__createLearningPlan($teacher_1->id, $semester_1->id);

        /* Validate created learningPlan */
        $this->assertEquals($teacher_1->id, $learningPlan->teacher_id);
        $this->assertEquals($semester_1->id, $learningPlan->semester_id);
        $this->assertEquals(1, $learningPlan->status);

        $assertVal = $teacher_1->name . ' ' . $semester_1->year . '-' . $semester_1->semester;

        /** Validate teacher name */
        $this->assertEquals($assertVal, $learningPlan->name);
    }


    /***********************************************
     ************** Helper Functions ***************
     ***********************************************/

    /**
     * Created an learningPlan object and return's it
     */
    protected function helper__createLearningPlan($teacherId, $semesterId)
    {
        /** Create new learningPlan */
        $learningPlan = LearningPlan::create([
            'teacher_id' => $teacherId,
            'status' => 1,
        ]);

        $learningPlan->semester_id = $semesterId;

        return $learningPlan;
    }

    /**
     * Created an semester object and return's it
     */
    protected function helper__createSemester($index)
    {
        /** Create new semester */
        $semester = Semester::create([
            'semester' => 1,
            'year' => 2020 + $index,
        ]);

        return $semester;
    }

    /**
     * Created an teacher object and return's it
     */
    protected function helper__createTeacher($index)
    {
        /** Create new teacher */
        $teacher = Teacher::create([
            'name' => 'test-learningPlan-teacher-name-' . $index,
            'slug' => 'test-learningPlan-teacher-slug-' . $index,
            'phone' => '072222222' . $index,
            'birth_date' => '1987-10-2' . $index,
            'address_id' => $index,
            'description' => 'test-learningPlan-teacher-description-' . $index,
            'user_id' => $index,
            'seniority_level_id' => $index,
            'status' => 1,
        ]);

        return $teacher;
    }
}
