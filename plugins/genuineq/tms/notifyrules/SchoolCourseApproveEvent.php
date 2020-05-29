<?php namespace Genuineq\Tms\NotifyRules;

use RainLab\Notify\Classes\EventBase;

class SchoolCourseApproveEvent extends EventBase
{
    /**
     * Returns information about this event, including name and description.
     */
    public function eventDetails()
    {
        return [
            'name'        => 'School Course Approve',
            'description' => 'A school approved a course requested by a teacher',
            'group'       => 'tms'
        ];
    }

    /**
     * @var array Local conditions supported by this event.
     */
    public $conditions = [
        \Genuineq\Tms\NotifyRules\SchoolAttributeCondition::class,
        \Genuineq\Tms\NotifyRules\TeacherAttributeCondition::class,
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
        $teacher = array_get($args, 0);
        $teacher_user = $teacher->user;
        $learningPlanCourse = array_get($args, 1);
        $user = array_get($args, 2);

        $params = $user->getNotificationVars();
        $params['user'] = $teacher_user;
        $params['teacher_name'] = $teacher->name;
        $params['school_name'] = $user->profile->name;
        $params['course_name'] = $learningPlanCourse->course->name;
        $params['teacher_covered_costs'] = $learningPlanCourse->teacher_covered_costs;
        $params['school_covered_costs'] = $learningPlanCourse->school_covered_costs;
        $params['teacher'] = $teacher;
        $params['school'] = $user->profile;
        $params['learningPlanCourse'] = $learningPlanCourse;
        $params['notification-type'] = 'school-course-approve';

        return $params;
    }
}
