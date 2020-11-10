<?php namespace Genuineq\User;

use App;
use Auth;
use Event;
use Backend;
use System\Classes\PluginBase;
use System\Classes\SettingsManager;
use Illuminate\Foundation\AliasLoader;
use Genuineq\User\Classes\UserRedirector;
use Genuineq\User\Models\MailBlocker;
use Genuineq\User\Models\User;
use RainLab\Notify\Classes\Notifier;
use RainLab\Notify\NotifyRules\SaveDatabaseAction;

class Plugin extends PluginBase
{
    /**
     * @var boolean Determine if this plugin should have elevated privileges.
     */
    public $elevated = true;

    public function pluginDetails()
    {
        return [
            'name'        => 'genuineq.user::lang.plugin.name',
            'description' => 'genuineq.user::lang.plugin.description',
            'author'      => 'genuineq',
            'icon'        => 'icon-user',
            'homepage'    => 'https://www.genuineq.com'
        ];
    }

    public function register()
    {
        $alias = AliasLoader::getInstance();
        $alias->alias('Auth', 'Genuineq\User\Facades\Auth');

        App::singleton('user.auth', function() {
            return \Genuineq\User\Classes\AuthManager::instance();
        });

        App::singleton('redirect', function ($app) {
            // overrides with our own extended version of Redirector to support
            // seperate url.intended session variable for frontend
            $redirector = new UserRedirector($app['url']);

            // If the session is set on the application instance, we'll inject it into
            // the redirector instance. This allows the redirect responses to allow
            // for the quite convenient "with" methods that flash to the session.
            if (isset($app['session.store'])) {
                $redirector->setSession($app['session.store']);
            }

            return $redirector;
        });

        /** Apply user-based mail blocking */
        Event::listen('mailer.prepareSend', function($mailer, $view, $message) {
            return MailBlocker::filterMessage($view, $message);
        });

        /** Compatability with Rainlab.Notify */
        $this->bindNotificationEvents();
        $this->extendSaveDatabaseAction();
    }

    public function registerComponents()
    {
        return [
            \Genuineq\User\Components\Account::class       => 'account',
            \Genuineq\User\Components\ResetPassword::class => 'resetPassword',
            \Genuineq\User\Components\Session::class       => 'session',
            \Genuineq\User\Components\Login::class         => 'login',
            \Genuineq\User\Components\Register::class      => 'register',
            \Genuineq\User\Components\Notifications::class => 'notifications',
        ];
    }

    public function registerPageSnippets()
    {
        return [
            \Genuineq\User\Components\Account::class       => 'account',
            \Genuineq\User\Components\ResetPassword::class => 'resetPassword',
            \Genuineq\User\Components\Session::class       => 'session',
            \Genuineq\User\Components\Login::class         => 'login',
            \Genuineq\User\Components\Register::class      => 'register',
            \Genuineq\User\Components\Notifications::class => 'notifications',
        ];
    }

    public function registerReportWidgets()
    {
        return [
            'Genuineq\User\ReportWidgets\UsersLoginLogging' => [
                'label'   => 'genuineq.user::lang.reportwidgets.usersloginlogging.label',
                'context' => 'dashboard',
            ]
        ];
    }

    public function registerPermissions()
    {
        return [
            'genuineq.users.access_users' => [
                'tab'   => 'genuineq.user::lang.plugin.tab',
                'label' => 'genuineq.user::lang.plugin.access_users'
            ],
            'genuineq.users.access_groups' => [
                'tab'   => 'genuineq.user::lang.plugin.tab',
                'label' => 'genuineq.user::lang.plugin.access_groups'
            ],
            'genuineq.users.access_settings' => [
                'tab'   => 'genuineq.user::lang.plugin.tab',
                'label' => 'genuineq.user::lang.plugin.access_settings'
            ],
            'genuineq.users.impersonate_user' => [
                'tab'   => 'genuineq.user::lang.plugin.tab',
                'label' => 'genuineq.user::lang.plugin.impersonate_user'
            ],
        ];
    }

    public function registerNavigation()
    {
        return [
            'user' => [
                'label'       => 'genuineq.user::lang.users.menu_label',
                'url'         => Backend::url('genuineq/user/users'),
                'icon'        => 'icon-user',
                'iconSvg'     => 'plugins/genuineq/user/assets/images/user-icon.svg',
                'permissions' => ['genuineq.users.*'],
                'order'       => 500,

                'sideMenu' => [
                    'users' => [
                        'label' => 'genuineq.user::lang.users.menu_label',
                        'icon'        => 'icon-user',
                        'url'         => Backend::url('genuineq/user/users'),
                        'permissions' => ['genuineq.users.access_users']
                    ],
                    'usergroups' => [
                        'label'       => 'genuineq.user::lang.groups.menu_label',
                        'icon'        => 'icon-users',
                        'url'         => Backend::url('genuineq/user/usergroups'),
                        'permissions' => ['genuineq.users.access_groups']
                    ]
                ]
            ]
        ];
    }

    public function registerSettings()
    {
        return [
            'settings' => [
                'label'       => 'genuineq.user::lang.settings.menu_label',
                'description' => 'genuineq.user::lang.settings.menu_description',
                'category'    => SettingsManager::CATEGORY_USERS,
                'icon'        => 'icon-cog',
                'class'       => 'Genuineq\User\Models\Settings',
                'order'       => 500,
                'permissions' => ['genuineq.users.access_settings']
            ]
        ];
    }

    public function registerMailTemplates()
    {
        return [
            'genuineq.user::mail.activate',
            'genuineq.user::mail.welcome',
            'genuineq.user::mail.restore',
            'genuineq.user::mail.new_user',
            'genuineq.user::mail.reactivate',
            'genuineq.user::mail.invite',
        ];
    }

    public function registerNotificationRules()
    {
        return [
            'events' => [
                \Genuineq\User\NotifyRules\UserActivatedEvent::class,
                \Genuineq\User\NotifyRules\UserRegisteredEvent::class,
            ],
            'actions' => [],
            'conditions' => [
                \Genuineq\User\NotifyRules\UserAttributeCondition::class
            ],
            'groups' => [
                'user' => [
                    'label' => 'User',
                    'icon' => 'icon-user'
                ],
            ],
        ];
    }

    protected function bindNotificationEvents()
    {
        if (!class_exists(Notifier::class)) {
            return;
        }

        Notifier::bindEvents([
            'genuineq.user.activate' => \Genuineq\User\NotifyRules\UserActivatedEvent::class,
            'genuineq.user.register' => \Genuineq\User\NotifyRules\UserRegisteredEvent::class
        ]);

        Notifier::instance()->registerCallback(function($manager) {
            $manager->registerGlobalParams([
                'user' => Auth::getUser()
            ]);
        });
    }

    protected function extendSaveDatabaseAction()
    {
        if (!class_exists(SaveDatabaseAction::class)) {
            return;
        }

        SaveDatabaseAction::extend(function ($action) {
            $action->addTableDefinition([
                'label' => 'User notifications',
                'class' => User::class,
                'relation' => 'notifications',
                'param' => 'user'
            ]);
        });
    }
}
