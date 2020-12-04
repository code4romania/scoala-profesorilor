<?php namespace Multiwebinc\Recaptcha\Models;

use Model;

class Settings extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];

    public $settingsCode = 'multiwebinc_recaptcha_settings';

    public $settingsFields = 'fields.yaml';
}