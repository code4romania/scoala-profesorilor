<?php namespace Genuineq\Tms\Components;

use Log;
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
use \System\Models\File;
use Cms\Classes\ComponentBase;
use Maatwebsite\Excel\Facades\Excel;
use Genuineq\Tms\Models\School;
use Genuineq\Tms\Models\Teacher;
use Genuineq\Tms\Models\Address;
use Genuineq\Tms\Models\Grade;
use Genuineq\Tms\Models\SchoolLevel;
use Genuineq\Tms\Models\ContractType;
use Genuineq\Tms\Models\SeniorityLevel;
use Genuineq\Tms\Models\Specialization;
use Genuineq\User\Helpers\AuthRedirect;
use Genuineq\Tms\Classes\TeachersImport;
use Genuineq\Tms\Classes\SchoolTeacher;

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

        /** Prepare the static data. */
        $this->prepareAutocompleteVars();
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
            return Redirect::guest($this->pageUrl(AuthRedirect::loginRequired()));
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
            return Redirect::guest($this->pageUrl(AuthRedirect::loginRequired()));
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
            return Redirect::guest($this->pageUrl(AuthRedirect::loginRequired()));
        }

        /* Get all the school teachers. */
        $this->extractTeachers(/*options*/[]);

        /** Prepare the static data. */
        $this->prepareAutocompleteVars();
    }

    /**
     * Adds a teacher to a school.
     */
    public function onTeacherAdd()
    {
        if (!Auth::check()) {
            return Redirect::guest($this->pageUrl(AuthRedirect::loginRequired()));
        }

        $data = [
            /** Teacher data */
            'name' => post('school_teacher_add_name'),
            'email' => post('school_teacher_add_email'),
            'identifier' => post('school_teacher_add_sid'),
            'phone' => post('school_teacher_add_phone'),
            'birth_date' => post('school_teacher_add_birth_date'),
            'description' => post('school_teacher_add_description'),
            'address' => post('school_teacher_add_address_id'),
            'seniority_level' => post('school_teacher_add_seniority_level_id'),
            /** Link data */
            'school_level' => post('school_teacher_add_school_level_id'),
            'contract_type' => post('school_teacher_add_contract_type_id'),
            'grade' => post('school_teacher_add_grade_id'),
            'specialization_1' => post('school_teacher_add_specialization_1_id'),
            'specialization_2' => post('school_teacher_add_specialization_2_id'),
        ];

        $result = SchoolTeacher::createSingleSchoolTeacher($data);
        switch ($result['value']) {
            case 1:
                Flash::success($result['status']);
                break;
            case 2:
                Flash::error($result['status']);
                break;
            case 3:
                throw new ValidationException($result['status']);
                break;
            case 4:
                throw new ValidationException(['identifier' => [$result['status']]]);
                break;
        }

        /** Prepare the static data. */
        $this->prepareAutocompleteVars();

        /* Get all the school teachers. */
        $this->extractTeachers(/*options*/[]);
    }

    /**
     * Prepares a school teacher for viewing.
     */
    public function onTeacherView()
    {
        if (!Auth::check()) {
            return Redirect::guest($this->pageUrl(AuthRedirect::loginRequired()));
        }

        $school = Auth::getUser()->profile;
        /** Extract the requested teacher */
        $teacher = $school->teachers->where('id', post('teacherId'))->first();

        if (!$teacher) {
            throw new ApplicationException(Lang::get('genuineq.tms::lang.component.school-teacher-profile.message.not_exists'));
        }

        $this->page['teacher'] = $teacher;
        $this->page['appraisal'] = $school->getActiveAppraisal(post('teacherId'));
        $this->page['proposedRequests'] = $school->getProposedLearningPlanRequests($teacher->active_learning_plan->id);
        $this->page['teacherDeclinedRequests'] = $teacher->declined_requests;
        $this->page['contractType'] = ($teacher->pivot->contract_type_id) ? (ContractType::find($teacher->pivot->contract_type_id)->name) : (null);
    }

    /**
     * Prepares a school teacher for editing.
     */
    public function onTeacherEdit()
    {
        if (!Auth::check()) {
            return Redirect::guest($this->pageUrl(AuthRedirect::loginRequired()));
        }

        /** Extract the requested teacher */
        $teacher = Auth::getUser()->profile->teachers->where('id', post('teacherId'))->first();

        if (!$teacher) {
            throw new ApplicationException(Lang::get('genuineq.tms::lang.component.school-teacher-profile.message.not_exists'));
        }

        /** Prepare the static data. */
        $this->prepareAutocompleteVars();

        $this->page['schoolTeacherProfile'] = $teacher;
        $this->page['schoolTeacherUser'] = $teacher->user;
        $this->page['schoolTeacherLink'] = $teacher->pivot;
        $this->page['schoolTeacherProfileAddress'] = ($teacher->address) ? ($teacher->address->name . ', ' . $teacher->address->county) : (null);
        $this->page['schoolTeacherProfileSeniorityLevel'] = ($teacher->seniority_level) ? ($teacher->seniority_level->name) : (null);

        $this->page['schoolTeacherProfileSchoolLevel'] = ($teacher->pivot->school_level_id) ? (SchoolLevel::find($teacher->pivot->school_level_id)->name) : (null);
        $this->page['schoolTeacherProfileContractType'] = ($teacher->pivot->contract_type_id) ? (ContractType::find($teacher->pivot->contract_type_id)->name) : (null);
        $this->page['schoolTeacherProfileGrade'] = ($teacher->pivot->grade_id) ? (Grade::find($teacher->pivot->grade_id)->name) : (null);
        $this->page['schoolTeacherProfileSpecialization_1'] = ($teacher->pivot->specialization_1_id) ? (Specialization::find($teacher->pivot->specialization_1_id)->name) : (null);
        $this->page['schoolTeacherProfileSpecialization_2'] = ($teacher->pivot->specialization_2_id) ? (Specialization::find($teacher->pivot->specialization_2_id)->name) : (null);
    }

    /**
     * Deletes the connection between a school teacher and the school.
     */
    public function onTeacherRemove()
    {
        if (!Auth::check()) {
            return Redirect::guest($this->pageUrl(AuthRedirect::loginRequired()));
        }

        /** Extract the school. */
        $school = Auth::getUser()->profile;
        /** Extract the requested teacher */
        $teacher = $school->teachers->where('id', post('teacherId'))->first();

        if (!$teacher) {
            throw new ApplicationException(Lang::get('genuineq.tms::lang.component.school-teacher-profile.message.not_exists'));
        }

        /** Extract the school teacher link and remove it. */
        $school->teachers()->detach($teacher->id);

        /**
         * Delete any course sponsorships from the current school,
         *  from the active learning plan of the teacher.
         */
        $learningPlan = $teacher->active_learning_plan;

        /** Check if the learning plan has any courses. */
        if ($learningPlan && $learningPlan->courses) {
            /** Extract all courses added by the school. */
            $schoolCourses = $learningPlan->courses->where('school_budget_id', $school->active_budget_id);

            /** Remove any sponsorship and/or the mandatory mark from the future courses. */
            foreach ($schoolCourses as $learningPlanCourse) {
                /** Check if the course has NOT started. */
                if ((date('Y-m-d') < $learningPlanCourse->course->start_date) && (($learningPlanCourse->school_covered_costs) || ($learningPlanCourse->mandatory))) {
                    /** Remove course from school budget */
                    $learningPlanCourse->school_budget_id = null;
                    /** Remove any sponsorship */
                    $learningPlanCourse->school_covered_costs = 0;
                    /** Mark as no longer mandatory */
                    $learningPlanCourse->mandatory = 0;

                    $learningPlanCourse->save();
                }
            }
        }

        /** Close the active appraisal. */
        $appraisal = $school->appraisals->where('teacher_id', $teacher->id)->where('status', '<>', 'closed')->first();
        $appraisal->status = 'closed';
        $appraisal->save();

        /** Prepare the static data. */
        $this->prepareAutocompleteVars();

        /* Get all the school teachers. */
        $this->extractTeachers(/*options*/[]);
    }

    /**
     * Update the teacher profile.
     */
    public function onSchoolTeacherProfileUpdate()
    {
        if (!Auth::check()) {
            return Redirect::guest($this->pageUrl(AuthRedirect::loginRequired()));
        }

        /** Extract the requested teacher */
        $teacher = Auth::getUser()->profile->teachers->where('id', post('teacherId'))->first();

        if (!$teacher) {
            throw new ApplicationException(Lang::get('genuineq.tms::lang.component.school-teacher-profile.message.not_exists'));
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
     * Update the teacher-school link.
     */
    public function onSchoolTeacherProfileLinkUpdate()
    {
        if (!Auth::check()) {
            return Redirect::guest($this->pageUrl(AuthRedirect::loginRequired()));
        }

        /** Extract the requested teacher */
        $teacher = Auth::getUser()->profile->teachers->where('id', post('teacherId'))->first();

        if (!$teacher) {
            throw new ApplicationException(Lang::get('genuineq.tms::lang.component.school-teacher-profile.message.not_exists'));
        }

        /** Extract the school teacher link. */
        $schoolTeacherLink = $teacher->pivot;

        /** Extract the school level ID. */
        if (post('school_level_id')) {
            $schoolTeacherLink->school_level_id = SchoolLevel::whereName(post('school_level_id'))->first()->id;
        }

        /** Extract the contract type ID. */
        if (post('contract_type_id')) {
            $schoolTeacherLink->contract_type_id = ContractType::whereName(post('contract_type_id'))->first()->id;
        }

        /** Extract the contract type ID. */
        if (post('grade_id')) {
            $schoolTeacherLink->grade_id = Grade::whereName(post('grade_id'))->first()->id;
        }

        /** Extract the contract type ID. */
        if (post('specialization_1_id')) {
            $schoolTeacherLink->specialization_1_id = Specialization::whereName(post('specialization_1_id'))->first()->id;
        }

        /** Extract the contract type ID. */
        if (post('specialization_2_id')) {
            $schoolTeacherLink->specialization_2_id = Specialization::whereName(post('specialization_2_id'))->first()->id;
        }

        $schoolTeacherLink->save();

        Flash::success(Lang::get('genuineq.tms::lang.component.school-teacher-profile.message.profile_update_successful'));
    }

    /**
     * Update the teacher profile description
     */
    public function onSchoolTeacherProfileDescriptionUpdate()
    {
        if (!Auth::check()) {
            return Redirect::guest($this->pageUrl(AuthRedirect::loginRequired()));
        }

        /** Extract the requested teacher */
        $teacher = Auth::getUser()->profile->teachers->where('id', post('teacherId'))->first();

        if (!$teacher) {
            throw new ApplicationException(Lang::get('genuineq.tms::lang.component.school-teacher-profile.message.not_exists'));
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
        if (!Auth::check()) {
            return Redirect::guest($this->pageUrl(AuthRedirect::loginRequired()));
        }

        /** Extract the requested teacher */
        $teacher = Auth::getUser()->profile->teachers->where('id', post('teacherId'))->first();

        if (!$teacher) {
            throw new ApplicationException(Lang::get('genuineq.tms::lang.component.school-teacher-profile.message.not_exists'));
        }

        /** Extract the user */
        $user = $teacher->user;

        if (Input::has('avatar')) {
            /**
             * The received data will be structured like following:
             *  - data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMgAAADICAYAAACtWK6eAAAgAElEQVR4Xuy9.....
             */
            $avatarData = base64_decode(explode(",", explode(";", Input::get('avatar'))[1])[1]);
            /** Create the file name. */
            $avatarName = time() . '.png';

            /** Check if an avatar already exists and delete it. */
            if ($user->avatar) {
                $user->avatar->delete();
            }

            /** Attach the new avatar. */
            $user->avatar = new File();
            $user->avatar->fromData($avatarData, $avatarName);
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
            return Redirect::guest($this->pageUrl(AuthRedirect::loginRequired()));
        }

        /** Extract the requested teacher */
        $teacher = Auth::getUser()->profile->teachers->where('id', post('teacherId'))->first();

        if (!$teacher) {
            throw new ApplicationException(Lang::get('genuineq.tms::lang.component.school-teacher-profile.message.not_exists'));
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
        if (!Auth::check()) {
            return Redirect::guest($this->pageUrl(AuthRedirect::loginRequired()));
        }

        /** Extract the requested teacher */
        $teacher = Auth::getUser()->profile->teachers->where('id', post('teacherId'))->first();

        if (!$teacher) {
            throw new ApplicationException(Lang::get('genuineq.tms::lang.component.school-teacher-profile.message.not_exists'));
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
            return Redirect::guest($this->pageUrl(AuthRedirect::loginRequired()));
        }

        return Redirect::to('tms-teachers-import-download');
    }

    /**
     * Imports a list of teachers and assign them to the current school
     */
    public function onTeachersImport(){
        if (!Auth::check()) {
            return Redirect::guest($this->pageUrl(AuthRedirect::loginRequired()));
        }

        /** Check if a file has beeen provided. */
        if (Input::hasFile('import_file')) {
            /** Import the teachers. */
            $import = new TeachersImport();
            Excel::import($import, Input::file('import_file'));

            /** Remove the uploaded file. */
            Storage::delete(Input::file('import_file')->getRealPath() . '/' . Input::file('import_file')->getClientOriginalName());

            Flash::success(
                $import->getSuccessfullRowCount() .
                Lang::get('genuineq.tms::lang.component.school-teacher-profile.message.teachers_import_successful_1') .
                $import->getFailedRowCount() .
                Lang::get('genuineq.tms::lang.component.school-teacher-profile.message.teachers_import_successful_2')
            );
            return Redirect::refresh();
        } else {
            Flash::error(Lang::get('genuineq.tms::lang.component.school-teacher-profile.message.teachers_import_failed'));
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
        /** Send the current school. */
        $this->page['school'] = Auth::getuser()->profile;

        /* Get the school teachers based on the received options. */
        $this->page['teachers'] = $this->page['school']->filterTeachers($options);
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
    protected function prepareAutocompleteVars()
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

        /* Extract all the grades and create the source array. */
        $value = 0;
        foreach (Grade::all()->pluck('name') as $grade) {
            $grades[$grade] = $value++;
        }
        $this->page['schoolTeacherGrades'] = json_encode($grades);

        /* Extract all the specializations and create the source array. */
        $value = 0;
        foreach (Specialization::all()->pluck('name') as $specialization) {
            $specializations[$specialization] = $value++;
        }
        $this->page['schoolTeacherSpecializations'] = json_encode($specializations);
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
