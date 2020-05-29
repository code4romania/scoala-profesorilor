<?php namespace Genuineq\Tms\NotifyRules;

use RainLab\Notify\Classes\EventBase;

class TeacherCourseApproveEvent extends EventBase
{
    /**
     * Returns information about this event, including name and description.
     */
    public function eventDetails()
    {
        return [
            'name'        => 'Teacher Course Approve',
            'description' => 'A teacher approved a course proposed by a school',
            'group'       => 'tms'
        ];
    }

    /**
     * @var array Local conditions supported by this event.
     */
    public $conditions = [
        \Genuineq\Tms\NotifyRules\TeacherAttributeCondition::class,
        \Genuineq\Tms\NotifyRules\SchoolAttributeCondition::class,
        \Genuineq\Tms\NotifyRules\LearningPlanCourseAttributeCondition::class,
        \Genuineq\User\NotifyRules\UserAttributeCondition::class
    ];

    /**
     * Defines the usable parameters provided by this class.
     */
    public function defineParams()
    {
        return [];
    }

    public static function makeParamsFromEvent(array $args, $eventName = null)
    {
        $school = array_get($args, 0);
        $school_user = $school->user;
        $learningPlanCourse = array_get($args, 1);
        $user = array_get($args, 2);

        $params = $user->getNotificationVars();
        $params['user'] = $school_user;
        $params['school_name'] = $school->name;
        $params['teacher_name'] = $user->profile->name;
        $params['course_name'] = $learningPlanCourse->course->name;
        $params['teacher_covered_costs'] = $learningPlanCourse->teacher_covered_costs;
        $params['school_covered_costs'] = $learningPlanCourse->school_covered_costs;
        $params['school'] = $school;
        $params['teacher'] = $user->profile;
        $params['learningPlanCourse'] = $learningPlanCourse;
        $params['notification-type'] = 'teacher-course-approve';

        return $params;
    }
}
