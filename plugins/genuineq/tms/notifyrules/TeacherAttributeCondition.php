<?php namespace Genuineq\Tms\NotifyRules;

use RainLab\Notify\Classes\ModelAttributesConditionBase;
use ApplicationException;

class TeacherAttributeCondition extends ModelAttributesConditionBase
{
    protected $modelClass = \Genuineq\Tms\Models\Teacher::class;

    public function getGroupingTitle()
    {
        return 'Teacher attribute';
    }

    public function getTitle()
    {
        return 'Teacher attribute';
    }

    /**
     * Checks whether the condition is TRUE for specified parameters
     * @param array $params Specifies a list of parameters as an associative array.
     * @return bool
     */
    public function isTrue(&$params)
    {
        $hostObj = $this->host;

        $attribute = $hostObj->subcondition;

        if (!$teacher = array_get($params, 'teacher')) {
            throw new ApplicationException('Error evaluating the teacher attribute condition: the teacher object is not found in the condition parameters.');
        }

        return parent::evalIsTrue($teacher);
    }
}
