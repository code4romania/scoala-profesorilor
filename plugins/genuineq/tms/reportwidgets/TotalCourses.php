<?php namespace Genuineq\Tms\ReportWidgets;

use Lang;
use Genuineq\Tms\Models\Course;
use Backend\Classes\ReportWidgetBase;

class TotalCourses extends ReportWidgetBase
{
    /**
     * Renders the widget.
     */
    public function render()
    {
        try {
            $this->vars['labelCourses'] = Lang::get('genuineq.tms::lang.reportwidgets.totalcourses.frontend.label_courses');
            $this->vars['totalCourses'] = Course::all()->count();
        } catch (Exception $ex) {
            $this->vars['error'] = $ex->getMessage();
        }

        return $this->makePartial('widget');
    }

    public function defineProperties()
    {
        return [
            'title' => [
                'title' => Lang::get('genuineq.tms::lang.reportwidgets.totalcourses.title'),
                'default' => Lang::get('genuineq.tms::lang.reportwidgets.totalcourses.title_default'),
                'type' => 'string',
                'validationPattern' => '^.+$',
                'validationMessage' => Lang::get('genuineq.tms::lang.reportwidgets.totalcourses.title_validation'),
            ]
        ];
    }
}
