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
use Genuineq\Tms\Models\Teacher;
use Genuineq\Tms\Models\LearningPlansCourse;
use Genuineq\Tms\Models\LearningPlan as LearningPlanModel;
use Genuineq\User\Helpers\AuthRedirect;

use Log;

/**
 * School profile component
 *
 * Allows to view and update a learning plan.
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
        // if (!Auth::check()) {
        //     return Redirect::to($this->pageUrl(AuthRedirect::loginRequired()));
        // }

        $this->prepareVars();
    }

    /***********************************************
     **************** AJAX handlers ****************
     ***********************************************/

    /***********************************************
     ******************* Common *******************
     ***********************************************/

    /**
     * Searches, filters, orders and paginates learning profile
     *  courses based on the post options.
     */
    public function onLearningPlanCourseSearch()
    {
        /* Extract the courses based on the received options. */
        $this->extractLearningPlanCourses(/*options*/post(), post('learningPlanId'));
    }

    /***********************************************
     ******************* School ********************
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
        $this->extractLearningPlanCourses(/*options*/[], post('learningPlanId'));
    }

    /**
     * Function that adds a new course to a learning plan.
     */
    public function onSchoolLearningPlanCourseAdd()
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

        /** Check if course is mandatory */
        if (post('mandatory')) {
            $data['mandatory'] = 1;
            /** Mark the course as accepted. */
            $data['status'] = 'accepted';
        } else {
            /** Mark the course as proposed. */
            $data['status'] = 'proposed';
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
        if (!post('mandatory')) {
            $learningPlanCourse->requestable = Teacher::find(post('teacherId'));
        }
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
        $this->extractLearningPlanCourses($options, post('learningPlanId'));
    }

    /**
     * Function that removes a course from a learning plan.
     */
    public function onSchoolLearningPlanCourseRemove()
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
        $this->extractLearningPlanCourses($options, post('learningPlanId'));
    }

    /**
     * Function that accepts a request for a school.
     */
    public function onSchoolLearningPlanRequestAccept()
    {
        if (!Auth::check()) {
            return Redirect::to($this->pageUrl(AuthRedirect::loginRequired()));
        }

        /** Extract the learning plan. */
        $learningPlan = LearningPlanModel::find(post('learningPlanId'));

        /** Extract the course */
        $learningPlanCourse = $learningPlan->courses->where('course_id', post('courseId'))->first();

        /** Mark the course as accepted. */
        $learningPlanCourse->status = 'accepted';
        $learningPlanCourse->school_id =  Auth::getUser()->profile->id;
        $learningPlanCourse->save();

        $this->page['teacher'] = $learningPlan->teacher;
        $this->page['proposedRequests'] = Auth::getUser()->profile->getProposedLearningPlanRequests($this->page['teacher']->active_learning_plan->id);
        $this->page['teacherDeclinedRequests'] = $this->page['teacher']->declined_requests;
    }

    /**
     * Function that declines a request for a school.
     */
    public function onSchoolLearningPlanRequestDecline()
    {
        if (!Auth::check()) {
            return Redirect::to($this->pageUrl(AuthRedirect::loginRequired()));
        }

        /** Extract the learning plan. */
        $learningPlan = LearningPlanModel::find(post('learningPlanId'));

        /** Extract the course */
        $learningPlanCourse = $learningPlan->courses->where('course_id', post('courseId'))->first();

        /** Mark the course as declined. */
        $learningPlanCourse->status = 'declined';
        $learningPlanCourse->covered_costs = 0;
        $learningPlanCourse->save();

        $this->page['teacher'] = $learningPlan->teacher;
        $this->page['proposedRequests'] = Auth::getUser()->profile->getProposedLearningPlanRequests($this->page['teacher']->active_learning_plan->id);
        $this->page['teacherDeclinedRequests'] = $this->page['teacher']->declined_requests;
    }

    /***********************************************
     ****************** Teacher ********************
     ***********************************************/

    /**
     * Prepares all the data needed for updating the learning plan.
     */
    public function onTeacherViewLearningPlan()
    {
        if (!Auth::check()) {
            return Redirect::to($this->pageUrl(AuthRedirect::loginRequired()));
        }

        /** Extract the learning plan. */
        $this->page['learningPlan'] = Auth::getUser()->profile->active_learning_plan;

        $this->page['proposedRequests'] = Auth::getUser()->profile->proposed_requests;
        $this->page['schoolDeclinedRequests'] = Auth::getUser()->profile->school_declined_requests;
    }

    /**
     * Prepares all the data needed for a teacher to update a learning plan.
     */
    public function onTeacherLearningPlanEdit()
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
        /** Extract the schools of the user. */
        $this->extractSchools();

        /** Get all the learning plan courses. */
        $this->extractLearningPlanCourses(/*options*/[], Auth::getUser()->profile->active_learning_plan->id);
    }

    /**
     * Function that adds a new course to a learning plan for a teacher.
     */
    public function onTeacherLearningPlanCourseAdd()
    {
        if (!Auth::check()) {
            return Redirect::to($this->pageUrl(AuthRedirect::loginRequired()));
        }

        if (post('school')) {
            /**
             * The teacher SELECTED a school to propose the course to.
             */

            /** Extract the post data to validate. */
            $data['learning_plan_id'] = post('learningPlanId');
            $data['course_id'] = post('courseId');
            $data['covered_costs'] = (post('covered_costs')) ? (post('covered_costs')) : (0);


            /** Extract the school. */
            $school = Auth::getUser()->profile->schools->where('name', post('school'))->first();

            if (!$school) {
                Flash::error(Lang::get('genuineq.tms::lang.component.learning-plan.message.school_not_valid'));
            } else {
                $data['school_id'] = $school->id;
                /** Mark the course as proposed. */
                $data['status'] = 'proposed';

                /** Extract the course. */
                $course = Course::find($data['course_id']);

                /** Extract the validation rules. */
                $rules = [
                    'learning_plan_id' => 'required|numeric|exists:genuineq_tms_learning_plans,id',
                    'course_id' => 'required|numeric|exists:genuineq_tms_courses,id',
                    'school_id' => 'required|numeric|exists:genuineq_tms_schools,id',
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

                /** Apply the validation rules. */
                $validation = Validator::make($data, $rules, $messages);
                if ($validation->fails()) {
                    throw new ValidationException($validation);
                }

                /** Add the course to the learning plan. */
                $learningPlanCourse = new LearningPlansCourse($data);
                $learningPlanCourse->requestable = $school;
                $learningPlanCourse->save();

                Flash::success(Lang::get('genuineq.tms::lang.component.learning-plan.message.course_added_successful'));
            }
        } else {
            /**
             * The teacher DID NOT select a school to propose the course to.
             */

            /** Extract the post data to validate. */
            $data['learning_plan_id'] = post('learningPlanId');
            $data['course_id'] = post('courseId');
            $data['covered_costs'] = 0;
            /** Mark the course as accepted. */
            $data['status'] = 'accepted';

            /** Create the validation rules. */
            $rules = [
                'learning_plan_id' => 'required|numeric|exists:genuineq_tms_learning_plans,id',
                'course_id' => 'required|numeric|exists:genuineq_tms_courses,id',
            ];

            /** Construct the validation error messages. */
            $messages = [
                'learning_plan_id.required' => Lang::get('genuineq.tms::lang.component.learning-plan.validation.learning_plan_id_required'),
                'learning_plan_id.numeric' => Lang::get('genuineq.tms::lang.component.learning-plan.validation.learning_plan_id_numeric'),
                'learning_plan_id.exists' => Lang::get('genuineq.tms::lang.component.learning-plan.validation.learning_plan_id_exists'),
                'course_id.required' => Lang::get('genuineq.tms::lang.component.learning-plan.validation.course_id_required'),
                'course_id.numeric' => Lang::get('genuineq.tms::lang.component.learning-plan.validation.course_id_numeric'),
                'course_id.exists' => Lang::get('genuineq.tms::lang.component.learning-plan.validation.course_id_exists'),
            ];

            /** Apply the validation rules. */
            $validation = Validator::make($data, $rules, $messages);
            if ($validation->fails()) {
                throw new ValidationException($validation);
            }

            /** Add the course to the learning plan. */
            $learningPlanCourse = new LearningPlansCourse($data);
            $learningPlanCourse->save();

            Flash::success(Lang::get('genuineq.tms::lang.component.learning-plan.message.course_added_successful'));
        }

        if (!post('noSearch')) {
            /** Extract the received courses filtering options */
            $options = [
                'searchInput' => post('learningPlanCourseSearchInput'),
                'sort' => post('learningPlanCourseSort'),
                'category' => post('learningPlanCourseCategory'),
                'accreditation' => post('learningPlanCourseAccreditation'),
            ];

            /* Extract the courses based on the received options. */
            $this->extractLearningPlanCourses($options, post('learningPlanId'));
        }
        /** Extract the schools of the user. */
        $this->extractSchools();
    }

    /**
     * Function that removes a course from a learning plan.
     */
    public function onTeacherLearningPlanCourseRemove()
    {
        if (!Auth::check()) {
            return Redirect::to($this->pageUrl(AuthRedirect::loginRequired()));
        }

        /** Extract the learning plan. */
        $learningPlan = LearningPlanModel::find(post('learningPlanId'));
        /** Extract the clearning plan course. */
        $learningPlanCourse = $learningPlan->courses->where('course_id', post('courseId'))->first();

        if( Auth::getUser()->profile->schools->count() &&
            $learningPlanCourse->school_id &&
            Auth::getUser()->profile->schools->where('id', $learningPlanCourse->school_id)->first() &&
            $learningPlanCourse->mandatory)
        {
            Flash::error(Lang::get('genuineq.tms::lang.component.learning-plan.message.course_deleted_not_allowed'));
        } else {
            /** Delete the course from the learning plan. */
            $learningPlan->courses->where('course_id', post('courseId'))->first()->delete();

            Flash::success(Lang::get('genuineq.tms::lang.component.learning-plan.message.course_deleted_successful'));
        }

        if (!post('noSearch')) {
            /** Extract the received courses filtering options */
            $options = [
                'searchInput' => post('learningPlanCourseSearchInput'),
                'sort' => post('learningPlanCourseSort'),
                'category' => post('learningPlanCourseCategory'),
                'accreditation' => post('learningPlanCourseAccreditation'),
            ];

            /* Extract the courses based on the received options. */
            $this->extractLearningPlanCourses($options, post('learningPlanId'));
        }
        /** Extract the schools of the user. */
        $this->extractSchools();
    }

    /**
     * Function that accepts a request for a teacher.
     */
    public function onTeacherLearningPlanRequestAccept()
    {
        if (!Auth::check()) {
            return Redirect::to($this->pageUrl(AuthRedirect::loginRequired()));
        }

        /** Extract the course */
        $learningPlanCourse = Auth::getUser()->profile->active_learning_plan->courses->where('course_id', post('courseId'))->first();

        /** Mark the course as accepted. */
        $learningPlanCourse->status = 'accepted';
        $learningPlanCourse->save();

        $this->page['learningPlan'] = Auth::getUser()->profile->active_learning_plan;
        $this->page['proposedRequests'] = Auth::getUser()->profile->proposed_requests;
        $this->page['schoolDeclinedRequests'] = Auth::getUser()->profile->school_declined_requests;
    }

    /**
     * Function that declines a request for a teacher.
     */
    public function onTeacherLearningPlanRequestDecline()
    {
        if (!Auth::check()) {
            return Redirect::to($this->pageUrl(AuthRedirect::loginRequired()));
        }

        /** Extract the course */
        $learningPlanCourse = Auth::getUser()->profile->active_learning_plan->courses->where('course_id', post('courseId'))->first();

        /** Mark the course as declined. */
        $learningPlanCourse->status = 'declined';
        // $learningPlanCourse->covered_costs = 0;
        $learningPlanCourse->save();

        $this->page['learningPlan'] = Auth::getUser()->profile->active_learning_plan;
        $this->page['proposedRequests'] = Auth::getUser()->profile->proposed_requests;
        $this->page['schoolDeclinedRequests'] = Auth::getUser()->profile->school_declined_requests;
    }

    /***********************************************
     ******************* Helpers *******************
     ***********************************************/

    /**
     * Function that extracts the schools of the teacher user.
     */
    protected function extractSchools(){
        /** Extact the teacher schools. */
        $value = 0;
        $schools = [];
        foreach (Auth::getUser()->profile->schools as $school) {
            $schools[$school->name] = $value++;
        }
        $this->page['schools'] = (count($schools)) ? (json_encode($schools)) : (null);
    }

    /**
     * Extract the requested courses.
     */
    protected function extractLearningPlanCourses($options, $learningPlanId)
    {
        /** Extract the learning plan. */
        $this->page['learningPlan'] = LearningPlanModel::find($learningPlanId);
        /** Extract all courses for filtering. */
        $this->page['learningPlanCourses'] = Course::filterCourses($options, /*_courses*/(($this->page['learningPlan'] && $this->page['learningPlan']->courses) ? ($this->page['learningPlan']->courses->pluck('course_id')) : (null)));
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
