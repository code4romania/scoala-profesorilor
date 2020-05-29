<?php namespace Genuineq\User\Models;

use Str;
use Auth;
use Mail;
use Event;
use Carbon\Carbon;
use October\Rain\Auth\Models\User as UserBase;
use October\Rain\Auth\AuthException;
use Genuineq\User\Models\Settings as UserSettings;
use Genuineq\User\Helpers\PluginConfig;

class User extends UserBase
{
    use \October\Rain\Database\Traits\SoftDelete;

    /**
     * @var string The database table used by the model.
     */
    protected $table = 'users';

    /**
     * Validation rules
     */
    public $rules = [
    ];

    /**
     * @var array Relations
     */
    public $belongsToMany = [
        'groups' => [UserGroup::class, 'table' => 'users_groups']
    ];

    public $attachOne = [
        'avatar' => \System\Models\File::class
    ];

    /**
     * @var array The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'surname',
        'login',
        'username',
        'email',
        'password',
        'identifier',
        'type',
        'password_confirmation',
        'created_ip_address',
        'last_ip_address'
    ];

    /**
     * Purge attributes from data set.
     */
    protected $purgeable = ['password_confirmation', 'send_invite'];

    protected $dates = [
        'last_seen',
        'deleted_at',
        'created_at',
        'updated_at',
        'activated_at',
        'last_login'
    ];

    /**
     * Notification relation.
     */
    public $morphMany = [
        'notifications' => [
            'RainLab\Notify\Models\Notification',
            'name' => 'notifiable',
            'order' => 'created_at desc'
        ]
    ];

    public static $loginAttribute = 'email';

    /**
     * Method that returns the user type options.
     * @return array
     */
    public function getTypeOptions($value, $formData)
    {
        return PluginConfig::getUserTypeOptions();
    }

    /**
     * Get an activation code for the given user.
     * @return string
     */
    public function getActivationCode()
    {
        if (!$this->activation_code) {
            return parent::getActivationCode();
        }

        return $this->activation_code;
    }

    /**
     * Sends the confirmation email to a user, after activating.
     * @param  string $code
     * @return bool
     */
    public function attemptActivation($code)
    {
        if ($this->trashed()) {
            if ($code === $this->activation_code) {
                $this->restore();
            } else {
                return false;
            }
        } else {
            $result = parent::attemptActivation($code);

            if ($result === false) {
                return false;
            }
        }

        Event::fire('genuineq.user.activate', [$this]);

        return true;
    }

    /***********************************************
     ***************** Constructors ****************
     ***********************************************/

    /**
     * Looks up a user by their email address.
     * @return self
     */
    public static function findByEmail($email)
    {
        if (!$email) {
            return;
        }

        return self::where('email', $email)->first();
    }

    /***********************************************
     ******************* Getters *******************
     ***********************************************/

    /**
     * Gets a code for when the user is persisted to a cookie or session which identifies the user.
     * @return string
     */
    public function getPersistCode()
    {
        $block = UserSettings::get('block_persistence', false);

        if ($block || !$this->persist_code) {
            return parent::getPersistCode();
        }

        return $this->persist_code;
    }

    /**
     * Returns the public image file path to this user's avatar.
     * * @return mix
     */
    public function getAvatarThumb($size = 25, $options = null)
    {
        if (is_string($options)) {
            $options = ['default' => $options];
        }
        elseif (!is_array($options)) {
            $options = [];
        }

        $default = array_get($options, 'default', 'mm');

        if ($this->avatar) {
            return $this->avatar->getThumb($size, $size, $options);
        }
        else {
            return null;
        }
    }

    /**
     * Returns the profile page name
     * @return String
     */
    public function getProfilePage()
    {
        return PluginConfig::getProfilePages()[$this->type];
    }

    /**
     * Returns the minimum length for a new password from settings.
     * @return int
     */
    public static function getMinPasswordLength()
    {
        return PluginConfig::getMinPasswordLength();
    }

    /***********************************************
     ******************** Scopes *******************
     ***********************************************/

    public function scopeIsActivated($query)
    {
        return $query->where('is_activated', 1);
    }

    public function scopeFilterByGroup($query, $filter)
    {
        return $query->whereHas('groups', function($group) use ($filter) {
            $group->whereIn('id', $filter);
        });
    }

    /***********************************************
     ******************** Events *******************
     ***********************************************/

    /**
     * Before validation event
     * @return void
     */
    // public function beforeValidate()
    // {
    //     /*
    //      * Guests are special
    //      */
    //     if ($this->is_guest && !$this->password) {
    //         $this->generatePassword();
    //     }

    //     /*
    //      * When the username is not used, the email is substituted.
    //      */
    //     if (
    //         (!$this->username) ||
    //         ($this->isDirty('email') && $this->getOriginal('email') == $this->username)
    //     ) {
    //         $this->username = $this->email;
    //     }

    //     /*
    //      * Apply Password Length Settings
    //      */
    //     $minPasswordLength = static::getMinPasswordLength();
    //     $this->rules['password'] = "required:create|between:$minPasswordLength,255|confirmed";
    //     $this->rules['password_confirmation'] = "required_with:password|between:$minPasswordLength,255";
    // }

    /**
     * After create event
     * @return void
     */
    public function afterCreate()
    {
        $this->restorePurgedValues();

        if ($this->send_invite) {
            $this->sendInvitation();
        }

        /** Fire global user creation event, */
        Event::fire('genuineq.user.created', [$this]);
    }

    /**
     * Before login event
     * @return void
     */
    public function beforeLogin()
    {
        if ($this->is_guest) {
            $login = $this->getLogin();
            throw new AuthException(sprintf(
                'Cannot login user "%s" as they are not registered.', $login
            ));
        }

        parent::beforeLogin();
    }

    /**
     * After login event
     * @return void
     */
    public function afterLogin()
    {
        $this->last_login = $this->freshTimestamp();

        if ($this->trashed()) {
            $this->restore();

            Mail::sendTo($this, 'genuineq.user::mail.reactivate', [
                'name' => $this->name
            ]);

            Event::fire('genuineq.user.reactivate', [$this]);
        }
        else {
            parent::afterLogin();
        }

        Event::fire('genuineq.user.login', [$this]);
    }

    /**
     * After delete event
     * @return void
     */
    public function afterDelete()
    {
        if ($this->isSoftDelete()) {
            Event::fire('genuineq.user.deactivate', [$this]);
            return;
        }

        $this->avatar && $this->avatar->delete();

        parent::afterDelete();
    }

    /***********************************************
     ******************* Banning *******************
     ***********************************************/

    /**
     * Ban this user, preventing them from signing in.
     * @return void
     */
    public function ban()
    {
        Auth::findThrottleByUserId($this->id)->ban();
    }

    /**
     * Remove the ban on this user.
     * @return void
     */
    public function unban()
    {
        Auth::findThrottleByUserId($this->id)->unban();
    }

    /**
     * Check if the user is banned.
     * @return bool
     */
    public function isBanned()
    {
        $throttle = Auth::createThrottleModel()->where('user_id', $this->id)->first();
        return $throttle ? $throttle->is_banned : false;
    }

    /***********************************************
     ****************** Suspending *****************
     ***********************************************/

    /**
     * Check if the user is suspended.
     * @return bool
     */
    public function isSuspended()
    {
        return Auth::findThrottleByUserId($this->id)->checkSuspended();
    }

    /**
     * Remove the suspension on this user.
     * @return void
     */
    public function unsuspend()
    {
        Auth::findThrottleByUserId($this->id)->unsuspend();
    }

    /***********************************************
     ********** IP Recording and Throttle **********
     ***********************************************/

    /**
     * Records the last_ip_address to reflect the last known IP for this user.
     * @param string|null $ipAddress
     * @return void
     */
    public function touchIpAddress($ipAddress)
    {
        $this
            ->newQuery()
            ->where('id', $this->id)
            ->update(['last_ip_address' => $ipAddress])
        ;
    }

    /**
     * Returns true if IP address is throttled and cannot register
     * again. Maximum 3 registrations every 60 minutes.
     * @param string|null $ipAddress
     * @return bool
     */
    public static function isRegisterThrottled($ipAddress)
    {
        if (!$ipAddress) {
            return false;
        }

        $timeLimit = Carbon::now()->subMinutes(60);
        $count = static::make()
            ->where('created_ip_address', $ipAddress)
            ->where('created_at', '>', $timeLimit)
            ->count()
        ;

        return $count > 2;
    }

    /***********************************************
     ****************** Last Seen ******************
     ***********************************************/

    /**
     * Checks if the user has been seen in the last 5 minutes, and if not,
     * updates the last_seen timestamp to reflect their online status.
     * @return void
     */
    public function touchLastSeen()
    {
        if ($this->isOnline()) {
            return;
        }

        $oldTimestamps = $this->timestamps;
        $this->timestamps = false;

        $this
            ->newQuery()
            ->where('id', $this->id)
            ->update(['last_seen' => $this->freshTimestamp()])
        ;

        $this->last_seen = $this->freshTimestamp();
        $this->timestamps = $oldTimestamps;
    }

    /**
     * Returns true if the user has been active within the last 5 minutes.
     * @return bool
     */
    public function isOnline()
    {
        return $this->getLastSeen() > $this->freshTimestamp()->subMinutes(5);
    }

    /**
     * Returns the date this user was last seen.
     * @return Carbon\Carbon
     */
    public function getLastSeen()
    {
        return $this->last_seen ?: $this->created_at;
    }

    /***********************************************
     ******************** Utils ********************
     ***********************************************/

    /**
     * Returns the variables available when sending a user notification.
     * @return array
     */
    public function getNotificationVars()
    {
        $vars = [
            'name'     => $this->name,
            'email'    => $this->email,
            'username' => $this->username,
            'login'    => $this->getLogin(),
            'password' => $this->getOriginalHashValue('password')
        ];

        /*
         * Extensibility
         */
        $result = Event::fire('genuineq.user.getNotificationVars', [$this]);
        if ($result && is_array($result)) {
            $vars = call_user_func_array('array_merge', $result) + $vars;
        }

        return $vars;
    }

    /**
     * Sends an invitation to the user using template "genuineq.user::mail.invite".
     * @return void
     */
    protected function sendInvitation()
    {
        Mail::sendTo($this, 'genuineq.user::mail.invite', $this->getNotificationVars());
    }

    /**
     * Assigns this user with a random password.
     * @return void
     */
    protected function generatePassword()
    {
        $this->password = $this->password_confirmation = Str::random(static::getMinPasswordLength());
    }
}
