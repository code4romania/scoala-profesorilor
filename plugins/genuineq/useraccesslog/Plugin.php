<?php namespace Genuineq\UserAccessLog;

use System\Classes\PluginBase;
use Event;
use Genuineq\UserAccessLog\Models\AccessLog;

/**
 * UserAccessLog Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * @var array Plugin dependencies
     */
    public $require = [
        'Genuineq.User',
    ];

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'genuineq.useraccesslog::lang.plugin.name',
            'description' => 'genuineq.useraccesslog::lang.plugin.description',
            'author'      => 'genuineq',
            'icon'        => 'icon-user',
            'homepage'    => 'https://www.genuineq.com'
        ];
    }

    public function boot()
    {
        /**
         * Log user after login
         */
        Event::listen('genuineq.user.login', function($user)
        {
            AccessLog::add($user);
        });
    }

    public function registerReportWidgets()
    {
        return [
            'Genuineq\UserAccessLog\ReportWidgets\AccessLogStatistics' => [
                'label'   => 'genuineq.useraccesslog::lang.reportwidgets.statistics.label',
                'context' => 'dashboard',
            ],
            'Genuineq\UserAccessLog\ReportWidgets\AccessLogChartLine' => [
                'label'   => 'genuineq.useraccesslog::lang.reportwidgets.chartline.label',
                'context' => 'dashboard',
            ],
            'Genuineq\UserAccessLog\ReportWidgets\AccessLogChartLineAggregated' => [
                'label'   => 'genuineq.useraccesslog::lang.reportwidgets.chartlineaggregated.label',
                'context' => 'dashboard',
            ],
            'Genuineq\UserAccessLog\ReportWidgets\Registrations' => [
                'label'   => 'genuineq.useraccesslog::lang.reportwidgets.registrations.label',
                'context' => 'dashboard',
            ],
        ];
    }
}
