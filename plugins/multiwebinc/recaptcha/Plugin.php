<?php namespace Multiwebinc\Recaptcha;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function pluginDetails()
    {
        return [
            'name' => 'multiwebinc.recaptcha::lang.plugin.name',
            'description' => 'multiwebinc.recaptcha::lang.plugin.description',
            'author' => 'Multiwebinc',
            'icon' => 'icon-key'
        ];
    }

    public function registerSettings()
    {
        return [
            'settings' => [
                'label' => 'multiwebinc.recaptcha::lang.settings.label',
                'description' => 'multiwebinc.recaptcha::lang.settings.description',
                'icon' => 'icon-key',
                'class' => 'Multiwebinc\Recaptcha\Models\Settings',
                'permissions' => ['multiwebinc.recaptcha.access_settings'],
            ]
        ];
    }

    public function registerComponents()
    {
        return [
            'Multiwebinc\Recaptcha\Components\Recaptcha' => 'recaptcha',
        ];
    }

    public function registerPermissions()
    {
        return [
            'multiwebinc.recaptcha.access_settings' => ['tab' => 'Captcha', 'label' => 'Access Settings']
        ];
    }
}
