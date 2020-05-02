<?php namespace Genuineq\User\Helpers;

use Auth;
use Lang;
use Flash;
use Redirect;
use Genuineq\User\Helpers\PluginConfig;

class AuthRedirect
{

    /**
     * Redirects the user to the right page when an access denied error occurs.
     * @return Redirect
     */
    private static function getRedirectPage()
    {
        if (Auth::check()) {
            return $redirectPage = trim((string) PluginConfig::getLoginRedirects()[Auth::getUser()->type]);
        } else {
            return $redirectPage = '/';
        }
    }

    /**
     * Redirects the user to the right page when an access denied error occurs.
     * @return Redirect
     */
    public static function accessDenied()
    {
        Flash::error(Lang::get('genuineq.user::lang.helper.access_denied'));

        return $redirectPage = self::getRedirectPage();
    }

    /**
     * Redirects the user to the right page when an access denied error occurs.
     * @return Redirect
     */
    public static function loginRequired()
    {
        Flash::error(Lang::get('genuineq.user::lang.helper.login_required'));

        return $redirectPage = self::getRedirectPage();
    }
}
