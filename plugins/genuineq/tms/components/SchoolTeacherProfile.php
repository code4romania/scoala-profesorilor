<?php namespace Genuineq\Tms\Components;

use Lang;
use Auth;
use Flash;
use Input;
use Request;
use Storage;
use Redirect;
use Response;
use Validator;
use ValidationException;
use ApplicationException;
use Cms\Classes\ComponentBase;
use Maatwebsite\Excel\Facades\Excel;
use Genuineq\Tms\Models\School;
use Genuineq\Tms\Models\Teacher;
use Genuineq\Tms\Models\Address;
use Genuineq\Tms\Models\SchoolLevel;
use Genuineq\Tms\Models\ContractType;
use Genuineq\Tms\Models\SeniorityLevel;
use Genuineq\User\Helpers\AuthRedirect;
use Genuineq\Tms\Classes\TeachersImport;

use Log;

/**
 * School teacher profile component
 *
 * Allows the update of a teacher profile.
 */
class SchoolTeacherProfile extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'genuineq.tms::lang.component.school-teacher-profile.name',
            'description' => 'genuineq.tms::lang.component.school-teacher-profile.description'
        ];
    }

    public function defineProperties()
    {
        return [
            'forceSecure' => [
                'title'       => 'genuineq.tms::lang.component.school-teacher-profile.backend.force_secure',
                'description' => 'genuineq.tms::lang.component.school-teacher-profile.backend.force_secure_desc',
                'type'        => 'checkbox',
                'default'     => 0
            ],
        ];
    }

    /**
     * Executed when the component is initialised.
     */
    public function prepareVars()
    {
        /* Get all the school teachers. */
        $this->extractTeachers(/*options*/[]);
    }

    /**
     * Executed when this component is bound to a page or layout.
     */
    public function onRun()
    {
        /** Redirect to HTTPS checker */
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
     * Searches, filters, orders and paginates school teachers based on
     *  on the post options.
     */
    public function onTeacherSearch()
    {
        if (!Auth::check()) {
            return Redirect::to($this->pageUrl(AuthRedirect::loginRequired()));
        }

        /* Extract the school teachers based on the received options. */
        $this->extractTeachers(/*options*/post());
    }

    /**
     * Prepares all the school teacher for viewing.
     */
    public function onDisplayTeachers()
    {
        if (!Auth::check()) {
            return Redirect::to($this->pageUrl(AuthRedirect::loginRequired()));
        }

        /* Get all the school teachers. */
        $this->extractTeachers(/*options*/[]);
    }

    /**
     * Prepares a school teacher for viewing.
     */
    public function onTeacherView()
    {
        if (!Auth::check()) {
            return Redirect::to($this->pageUrl(AuthRedirect::loginRequired()));
        }

        $this->page['teacher'] = Teacher::find(post('teacherId'));
    }

    /**
     * Prepares a school teacher for editing.
     */
    public function onTeacherEdit()
    {
        if (!Auth::check()) {
            return Redirect::to($this->pageUrl(AuthRedirect::loginRequired()));
        }

        /** Prepare the static data. */
        $this->prepareEditVars();

        $this->page['schoolTeacherProfile'] = Teacher::find(post('teacherId'));
        $this->page['schoolTeacherUser'] = $this->page['schoolTeacherProfile']->user;

        $this->page['schoolTeacherProfileAddress'] = ($this->page['schoolTeacherProfile']->address) ? ($this->page['schoolTeacherProfile']->address->name . ', ' . $this->page['schoolTeacherProfile']->address->county) : (null);
        $this->page['schoolTeacherProfileSeniorityLevel'] = ($this->page['schoolTeacherProfile']->seniority_level) ? ($this->page['schoolTeacherProfile']->seniority_level->name) : (null);
        $this->page['schoolTeacherProfileSchoolLevel'] = ($this->page['schoolTeacherProfile']->school_level) ? ($this->page['schoolTeacherProfile']->school_level->name) : (null);
        $this->page['schoolTeacherProfileContractType'] = ($this->page['schoolTeacherProfile']->contract_type) ? ($this->page['schoolTeacherProfile']->contract_type->name) : (null);

        $this->page['schoolTeacher'] = Teacher::find(post('teacherId'));
    }

    /**
     * Deletes the connection between a school teacher and the school.
     */
    public function onTeacherRemove()
    {
        if (!Auth::check()) {
            return Redirect::to($this->pageUrl(AuthRedirect::loginRequired()));
        }

        /** Extract the teacher. */
        $teacher = Teacher::find(post('teacherId'));
        /** Extract the school. */
        $school = Auth::getUser()->profile;

        /** Extract the school teacher link. */
        $link = $school->teachers->where('id', $teacher->id)->first()->pivot;

        /** Delete the link */
        $link->delete();

        /**
         * Delete any course sponsorships from the current school,
         *  from the active learning plan of the teacher.
         */
        $learningPlan = $teacher->getActiveLearningPlan();

        /** Extract all courses added by the school. */
        $schoolCourses = $learningPlan->courses->where('school_id', $school->id);

        /** Remove any sponsorship and/or the mandatory mark from the future courses. */
        foreach ($schoolCourses as $learningPlanCourse) {
            /** Check if the course has NOT started. */
            if ((date('Y-m-d') < $learningPlanCourse->course->start_date) && (($learningPlanCourse->covered_costs) || ($learningPlanCourse->mandatory))) {
                /** Remove any sponsorship */
                $learningPlanCourse->covered_costs = 0;
                /** Mark as no longer mandatory */
                $learningPlanCourse->mandatory = 0;

                $learningPlanCourse->save();
            }
        }

        /////////////////////////////////////
        //TO DO: Add appraisal remove/close
        /////////////////////////////////////
    }

    /**
     * Update the teacher profile.
     */
    public function onSchoolTeacherProfileUpdate()
    {
        $teacher = Teacher::find(post('teacherId'));
        if (!Auth::check()) {
            return Redirect::to($this->pageUrl(AuthRedirect::loginRequired()));
        } elseif (!$this->isTeacherLinked($teacher)) {
            throw new ApplicationException(Lang::get('genuineq.tms::lang.component.school-teacher-profile.message.teacher_not_linked'));
        }

        /** Extract the post data to validate. */
        $data = post();
        $data['slug'] = str_replace(' ', '-', strtolower($data['name']));

        /** Extract the address ID. */
        if ($data['address_id']) {
            $fullAddress = explode(', ', $data['address_id']);
            $address = Address::whereName($fullAddress[0])->whereCounty($fullAddress[1])->first();
            $data['address_id'] = ($address) ? ($address->id) : ('');
        } else {
            unset($data['address_id']);
        }

        /** Extract the seniority level ID. */
        if ($data['seniority_level_id']) {
            $seniorityLevel = SeniorityLevel::whereName($data['seniority_level_id'])->first();
            $data['seniority_level_id'] = ($seniorityLevel) ? ($seniorityLevel->id) : ('');
        } else {
            unset($data['seniority_level_id']);
        }

        /** Extract the school level ID. */
        if ($data['school_level_id']) {
            $schoolLevel = SchoolLevel::whereName($data['school_level_id'])->first();
            $data['school_level_id'] = ($schoolLevel) ? ($schoolLevel->id) : ('');
        } else {
            unset($data['school_level_id']);
        }

        /** Extract the contract type ID. */
        if ($data['contract_type_id']) {
            $contractType = ContractType::whereName($data['contract_type_id'])->first();
            $data['contract_type_id'] = ($contractType) ? ($contractType->id) : ('');
        } else {
            unset($data['contract_type_id']);
        }

        if ($data['birth_date']) {
            $data['birth_date'] = date('Y-m-d H:i:s', strtotime($data['birth_date']));
        } else {
            $data['birth_date'] = null;
        }

        /** Extract the validation rules. */
        $rules = [
            'name' => ['nullable', 'max:50', 'regex:/^[a-zA-Z0123456789 -]*$/i'],
            'phone' => ['nullable', 'max:15', 'regex:/^[0123456789 +]*$/i'],
            'birth_date' => 'date|nullable',
            'address_id' => 'numeric|nullable',
            'seniority_level_id' => 'numeric|nullable',
            'school_level_id' => 'numeric|nullable',
            'contract_type_id' => 'numeric|nullable',
        ];

        /** Construct the validation error messages. */
        $messages = [
            'name.regex' => Lang::get('genuineq.tms::lang.component.school-teacher-profile.validation.name_regex'),
            'name.max' => Lang::get('genuineq.tms::lang.component.school-teacher-profile.validation.name_max'),
            'phone.regex' => Lang::get('genuineq.tms::lang.component.school-teacher-profile.validation.phone_regex'),
            'phone.max' => Lang::get('genuineq.tms::lang.component.school-teacher-profile.validation.phone_max'),
            'birth_date.date' => Lang::get('genuineq.tms::lang.component.school-teacher-profile.validation.birth_date_date'),
            'address_id.numeric' => Lang::get('genuineq.tms::lang.component.school-teacher-profile.validation.address_id_numeric'),
            'seniority_level_id.numeric' => Lang::get('genuineq.tms::lang.component.school-teacher-profile.validation.seniority_level_id_numeric'),
            'school_level_id.numeric' => Lang::get('genuineq.tms::lang.component.school-teacher-profile.validation.school_level_id_numeric'),
            'contract_type_id.numeric' => Lang::get('genuineq.tms::lang.component.school-teacher-profile.validation.contract_type_id_numeric'),
        ];

        /** Apply the validation rules. */
        $validation = Validator::make($data, $rules, $messages);
        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

        if ($teacher) {
            $teacher->fill($data);
            $teacher->save();
        } else {
            throw new ApplicationException(Lang::get('genuineq.tms::lang.component.school-teacher-profile.message.profile_update_failed'));
        }

        Flash::success(Lang::get('genuineq.tms::lang.component.school-teacher-profile.message.profile_update_successful'));
    }

    /**
     * Update the teacher profile description
     */
    public function onSchoolTeacherProfileDescriptionUpdate()
    {
        $teacher = Teacher::find(post('teacherId'));
        if (!Auth::check()) {
            return Redirect::to($this->pageUrl(AuthRedirect::loginRequired()));
        } elseif (!$this->isTeacherLinked($teacher)) {
            throw new ApplicationException(Lang::get('genuineq.tms::lang.component.school-teacher-profile.message.teacher_not_linked'));
        }

        /** Extract the post data to validate. */
        $data = post();

        /** Extract the validation rules. */
        $rules = [
            'description' => 'string|nullable',
        ];

        /** Construct the validation error messages. */
        $messages = [
            'description.string' => Lang::get('genuineq.tms::lang.component.school-teacher-profile.validation.description_string'),
        ];

        /** Apply the validation rules. */
        $validation = Validator::make($data, $rules, $messages);
        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

        if ($teacher) {
            $teacher->fill($data);
            $teacher->save();
        } else {
            throw new ApplicationException(Lang::get('genuineq.tms::lang.component.school-teacher-profile.message.description_update_failed'));
        }

        Flash::success(Lang::get('genuineq.tms::lang.component.school-teacher-profile.message.description_update_successful'));
    }

    /**
     * Updates the teacher avatar.
     */
    public function onSchoolTeacherAvatarUpdate()
    {
        $teacher = Teacher::find(post('teacherId'));
        if (!Auth::check()) {
            return Redirect::to($this->pageUrl(AuthRedirect::loginRequired()));
        } elseif (!$this->isTeacherLinked($teacher)) {
            throw new ApplicationException(Lang::get('genuineq.tms::lang.component.school-teacher-profile.message.teacher_not_linked'));
        }

        /** Extract the user */
        $user = $teacher->user;

        if (Input::hasFile('avatar')) {
            $user->avatar = Input::file('avatar');
            $user->save();

            Flash::success(Lang::get('genuineq.tms::lang.component.school-teacher-profile.message.avatar_update_successful'));
            return Redirect::refresh();
        }

        Flash::success(Lang::get('genuineq.tms::lang.component.school-teacher-profile.message.avatar_update_failed'));
    }

    /**
     * Updates the teacher email
     */
    public function onSchoolTeacherEmailUpdate()
    {
        $teacher = Teacher::find(post('teacherId'));
        if (!Auth::check()) {
            return Redirect::to($this->pageUrl(AuthRedirect::loginRequired()));
        } elseif (!$this->isTeacherLinked($teacher)) {
            throw new ApplicationException(Lang::get('genuineq.tms::lang.component.school-teacher-profile.message.teacher_not_linked'));
        }

        /** Extract the form data. */
        $data = post();

        /** Extract the validation rules. */
        $rules = [
            'accountEmail' => 'required|between:6,255|email|unique:users,email',
        ];

        /** Construct the validation error messages. */
        $messages = [
            'accountEmail.required' => Lang::get('genuineq.tms::lang.component.school-teacher-profile.validation.account_mail_required'),
            'accountEmail.between' => Lang::get('genuineq.tms::lang.component.school-teacher-profile.validation.account_mail_between'),
            'accountEmail.email' => Lang::get('genuineq.tms::lang.component.school-teacher-profile.validation.account_mail_email'),
            'accountEmail.unique' => Lang::get('genuineq.tms::lang.component.school-teacher-profile.validation.account_mail_unique'),
        ];

        /** Apply the validation rules. */
        $validation = Validator::make($data, $rules, $messages);
        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

        /** Extract the user */
        $user = $teacher->user;

        /** Update the enmail */
        $user->email = $data['accountEmail'];
        $user->save();

        Flash::success(Lang::get('genuineq.tms::lang.component.school-teacher-profile.message.email_update_successful'));
    }

    /**
     * Updates the teacher password
     */
    public function onSchoolTeacherPasswordUpdate()
    {
        $teacher = Teacher::find(post('teacherId'));
        if (!Auth::check()) {
            return Redirect::to($this->pageUrl(AuthRedirect::loginRequired()));
        } elseif (!$this->isTeacherLinked($teacher)) {
            throw new ApplicationException(Lang::get('genuineq.tms::lang.component.school-teacher-profile.message.teacher_not_linked'));
        }

        /** Extract the form data. */
        $data = post();

        /** Extract the validation rules. */
        $rules = [
            'accountNewPassword' => 'required|between:' . PluginConfig::getMinPasswordLength() . ',' . PluginConfig::getMaxPasswordLength() . '|confirmed',
            'accountNewPassword_confirmation' => 'required|required_with:password',
        ];

        /** Construct the validation error messages. */
        $messages = [
            'accountNewPassword.required' => Lang::get('genuineq.tms::lang.component.school-teacher-profile.validation.account_new_password_required'),
            'accountNewPassword.between' => Lang::get('genuineq.tms::lang.component.school-teacher-profile.validation.account_new_password_between_s') . PluginConfig::getMinPasswordLength() . ' si ' . PluginConfig::getMaxPasswordLength() . Lang::get('genuineq.tms::lang.component.school-teacher-profile.validation.account_new_password_between_e'),
            'accountNewPassword.confirmed' => Lang::get('genuineq.tms::lang.component.school-teacher-profile.validation.account_new_password_confirmed'),
            'accountNewPassword_confirmation.required' => Lang::get('genuineq.tms::lang.component.school-teacher-profile.validation.account_new_password_confirmation_required'),
            'accountNewPassword_confirmation.required_with' => Lang::get('genuineq.tms::lang.component.school-teacher-profile.validation.account_new_password_confirmation_required_with'),
        ];

        /** Apply the validation rules. */
        $validation = Validator::make($data, $rules, $messages);
        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

        /** Extract the user */
        $user = $teacher->user;

        /** Update the password */
        $user->password = $data['accountNewPassword'];
        $user->forceSave();

        Flash::success(Lang::get('genuineq.tms::lang.component.school-teacher-profile.message.password_update_successful'));
    }

    /**
     * Prepares the download of a teachers import file template
     */
    public function onDownloadImportTemplate(){
        if (!Auth::check()) {
            return Redirect::to($this->pageUrl(AuthRedirect::loginRequired()));
        }

        return Redirect::to('tms-teachers-import-download');
    }

    /**
     * Imports a list of teachers and assign them to the current school
     */
    public function onTeachersImport(){
        if (!Auth::check()) {
            return Redirect::to($this->pageUrl(AuthRedirect::loginRequired()));
        }

        /** Check if a file has beeen provided. */
        if (Input::hasFile('import_file')) {
            /** Import the teachers. */
            Excel::import(new TeachersImport, Input::file('import_file'));

            /** Remove the uploaded file. */
            Storage::delete(Input::file('import_file')->getRealPath() . '/' . Input::file('import_file')->getClientOriginalName());

            Flash::success(Lang::get('genuineq.tms::lang.component.school-teacher-profile.message.teachers_import_successful'));
        } else {
            Flash::success(Lang::get('genuineq.tms::lang.component.school-teacher-profile.message.teachers_import_failed'));
        }
    }

    /***********************************************
     ******************* Helpers *******************
     ***********************************************/

    /**
     * Extract the requested teachers.
     */
    protected function extractTeachers($options)
    {
        /* Get the school teachers based on the received options. */
        $this->page['teachers'] = Auth::getUser()->profile->filterTeachers($options);
        /** Extract the number of pages. */
        $this->page['pages'] = $this->page['teachers']->lastPage();
        /** Extract the current page. */
        $this->page['page'] = $this->page['teachers']->currentPage();

        /** Extract all seniority levels. */
        $this->page['seniorityLevels'] = Teacher::getFilterSeniorityLevel();
        /** Extract all school levels. */
        $this->page['schoolLevels'] = Teacher::getFilterSchoolLevel();
        /** Extract all conract types. */
        $this->page['contractTypes'] = Teacher::getFilterContractType();
        /** Extract all sort types. */
        $this->page['sortTypes'] = Teacher::getSortingTypes();
    }

    /**
     * Executed when a school teacher is about to b edited.
     */
    protected function prepareEditVars()
    {
        /* Extract all the addresses and create the source array. */
        $value = 0;
        foreach (Address::all() as $address) {
            $addresses[$address->name . ', ' . $address->county] = $value++;
        }
        $this->page['schoolTeacherAddresses'] = json_encode($addresses);

        /* Extract all the seniority levels and create the source array. */
        $value = 0;
        foreach (SeniorityLevel::all()->pluck('name') as $seniorityLevel) {
            $seniorityLevels[$seniorityLevel] = $value++;
        }
        $this->page['schoolTeacherSeniorityLevels'] = json_encode($seniorityLevels);

        /* Extract all the school levels and create the source array. */
        $value = 0;
        foreach (SchoolLevel::all()->pluck('name') as $schoolLevel) {
            $schoolLevels[$schoolLevel] = $value++;
        }
        $this->page['schoolTeacherSchoolLevels'] = json_encode($schoolLevels);

        /* Extract all the contract types and create the source array. */
        $value = 0;
        foreach (ContractType::all()->pluck('name') as $contractType) {
            $contractTypes[$contractType] = $value++;
        }
        $this->page['schoolTeacherContractTypes'] = json_encode($contractTypes);
    }

    /**
     * Checks is a teacher is linked with the authenticated school user.
     *
     * Teacher $teacher The teacher to check.
     *
     * @return boolean
     */
    protected function isTeacherLinked($teacher){
        /** Extract the school */
        $school = Auth::getUser()->profile;

        /** Check if the teacher is linked */
        foreach ($school->teachers as $_teacher) {
            if ($_teacher->id == $teacher->id) {
                return true;
            }
        }

        return false;
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
