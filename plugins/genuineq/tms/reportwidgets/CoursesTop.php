<?php namespace Genuineq\Tms\ReportWidgets;

use Lang;
use Genuineq\Tms\Models\Semester;
use Genuineq\Tms\Models\LearningPlan;
use Backend\Classes\ReportWidgetBase;

class CoursesTop extends ReportWidgetBase
{
    /**
     * Renders the widget.
     */
    public function render()
    {
        try {
            $this->loadData();
        } catch (Exception $ex) {
            $this->vars['error'] = $ex->getMessage();
        }

        return $this->makePartial('widget');
    }

    public function defineProperties()
    {
        return [
            'title' => [
                'title' => Lang::get('genuineq.tms::lang.reportwidgets.coursestop.title'),
                'default' => Lang::get('genuineq.tms::lang.reportwidgets.coursestop.title_default'),
                'type' => 'string',
                'validationPattern' => '^.+$',
                'validationMessage' => Lang::get('genuineq.tms::lang.reportwidgets.coursestop.title_validation'),
            ]
        ];
    }

    protected function loadData()
    {
        $this->vars['courses'] = [];
        $this->vars['coursesNames'] = [];

        foreach (Semester::getLatest()->learningPlans as $key => $learningPlan) {

            if ($learningPlan->realCourses->count()) {
                foreach ($learningPlan->realCourses as $key => $course) {
                    if (array_key_exists($course->id, $this->vars['courses'])) {
                        $this->vars['courses'][$course->id]++;
                    } else {
                        $this->vars['courses'][$course->id] = 1;
                        $this->vars['coursesNames'][$course->id] = $course->name;
                    }
                }
            }
        }
    }
}
