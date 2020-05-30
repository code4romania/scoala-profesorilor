<?php namespace Genuineq\Tms\ReportWidgets;

use Lang;
use Genuineq\Tms\Models\Skill;
use Backend\Classes\ReportWidgetBase;

class TotalSkills extends ReportWidgetBase
{
    /**
     * Renders the widget.
     */
    public function render()
    {
        try {
            $this->vars['labelSkills'] = Lang::get('genuineq.tms::lang.reportwidgets.totalskills.frontend.label_skills');
            $this->vars['totalSkills'] = Skill::all()->count();
        } catch (Exception $ex) {
            $this->vars['error'] = $ex->getMessage();
        }

        return $this->makePartial('widget');
    }

    public function defineProperties()
    {
        return [
            'title' => [
                'title' => Lang::get('genuineq.tms::lang.reportwidgets.totalskills.title'),
                'default' => Lang::get('genuineq.tms::lang.reportwidgets.totalskills.title_default'),
                'type' => 'string',
                'validationPattern' => '^.+$',
                'validationMessage' => Lang::get('genuineq.tms::lang.reportwidgets.totalskills.title_validation'),
            ]
        ];
    }
}
