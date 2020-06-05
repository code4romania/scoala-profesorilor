<?php namespace Genuineq\Https;

use Event;
use System\Classes\PluginBase;

/**
 * https Plugin Information File
 */
class Plugin extends PluginBase
{

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'https',
            'description' => 'No description provided yet...',
            'author'      => 'genuineq',
            'icon'        => 'icon-leaf'
        ];
    }

    public function boot()
    {
        Event::listen('backend.beforeRoute', function () {
            $this->app['url']->forceScheme("https");
        });

        Event::listen('backend.route', function () {
            $this->app['url']->forceScheme("https");
        });
    }

}
