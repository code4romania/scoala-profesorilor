<?php namespace Genuineq\User\Components;

use Log;
use Lang;
use Auth;
use Event;
use Flash;
use Request;
use Response;
use Redirect;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use Genuineq\User\Models\UserGroup;
use Genuineq\User\Helpers\AuthRedirect;
use Genuineq\User\Helpers\PluginConfig;
use Genuineq\User\Models\UsersLoginLog;

/**
 * User session
 *
 * This will inject the user object to every page and provide the ability for
 * the user to sign out. This can also be used to restrict access to pages.
 */
class Session extends ComponentBase
{
    const ALLOW_ALL = 'all';
    const ALLOW_GUEST = 'guest';
    const ALLOW_USER = 'user';

    const SECURITY_SUCCESS           = 0;
    const SECURITY_ERROR_NOT_GUEST   = -1;
    const SECURITY_ERROR_NOT_USER    = -2;
    const SECURITY_ERROR_WRONG_GROUP = -3;

    public function componentDetails()
    {
        return [
            'name'        => 'genuineq.user::lang.component.session.name',
            'description' => 'genuineq.user::lang.component.session.description'
        ];
    }

    public function defineProperties()
    {
        return [
            'security' => [
                'title'       => 'genuineq.user::lang.component.session.backend.security_title',
                'description' => 'genuineq.user::lang.component.session.backend.security_desc',
                'type'        => 'dropdown',
                'default'     => 'all',
                'options'     => [
                    'all'   => 'genuineq.user::lang.component.session.backend.all',
                    'user'  => 'genuineq.user::lang.component.session.backend.users',
                    'guest' => 'genuineq.user::lang.component.session.backend.guests'
                ]
            ],
            'allowedUserGroups' => [
                'title'       => 'genuineq.user::lang.component.session.backend.allowed_groups_title',
                'description' => 'genuineq.user::lang.component.session.backend.allowed_groups_description',
                'placeholder' => '*',
                'type'        => 'set',
                'default'     => []
            ]
        ];
    }

    public function getAllowedUserGroupsOptions()
    {
        return UserGroup::lists('name','code');
    }

    /**
     * Executed when this component is bound to a page or layout.
     */
    public function onRun()
    {
        /**
         * If the user doesn't have access it will be redirected to it's
         *  specific dashboard if it's logged in or to the root URL in
         *  case of guest users.
         */
        $userSecurity = $this->checkUserSecurity();
        if ((self::SECURITY_ERROR_NOT_GUEST == $userSecurity) || (self::SECURITY_ERROR_WRONG_GROUP == $userSecurity)) {
            return Redirect::guest($this->pageUrl(AuthRedirect::accessDenied()));
        } elseif (self::SECURITY_ERROR_NOT_USER == $userSecurity) {
            return Redirect::guest($this->pageUrl(AuthRedirect::loginRequired()));
        }

        /** Make the user data available inside the page. */
        $this->page['user'] = $this->user();
    }

    /**
     * Returns the logged in user, if available, and touches
     * the last seen timestamp.
     * @return Genuineq\User\Models\User
     */
    public function user()
    {
        if (!$user = Auth::getUser()) {
            return null;
        }

        if (!Auth::isImpersonator()) {
            $user->touchLastSeen();
        }

        return $user;
    }

    /**
     * Returns the previously signed in user when impersonating.
     */
    public function impersonator()
    {
        return Auth::getImpersonator();
    }

    /**
     * Log out the user
     *
     * Usage:
     *   <a data-request="onLogout">Sign out</a>
     *
     * With the optional redirect parameter:
     *   <a data-request="onLogout" data-request-data="redirect: '/good-bye'">Sign out</a>
     *
     */
    public function onLogout()
    {
        if (!Auth::check()) {
            return Redirect::guest($this->pageUrl(AuthRedirect::loginRequired()));
        }

        /** Extract the user. */
        $user = Auth::getUser();

        /** Logout the user. */
        Auth::logout();

        /** Log the logout request. */
        UsersLoginLog::create([
            "type" => "Successful logout",
            "name" => $user->name,
            "email" => $user->email,
            "ip_address" => Request::ip(),

        ]);

        Flash::success(Lang::get('genuineq.user::lang.component.session.message.logout'));

        return Redirect::to($this->pageUrl('/'));
    }

    /**
     * If impersonating, revert back to the previously signed in user.
     * @return Redirect
     */
    public function onStopImpersonating()
    {
        if (!Auth::isImpersonator()) {
            return $this->onLogout();
        }

        Auth::stopImpersonate();

        $url = post('redirect', Request::fullUrl());

        Flash::success(Lang::get('genuineq.user::lang.component.session.message.stop_impersonate_success'));

        return Redirect::to($url);
    }

    /**
     * Checks if the user can access this page based on the security rules
     * @return integer One of the following:
     *                      SECURITY_SUCCESS
     *                      SECURITY_ERROR_NOT_GUEST
     *                      SECURITY_ERROR_NOT_USER
     *                      SECURITY_ERROR_WRONG_GROUP
     */
    protected function checkUserSecurity()
    {
        $allowedGroup = $this->property('security', self::ALLOW_ALL);
        $allowedUserGroups = $this->property('allowedUserGroups', []);
        $isAuthenticated = Auth::check();

        if ($isAuthenticated) {
            /** Check if only guests are allowed. */
            if ($allowedGroup == self::ALLOW_GUEST) {
                /** User should not be authenticated. */
                return self::SECURITY_ERROR_NOT_GUEST;
            }

            /** Check 'user' has the right group. */
            if (!empty($allowedUserGroups)) {
                $userGroups = Auth::getUser()->groups->lists('code');
                if (!count(array_intersect($allowedUserGroups, $userGroups))) {
                    /** User doesn't have the right group. */
                    return self::SECURITY_ERROR_WRONG_GROUP;
                }
            }
        } else {
            /** Check if only users are allowed. */
            if ($allowedGroup == self::ALLOW_USER) {
                /** User should be authenticated. */
                return self::SECURITY_ERROR_NOT_USER;
            }
        }

        return self::SECURITY_SUCCESS;
    }
}
