<?php

namespace GinoPane\AwesomeSocialLinks;

use GinoPane\AwesomeSocialLinks\Components\LinkList;
use System\Classes\PluginBase;
use GinoPane\AwesomeSocialLinks\Models\Settings;

/**
 * Class Plugin
 *
 * @package GinoPane\AwesomeSocialLinks
 */
class Plugin extends PluginBase
{
    const DEFAULT_ICON = 'icon-users';

    const LOCALIZATION_KEY = 'ginopane.awesomesociallinks::lang.';

    public $require = [
        'GinoPane.AwesomeIconsList'
    ];

    /**
     * Returns information about this plugin
     *
     * @return  array
     */
    public function pluginDetails(): array
    {
        return [
            'name'        => self::LOCALIZATION_KEY . 'plugin.name',
            'description' => self::LOCALIZATION_KEY . 'plugin.description',
            'author'      => 'Siarhei <Gino Pane> Karavai',
            'icon'        => self::DEFAULT_ICON,
            'homepage'    => 'https://github.com/ginopane/oc-awesomesociallinks-plugin'
        ];
    }

    /**
     * Register components
     *
     * @return  array
     */
    public function registerComponents(): array
    {
        return [
            LinkList::class => LinkList::NAME
        ];
    }

    /**
     * Register plugin settings
     *
     * @return array
     */
    public function registerSettings(): array
    {
        return [
            'settings' => [
                'label'       => self::LOCALIZATION_KEY . 'settings.name',
                'description' => self::LOCALIZATION_KEY . 'settings.description',
                'icon'        => self::DEFAULT_ICON,
                'class'       => Settings::class,
                'order'       => 100
            ]
        ];
    }
}
