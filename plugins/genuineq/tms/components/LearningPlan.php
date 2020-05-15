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
use Genuineq\Tms\Models\LearningPlansCourse;
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
        /* Extract the courses based on the received options. */
        $this->extractLearningPlanCourses(/*options*/post());
    }

    /**
     * Function that adds a new course to a learning plan.
     */
    public function onLearningPlanCourseAdd()
    {
        if (!Auth::check()) {
            return Redirect::to($this->pageUrl(AuthRedirect::loginRequired()));
        }

        /** Extract the post data to validate. */
        $data['learning_plan_id'] = post('learningPlanId');
        $data['course_id'] = post('courseId');
        $data['covered_costs'] = post('covered_costs');

        /** Extract the school ID. */
        $data['school_id'] = Auth::getUser()->profile->id;

        /** Prepare value for the mandatory field. */
        if (post('mandatory')) {
            $data['mandatory'] = 1;
        }

        /** Extract the course. */
        $course = Course::find($data['course_id']);

        /** Extract the validation rules. */
        $rules = [
            'learning_plan_id' => 'required|numeric|exists:genuineq_tms_learning_plans,id',
            'school_id' => 'required|numeric|exists:genuineq_tms_schools,id',
            'course_id' => 'required|numeric|exists:genuineq_tms_courses,id',
            'covered_costs' => 'present|numeric|max:' . $course->price,
        ];

        /** Construct the validation error messages. */
        $messages = [
            'learning_plan_id.required' => Lang::get('genuineq.tms::lang.component.learning-plan.validation.learning_plan_id_required'),
            'learning_plan_id.numeric' => Lang::get('genuineq.tms::lang.component.learning-plan.validation.learning_plan_id_numeric'),
            'learning_plan_id.exists' => Lang::get('genuineq.tms::lang.component.learning-plan.validation.learning_plan_id_exists'),
            'course_id.required' => Lang::get('genuineq.tms::lang.component.learning-plan.validation.course_id_required'),
            'course_id.numeric' => Lang::get('genuineq.tms::lang.component.learning-plan.validation.course_id_numeric'),
            'course_id.exists' => Lang::get('genuineq.tms::lang.component.learning-plan.validation.course_id_exists'),
            'school_id.required' => Lang::get('genuineq.tms::lang.component.learning-plan.validation.school_id_required'),
            'school_id.numeric' => Lang::get('genuineq.tms::lang.component.learning-plan.validation.school_id_numeric'),
            'school_id.exists' => Lang::get('genuineq.tms::lang.component.learning-plan.validation.school_id_exists'),
            'covered_costs.present' => Lang::get('genuineq.tms::lang.component.learning-plan.validation.covered_costs_present'),
            'covered_costs.numeric' => Lang::get('genuineq.tms::lang.component.learning-plan.validation.covered_costs_numeric'),
            'covered_costs.max' => Lang::get('genuineq.tms::lang.component.learning-plan.validation.covered_costs_max'),
        ];

        /** Remove empty entries of covered costs. */
        if (!$data['covered_costs']) {
            $data['covered_costs'] = 0;
        }

        /** Apply the validation rules. */
        $validation = Validator::make($data, $rules, $messages);
        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

        /** Add the course to the learning plan. */
        $learningPlanCourse = new LearningPlansCourse($data);
        $learningPlanCourse->save();

        Flash::success(Lang::get('genuineq.tms::lang.component.learning-plan.message.course_added_successful'));

        /** Extract the received courses filtering options */
        $options = [
            'searchInput' => post('learningPlanCourseSearchInput'),
            'sort' => post('learningPlanCourseSort'),
            'category' => post('learningPlanCourseCategory'),
            'accreditation' => post('learningPlanCourseAccreditation'),
        ];

        /* Extract the courses based on the received options. */
        $this->extractLearningPlanCourses($options);
    }

    /**
     * Function that removed a new course from a learning plan.
     */
    public function onLearningPlanCourseRemove()
    {
        if (!Auth::check()) {
            return Redirect::to($this->pageUrl(AuthRedirect::loginRequired()));
        }

        /** Extract the learning plan. */
        $learningPlan = LearningPlanModel::find(post('learningPlanId'));

        /** Delete the course from the learning plan. */
        $learningPlan->courses->where('course_id', post('courseId'))->first()->delete();

        Flash::success(Lang::get('genuineq.tms::lang.component.learning-plan.message.course_deleted_successful'));

        /** Extract the received courses filtering options */
        $options = [
            'searchInput' => post('learningPlanCourseSearchInput'),
            'sort' => post('learningPlanCourseSort'),
            'category' => post('learningPlanCourseCategory'),
            'accreditation' => post('learningPlanCourseAccreditation'),
        ];

        /* Extract the courses based on the received options. */
        $this->extractLearningPlanCourses($options);
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
