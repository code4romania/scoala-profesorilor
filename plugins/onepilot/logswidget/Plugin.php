<?php namespace OnePilot\LogsWidget;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    /**
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'Logs Widget',
            'description' => 'Show an overview of site errors direct on your OctoberCMS dashboard',
            'author'      => '1Pilot.io',
            'icon'        => 'icon-file-text-o',
            'homepage'    => 'https://1pilot.io',
        ];
    }

    /**
     * @return array
     */
    public function registerReportWidgets()
    {
        return [
            ReportWidgets\ErrorsOverview::class => [
                'label'       => 'Errors Logs Overview',
                'context'     => 'dashboard',
                'permissions' => ['system.access_logs'],
            ],
        ];
    }
}
