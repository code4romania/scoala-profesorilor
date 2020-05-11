<?php namespace Genuineq\Tms\Components;

use Lang;
use Auth;
use Flash;
use Input;
use Request;
use Redirect;
use Validator;
use ValidationException;
use ApplicationException;
use Cms\Classes\ComponentBase;
use Genuineq\Tms\Models\Course;
use Genuineq\Tms\Models\LearningPlan as LearningPlanModel;

use Log;

/**
 * School profile component
 *
 * Allows the update of a school profile.
 */
class LearningPlan extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'genuineq.tms::lang.component.learning-plan.name',
            'description' => 'genuineq.tms::lang.component.learning-plan.description'
        ];
    }

    public function defineProperties()
    {
        return [
            'forceSecure' => [
                'title'       => 'genuineq.tms::lang.component.learning-plan.backend.force_secure',
                'description' => 'genuineq.tms::lang.component.learning-plan.backend.force_secure_desc',
                'type'        => 'checkbox',
                'default'     => 0
            ],
        ];
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
        /*
         * Redirect to HTTPS checker
         */
        if ($redirect = $this->redirectForceSecure()) {
            return $redirect;
        }

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
     * Prepares all the data needed for updating the learning plan.
     */
    public function onLearningPlanEdit()
    {
        if (!Auth::check()) {
            return Redirect::to($this->pageUrl(AuthRedirect::loginRequired()));
        }


        /** Extract all categories for filtering. */
        $this->page['learningPlanCourseCategories'] = Course::getFilterCategories();
        /** Extract all accreditations for filtering. */
        $this->page['learningPlanCourseAccreditations'] = Course::getFilterAccreditations();
        /** Extract all sorting types for filtering. */
        $this->page['learningPlanCourseSortTypes'] = Course::getSortingTypes();

        /** Get all the learning plan courses. */
        $this->extractLearningPlanCourses(/*options*/[]);
    }

    /**
     * Searches, filters, orders and paginates learning profilecourses
     *  based on the post options.
     */
    public function onLearningPlanCourseSearch()
    {
        Log::info('post() = ' . print_r(post(), true));
        /* Extract the courses based on the received options. */
        $this->extractLearningPlanCourses(/*options*/post());
    }

    /**
     * Function that adds a new course to a learning plan.
     */
    public function onLearningPlanCourseAdd()
    {
        Log::info('post(): ' . print_r(post(), true));
    }
    /***********************************************
     ******************* Helpers *******************
     ***********************************************/

    /**
     * Extract the requested courses.
     */
    protected function extractLearningPlanCourses($options)
    {
        /** Extract the learning plan. */
        $this->page['learningPlan'] = LearningPlanModel::find(post('learningPlanId'));
        /** Extract all courses for filtering. */
        $this->page['learningPlanCourses'] = Course::filterCourses($options, /*_courses*/$this->page['learningPlan']->realCourses->pluck('id'));
        /** Extract the number of pages. */
        $this->page['learningPlanCoursesPages'] = $this->page['learningPlanCourses']->lastPage();
        /** Extract the current page. */
        $this->page['learningPlanCoursesPage'] = $this->page['learningPlanCourses']->currentPage();
    }

    /**
     * Checks if the force secure property is enabled and if so
     * returns a redirect object.
     * @return mixed
     */
    protected function redirectForceSecure()
    {
        if (
            Request::secure() ||
            Request::ajax() ||
            !$this->property('forceSecure')
        ) {
            return;
        }

        return Redirect::secure(Request::path());
    }
}
