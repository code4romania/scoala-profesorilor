<?php namespace Genuineq\User\Tests;

use App;
use Artisan;
use Illuminate\Foundation\AliasLoader;
use PluginTestCase as BasePluginTestCase;
use Genuineq\User\Models\Settings;

abstract class PluginTestCase extends BasePluginTestCase
{
    /**
     * @var array   Plugins to refresh between tests.
     */
    protected $refreshPlugins = [
        'Genuineq.User',
    ];

    /**
     * Perform test case set up.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        // reset any modified settings
        Settings::resetDefault();

        // log out after each test
        \Genuineq\User\Classes\AuthManager::instance()->logout();

        // register the auth facade
        $alias = AliasLoader::getInstance();
        $alias->alias('Auth', 'Genuineq\User\Facades\Auth');

        App::singleton('user.auth', function() {
            return \Genuineq\User\Classes\AuthManager::instance();
        });
    }
}
