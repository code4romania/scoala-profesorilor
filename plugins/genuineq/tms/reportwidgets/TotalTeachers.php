<?php namespace Genuineq\Tms\ReportWidgets;

use Lang;
use Genuineq\Tms\Models\Teacher;
use Backend\Classes\ReportWidgetBase;

class TotalTeachers extends ReportWidgetBase
{
    /**
     * Renders the widget.
     */
    public function render()
    {
        try {
            $this->vars['labelTeachers'] = Lang::get('genuineq.tms::lang.reportwidgets.totalteachers.frontend.label_teachers');
            $this->vars['totalTeachers'] = Teacher::all()->count();
        } catch (Exception $ex) {
            $this->vars['error'] = $ex->getMessage();
        }

        return $this->makePartial('widget');
    }

    public function defineProperties()
    {
        return [
            'title' => [
                'title' => Lang::get('genuineq.tms::lang.reportwidgets.totalteachers.title'),
                'default' => Lang::get('genuineq.tms::lang.reportwidgets.totalteachers.title_default'),
                'type' => 'string',
                'validationPattern' => '^.+$',
                'validationMessage' => Lang::get('genuineq.tms::lang.reportwidgets.totalteachers.title_validation'),
            ]
        ];
    }
}
