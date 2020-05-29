<?php namespace Genuineq\Tms\NotifyRules;

use RainLab\Notify\Classes\ModelAttributesConditionBase;
use ApplicationException;

class LearningPlanCourseAttributeCondition extends ModelAttributesConditionBase
{
    protected $modelClass = \Genuineq\Tms\Models\LearningPlansCourse::class;

    public function getGroupingTitle()
    {
        return 'Learning plans course attribute';
    }

    public function getTitle()
    {
        return 'Learning plans course attribute';
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

        if (!$learningPlansCourse = array_get($params, 'learningPlansCourse')) {
            throw new ApplicationException('Error evaluating the learning plans course attribute condition: the learning plans course object is not found in the condition parameters.');
        }

        return parent::evalIsTrue($learningPlansCourse);
    }
}
