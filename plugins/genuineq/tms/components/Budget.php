<?php namespace Genuineq\Tms\Components;

use Log;
use Lang;
use Auth;
use Flash;
use Request;
use Redirect;
use Cms\Classes\ComponentBase;
use Genuineq\Tms\Models\Course;
use Genuineq\Tms\Models\Budget as BudgetModel;
use Genuineq\Tms\Models\Category;
use Genuineq\Tms\Models\Teacher;
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
            return Redirect::guest($this->pageUrl(AuthRedirect::loginRequired()));
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
            return Redirect::guest($this->pageUrl(AuthRedirect::loginRequired()));
        }

        /* Extract the courses based on the received options. */
        $this->teacherExtractActiveSemesterBudgetCourses(
            [
                'id' => Auth::getUser()->profile->id,
                'type' => 'Genuineq\Tms\Models\Teacher',
                'sortBy' => 'teacher_covered_costs'
            ]
        );

        /** Send the "nonce" attribute for ajax loaded scripts. */
        $this->page['csp_nonce'] = post('nonce');
    }

    /**
     * Loads all the courses from all the budgets
     *  of the teacher.
     */
    public function onTeacherBudgetCourseSearch()
    {
        /** Force authentication in case user is not authenticated. */
        if (!Auth::check()) {
            return Redirect::guest($this->pageUrl(AuthRedirect::loginRequired()));
        }

        /** Extract all the options. */
        $options = post();
        /** Add teacher options. */
        $options['id'] = Auth::getUser()->profile->id;
        $options['type'] = 'Genuineq\Tms\Models\Teacher';
        $options['sortBy'] = 'teacher_covered_costs';

        /* Extract the courses based on the received options. */
        $this->teacherExtractActiveSemesterBudgetCourses($options);

        /** Send the "nonce" attribute for ajax loaded scripts. */
        $this->page['csp_nonce'] = post('nonce');
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
            return Redirect::guest($this->pageUrl(AuthRedirect::loginRequired()));
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

        /** Send the "nonce" attribute for ajax loaded scripts. */
        $this->page['csp_nonce'] = post('nonce');
    }

    /**
     * Loads all the courses from the active budget
     *  of the school.
     */
    public function onSchoolCourseSearch()
    {
        /** Force authentication in case user is not authenticated. */
        if (!Auth::check()) {
            return Redirect::guest($this->pageUrl(AuthRedirect::loginRequired()));
        }

        /** Extract all the options. */
        $options = post();
        /** Add teacher options. */
        $options['id'] = Auth::getUser()->profile->id;
        $options['type'] = 'Genuineq\Tms\Models\School';
        $options['sortBy'] = 'school_covered_costs';

        /* Extract the courses based on the received options. */
        $this->schoolExtractCourses($options);

        /** Send the "nonce" attribute for ajax loaded scripts. */
        $this->page['csp_nonce'] = post('nonce');
    }


    /***********************************************
     ******************* Helpers *******************
     ***********************************************/

    /**
     * Extract the requested courses.
     */
    protected function teacherExtractActiveSemesterBudgetCourses($options)
    {
        $teacherBudgetCourses = BudgetModel::filterTeacherBudgetCourses($options);

        $this->page['budgetCourses'] = $teacherBudgetCourses['courses'];
        $this->page['budgetCategories'] = $teacherBudgetCourses['categories'];
        $this->page['budgetCoursesPages'] = $this->page['budgetCourses']->lastPage();
        $this->page['budgetCoursesPage'] = $this->page['budgetCourses']->currentPage();
        $this->page['budgetCoursesNumber'] = $teacherBudgetCourses['number-courses'];
        $this->page['budgetCategoriesNumber'] = $teacherBudgetCourses['number-categories'];
        $this->page['budgetCoursesAccredited'] = $teacherBudgetCourses['number-accredited'];
        $this->page['budgetCoursesSupported'] = $teacherBudgetCourses['costs-supported'];
        $this->page['budgetCoursesDiscounted'] = $teacherBudgetCourses['costs-discounted'];
        $this->page['budgetCoursesCredits'] = $teacherBudgetCourses['total-credits'];
        $this->page['budgetCoursesHours'] = $teacherBudgetCourses['total-hours'];

        /** Extract the data for the average seniority courses number. */
        $this->teacherExtractAverageSeniorityCoursesNumber($options);

        /** Extracts all the budget statics of specified teacher. */
        $this->teacherExtractSearchStatics(Auth::getUser()->profile->id);
    }

    /**
     * Funtion that extracts the average number of courses from
     *  teachers with a specific seniority level.
     */
    protected function teacherExtractAverageSeniorityCoursesNumber($options)
    {
        /** Extract the teacher. */
        $teacher = Auth::getUser()->profile;

        /** Define the default options. */
        extract(array_merge([
            'year' => -1,
            'semester' => -1
        ], $options));

        /** Calculate the number of courses for the current teacher in the specified period. */
        $teacherCoursesNumber = 0;

        /** Extract the budgets for the specified period. */
        $teacherBudgets = BudgetModel::where('budgetable_id', $teacher->id)->where('budgetable_type', $type);

        /** Apply the budgets year filter */
        if ($year && (-1 != $year)) {
            $teacherBudgets = $teacherBudgets->whereHas('semester', function ($query) use ($year) { $query->where('year', $year); });
        }

        /** Apply the budgets semester filter */
        if ($semester && (-1 != $semester)) {
            $teacherBudgets = $teacherBudgets->whereHas('semester', function ($query) use ($semester) { $query->where('semester', $semester); });
        }

        foreach ($teacherBudgets->get() as $key => $teacherBudget) {
            $this->page['budgetCoursesAverageTeacher'] += $teacherBudget->teacherCourses->count();
        }


        /** Extract all teachers IDs of a specific seniority. */
        $seniorityTeachers = Teacher::ofSeniority($teacher->seniority_level_id)->where('id', '!=', $teacher->id)->get();

        /**
         * Calculate the average number of courses for all other
         *  teachers of the same seniority in the specified period.
         */
        foreach ($seniorityTeachers as $key => $seniorityTeacher) {
            /** Extract the budgets for the specified period. */
            $budgets = BudgetModel::where('budgetable_id', $seniorityTeacher->id)->where('budgetable_type', $type);

            /** Apply the budgets year filter */
            if ($year && (-1 != $year)) {
                $budgets = $budgets->whereHas('semester', function ($query) use ($year) { $query->where('year', $year); });
            }

            /** Apply the budgets semester filter */
            if ($semester && (-1 != $semester)) {
                $budgets = $budgets->whereHas('semester', function ($query) use ($semester) { $query->where('semester', $semester); });
            }

            foreach ($budgets->get() as $key => $budget) {
                $this->page['budgetCoursesAverageTeachers'] += $budget->teacherCourses->count();
            }
        }
        $this->page['budgetCoursesAverageTeachers'] = ($seniorityTeachers->count() > 0) ? ($this->page['budgetCoursesAverageTeachers'] / $seniorityTeachers->count()) : (0);
    }

    /**
     * Extract the requested teacher statics.
     */
    protected function teacherExtractSearchStatics($teacherId)
    {
        /** Extract all sort types for filtering. */
        $this->page['budgetCourseSortTypes'] = BudgetModel::getSortingTypes();
        /** Extract all years for filtering. */
        $this->page['budgetCourseYears'] = BudgetModel::getTeacherFilterYears($teacherId);
        /** Extract all semesters for filtering. */
        $this->page['budgetCourseSemesters'] = BudgetModel::getTeacherFilterSemesters();
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
