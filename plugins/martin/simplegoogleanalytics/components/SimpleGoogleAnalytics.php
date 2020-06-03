<?php

    namespace Martin\SimpleGoogleAnalytics\Components;

    use Event, Lang;
    use Cms\Classes\ComponentBase;
    use Cms\Classes\Page;

    class SimpleGoogleAnalytics extends ComponentBase {

        public $extendedTrackingCode;

        public function componentDetails() {
            return [
                'name'        => 'martin.simplegoogleanalytics::lang.components.simplegoogleanalytics.name',
                'description' => 'martin.simplegoogleanalytics::lang.components.simplegoogleanalytics.description'
            ];
        }

        public function onRender() {
            $trackingCodeExtensions = Event::fire('googleanalytics.extend_tracking_code');
            $this->extendedTrackingCode = implode("\n", $trackingCodeExtensions);
        }

        public function defineProperties() {
            $properties = [
                'tracking_id' => [
                    'title'             => Lang::get('martin.simplegoogleanalytics::lang.components.simplegoogleanalytics.track_id'),
                    'description'       => Lang::get('martin.simplegoogleanalytics::lang.components.simplegoogleanalytics.track_id_desc'),
                    'default'           => '',
                    'type'              => 'string',
                    'required'          => true,
                    'placeholder'       => 'UA-XXXXXXX-Y',
                    'showExternalParam' => false
                ],
                'domain' => [
                    'title'             => Lang::get('martin.simplegoogleanalytics::lang.components.simplegoogleanalytics.domain'),
                    'description'       => Lang::get('martin.simplegoogleanalytics::lang.components.simplegoogleanalytics.domain_desc'),
                    'default'           => 'auto',
                    'type'              => 'string',
                    'required'          => true,
                    'showExternalParam' => false
                ],
                'production' => [
                    'title'             => Lang::get('martin.simplegoogleanalytics::lang.components.simplegoogleanalytics.production'),
                    'description'       => Lang::get('martin.simplegoogleanalytics::lang.components.simplegoogleanalytics.production_desc'),
                    'default'           => false,
                    'type'              => 'checkbox',
                  //'required'          => false,
                    'showExternalParam' => false
                ],
                'anonymize_ip' => [
                    'title'             => Lang::get('martin.simplegoogleanalytics::lang.components.simplegoogleanalytics.anonymize_ip_title'),
                    'description'       => Lang::get('martin.simplegoogleanalytics::lang.components.simplegoogleanalytics.anonymize_ip_desc'),
                    'default'           => false,
                    'type'              => 'checkbox',
                  //'required'          => false,
                    'showExternalParam' => false
                ]
            ];
            return $properties;
        }

    }

?>