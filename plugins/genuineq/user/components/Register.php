<?php namespace Genuineq\User\Components;

use Log;
use URL;
use Lang;
use Auth;
use Mail;
use Event;
use Flash;
use Input;
use Request;
use Redirect;
use Validator;
use Exception;
use ValidationException;
use ApplicationException;
use October\Rain\Auth\AuthException;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use alcea\cnp\Cnp;
use Genuineq\User\Models\User as UserModel;
use Genuineq\User\Models\Settings as UserSettings;
use Genuineq\User\Helpers\PluginConfig;

/**
 * Register component
 *
 * Allows users to register.
 */
class Register extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'genuineq.user::lang.component.register.name',
            'description' => 'genuineq.user::lang.component.register.description'
        ];
    }

    public function defineProperties()
    {
        return [
            'forceSecure' => [
                'title'       => 'genuineq.user::lang.component.register.backend.force_secure',
                'description' => 'genuineq.user::lang.component.register.backend.force_secure_desc',
                'type'        => 'checkbox',
                'default'     => 0
            ]
        ];
    }

    /**
     * Executed when this component is initialized
     */
    public function prepareVars()
    {
        $this->page['user'] = $this->user();
        $this->page['canRegister'] = $this->canRegister();
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

        /** Check if an activation code was supplied */
        if ($code = get('activate')) {
            $this->onActivate($code);
        }

        $this->prepareVars();
    }


    /***********************************************
     ****************** Properties *****************
     ***********************************************/

    /**
     * Returns the logged in user, if available
     */
    public function user()
    {
        if (!Auth::check()) {
            return null;
        }

        return Auth::getUser();
    }

    /**
     * Flag for allowing registration, pulled from UserSettings
     */
    public function canRegister()
    {
        return UserSettings::get('allow_registration', true);
    }

    /***********************************************
     **************** AJAX handlers ****************
     ***********************************************/

    /**
     * Register the user
     */
    public function onRegister()
    {
        try {
            if (!$this->canRegister()) {
                throw new ApplicationException(Lang::get('genuineq.user::lang.component.register.message.registration_disabled'));
            }

            if ($this->isRegisterThrottled()) {
                throw new ApplicationException(Lang::get('genuineq.user::lang.component.register.message.registration_throttled'));
            }

            /** Extract the form data. */
            $data = [
                'name' => post('name'),
                'email' => post('email'),
                'password' => post('password'),
                ((post('cif')) ? ('cif') : ('sid')) => (post('cif')) ? (post('cif')) : (post('sid')),
                'password_confirmation' => post('password_confirmation'),
            ];

            /** Extract the validation rules. */
            $rules = [
                'name' => ['required', 'regex:/^[a-zA-Z -]*$/i'],
                'email' => 'required|between:6,255|email|unique:users',
                ((post('cif')) ? ('cif') : ('sid')) => 'required',
                'password' => 'required|between:' . PluginConfig::getMinPasswordLength() . ',' . PluginConfig::getMaxPasswordLength() . '|confirmed',
                'password_confirmation' => 'required|required_with:password',
            ];

            /** Construct the validation error messages. */
            $messages = [
                'name.required' =>Lang::get('genuineq.user::lang.component.register.validation.name_required'),
                'name.regex' =>Lang::get('genuineq.user::lang.component.register.validation.name_alpha'),
                'email.required' => Lang::get('genuineq.user::lang.component.register.validation.email_required'),
                'email.between' => Lang::get('genuineq.user::lang.component.register.validation.email_between'),
                'email.email' => Lang::get('genuineq.user::lang.component.register.validation.email_email'),
                'email.unique' => Lang::get('genuineq.user::lang.component.register.validation.email_unique'),
                'cui.required' => Lang::get('genuineq.user::lang.component.register.validation.cui_required'),
                'sid.required' => Lang::get('genuineq.user::lang.component.register.validation.sid_required'),
                'password.required' => Lang::get('genuineq.user::lang.component.register.validation.password_required'),
                'password.between' => Lang::get('genuineq.user::lang.component.register.validation.password_between_s') . PluginConfig::getMinPasswordLength() . ' si ' . PluginConfig::getMaxPasswordLength() . Lang::get('genuineq.user::lang.component.register.validation.password_between_e'),
                'password.confirmed' => Lang::get('genuineq.user::lang.component.register.validation.password_confirmed'),
                'password_confirmation.required' => Lang::get('genuineq.user::lang.component.register.validation.password_confirmation_required'),
                'password_confirmation.required_with' => Lang::get('genuineq.user::lang.component.register.validation.password_confirmation_required_with'),
            ];

            /** Apply the validation rules. */
            $validation = Validator::make($data, $rules, $messages);
            if ($validation->fails()) {
                throw new ValidationException($validation);
            }

            /** Extra validate the SID unique identifier field. */
            if (post('sid') && !Cnp::validate(post('sid'))) {
                throw new ValidationException([
                    'sid' => [Lang::get('genuineq.user::lang.component.register.validation.sid_invalid')]
                ]);
            }

            /** Record IP address */
            if ($ipAddress = Request::ip()) {
                $data['created_ip_address'] = $data['last_ip_address'] = $ipAddress;
            }

            /** Replace key 'cif'/'sid' with 'identifier'. */
            $data['identifier'] = $data[((post('cif')) ? ('cif') : ('sid'))];
            unset($data[((post('cif')) ? ('cif') : ('sid'))]);

            /** Set the user type field. */
            $data['type'] = (post('cif')) ? ('school') : ('teacher');

            /** Register user */
            Event::fire('genuineq.user.beforeRegister', [&$data]);

            $user = Auth::register(
                $data,
                /*$activate*/(UserSettings::ACTIVATE_AUTO == UserSettings::get('activate_mode')),
                /*$autoLogin*/false
            );

            Event::fire('genuineq.user.register', [$user, $data]);

            /** Send activation email if the activation is configured to be performed by the user */
            if (UserSettings::ACTIVATE_USER == UserSettings::get('activate_mode')) {
                $this->sendActivationEmail($user);

                Flash::success(Lang::get('genuineq.user::lang.component.register.message.activation_email_sent'));
            }
            Flash::success(Lang::get('genuineq.user::lang.component.register.message.registration_successful'));
        }
        catch (Exception $ex) {
            if (Request::ajax()) throw $ex;
            else Flash::error($ex->getMessage());
        }
    }

    /**
     * Activate the user
     * @param  string $code The activation code
     */
    public function onActivate($code = null)
    {
        try {
            /** Extract the value of the activation code. */
            $code = post('code', $code);

            $errorFields = ['code' => Lang::get('genuineq.user::lang.component.register.message.invalid_activation_code')];


            /** Break up the code parts */
            $parts = explode('!', $code);
            if (count($parts) != 2) {
                throw new ValidationException($errorFields);
            }

            list($userId, $code) = $parts;

            if (!strlen(trim($userId)) || !strlen(trim($code))) {
                throw new ValidationException($errorFields);
            }

            if (!$user = Auth::findUserById($userId)) {
                throw new ValidationException($errorFields);
            }

            if (!$user->is_activated) {
                if (!$user->attemptActivation($code)) {
                    throw new ValidationException($errorFields);
                }

                Flash::success(Lang::get('genuineq.user::lang.component.register.message.success_activation'));
            } else {
                Flash::success(Lang::get('genuineq.user::lang.component.register.message.already_activated'));
            }
        }
        catch (Exception $ex) {
            if (Request::ajax()) throw $ex;
            else Flash::error($ex->getMessage());
        }
    }

    /***********************************************
     ******************* Helpers *******************
     ***********************************************/

    /**
     * Sends the activation email to a user
     * @param  User $user
     * @return void
     */
    protected function sendActivationEmail($user)
    {
        /** Generate an activation code. */
        $code = implode('!', [$user->id, $user->getActivationCode()]);
        /** Create the activation URL. */
        $link = URL::to('/') . '?activate=' . $code;

        $data = [
            'name' => $user->name,
            'link' => $link,
            'code' => $code
        ];

        Mail::send('genuineq.user::mail.activate', $data, function($message) use ($user) {
            $message->to($user->email, $user->name);
        });
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

    /**
     * Returns true if user is throttled.
     * @return bool
     */
    protected function isRegisterThrottled()
    {
        if (!UserSettings::get('use_register_throttle', false)) {
            return false;
        }

        return UserModel::isRegisterThrottled(Request::ip());
    }
}
