<?php namespace Genuineq\User\Helpers;

use Config;

class PluginConfig
{
    /**
     * Returns the minimum length for a new password from settings.
     * @return int
     */
    public static function getMinPasswordLength()
    {
        return Config::get('genuineq.user::minPasswordLength', 8);
    }

    /**
     * Returns the maximum length for a new password from settings.
     * @return int
     */
    public static function getMaxPasswordLength()
    {
        return Config::get('genuineq.user::maxPasswordLength', 255);
    }

    /**
     * Returns the configured user groups.
     * @return array
     */
    public static function getUserGroups()
    {
        return Config::get(
            'genuineq.user::userGroups',
            [
                'school' => [
                    'name' => 'School',
                    'code' => 'school',
                    'description' => 'Group that contains all users of a school.',
                ],
                'teacher' => [
                    'name' => 'Teacher',
                    'code' => 'teacher',
                    'description' => 'Group that contains all users of a teacher.',
                ],
            ],
        );
    }

    /**
     * Returns the configured user types.
     * @return array
     */
    public static function getUserTypes()
    {
        return Config::get(
            'genuineq.user::userTypes',
            [
                'school',
                'teacher',
            ]
        );
    }

    /**
     * Returns the available user type options displayed in the admin view.
     * @return array
     */
    public static function getUserTypeOptions()
    {
        return Config::get(
            'genuineq.user::userTypeOptions',
            [
                'school' => 'School',
                'teacher' => 'Teacher'
            ]
        );
    }

    /**
     * Returns the configured login redirects.
     * @return array
     */
    public static function getLoginRedirects()
    {
        return Config::get(
            'genuineq.user::loginRedirects',
            [
                'school' => 'dashboard-school',
                'teacher' => 'dashboard-teacher',
            ]
        );
    }

    /**
     * Returns the configured profile pages.
     * @return array
     */
    public static function getProfilePages()
    {
        return Config::get(
            'genuineq.user::profilePages',
            [
                'school' => 'profile-school',
                'teacher' => 'profile-teacher',
            ]
        );
    }
}
