<?php namespace Zen\Cleaner;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
    }

    public function registerSettings()
    {
        return [
            'options' => [
                'label'       => 'zen.cleaner::lang.plugin.name',
                'description' => 'zen.cleaner::lang.plugin.description',
                'icon'        => 'oc-icon-trash',
                'permissions' => [],
                'class' => 'Zen\Cleaner\Models\Settings',
                'order' => 600,
            ]
        ];
    }
}
