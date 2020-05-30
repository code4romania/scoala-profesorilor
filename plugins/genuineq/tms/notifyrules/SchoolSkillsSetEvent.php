<?php namespace Genuineq\Tms\NotifyRules;

use RainLab\Notify\Classes\EventBase;

class SchoolSkillsSetEvent extends EventBase
{
    /**
     * Returns information about this event, including name and description.
     */
    public function eventDetails()
    {
        return [
            'name'        => 'School Skills Set',
            'description' => 'A school sets a set of skills',
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
        $teacher = array_get($args, 0);
        $teacher_user = $teacher->user;
        $user = array_get($args, 1);
        $appraisal = array_get($args, 2);

        $params = $user->getNotificationVars();
        $params['user'] = $teacher_user;
        $params['school_name'] = $user->profile->name;
        $params['teacher_name'] = $teacher->name;
        $params['first_skill'] = $appraisal->firstSkill->name;
        $params['second_skill'] = $appraisal->secondSkill->name;
        $params['third_skill'] = $appraisal->thirdSkill->name;
        $params['school'] = $user->profile;
        $params['teacher'] = $teacher;
        $params['appraisal'] = $appraisal;
        $params['notification-type'] = 'school-skills-set';

        return $params;
    }
}
