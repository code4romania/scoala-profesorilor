<?php namespace Genuineq\Tms\Components;

use Log;
use Lang;
use Auth;
use Flash;
use Input;
use Request;
use Redirect;
use Validator;
use Carbon\Carbon;
use ValidationException;
use ApplicationException;
use Cms\Classes\ComponentBase;
use Genuineq\Tms\Models\Teacher;
use Genuineq\Tms\Models\Address;
use Genuineq\Tms\Models\SeniorityLevel;
use Genuineq\Tms\Models\SchoolLevel;
use Genuineq\Tms\Models\ContractType;
use Genuineq\User\Helpers\AuthRedirect;
use Genuineq\User\Models\UsersLoginLog;

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

        /* Extract all the addresses and create the source array. */
        $value = 0;
        $addresses = [];
        foreach (Address::all() as $address) {
            $addresses[$address->name . ', ' . $address->county] = $value++;
        }
        $this->page['addresses'] = json_encode($addresses);

        /* Extract all the seniority levels and create the source array. */
        $value = 0;
        $seniorityLevels = [];
        foreach (SeniorityLevel::all()->pluck('name') as $seniorityLevel) {
            $seniorityLevels[$seniorityLevel] = $value++;
        }
        $this->page['seniorityLevels'] = json_encode($seniorityLevels);

        /** Extract the last login date and time. */
        $this->page['lastLogin'] = Auth::getUser()->last_login;

        /** Extract the previous login. */
        $previousLogin = UsersLoginLog::where('email', Auth::getUser()->email)->where('type', 'Successful login')->where('created_at', '<', Auth::getUser()->last_login)->orderBy('created_at', 'desc')->first();

        /** Extract the number of failed logins from last successfull login. */
        if ($previousLogin) {
            $this->page['failedLogins'] = UsersLoginLog::where('email', Auth::getUser()->email)->where('type', 'Unsuccessful login')->whereBetween('created_at', [$previousLogin->created_at, Auth::getUser()->last_login])->count();
        } else {
            $this->page['failedLogins'] = 0;
        }
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
            return Redirect::guest($this->pageUrl(AuthRedirect::loginRequired()));
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
            return Redirect::guest($this->pageUrl(AuthRedirect::loginRequired()));
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
            'name.regex' => Lang::get('genuineq.tms::lang.component.teacher-profile.validation.name_regex'),
            'name.max' => Lang::get('genuineq.tms::lang.component.teacher-profile.validation.name_max'),
            'phone.regex' => Lang::get('genuineq.tms::lang.component.teacher-profile.validation.phone_regex'),
            'phone.max' => Lang::get('genuineq.tms::lang.component.teacher-profile.validation.phone_max'),
            'birth_date.date' => Lang::get('genuineq.tms::lang.component.teacher-profile.validation.birth_date_date'),
            'address_id.numeric' => Lang::get('genuineq.tms::lang.component.teacher-profile.validation.address_id_numeric'),
            'seniority_level_id.numeric' => Lang::get('genuineq.tms::lang.component.teacher-profile.validation.seniority_level_id_numeric'),
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
            return Redirect::guest($this->pageUrl(AuthRedirect::loginRequired()));
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

    /**
     * Update the teacher active budget
     */
    public function onTeacherProfileBudgetUpdate()
    {
        if (!Auth::check()) {
            return Redirect::guest($this->pageUrl(AuthRedirect::loginRequired()));
        }

        if (0 > post('budget')) {
            throw new ApplicationException(Lang::get('genuineq.tms::lang.component.teacher-profile.validation.invalid_budget'));
        }

        /** Extract the teacher profile budget and update it. */
        $budget = Auth::getUser()->profile->active_budget;
        $budget->budget = post('budget');
        $budget->save();

        Flash::success(Lang::get('genuineq.tms::lang.component.teacher-profile.message.budget_update_successful'));
    }

    /**
     * Delete the teacher.
     */
    public function onTeacherProfileDelete()
    {
        if (!Auth::check()) {
            return Redirect::guest($this->pageUrl(AuthRedirect::loginRequired()));
        }

        /** Extract the user. */
        $user = Auth::getUser();
        /** Extract the user profile. */
        $profile = $user->profile;

        /** Logout the user. */
        Auth::logout();

        /** Log the logout request. */
        UsersLoginLog::create([
            "type" => "Successful logout",
            "name" => $user->name,
            "email" => $user->email,
            "ip_address" => Request::ip(),

        ]);

        /** Anonymize the user. */
        $user->update([
            'name' => 'name_' . Carbon::now()->timestamp,
            'surname' => 'surname_' . Carbon::now()->timestamp,
            'username' => 'username_' . Carbon::now()->timestamp,
            'email' => 'email_' . Carbon::now()->timestamp . '@email.com',
            'password' => str_random(32),
            'identifier' => Carbon::now()->timestamp,
            'created_ip_address' => '0.0.0.0',
            'last_ip_address' => '0.0.0.0',
        ]);

        /** Anonimize the user profile. */
        $profile->update([
            'name' => 'name_' . Carbon::now()->timestamp,
            'slug' => 'slug_' . Carbon::now()->timestamp,
            'phone' => '111111111111111',
            'birth_date' => '1111-11-11',
            'address_id' => '0',
            'description' => 'description_' . Carbon::now()->timestamp,
        ]);

        Flash::success(Lang::get('genuineq.tms::lang.component.teacher-profile.message.delete_successful'));

        return Redirect::to($this->pageUrl('/'));
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
