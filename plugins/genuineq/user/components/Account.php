<?php namespace Genuineq\User\Components;

use Log;
use Lang;
use Auth;
use Mail;
use Hash;
use Event;
use Flash;
use Input;
use Request;
use Redirect;
use Validator;
use Exception;
use ValidationException;
use ApplicationException;
use Backend\Models\User;
use \System\Models\File;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use Genuineq\User\Models\User as UserModel;
use Genuineq\User\Models\Settings as UserSettings;
use Genuineq\User\Helpers\AuthRedirect;
use Genuineq\User\Helpers\PluginConfig;

/**
 * Account component
 *
 * Allows users to update and deactivate their account.
 */
class Account extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'genuineq.user::lang.component.account.name',
            'description' => 'genuineq.user::lang.component.account.description'
        ];
    }

    public function defineProperties()
    {
        return [
            'forceSecure' => [
                'title'       => 'genuineq.user::lang.component.account.backend.force_secure',
                'description' => 'genuineq.user::lang.component.account.backend.force_secure_desc',
                'type'        => 'checkbox',
                'default'     => 0
            ],
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
    }

    /***********************************************
     **************** AJAX handlers ****************
     ***********************************************/

    /**
     * Updates the user avatar.
     */
    public function onAvatarUpdate()
    {
        if (!Auth::check()) {
            return Redirect::guest($this->pageUrl(AuthRedirect::loginRequired()));
        }

        /** Extract the user */
        $user = Auth::getUser();

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

            Flash::success(Lang::get('genuineq.user::lang.component.account.message.avatar_update_successful'));
            return Redirect::refresh();
        }

        Flash::error(Lang::get('genuineq.user::lang.component.account.message.avatar_update_failed'));
    }

    /**
     * Updates the user email
     */
    public function onEmailUpdate()
    {
        if (!Auth::check()) {
            return Redirect::guest($this->pageUrl(AuthRedirect::loginRequired()));
        }

        /** Extract the form data. */
        $data = post();

        /** Extract the validation rules. */
        $rules = [
            'accountEmail' => 'required|between:6,255|email|unique:users,email',
        ];

        /** Construct the validation error messages. */
        $messages = [
            'accountEmail.required' => Lang::get('genuineq.user::lang.component.account.validation.account_mail_required'),
            'accountEmail.between' => Lang::get('genuineq.user::lang.component.account.validation.account_mail_between'),
            'accountEmail.email' => Lang::get('genuineq.user::lang.component.account.validation.account_mail_email'),
            'accountEmail.unique' => Lang::get('genuineq.user::lang.component.account.validation.account_mail_unique'),
        ];

        /** Apply the validation rules. */
        $validation = Validator::make($data, $rules, $messages);
        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

        /** Extract the user */
        $user = Auth::getUser();

        /** Update the email */
        $user->email = $data['accountEmail'];
        $user->save();

        Flash::success(Lang::get('genuineq.user::lang.component.account.message.email_update_successful'));
    }

    /**
     * Updates the user password
     */
    public function onPasswordUpdate()
    {
        if (!Auth::check()) {
            return Redirect::guest($this->pageUrl(AuthRedirect::loginRequired()));
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
            'accountNewPassword.required' => Lang::get('genuineq.user::lang.component.account.validation.account_new_password_required'),
            'accountNewPassword.between' => Lang::get('genuineq.user::lang.component.account.validation.account_new_password_between_s') . PluginConfig::getMinPasswordLength() . ' si ' . PluginConfig::getMaxPasswordLength() . Lang::get('genuineq.user::lang.component.account.validation.account_new_password_between_e'),
            'accountNewPassword.confirmed' => Lang::get('genuineq.user::lang.component.account.validation.account_new_password_confirmed'),
            'accountNewPassword_confirmation.required' => Lang::get('genuineq.user::lang.component.account.validation.account_new_password_confirmation_required'),
            'accountNewPassword_confirmation.required_with' => Lang::get('genuineq.user::lang.component.account.validation.account_new_password_confirmation_required_with'),
        ];

        /** Apply the validation rules. */
        $validation = Validator::make($data, $rules, $messages);
        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

        /** Extract the user */
        $user = Auth::getUser();
        if(!$user->checkPassword(post('accountCurrentPassword'))) {
            throw new ValidationException(['accountCurrentPassword' => "Parola curenta este gresita."]);
        }

        /** Update the password */
        $user->password = $data['accountNewPassword'];
        $user->forceSave();

        Flash::success(Lang::get('genuineq.user::lang.component.account.message.password_update_successful'));
    }

    /**
     * Updated email notifications flag.
     */
    public function onEmailNotificationsUpdate()
    {
        if (!Auth::check()) {
            return Redirect::guest($this->pageUrl(AuthRedirect::loginRequired()));
        }

        /** Extract the user */
        $user = Auth::getUser();

        /** Update the enmail notifications */
        $user->email_notifications = post('emailNotifications');
        $user->save();

        Flash::success(Lang::get('genuineq.user::lang.component.account.message.email_notifications_update_successful'));
    }

    /**
     * Deactivate user
     */
    public function onDeactivate()
    {
        if (!$user = $this->user()) {
            return;
        }

        if (!$user->checkHashValue('password', post('password'))) {
            throw new ValidationException(['password' => Lang::get('genuineq.user::lang.account.invalid_deactivation_pass')]);
        }

        Auth::logout();
        $user->delete();

        Flash::success(post('flash', Lang::get(/*Successfully deactivated your account. Sorry to see you go!*/'genuineq.user::lang.account.success_deactivation')));

        /*
         * Redirect
         */
        if ($redirect = $this->makeRedirection()) {
            return $redirect;
        }
    }

    /***********************************************
     ******************* Helpers *******************
     ***********************************************/


    /**
     * Redirect to the intended page after successful update, sign in or registration.
     * The URL can come from the "redirect" property or the "redirect" postback value.
     * @return mixed
     */
    protected function makeRedirection($intended = false)
    {
        $method = $intended ? 'intended' : 'to';

        $property = trim((string) $this->property('redirect'));

        // No redirect
        if ($property === '0') {
            return;
        }
        // Refresh page
        if ($property === '') {
            return Redirect::refresh();
        }

        $redirectUrl = $this->pageUrl($property) ?: $property;

        if ($redirectUrl = post('redirect', $redirectUrl)) {
            return Redirect::$method($redirectUrl);
        }
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
