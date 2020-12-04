<?php namespace Walid\DiskUsage\Models;

use Model;

/**
* Settings Model
*/
class Settings extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];

    // A unique code
    public $settingsCode = 'walid_diskusage';

    // Reference to field configuration
    public $settingsFields = 'fields.yaml';
}
