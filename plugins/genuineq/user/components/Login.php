<?php namespace Genuineq\User\Components;

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
use Genuineq\User\Models\User as UserModel;
use Genuineq\User\Models\Settings as UserSettings;
use Genuineq\User\Helpers\PluginConfig;

use Log;

/**
 * Login component
 *
 * Allows users to login.
 */
class Login extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'genuineq.user::lang.component.login.name',
            'description' => 'genuineq.user::lang.component.login.description'
        ];
    }

    public function defineProperties()
    {
        return [
            'forceSecure' => [
                'title'       => 'genuineq.user::lang.component.login.backend.force_secure',
                'description' => 'genuineq.user::lang.component.login.backend.force_secure_desc',
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
        $this->page['user'] = $this->user();
        $this->page['rememberLoginMode'] = $this->rememberLoginMode();
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
     * Returns the login remember mode.
     */
    public function rememberLoginMode()
    {
        return UserSettings::get('remember_login', UserSettings::REMEMBER_ALWAYS);
    }


    /***********************************************
     **************** AJAX handlers ****************
     ***********************************************/

    /**
     * Handle login requests.
     */
    public function onLogin()
    {
        try {
            /** Extract the post data to validate. */
            $credentials = [
                'email' => post('email'),
                'password' => post('password'),
            ];

            /** Construct the validation rules. */
            $rules = [
                'email' => 'required|email|between:6,255',
                'password' => 'required|between:' . PluginConfig::getMinPasswordLength() . ',' . PluginConfig::getMaxPasswordLength(),
            ];

            /** Construct the validation error messages. */
            $messages = [
                'email.required' => Lang::get('genuineq.user::lang.component.login.validation.login_required'),
                'email.between' => Lang::get('genuineq.user::lang.component.login.validation.login_between'),
                'email.email' => Lang::get('genuineq.user::lang.component.login.validation.login_email'),
                'password.required' => Lang::get('genuineq.user::lang.component.login.validation.password_required'),
                'password.between' => Lang::get('genuineq.user::lang.component.login.validation.password_between_s') . PluginConfig::getMinPasswordLength() . ' si ' . PluginConfig::getMaxPasswordLength() . Lang::get('genuineq.user::lang.component.login.validation.password_between_e'),
            ];

            /** Apply the validation rules. */
            $validation = Validator::make($credentials, $rules, $messages);
            if ($validation->fails()) {
                throw new ValidationException($validation);
            }

            /** Check the login remember mode */
            switch ($this->rememberLoginMode()) {
                case UserSettings::REMEMBER_ALWAYS:
                    $remember = true;
                    break;
                case UserSettings::REMEMBER_NEVER:
                    $remember = false;
                    break;
                case UserSettings::REMEMBER_ASK:
                    $remember = (bool) array_get($data, 'remember', false);
                    break;
            }

            /** File the beforeAuthenticate event. */
            Event::fire('genuineq.user.beforeAuthenticate', [$this, $credentials]);

            /** Authenticate the user. */
            $user = Auth::authenticate($credentials, $remember);

            /** Check if authentication was successful. */
            if (!$user) {
                throw new AuthException(Lang::get('genuineq.user::lang.component.login.message.wrong_credentials'));
            }

            /** Check if the user is banned. */
            if ($user->isBanned()) {
                Auth::logout();
                throw new AuthException(Lang::get('genuineq.user::lang.component.login.message.banned'));
            }

            /** Check if the user is NOT activated. */
            if (!$user->is_activated) {
                Auth::logout();

                if (UserSettings::ACTIVATE_USER == UserSettings::get('activate_mode')) {
                    $this->sendActivationEmail($user);

                    Flash::success(Lang::get('genuineq.user::lang.component.login.message.activation_email_sent'));
                }
                throw new AuthException(Lang::get('genuineq.user::lang.component.login.message.not_active'));
            }

            /** Record IP address. */
            if ($ipAddress = Request::ip()) {
                $user->touchIpAddress($ipAddress);
            }

            /** Redirect */
            if ($redirect = $this->loginRedirect($user, true)) {
                return $redirect;
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
     * Redirect to the intended page based on the user type.
     * @param  User $user
     * @return mixed
     */
    protected function loginRedirect($user, $intended = false)
    {
        /** Extract the redirect method. */
        $method = $intended ? 'intended' : 'to';
        /** Extract the login redirect page. */
        $page = trim((string) PluginConfig::getLoginRedirects()[$user->type]);

        /** Refresh page if $page is empty */
        if ('' === $page) {
            return Redirect::refresh();
        }

        $redirectUrl = $this->pageUrl($page);

        return Redirect::to($redirectUrl);

        // if ($redirectUrl = post('redirect', $redirectUrl)) {
        //     return Redirect::$method($redirectUrl);
        // }
    }

    /**
     * Checks if the force secure property is enabled and if so
     * returns a redirect object.
     * @return mixed
     */
    protected function redirectForceSecure()
    {
        if (
            !$this->property('forceSecure') ||
            Request::secure() ||
            Request::ajax()
        ) {
            return;
        }

        return Redirect::secure(Request::path());
    }
}
