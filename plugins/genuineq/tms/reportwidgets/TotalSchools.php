<?php namespace Genuineq\Tms\ReportWidgets;

use Lang;
use Genuineq\Tms\Models\School;
use Backend\Classes\ReportWidgetBase;

class TotalSchools extends ReportWidgetBase
{
    /**
     * Renders the widget.
     */
    public function render()
    {
        try {
            $this->vars['labelSchool'] = Lang::get('genuineq.tms::lang.reportwidgets.totalschools.frontend.label_school');
            $this->vars['totalSchool'] = School::all()->count();
        } catch (Exception $ex) {
            $this->vars['error'] = $ex->getMessage();
        }

        return $this->makePartial('widget');
    }

    public function defineProperties()
    {
        return [
            'title' => [
                'title' => Lang::get('genuineq.tms::lang.reportwidgets.totalschools.title'),
                'default' => Lang::get('genuineq.tms::lang.reportwidgets.totalschools.title_default'),
                'type' => 'string',
                'validationPattern' => '^.+$',
                'validationMessage' => Lang::get('genuineq.tms::lang.reportwidgets.totalschools.title_validation'),
            ]
        ];
    }
}
