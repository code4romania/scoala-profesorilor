<?php namespace Genuineq\Tms\NotifyRules;

use RainLab\Notify\Classes\ModelAttributesConditionBase;
use ApplicationException;

class SchoolAttributeCondition extends ModelAttributesConditionBase
{
    protected $modelClass = \Genuineq\Tms\Models\School::class;

    public function getGroupingTitle()
    {
        return 'School attribute';
    }

    public function getTitle()
    {
        return 'School attribute';
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

        if (!$school = array_get($params, 'school')) {
            throw new ApplicationException('Error evaluating the school attribute condition: the school object is not found in the condition parameters.');
        }

        return parent::evalIsTrue($school);
    }
}
