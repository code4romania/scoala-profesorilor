<?php namespace Iabsis\Https;

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
            'author'      => 'iabsis',
            'icon'        => 'icon-leaf'
        ];
    }

}
