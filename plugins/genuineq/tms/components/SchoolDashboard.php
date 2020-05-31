<?php namespace Genuineq\Tms\Components;

use Log;
use Auth;
use Lang;
use DateTime;
use Redirect;
use Genuineq\Tms\Models\Budget;
use Genuineq\Tms\Models\Semester;
use Genuineq\User\Helpers\AuthRedirect;
use Cms\Classes\ComponentBase;

/**
 * School dashboard component
 *
 * Displays the school dashboard
 */
class SchoolDashboard extends ComponentBase
{

    public function componentDetails()
    {
        return [
            'name'        => 'genuineq.tms::lang.component.school-dashboard.name',
            'description' => 'genuineq.tms::lang.component.school-dashboard.description'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    /**
     * Executed when this component is initialized
     */
    public function prepareVars()
    {
        $this->page['school'] = (Auth::check()) ? (Auth::getUser()->profile) : (null);
    }

    /**
     * Executed when this component is bound to a page or layout.
     */
    public function onRun()
    {
        /** Force authentication in case user is not authenticated. */
        if (!Auth::check()) {
            return Redirect::to($this->pageUrl(AuthRedirect::loginRequired()));
        }

        $this->prepareVars();
    }

    /***********************************************
     **************** AJAX handlers ****************
     ***********************************************/

    /**
     * Loads all the needed school dashboard data.
     */
    public function onSchoolDashboardView()
    {
        /** Force authentication in case user is not authenticated. */
        if (!Auth::check()) {
            return Redirect::to($this->pageUrl(AuthRedirect::loginRequired()));
        }

        $this->page['school'] = (Auth::check()) ? (Auth::getUser()->profile) : (null);
        $this->page['schoolYears'] = Budget::getSchoolFilterYears(Auth::getUser()->profile->id);
        $this->page['schoolSemesters'] = Budget::getSchoolFilterSemesters(Auth::getUser()->profile->id);


        /** Get active semester ID. */
        $activeSemesterId = Auth::getUser()->profile->active_budget->semester_id;

        $this->prepareBudgetAllocationData($activeSemesterId);
        $this->prepareFinancedTeachersData($activeSemesterId);
        $this->prepareAccreditedCoursesData($activeSemesterId);
        $this->prepareSpentMoneyData($activeSemesterId);
        $this->prepareBudgetTotalData($activeSemesterId);
        $this->prepareDistributedCostsData($activeSemesterId);
    }

    /**
     * Loads all the needed school dashboard to make a cost distribution comparison.
     */
    public function onSchoolCostDistributionCompare()
    {
        /** Force authentication in case user is not authenticated. */
        if (!Auth::check()) {
            return Redirect::to($this->pageUrl(AuthRedirect::loginRequired()));
        }

        $this->page['school'] = (Auth::check()) ? (Auth::getUser()->profile) : (null);
        $this->page['schoolYears'] = Budget::getSchoolFilterYears(Auth::getUser()->profile->id);
        $this->page['schoolSemesters'] = Budget::getSchoolFilterSemesters(Auth::getUser()->profile->id);

        $this->prepareDistributedCostsCompareData(Auth::getUser()->profile->active_budget->semester_id, Semester::where('year', post('year'))->where('semester', post('semester'))->first()->id);
    }

    /**
     * Loads all the needed cost distribution school data.
     */
    public function onSchoolCostDistributionReset()
    {
        /** Force authentication in case user is not authenticated. */
        if (!Auth::check()) {
            return Redirect::to($this->pageUrl(AuthRedirect::loginRequired()));
        }

        $this->page['school'] = (Auth::check()) ? (Auth::getUser()->profile) : (null);
        $this->page['schoolYears'] = Budget::getSchoolFilterYears(Auth::getUser()->profile->id);
        $this->page['schoolSemesters'] = Budget::getSchoolFilterSemesters(Auth::getUser()->profile->id);

        $this->prepareDistributedCostsData(Auth::getUser()->profile->active_budget->semester_id);
    }

    /***********************************************
     ******************* Helpers *******************
     ***********************************************/

    /**
     * Extract the budget allocation for a specified semester.
     */
    protected function prepareBudgetAllocationData($semesterId)
    {
        /** Extract the school budget. */
        $budget = Auth::getUser()->profile->budgets->where('semester_id', $semesterId)->first();

        /** Get the total budget. */
        $this->page['budgetTotal'] = $budget->budget;
        /** Get the total spent budget. */
        foreach ($budget->schoolCourses as $key => $course) {
            $this->page['budgetSpent'] += $course->school_covered_costs;
        }

        $this->page['budgetTotalLabel'] = Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.budget_total');
        $this->page['budgetSpentLabel'] = Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.budget_spent');
    }

    /**
     * Extract the finaced teachers for a specified semester.
     */
    protected function prepareFinancedTeachersData($semesterId)
    {
        /** Extract the school budget. */
        $budget = Auth::getUser()->profile->budgets->where('semester_id', $semesterId)->first();

        /** Get the total financed teachers. */
        $this->page['teachersFianced'] = $budget->schoolCourses->groupBy('teacher_id')->count();
        /** Get the total not financed teachers. */
        $this->page['teachersNotFianced'] = Auth::getUser()->profile->teachers->count() - $this->page['teachersFianced'];

        $this->page['teachersFiancedLabel'] = Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.teachers_financed');
        $this->page['teachersNotFiancedLabel'] = Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.teachers_not_financed');
    }

    /**
     * Extract the accredited courses for a specified semester.
     */
    protected function prepareAccreditedCoursesData($semesterId)
    {
        /** Extract the school budget. */
        $budget = Auth::getUser()->profile->budgets->where('semester_id', $semesterId)->first();

        $this->page['accreditedCourses'] = 0;
        $this->page['noncreditedCourses'] = 0;

        foreach ($budget->schoolCourses as $key => $learningPlanCourse) {
            if ($learningPlanCourse->course->accredited) {
                $this->page['accreditedCourses'] += 1;
            } else {
                $this->page['noncreditedCourses'] += 1;
            }
        }

        $this->page['accreditedCoursesLabel'] = Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.accredited_courses');
        $this->page['noncreditedCoursesLabel'] = Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.noncredited_courses');
    }

    /**
     * Extract the spent money for a specified semester.
     */
    protected function prepareSpentMoneyData($semesterId)
    {
        /** Extract the school budget. */
        $budget = Auth::getUser()->profile->budgets->where('semester_id', $semesterId)->first();

        $this->page['schoolSpentMoney'] = 0;
        $this->page['teachersSpentMoney'] = 0;

        /** Calculate the school total costs. */
        foreach ($budget->schoolCourses as $key => $learningPlanCourse) {
            $this->page['schoolSpentMoney'] += $learningPlanCourse->school_covered_costs;
        }

        /** Calculate the teachers total costs. */
        foreach (Auth::getUser()->profile->teachers as $key => $teacher) {
            foreach ($teacher->active_budget->teacherCourses as $key => $learningPlanCourse) {
                $this->page['teachersSpentMoney'] += $learningPlanCourse->teacher_covered_costs;
            }
        }

        $this->page['schoolSpentMoneyLabel'] = Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.school_spent_money');
        $this->page['teachersSpentMoneyLabel'] = Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.teachers_spent_money');
    }

    /**
     * Extract the budget totals for a specified semester.
     */
    protected function prepareBudgetTotalData($semesterId)
    {
        /** Extract the school budget. */
        $budget = Auth::getUser()->profile->budgets->where('semester_id', $semesterId)->first();

        $this->page['schoolBudget'] = $budget->budget;
        $this->page['teachersBudget'] = 0;

        /** Calculate the teachers total costs. */
        foreach (Auth::getUser()->profile->teachers as $key => $teacher) {
            $this->page['teachersBudget'] += $teacher->active_budget->budget;
        }

        $this->page['schoolBudgetLabel'] = Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.school_budget');
        $this->page['teachersBudgetLabel'] = Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.teachers_budget');
    }

    /**
     * Extract the bufet totals for a specified semester.
     */
    protected function prepareDistributedCostsData($semesterId)
    {
        $semester = Semester::find($semesterId);
        /** Check what semester it is. */
        if (1 == $semester->semester) {
            $this->page['distributedLabels'] = Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.first_semester');
            $distributedCosts = [ '8' => 0, '9' => 0, '10' => 0, '11' => 0, '12' => 0, '1' => 0 ];
            $this->page['distributedCostsLabels'] = [0 => Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.first_semester_distributed_costs_label') . $semester->year];
        } else {
            $distributedCosts = [ '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0 ];
            $this->page['distributedLabels'] = Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.second_semester');
            $this->page['distributedCostsLabels'] = [0 => Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.second_semester_distributed_costs_label') . $semester->year];
        }

        /** Extract the school budget. */
        $budget = Auth::getUser()->profile->budgets->where('semester_id', $semesterId)->first();

        /** Calculate the distributed costs. */
        foreach ($budget->schoolCourses as $key => $learningPlanCourse) {
            $month = date("n", strtotime($learningPlanCourse->course->end_date));
            $distributedCosts[$month] += $learningPlanCourse->school_covered_costs;
        }

        $this->page['distributedCosts'] = [0 => array_values($distributedCosts)];
        $this->page['distributedCostsColor'] = [0 => 'rgba(75, 192, 192, 0.2)'];
        $this->page['distributedCostsBorderColor'] = [0 => 'rgba(75, 192, 192, 1)'];
    }

    /**
     * Extract the bufet totals for a specified semester.
     */
    protected function prepareDistributedCostsCompareData($activeSemesterId, $secondSemesterId)
    {
        /** Save the labels */
        $this->page['distributedLabels'] = Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.compare_semester');

        /****************** Extarct the first semester ****************************/
        $semester = Semester::find($activeSemesterId);
        /** Check what semester it is. */
        if (1 == $semester->semester) {
            $distributedCosts = [ '8' => 0, '9' => 0, '10' => 0, '11' => 0, '12' => 0, '1' => 0 ];
            $this->page['distributedCostsLabels'] = [0 => Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.first_semester_distributed_costs_label') . $semester->year];
        } else {
            $distributedCosts = [ '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0 ];
            $this->page['distributedCostsLabels'] = [0 => Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.second_semester_distributed_costs_label') . $semester->year];
        }

        /** Extract the school budget. */
        $budget = Auth::getUser()->profile->budgets->where('semester_id', $activeSemesterId)->first();

        /** Calculate the distributed costs. */
        foreach ($budget->schoolCourses as $key => $learningPlanCourse) {
            $month = date("n", strtotime($learningPlanCourse->course->end_date));
            $distributedCosts[$month] += $learningPlanCourse->school_covered_costs;
        }
        $this->page['distributedCosts'] = [0 => array_values($distributedCosts)];

        /****************** Extarct the second semester ****************************/
        $semester = Semester::find($secondSemesterId);
        /** Check what semester it is. */
        if (1 == $semester->semester) {
            $distributedCosts = [ '8' => 0, '9' => 0, '10' => 0, '11' => 0, '12' => 0, '1' => 0 ];
            $this->page['distributedCostsLabels'] = [0 => $this->page['distributedCostsLabels'][0], 1 => Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.first_semester_distributed_costs_label') . $semester->year];
        } else {
            $distributedCosts = [ '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0 ];
            $this->page['distributedCostsLabels'] = [0 => $this->page['distributedCostsLabels'][0], 1 => Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.second_semester_distributed_costs_label') . $semester->year];
        }

        /** Extract the school budget. */
        $budget = Auth::getUser()->profile->budgets->where('semester_id', $secondSemesterId)->first();

        /** Calculate the distributed costs. */
        foreach ($budget->schoolCourses as $key => $learningPlanCourse) {
            $month = date("n", strtotime($learningPlanCourse->course->end_date));
            $distributedCosts[$month] += $learningPlanCourse->school_covered_costs;
        }
        $this->page['distributedCosts'] = [0 => $this->page['distributedCosts'][0], 1 => array_values($distributedCosts)];
        $this->page['distributedCostsColor'] = [0 => 'rgba(75, 192, 192, 0.2)', 1 => 'rgba(153, 102, 255, 0.2)'];
        $this->page['distributedCostsBorderColor'] = [0 => 'rgba(75, 192, 192, 1)', 1 => 'rgba(153, 102, 255, 1)'];
    }
}
