<?php namespace Genuineq\Tms\Components;

use Log;
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
use Genuineq\Tms\Models\School;
use Genuineq\Tms\Models\Address;
use Genuineq\Tms\Models\Inspectorate;
use Genuineq\User\Helpers\AuthRedirect;

/**
 * School profile component
 *
 * Allows the update of a school profile.
 */
class SchoolProfile extends ComponentBase
{
    /**
     * @var Genuineq\Tms\Models\School The school that is displayed.
     */
    public $profile;

    public function componentDetails()
    {
        return [
            'name'        => 'genuineq.tms::lang.component.school-profile.name',
            'description' => 'genuineq.tms::lang.component.school-profile.description'
        ];
    }

    public function defineProperties()
    {
        return [
            'forceSecure' => [
                'title'       => 'genuineq.tms::lang.component.school-profile.backend.force_secure',
                'description' => 'genuineq.tms::lang.component.school-profile.backend.force_secure_desc',
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
        $this->profile = $this->page['profile'] = (Auth::check()) ? (Auth::getUser()->profile) : (null);
        $this->page['profileAddress'] = ($this->page['profile'] && $this->page['profile']->address) ? ($this->page['profile']->address->name . ', ' . $this->page['profile']->address->county) : (null);

        /* Extract all the inspectorates and create the source array. */
        foreach (Inspectorate::all() as $inspectorate) {
            $inspectorates[$inspectorate->name] = $inspectorate->id;
        }
        $this->page['inspectorates'] = json_encode($inspectorates);

        /* Extract all the addresses and create the source array. */
        foreach (Address::all() as $address) {
            $addresses[$address->name . ', ' . $address->county] = $address->id;
        }
        $this->page['addresses'] = json_encode($addresses);

        /* Construct school types*/
        $this->page['schoolTypes'] = [
            'Public' => 'public',
            'Privat' => 'private'
        ];
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
     * Update the school profile.
     */
    public function onSchoolProfileUpdate()
    {
        if (!Auth::check()) {
            return Redirect::guest($this->pageUrl(AuthRedirect::loginRequired()));
        }

        /** Extract the post data to validate. */
        $data = post();
        $data['slug'] = str_replace(' ', '-', strtolower($data['name']));

        /** Extract the inspectorate ID. */
        if ($data['inspectorate_id']) {
            $inspectorate = Inspectorate::whereName($data['inspectorate_id'])->first();
            $data['inspectorate_id'] = ($inspectorate) ? ($inspectorate->id) : ('');
        } else {
            unset($data['inspectorate_id']);
        }

        /** Extract the address ID. */
        if ($data['address_id']) {
            $fullAddress = explode(', ', $data['address_id']);
            $address = Address::whereName($fullAddress[0])->whereCounty($fullAddress[1])->first();
            $data['address_id'] = ($address) ? ($address->id) : ('');;
        } else {
            unset($data['address_id']);
        }

        /** Extract the validation rules. */
        $rules = [
            'name' => ['nullable', 'max:50', 'regex:/^[a-zA-Z0123456789 -]*$/i'],
            'slug' => 'string|max:50|nullable',
            'phone' => ['nullable', 'max:15', 'regex:/^[0123456789 +]*$/i'],
            'email' => 'string|max:50|email|nullable',
            'principal' => ['nullable', 'max:50', 'regex:/^[a-zA-Z0123456789 -]*$/i'],
            'contact_name' => ['nullable', 'max:50', 'regex:/^[a-zA-Z0123456789 -]*$/i'],
            'contact_email' => 'string|max:50|email|nullable',
            'contact_phone' => ['nullable', 'max:15', 'regex:/^[0123456789 +]*$/i'],
            'contact_role' => 'string|max:50|nullable',
            'user_id' => 'numeric|nullable',
            'address_id' => 'numeric|nullable',
            'inspectorate_id' => 'numeric|nullable',
        ];

        /** Construct the validation error messages. */
        $messages = [
            'name.regex' => Lang::get('genuineq.tms::lang.component.school-profile.validation.name_regex'),
            'name.max' => Lang::get('genuineq.tms::lang.component.school-profile.validation.name_max'),
            'phone.regex' => Lang::get('genuineq.tms::lang.component.school-profile.validation.phone_regex'),
            'phone.max' => Lang::get('genuineq.tms::lang.component.school-profile.validation.phone_max'),
            'email.string' => Lang::get('genuineq.tms::lang.component.school-profile.validation.email_string'),
            'email.max' => Lang::get('genuineq.tms::lang.component.school-profile.validation.email_max'),
            'email.email' => Lang::get('genuineq.tms::lang.component.school-profile.validation.email_email'),
            'principal.regex' => Lang::get('genuineq.tms::lang.component.school-profile.validation.principal_regex'),
            'principal.max' => Lang::get('genuineq.tms::lang.component.school-profile.validation.principal_max'),
            'contact_name.regex' => Lang::get('genuineq.tms::lang.component.school-profile.validation.contact_name_regex'),
            'contact_name.max' => Lang::get('genuineq.tms::lang.component.school-profile.validation.contact_name_max'),
            'contact_phone.numeric' => Lang::get('genuineq.tms::lang.component.school-profile.validation.contact_phone_numeric'),
            'contact_phone.max' => Lang::get('genuineq.tms::lang.component.school-profile.validation.contact_phone_max'),
            'contact_role.string' => Lang::get('genuineq.tms::lang.component.school-profile.validation.contact_role_string'),
            'contact_role.max' => Lang::get('genuineq.tms::lang.component.school-profile.validation.contact_role_max'),
            'contact_email.string' => Lang::get('genuineq.tms::lang.component.school-profile.validation.contact_email_string'),
            'contact_email.max' => Lang::get('genuineq.tms::lang.component.school-profile.validation.contact_email_max'),
            'contact_email.email' => Lang::get('genuineq.tms::lang.component.school-profile.validation.contact_email_email'),
            'address_id.numeric' => Lang::get('genuineq.tms::lang.component.school-profile.validation.address_id_numeric'),
            'inspectorate_id.numeric' => Lang::get('genuineq.tms::lang.component.school-profile.validation.inspectorate_id_numeric'),
        ];

        /** Apply the validation rules. */
        $validation = Validator::make($data, $rules, $messages);
        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

        /** Extract the school profile */
        $schoolProfile = Auth::getUser()->profile;

        if ($schoolProfile) {
            Log::info('Data='.print_r($data,true));

            $schoolProfile->fill($data);
            
            Log::info('Data='.print_r($schoolProfile,true));

            $schoolProfile->save();
        } else {
            throw new ApplicationException(Lang::get('genuineq.tms::lang.component.school-profile.message.profile_update_failed'));
        }

        Flash::success(Lang::get('genuineq.tms::lang.component.school-profile.message.profile_update_successful'));
    }

    /**
     * Update the school profile detailed address
     */
    public function onSchoolProfileDetailedAddreddUpdate()
    {
        if (!Auth::check()) {
            return Redirect::guest($this->pageUrl(AuthRedirect::loginRequired()));
        }

        /** Extract the post data to validate. */
        $data = post();

        /** Extract the validation rules. */
        $rules = [
            'detailed_address' => 'string|nullable',
        ];

        /** Construct the validation error messages. */
        $messages = [
            'detailed_address.string' => Lang::get('genuineq.tms::lang.component.school-profile.validation.detailed_address_string'),
        ];

        /** Apply the validation rules. */
        $validation = Validator::make($data, $rules, $messages);
        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

        /** Extract the school profile */
        $schoolProfile = Auth::getUser()->profile;

        if ($schoolProfile) {
            $schoolProfile->fill($data);
            $schoolProfile->save();
        } else {
            throw new ApplicationException(Lang::get('genuineq.tms::lang.component.school-profile.message.detailed_address_update_failed'));
        }

        Flash::success(Lang::get('genuineq.tms::lang.component.school-profile.message.detailed_address_update_successful'));
    }

    /**
     * Update the school profile description
     */
    public function onSchoolProfileDescriptionUpdate()
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
            'description.string' => Lang::get('genuineq.tms::lang.component.school-profile.validation.description_string'),
        ];

        /** Apply the validation rules. */
        $validation = Validator::make($data, $rules, $messages);
        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

        /** Extract the school profile */
        $schoolProfile = Auth::getUser()->profile;

        if ($schoolProfile) {
            $schoolProfile->fill($data);
            $schoolProfile->save();
        } else {
            throw new ApplicationException(Lang::get('genuineq.tms::lang.component.school-profile.message.description_update_failed'));
        }

        Flash::success(Lang::get('genuineq.tms::lang.component.school-profile.message.description_update_successful'));
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
