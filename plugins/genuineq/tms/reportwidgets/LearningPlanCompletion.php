<?php namespace Genuineq\Tms\ReportWidgets;

use Lang;
use DateTime;
use Genuineq\Tms\Models\Semester;
use Genuineq\Tms\Models\LearningPlan;
use Backend\Classes\ReportWidgetBase;

class LearningPlanCompletion extends ReportWidgetBase
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
                'title' => Lang::get('genuineq.tms::lang.reportwidgets.learningplancompletion.title'),
                'default' => Lang::get('genuineq.tms::lang.reportwidgets.learningplancompletion.title_default'),
                'type' => 'string',
                'validationPattern' => '^.+$',
                'validationMessage' => Lang::get('genuineq.tms::lang.reportwidgets.learningplancompletion.title_validation'),
            ]
        ];
    }

    protected function loadData()
    {
        $this->vars['learningPlansTotal'] = 0;
        $this->vars['learningPlansCompleted'] = 0;
        $this->vars['learningPlansCompletionPercentage'] = 0;

        $this->vars['labelLearningPlansTotal'] = Lang::get('genuineq.tms::lang.reportwidgets.learningplancompletion.frontend.label_learning_plans_total');
        $this->vars['labelLearningPlansCompleted'] = Lang::get('genuineq.tms::lang.reportwidgets.learningplancompletion.frontend.label_learning_plans_completed');
        $this->vars['labelLearningPlansCompletionPercentage'] = Lang::get('genuineq.tms::lang.reportwidgets.learningplancompletion.frontend.label_learning_plans_completed');

        $nowDate = new DateTime();

        foreach (Semester::getLatest()->learningPlans as $key => $learningPlan) {

            if ($learningPlan->realCourses->count()) {
                /** Assume learning plan is completed */
                $completed = true;

                /** Check that each course is completed. */
                foreach ($learningPlan->realCourses as $key => $course) {
                    /** Calculate the course date. */
                    $courseDate = new DateTime($course->end_date);

                    /** Compare the dates. */
                    if ($courseDate > $nowDate) {
                        $completed = false;

                        break;
                    }
                }

                /** Count only learning plans that have at least 1 course to total number of courses. */
                $this->vars['learningPlansTotal']++;

                /** Count only learning plans that have all courses in the past as completed. */
                if ($completed) {
                    $this->vars['learningPlansCompleted']++;
                }
            }
        }

        if ($this->vars['learningPlansTotal'] && $this->vars['learningPlansCompleted']) {
            $this->vars['learningPlansCompletionPercentage'] = $this->vars['learningPlansCompleted'] / $this->vars['learningPlansTotal'];
        }
    }
}
