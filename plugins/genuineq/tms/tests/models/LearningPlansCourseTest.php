<?php namespace Genuineq\Tms\Tests\Models;

use Log;
use PluginTestCase;
use Genuineq\Tms\Models\Course;
use Genuineq\Tms\Models\LearningPlan;
use Genuineq\Tms\Models\LearningPlansCourse;
use System\Classes\PluginManager;

class LearningPlansCourseTest extends PluginTestCase
{
    public function test__createLearningPlansCourse()
    {
        /** Create learning plan */
        $learningPlan_1 = $this->helper__createLearningPlan(1);
        /** Create a course */
        $course_1 = $this->helper__createCourse(1);
        /** Create new learningPlansCourse */
        $learningPlansCourse = $this->helper__createLearningPlansCourse($learningPlan_1->id, $course_1->id);

        /* Validate created learningPlansCourse */
        $this->assertEquals($learningPlan_1->id, $learningPlansCourse->learning_plan_id);
        $this->assertEquals($course_1->id, $learningPlansCourse->course_id);
        $this->assertEquals(0, $learningPlansCourse->mandatory);
        $this->assertEquals('proposed', $learningPlansCourse->status);
    }

    public function test__updateLearningPlansCourse()
    {
        /** Create learning plan */

        $learningPlan_1 = $this->helper__createLearningPlan(1);
        /** Create a course */
        $course_1 = $this->helper__createCourse(1);
        /** Create new learningPlansCourse */
        $learningPlansCourse = $this->helper__createLearningPlansCourse($learningPlan_1->id, $course_1->id);

        /* Validate created learningPlansCourse */
        $this->assertEquals($learningPlan_1->id, $learningPlansCourse->learning_plan_id);
        $this->assertEquals($course_1->id, $learningPlansCourse->course_id);
        $this->assertEquals(0, $learningPlansCourse->mandatory);
        $this->assertEquals('proposed', $learningPlansCourse->status);

        /** Create learning plan */
        $learningPlan_2 = $this->helper__createLearningPlan(2);
        /** Create a course */
        $course_2 = $this->helper__createCourse(2);

        /** Update learningPlansCourse */
        $learningPlansCourse->learning_plan_id = $learningPlan_2->id;
        $learningPlansCourse->course_id = $course_2->id;
        $learningPlansCourse->mandatory = 1;
        $learningPlansCourse->status = 'accepted';
        $learningPlansCourse->save();

        /** Check learningPlansCourse new values */
        $this->assertEquals($learningPlan_2->id, $learningPlansCourse->learning_plan_id);
        $this->assertEquals($course_2->id, $learningPlansCourse->course_id);
        $this->assertEquals(1, $learningPlansCourse->mandatory);
        $this->assertEquals('accepted', $learningPlansCourse->status);
    }

    public function test__deleteLearningPlansCourse()
    {
        /** Create learning plan */
        $learningPlan_1 = $this->helper__createLearningPlan(1);
        /** Create a course */
        $course_1 = $this->helper__createCourse(1);
        /** Create new learningPlansCourse */
        $learningPlansCourse = $this->helper__createLearningPlansCourse($learningPlan_1->id, $course_1->id);

        /* Validate created learningPlansCourse */
        $this->assertEquals($learningPlan_1->id, $learningPlansCourse->learning_plan_id);
        $this->assertEquals($course_1->id, $learningPlansCourse->course_id);
        $this->assertEquals(0, $learningPlansCourse->mandatory);
        $this->assertEquals('proposed', $learningPlansCourse->status);

        /** Save the ID */
        $learningPlansCourseId = $learningPlansCourse->id;

        /** Delete learningPlansCourse. */
        $learningPlansCourse->delete();

        /** Search for deleted learningPlansCourse */
        $_learningPlansCourse = LearningPlansCourse::where('id', $learningPlansCourseId)->first();

        $this->assertEquals(null, $_learningPlansCourse);
    }


    /***********************************************
     ************** Helper Functions ***************
     ***********************************************/

    /**
     * Created an learningPlansCourse object and return's it
     */
    protected function helper__createLearningPlansCourse($learningPlanId, $courseId)
    {
        /** Create new learningPlan */
        $learningPlansCourse = LearningPlansCourse::create([
            'learning_plan_id' => $learningPlanId,
            'course_id' => $courseId,
            'mandatory' => 0,
            'status' => 'proposed',
        ]);

        return $learningPlansCourse;
    }

    /**
     * Created an learningPlan object and return's it
     */
    protected function helper__createLearningPlan($indexId)
    {
        /** Create new learningPlan */
        $learningPlan = LearningPlan::create([
            'teacher_id' => $indexId,
            'status' => 1,
        ]);

        return $learningPlan;
    }

    /**
     * Created an course object and return's it
     */
    protected function helper__createCourse($indexId)
    {
        /** Create new course */
        $course = Course::create([
            'name' => 'test-course-name-' . $indexId,
            'slug' => 'test-course-slug-' . $indexId,
            'supplier_id' => $indexId,
            'duration' => 5.6 + $indexId,
            'address' => 'test-course-address-' . $indexId,
            'start_date' => '2020-05-0' . $indexId,
            'end_date' => '2020-05-0' . ($indexId + 2),
            'accredited' => 0,
            'credits' => 0,
            'price' => 100 + $indexId,
            'description' => 'test-course-description-' . $indexId,
            'status' => 1
        ]);

        return $course;
    }
}
