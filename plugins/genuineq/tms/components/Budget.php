<?php namespace Genuineq\Tms\Components;

use Log;
use Lang;
use Auth;
use Request;
use Redirect;
use Cms\Classes\ComponentBase;
use Genuineq\Tms\Models\Course;
use Genuineq\Tms\Models\Budget as BudgetModel;
use Genuineq\Tms\Models\Category;
use Genuineq\User\Helpers\AuthRedirect;

/**
 * School profile component
 *
 * Allows the search, filter, order and paginate courses.
 */
class Budget extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'genuineq.tms::lang.component.budget.name',
            'description' => 'genuineq.tms::lang.component.budget.description'
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

     /***********************************************
     ****************** Teacher ********************
     ***********************************************/

    /**
     * Loads all the courses from all the budgets
     *  of the teacher.
     */
    public function onTeacherViewBudget()
    {
        /** Force authentication in case user is not authenticated. */
        if (!Auth::check()) {
            return Redirect::to($this->pageUrl(AuthRedirect::loginRequired()));
        }

        /* Extract the courses based on the received options. */
        $this->teacherExtractBudgetCourses(
            [
                'id' => Auth::getUser()->profile->id,
                'type' => 'Genuineq\Tms\Models\Teacher',
                'sortBy' => 'teacher_covered_costs'
            ]
        );
    }

    /**
     * Loads all the courses from all the budgets
     *  of the teacher.
     */
    public function onTeacherBudgetCourseSearch()
    {
        /** Force authentication in case user is not authenticated. */
        if (!Auth::check()) {
            return Redirect::to($this->pageUrl(AuthRedirect::loginRequired()));
        }

        /** Extract all the options. */
        $options = post();
        /** Add teacher options. */
        $options['id'] = Auth::getUser()->profile->id;
        $options['type'] = 'Genuineq\Tms\Models\Teacher';
        $options['sortBy'] = 'teacher_covered_costs';

        /* Extract the courses based on the received options. */
        $this->teacherExtractBudgetCourses($options);
    }

    /***********************************************
     ******************* School ********************
     ***********************************************/

    /**
     * Loads all the courses from the active budget
     *  of the school.
     */
    public function onSchoolViewCourses()
    {
        /** Force authentication in case user is not authenticated. */
        if (!Auth::check()) {
            return Redirect::to($this->pageUrl(AuthRedirect::loginRequired()));
        }

        /* Extract the courses based on the received options. */
        $this->schoolExtractCourses(
            [
                'id' => Auth::getUser()->profile->id,
                'budget' => Auth::getUser()->profile,
                'type' => 'Genuineq\Tms\Models\School',
                'sortBy' => 'school_covered_costs'
            ]
        );
    }

    /**
     * Loads all the courses from the active budget
     *  of the school.
     */
    public function onSchoolCourseSearch()
    {
        /** Force authentication in case user is not authenticated. */
        if (!Auth::check()) {
            return Redirect::to($this->pageUrl(AuthRedirect::loginRequired()));
        }

        /** Extract all the options. */
        $options = post();
        /** Add teacher options. */
        $options['id'] = Auth::getUser()->profile->id;
        $options['type'] = 'Genuineq\Tms\Models\School';
        $options['sortBy'] = 'school_covered_costs';

        /* Extract the courses based on the received options. */
        $this->schoolExtractCourses($options);
    }


    /***********************************************
     ******************* Helpers *******************
     ***********************************************/

    /**
     * Extract the requested courses.
     */
    protected function teacherExtractBudgetCourses($options)
    {
        $teacherBudgetCourses = BudgetModel::filterTeacherBudgetCourses($options);

        $this->page['budgetCourses'] = $teacherBudgetCourses['courses'];
        $this->page['budgetCoursesPages'] = $this->page['budgetCourses']->lastPage();
        $this->page['budgetCoursesPage'] = $this->page['budgetCourses']->currentPage();
        $this->page['budgetCoursesNumber'] = $teacherBudgetCourses['number-courses'];
        $this->page['budgetCoursesAccredited'] = $teacherBudgetCourses['number-accredited'];
        $this->page['budgetCoursesSupported'] = $teacherBudgetCourses['costs-supported'];
        $this->page['budgetCoursesDiscounted'] = $teacherBudgetCourses['costs-discounted'];
        $this->page['budgetCoursesCredits'] = $teacherBudgetCourses['total-credits'];
        $this->page['budgetCoursesHours'] = $teacherBudgetCourses['total-hours'];

        /** Extracts all the budget  statics of specified teacher. */
        $this->teacherExtractSearchStatics(Auth::getUser()->profile->id);
    }

    /**
     * Extract the requested teacher statics.
     */
    protected function teacherExtractSearchStatics($teacherId)
    {
        /** Extract all sort types for filtering. */
        $this->page['budgetCourseSortTypes'] = BudgetModel::getSortingTypes();
        /** Extract all years for filtering. */
        $this->page['budgetCourseYears'] = BudgetModel::getFilterYears($teacherId);
        /** Extract all semesters for filtering. */
        $this->page['budgetCourseSemesters'] = BudgetModel::getFilterSemesters();
    }

    /**
     * Extract the requested courses.
     */
    protected function schoolExtractCourses($options)
    {
        $schoolCourses = BudgetModel::filterSchoolCourses($options);

        $this->page['courses'] = $schoolCourses['courses'];
        $this->page['coursesPages'] = $this->page['courses']->lastPage();
        $this->page['coursesPage'] = $this->page['courses']->currentPage();
        $this->page['coursesNumber'] = $schoolCourses['number-courses'];
        $this->page['coursesAccredited'] = $schoolCourses['number-accredited'];
        $this->page['coursesSupported'] = $schoolCourses['costs-supported'];
        $this->page['coursesCredits'] = $schoolCourses['total-credits'];
        $this->page['coursesHours'] = $schoolCourses['total-hours'];

        /** Extracts all the budget statics of specified teacher. */
        $this->schoolExtractSearchStatics(Auth::getUser()->profile->id);
    }

    /**
     * Extract the requested school statics.
     */
    protected function schoolExtractSearchStatics($teacherId)
    {
        /** Extract all sort types for filtering. */
        $this->page['coursesSortTypes'] = BudgetModel::getSortingTypes();
        /** Extract all years for filtering. */
        $this->page['coursesCategories'] = Course::getFilterCategories();
        /** Extract all semesters for filtering. */
        $this->page['coursesAccreditations'] = Course::getFilterAccreditations();
    }
}
