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
use Genuineq\Tms\Models\Teacher;
use Genuineq\Tms\Models\Address;
use Genuineq\Tms\Models\SeniorityLevel;
use Genuineq\Tms\Models\SchoolLevel;
use Genuineq\Tms\Models\ContractType;
use Genuineq\User\Helpers\AuthRedirect;

/**
 * Teacher profile component
 *
 * Allows the update of a teacher profile.
 */
class TeacherProfile extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'genuineq.tms::lang.component.teacher-profile.name',
            'description' => 'genuineq.tms::lang.component.teacher-profile.description'
        ];
    }

    public function defineProperties()
    {
        return [
            'forceSecure' => [
                'title'       => 'genuineq.tms::lang.component.teacher-profile.backend.force_secure',
                'description' => 'genuineq.tms::lang.component.teacher-profile.backend.force_secure_desc',
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
        $this->page['profile'] = (Auth::check()) ? (Auth::getUser()->profile) : (null);
        $this->page['profileAddress'] = ($this->page['profile'] && $this->page['profile']->address) ? ($this->page['profile']->address->name . ', ' . $this->page['profile']->address->county) : (null);
        $this->page['profileSeniorityLevel'] = ($this->page['profile'] && $this->page['profile']->seniority_level) ? ($this->page['profile']->seniority_level->name) : (null);
        $this->page['profileSchoolLevel'] = ($this->page['profile'] && $this->page['profile']->school_level) ? ($this->page['profile']->school_level->name) : (null);
        $this->page['profileContractType'] = ($this->page['profile'] && $this->page['profile']->contract_type) ? ($this->page['profile']->contract_type->name) : (null);

        /* Extract all the addresses and create the source array. */
        $value = 0;
        foreach (Address::all() as $address) {
            $addresses[$address->name . ', ' . $address->county] = $value++;
        }
        $this->page['addresses'] = json_encode($addresses);

        /* Extract all the seniority levels and create the source array. */
        $value = 0;
        foreach (SeniorityLevel::all()->pluck('name') as $seniorityLevel) {
            $seniorityLevels[$seniorityLevel] = $value++;
        }
        $this->page['seniorityLevels'] = json_encode($seniorityLevels);

        /* Extract all the school levels and create the source array. */
        $value = 0;
        foreach (SchoolLevel::all()->pluck('name') as $schoolLevel) {
            $schoolLevels[$schoolLevel] = $value++;
        }
        $this->page['schoolLevels'] = json_encode($schoolLevels);

        /* Extract all the contract types and create the source array. */
        $value = 0;
        foreach (ContractType::all()->pluck('name') as $contractType) {
            $contractTypes[$contractType] = $value++;
        }
        $this->page['contractTypes'] = json_encode($contractTypes);
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
     * Update the teacher profile.
     */
    public function onTeacherProfileUpdate()
    {
        if (!Auth::check()) {
            return Redirect::to($this->pageUrl(AuthRedirect::loginRequired()));
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
            'name.regex' => Lang::get('genuineq.tms::lang.component.teacher-profile.validation.name_regex'),
            'name.max' => Lang::get('genuineq.tms::lang.component.teacher-profile.validation.name_max'),
            'phone.regex' => Lang::get('genuineq.tms::lang.component.teacher-profile.validation.phone_regex'),
            'phone.max' => Lang::get('genuineq.tms::lang.component.teacher-profile.validation.phone_max'),
            'birth_date.date' => Lang::get('genuineq.tms::lang.component.teacher-profile.validation.birth_date_date'),
            'address_id.numeric' => Lang::get('genuineq.tms::lang.component.teacher-profile.validation.address_id_numeric'),
            'seniority_level_id.numeric' => Lang::get('genuineq.tms::lang.component.teacher-profile.validation.seniority_level_id_numeric'),
            'school_level_id.numeric' => Lang::get('genuineq.tms::lang.component.teacher-profile.validation.school_level_id_numeric'),
            'contract_type_id.numeric' => Lang::get('genuineq.tms::lang.component.teacher-profile.validation.contract_type_id_numeric'),
        ];

        /** Apply the validation rules. */
        $validation = Validator::make($data, $rules, $messages);
        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

        /** Extract the teacher profile */
        $teacherProfile = Auth::getUser()->profile;

        if ($teacherProfile) {
            $teacherProfile->fill($data);
            $teacherProfile->save();
        } else {
            throw new ApplicationException(Lang::get('genuineq.tms::lang.component.teacher-profile.message.profile_update_failed'));
        }

        Flash::success(Lang::get('genuineq.tms::lang.component.teacher-profile.message.profile_update_successful'));
    }

    /**
     * Update the teacher profile description
     */
    public function onTeacherProfileDescriptionUpdate()
    {
        if (!Auth::check()) {
            return Redirect::to($this->pageUrl(AuthRedirect::loginRequired()));
        }

        /** Extract the post data to validate. */
        $data = post();

        /** Extract the validation rules. */
        $rules = [
            'description' => 'string|nullable',
        ];

        /** Construct the validation error messages. */
        $messages = [
            'description.string' => Lang::get('genuineq.tms::lang.component.teacher-profile.validation.description_string'),
        ];

        /** Apply the validation rules. */
        $validation = Validator::make($data, $rules, $messages);
        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

        /** Extract the teacher profile */
        $teacherProfile = Auth::getUser()->profile;

        if ($teacherProfile) {
            $teacherProfile->fill($data);
            $teacherProfile->save();
        } else {
            throw new ApplicationException(Lang::get('genuineq.tms::lang.component.teacher-profile.message.description_update_failed'));
        }

        Flash::success(Lang::get('genuineq.tms::lang.component.teacher-profile.message.description_update_successful'));
    }

    /***********************************************
     ******************* Helpers *******************
     ***********************************************/

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
