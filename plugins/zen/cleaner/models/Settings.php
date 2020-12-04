<?php namespace Zen\Cleaner\Models;

use Config;
use October\Rain\Database\Model;
use October\Rain\Database\Traits\Validation as ValidationTrait;

class Settings extends Model
{
    use ValidationTrait;

    public $implement = [
        'System.Behaviors.SettingsModel',
    ];

    public $settingsCode = 'zen_cleaner_settings';

    public $settingsFields = 'fields.yaml';

    public $rules = [
    ];
}
