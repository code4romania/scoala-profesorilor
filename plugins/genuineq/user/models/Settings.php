<?php namespace Genuineq\User\Models;

use Model;

class Settings extends Model
{
    /**
     * @var array Behaviors implemented by this model.
     */
    public $implement = [
        \System\Behaviors\SettingsModel::class
    ];

    public $settingsCode = 'user_settings';
    public $settingsFields = 'fields.yaml';


    const ACTIVATE_AUTO = 'auto';
    const ACTIVATE_USER = 'user';
    const ACTIVATE_ADMIN = 'admin';

    const REMEMBER_ALWAYS = 'always';
    const REMEMBER_NEVER = 'never';
    const REMEMBER_ASK = 'ask';

    public function initSettingsData()
    {
        $this->require_activation = true;
        $this->activate_mode = self::ACTIVATE_ADMIN;
        $this->use_throttle = true;
        $this->block_persistence = false;
        $this->allow_registration = true;
        $this->remember_login = self::REMEMBER_ASK;
        $this->use_register_throttle = true;
    }

    public function getActivateModeOptions()
    {
        return [
            self::ACTIVATE_AUTO => [
                'genuineq.user::lang.settings.activate_mode_auto',
                'genuineq.user::lang.settings.activate_mode_auto_comment'
            ],
            self::ACTIVATE_USER => [
                'genuineq.user::lang.settings.activate_mode_user',
                'genuineq.user::lang.settings.activate_mode_user_comment'
            ],
            self::ACTIVATE_ADMIN => [
                'genuineq.user::lang.settings.activate_mode_admin',
                'genuineq.user::lang.settings.activate_mode_admin_comment'
            ]
        ];
    }

    public function getActivateModeAttribute($value)
    {
        if (!$value) {
            return self::ACTIVATE_ADMIN;
        }

        return $value;
    }

    public function getRememberLoginOptions()
    {
        return [
            self::REMEMBER_ALWAYS => [
                'genuineq.user::lang.settings.remember_always',
            ],
            self::REMEMBER_NEVER => [
                'genuineq.user::lang.settings.remember_never',
            ],
            self::REMEMBER_ASK => [
                'genuineq.user::lang.settings.remember_ask',
            ]
        ];
    }

    public function getRememberLoginAttribute($value)
    {
        if (!$value) {
            return self::REMEMBER_ASK;
        }

        return $value;
    }
}
