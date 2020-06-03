<?php

    namespace Martin\SimpleGoogleAnalytics;

    use System\Classes\PluginBase;
    use System\Classes\SettingsManager;

    class Plugin extends PluginBase {

        public function pluginDetails() {
            return [
                'name'        => 'martin.simplegoogleanalytics::lang.plugin.name',
                'description' => 'martin.simplegoogleanalytics::lang.plugin.description',
                'author'      => 'Martin M.',
                'icon'        => 'icon-bar-chart'
            ];
        }

        public function registerComponents() {
            return [
                'Martin\SimpleGoogleAnalytics\Components\SimpleGoogleAnalytics' => 'simplegoogleanalytics',
            ];
        }

    }

?>