<?php namespace Multiwebinc\Recaptcha\Components;

use Cms\Classes\ComponentBase;
use Multiwebinc\Recaptcha\Models\Settings;
use Session;

class Recaptcha extends ComponentBase
{
    /**
     * Returns details about this component.
     *
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name'        => 'multiwebinc.recaptcha::lang.component.name',
            'description' => 'multiwebinc.recaptcha::lang.component.description'
        ];
    }

    public function onRun() {
        $this->page['site_key'] = Settings::get('site_key');

        // Get language from plugin settings
        if (!empty(Settings::get('lang'))) {
            $this->page['lang'] = Settings::get('lang');
        }
        // Get language from the user's session if the translate plugin is loaded
        elseif (!empty(Session::get('rainlab.translate.locale'))) {
            $this->page['lang'] = Session::get('rainlab.translate.locale');
        }
        // Determine the language automatically
        else {
            $this->page['lang'] = '';
        }
    }

    public function defineProperties() {
        return [
            'type' => [
                'title'             => 'multiwebinc.recaptcha::lang.component.type_title',
                'type'              => 'dropdown',
                'options'           => [
                    'visible'       => 'visible',
                    'invisible'     => 'invisible',
                    'v3'            => 'v3',
                ],
                'description'       => 'multiwebinc.recaptcha::lang.component.type_description',
                'default'           => 'v3',
                'showExternalParam' => false,
                'required'          => true,
            ],
            'load_script' => [
                'title'             => 'multiwebinc.recaptcha::lang.component.load_script_title',
                'description'       => 'multiwebinc.recaptcha::lang.component.load_script_description',
                'type'              => 'checkbox',
                'default'           => true,
                'showExternalParam' => false,
            ],
            'action_name' => [
                'title'             => 'multiwebinc.recaptcha::lang.component.action_name_title',
                'default'           => 'submitForm',
                'type'              => 'string',
                'showExternalParam' => false,
                'required'          => false,
            ],
            'button_text' => [
                'title'             => 'multiwebinc.recaptcha::lang.component.button_text_title',
                'description'       => 'multiwebinc.recaptcha::lang.component.button_text_description',
                'default'           => 'Submit',
                'type'              => 'string',
                'showExternalParam' => false,
                'required'          => true,
            ],
            'button_class' => [
                'title'             => 'multiwebinc.recaptcha::lang.component.button_class_title',
                'description'       => 'multiwebinc.recaptcha::lang.component.button_class_description',
                'type'              => 'string',
                'showExternalParam' => false,
            ],
        ];
        return array_merge(parent::defineProperties(), $local);
    }
}