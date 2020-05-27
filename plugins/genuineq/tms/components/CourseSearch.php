<?php namespace Genuineq\Tms\Components;

use Log;
use Auth;
use Request;
use Redirect;
use Cms\Classes\ComponentBase;
use Genuineq\Tms\Models\Course;
use Genuineq\Tms\Models\Category;
use Genuineq\User\Helpers\AuthRedirect;

/**
 * School profile component
 *
 * Allows the search, filter, order and paginate courses.
 */
class CourseSearch extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'genuineq.tms::lang.component.course-search.name',
            'description' => 'genuineq.tms::lang.component.course-search.description'
        ];
    }

    public function defineProperties()
    {
        return [
            'forceSecure' => [
                'title'       => 'genuineq.tms::lang.component.course-search.backend.force_secure',
                'description' => 'genuineq.tms::lang.component.course-search.backend.force_secure_desc',
                'type'        => 'checkbox',
                'default'     => 0
            ],

            'category' => [
                'title'       => 'genuineq.tms::lang.component.course-search.backend.category',
                'description' => 'genuineq.tms::lang.component.course-search.backend.category_desc',
                'type'        => 'string',
                'default'     => null
            ],
        ];
    }

    /**
     * Executed when this component is initialized
     */
    public function prepareVars()
    {
        if (!$this->property('category')) {
            $this->page['categories'] = Course::getFilterCategories();
        }

        $this->page['accreditations'] = Course::getFilterAccreditations();
        $this->page['sortTypes'] = Course::getSortingTypes();

        /* Get all the courses. */
        $this->extractCourses(/*options*/[]);
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

        $this->prepareVars();
    }

    /***********************************************
     **************** AJAX handlers ****************
     ***********************************************/

    /**
     * Searches, filters, orders and paginates courses based
     *  on the post options.
     */
    public function onCourseSearch()
    {
        /* Extract the courses based on the received options. */
        $this->extractCourses(/*options*/post());
    }


    /***********************************************
     ******************* Helpers *******************
     ***********************************************/

    /**
     * Extract the requested courses.
     */
    protected function extractCourses($options)
    {
        if (!$this->property('category')) {
            $this->page['courses'] = Course::filterCourses($options);
        } else {
            $this->page['courses'] = Category::whereSlug($this->property('category'))->first()->filterCourses($options);
        }

        $this->page['pages'] = $this->page['courses']->lastPage();
        $this->page['page'] = $this->page['courses']->currentPage();
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
