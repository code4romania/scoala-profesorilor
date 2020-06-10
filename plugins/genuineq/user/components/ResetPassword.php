<?php namespace Genuineq\User\Components;

use Log;
use URL;
use Auth;
use Lang;
use Mail;
use Flash;
use Validator;
use ValidationException;
use ApplicationException;
use Cms\Classes\ComponentBase;
use Genuineq\User\Models\User as UserModel;
use Genuineq\User\Helpers\PluginConfig;

/**
 * Password reset workflow
 *
 * When a user has forgotten their password, they are able to reset it using
 * a unique token that, sent to their email address upon request.
 */
class ResetPassword extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'genuineq.user::lang.component.password-reset.name',
            'description' => 'genuineq.user::lang.component.password-reset.description'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    /**
     * Executed when this component is bound to a page or layout.
     */
    public function onRun()
    {
        /** Check if an password reset code was supplied */
        if ($code = get('reset')) {
            $this->page['resetCode'] = $code;
        }
    }

    /***********************************************
     **************** AJAX handlers ****************
     ***********************************************/

    /**
     * Trigger the password reset email
     */
    public function onRestorePassword()
    {
        /** Extract the form data. */
        $data = [
            'email' => post('email'),
        ];

        /** Construct the validation rules. */
        $rules = [
            'email' => 'required|email|between:6,255',
        ];

        /** Construct the validation error messages. */
        $messages = [
            'email.required' => Lang::get('genuineq.user::lang.component.password-reset.validation.login_required'),
            'email.between' => Lang::get('genuineq.user::lang.component.password-reset.validation.login_between'),
            'email.email' => Lang::get('genuineq.user::lang.component.password-reset.validation.login_email'),
        ];

        /** Apply the validation rules. */
        $validation = Validator::make($data, $rules, $messages);
        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

        /** Search the user. */
        $user = UserModel::findByEmail(post('email'));

        if ($user) {
            $this->sendPasswordResetEmail($user);
        }

        Flash::success(Lang::get('genuineq.user::lang.component.password-reset.message.restore_successful'));
    }

    /**
     * Perform the password reset
     */
    public function onResetPassword($code = null)
    {
        /** Extract the form data. */
        $data = [
            'code' => post('code'),
            'password' => post('password'),
            'password_confirmation' => post('password_confirmation'),
        ];

        /** Extract the validation rules. */
        $rules = [
            'password' => 'required|between:' . PluginConfig::getMinPasswordLength() . ',' . PluginConfig::getMaxPasswordLength() . '|confirmed',
            'password_confirmation' => 'required|required_with:password',
        ];

        /** Construct the validation error messages. */
        $messages = [
            'password.required' => Lang::get('genuineq.user::lang.component.password-reset.validation.password_required'),
            'password.between' => Lang::get('genuineq.user::lang.component.password-reset.validation.password_between_s') . PluginConfig::getMinPasswordLength() . ' si ' . PluginConfig::getMaxPasswordLength() . Lang::get('genuineq.user::lang.component.password-reset.validation.password_between_e'),
            'password.confirmed' => Lang::get('genuineq.user::lang.component.password-reset.validation.password_confirmed'),
            'password_confirmation.required' => Lang::get('genuineq.user::lang.component.password-reset.validation.password_confirmation_required'),
            'password_confirmation.required_with' => Lang::get('genuineq.user::lang.component.password-reset.validation.password_confirmation_required_with'),
        ];

        /** Apply the validation rules. */
        $validation = Validator::make($data, $rules, $messages);
        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

        /** Break up the code parts. */
        $parts = explode('!', post('code'));
        if (count($parts) != 2) {
            throw new ApplicationException(Lang::get('genuineq.user::lang.component.password-reset.message.invalid_reset_code'));
        }

        list($userId, $code) = $parts;

        if (!strlen(trim($userId)) || !strlen(trim($code)) || !$code) {
            throw new ApplicationException(Lang::get('genuineq.user::lang.component.password-reset.message.invalid_reset_code'));
        }

        if (!$user = Auth::findUserById($userId)) {
            throw new ApplicationException(Lang::get('genuineq.user::lang.component.password-reset.message.invalid_reset_code'));
        }

        if (!$user->attemptResetPassword($code, post('password'))) {
            throw new ApplicationException(Lang::get('genuineq.user::lang.component.password-reset.message.invalid_reset_code'));
        }

        Flash::success(Lang::get('genuineq.user::lang.component.password-reset.message.reset_successful'));
    }

    /***********************************************
     ******************* Helpers *******************
     ***********************************************/

    /**
     * Sends the password reset email to a user
     * @param  User $user
     * @return void
     */
    protected function sendPasswordResetEmail($user)
    {
        /** Generate a password reset code. */
        $code = implode('!', [$user->id, $user->getResetPasswordCode()]);
        /** Create the password reset URL. */
        $link = URL::to('/') . '?reset=' . $code;

        $data = [
            'name' => $user->name,
            'username' => $user->username,
            'link' => $link
        ];

        Mail::send('genuineq.user::mail.restore', $data, function($message) use ($user) {
            $message->to($user->email, $user->name);
        });
    }
}
