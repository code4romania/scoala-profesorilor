<?php namespace Genuineq\Tms\NotifyRules;

use URL;
use RainLab\Notify\Classes\EventBase;

class SchoolCourseRequestEvent extends EventBase
{
    /**
     * Returns information about this event, including name and description.
     */
    public function eventDetails()
    {
        return [
            'name'        => 'School Course Request',
            'description' => 'A school proposed a course to a teacher',
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

        $link_reject = URL::to('/tms-profil-profesor') . '?a=1&t=tr&si=' . $user->profile->id . '&lpci=' . $learningPlanCourse->id;
        $link_approve = URL::to('/tms-profil-profesor') . '?a=1&t=ta&si=' . $user->profile->id . '&lpci=' . $learningPlanCourse->id;

        $params = $user->getNotificationVars();
        $params['user'] = $teacher_user;
        $params['teacher_name'] = $teacher->name;
        $params['school_name'] = $user->profile->name;
        $params['course_name'] = $learningPlanCourse->course->name;
        $params['teacher_covered_costs'] = $learningPlanCourse->teacher_covered_costs;
        $params['school_covered_costs'] = $learningPlanCourse->school_covered_costs;
        $params['link_reject'] = $link_reject;
        $params['link_approve'] = $link_approve;
        $params['teacher'] = $teacher;
        $params['school'] = $user->profile;
        $params['learningPlanCourse'] = $learningPlanCourse;
        $params['notification-type'] = 'school-course-request';

        return $params;
    }
}
