<?php namespace Genuineq\Tms\NotifyRules;

use RainLab\Notify\Classes\ModelAttributesConditionBase;
use ApplicationException;

class AppraisalAttributeCondition extends ModelAttributesConditionBase
{
    protected $modelClass = \Genuineq\Tms\Models\Appraisal::class;

    public function getGroupingTitle()
    {
        return 'Appraisal attribute';
    }

    public function getTitle()
    {
        return 'Appraisal attribute';
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

        if (!$appraisal = array_get($params, 'appraisal')) {
            throw new ApplicationException('Error evaluating the appraisal attribute condition: the appraisal object is not found in the condition parameters.');
        }

        return parent::evalIsTrue($appraisal);
    }
}
