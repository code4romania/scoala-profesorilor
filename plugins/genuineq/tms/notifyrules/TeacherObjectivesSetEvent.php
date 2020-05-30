<?php namespace Genuineq\Tms\NotifyRules;

use RainLab\Notify\Classes\EventBase;

class TeacherObjectivesSetEvent extends EventBase
{
    /**
     * Returns information about this event, including name and description.
     */
    public function eventDetails()
    {
        return [
            'name'        => 'Teacher Objectives Set',
            'description' => 'A teacher finished setting the objectives',
            'group'       => 'tms'
        ];
    }

    /**
     * @var array Local conditions supported by this event.
     */
    public $conditions = [
        \Genuineq\Tms\NotifyRules\TeacherAttributeCondition::class,
        \Genuineq\Tms\NotifyRules\SchoolAttributeCondition::class,
        \Genuineq\Tms\NotifyRules\AppraisalAttributeCondition::class,
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
        $user = array_get($args, 1);
        $appraisal = array_get($args, 2);

        $params = $user->getNotificationVars();
        $params['user'] = $school_user;
        $params['school_name'] = $school->name;
        $params['teacher_name'] = $user->profile->name;
        $params['appraisal_objectives'] = $appraisal->objectives;
        $params['school'] = $school;
        $params['teacher'] = $user->profile;
        $params['appraisal'] = $appraisal;
        $params['notification-type'] = 'teacher-objectives-set';

        return $params;
    }
}
