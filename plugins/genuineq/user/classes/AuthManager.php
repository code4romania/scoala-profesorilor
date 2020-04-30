<?php namespace Genuineq\User\Classes;

use October\Rain\Auth\Manager as RainAuthManager;
use Genuineq\User\Models\Settings as UserSettings;
use Genuineq\User\Models\UserGroup as UserGroupModel;
use Log;

class AuthManager extends RainAuthManager
{
    protected static $instance;

    protected $sessionKey = 'user_auth';

    protected $userModel = 'Genuineq\User\Models\User';

    protected $groupModel = 'Genuineq\User\Models\UserGroup';

    protected $throttleModel = 'Genuineq\User\Models\Throttle';

    public function init()
    {
        $this->useThrottle = UserSettings::get('use_throttle', $this->useThrottle);
        $this->requireActivation = false;
        parent::init();
    }

    /**
     * {@inheritDoc}
     */
    public function extendUserQuery($query)
    {
        $query->withTrashed();
    }

    /**
     * {@inheritDoc}
     */
    public function register(array $credentials, $activate = false, $autoLogin = true)
    {
        return parent::register($credentials, $activate, $autoLogin);
    }

    /**
     * {@inheritDoc}
     */
    public function findUserByCredentials(array $credentials)
    {
        $model = $this->createUserModel();
        $loginName = $model->getLoginName();

        if (!array_key_exists($loginName, $credentials)) {
            return null;
        }

        $query = $this->createUserModelQuery();
        $hashableAttributes = $model->getHashableAttributes();
        $hashedCredentials = [];

        /** Build query from given credentials */
        foreach ($credentials as $credential => $value) {
            // All excepted the hashed attributes
            if (in_array($credential, $hashableAttributes)) {
                $hashedCredentials = array_merge($hashedCredentials, [$credential => $value]);
            }
            else {
                $query = $query->where($credential, '=', $value);
            }
        }

        $user = $query->first();
        if (!$this->validateUserModel($user)) {
            return null;
        }

        /** Check the hashed credentials match */
        foreach ($hashedCredentials as $credential => $value) {
            if (!$user->checkHashValue($credential, $value)) {
                return null;
            }
        }

        return $user;
    }

    /**
     * {@inheritDoc}
     */
    public function findThrottleByLogin($loginName, $ipAddress)
    {
        $user = $this->findUserByLogin($loginName);
        if (!$user) {
            return null;
        }

        $userId = $user->getKey();
        return $this->findThrottleByUserId($userId, $ipAddress);
    }

    /**
     * {@inheritDoc}
     */
    protected function validateInternal(array $credentials = [])
    {
        /** Default to the login name field or fallback to a hard-coded 'login' value */
        $loginName = $this->createUserModel()->getLoginName();
        $loginCredentialKey = isset($credentials[$loginName]) ? $loginName : 'login';

        if (empty($credentials[$loginCredentialKey])) {
            return null;
        }

        if (empty($credentials['password'])) {
            return null;
        }

        /** If the fallback 'login' was provided and did not match the necessary login name, swap it over */
        if ($loginCredentialKey !== $loginName) {
            $credentials[$loginName] = $credentials[$loginCredentialKey];
            unset($credentials[$loginCredentialKey]);
        }

        /** If throttling is enabled, check they are not locked out first and foremost. */
        if ($this->useThrottle) {
            $throttle = $this->findThrottleByLogin($credentials[$loginName], $this->ipAddress);
            if ($throttle) {
                $throttle->check();
            } else {
                return null;
            }
        }

        /** Look up the user by authentication credentials. */
        $user = $this->findUserByCredentials($credentials);

        if ($this->useThrottle) {
            $throttle->clearLoginAttempts();
        }

        return $user;
    }

    /**
     * Attempts to authenticate the given user according to the passed credentials.
     *
     * @param array $credentials The user login details
     * @param bool $remember Store a non-expire cookie for the user
     */
    public function authenticate(array $credentials, $remember = true)
    {
        $user = $this->validateInternal($credentials);

        if ($user) {
            $user->clearResetPassword();

            $this->login($user, $remember);

            return $this->user;
        }

        return null;
    }

    //
    // Guest users
    //

    public function findGuestUserByCredentials(array $credentials)
    {
        if ($email = array_get($credentials, 'email')) {
            return $this->findGuestUser($email);
        }

        return null;
    }

    public function findGuestUser($email)
    {
        $query = $this->createUserModelQuery();

        return $user = $query
            ->where('email', $email)
            ->where('is_guest', 1)
            ->first();
    }

    /**
     * Registers a guest user by giving the required credentials.
     *
     * @param array $credentials
     * @return Models\User
     */
    public function registerGuest(array $credentials)
    {
        $user = $this->findGuestUserByCredentials($credentials);
        $newUser = false;

        if (!$user) {
            $user = $this->createUserModel();
            $newUser = true;
        }

        $user->fill($credentials);
        $user->is_guest = true;
        $user->save();

        // Add user to guest group
        if ($newUser && $group = UserGroupModel::getGuestGroup()) {
            $user->groups()->add($group);
        }

        // Prevents revalidation of the password field
        // on subsequent saves to this model object
        $user->password = null;

        return $this->user = $user;
    }

    /**
     * Converts a guest user to a registered user.
     *
     * @param Models\User $user
     * @param array $credentials
     * @param bool $activate
     * @return Models\User
     */
    public function convertGuestToUser($user, $credentials, $activate = false)
    {
        $user->fill($credentials);
        $user->convertToRegistered(false);

        // Remove user from guest group
        if ($group = UserGroupModel::getGuestGroup()) {
            $user->groups()->remove($group);
        }

        if ($activate) {
            $user->attemptActivation($user->getActivationCode());
        }

        // Prevents revalidation of the password field
        // on subsequent saves to this model object
        $user->password = null;

        return $this->user = $user;
    }
}
