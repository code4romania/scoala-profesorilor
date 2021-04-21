<?php
namespace Genuineq\Cookie;
use BackendMenu;
use Backend;
use Event;
use Genuineq\Cookie\Models\Config as Config_Model;
use Cookie;
use Schema;

class Plugin extends \System\Classes\PluginBase
{
    public function pluginDetails()
    {
        return [
            'name' => 'Cookie Popup',
            'description' => 'Cookie Popup allows a user to configure how the cookie-acceptance popup will be displayed and what information contains.',
            'author' => 'Genuineq'
        ];
    }

    public function boot(){
        Event::listen('cms.page.beforeRenderPage', function($controller) {
            /** Check if cookied have already been accepted or plugin is not installed. */
            if(isset($_COOKIE['accept-cookies']) || !Schema::hasTable('genuineq_cookie_configuration')) {
                return;
            } else {
                /** Add frontend dependencies. */
                $controller->addCss('/plugins/genuineq/cookie/assets/css/cookie.css');
                $controller->addJs('/plugins/genuineq/cookie/assets/js/cookie.js');

                $this->renderCookieContent();
            }
        });
    }

    function renderCookieContent(){
        $config = Config_Model::all()->first();

        if ("top" == $config->display_position) {
            $add_class = "cookie_container_top";
        } elseif ("bottom" == $config->display_position) {
            $add_class = "cookie_container_bottom";
        } elseif ("left" == $config->display_position) {
            $add_class = "cookie_container_left";
        } elseif ("right" == $config->display_position) {
            $add_class = "cookie_container_right";
        } elseif ("floating" == $config->display_position) {
            $add_class = "cookie_container_floating";
        } elseif ("rounded" == $config->display_position) {
            $add_class = "cookie_container_rounded";
        }

        echo
            "<style>
                .cookie_container {
                    background: $config->background_color;
                    color: $config->text_color;
                }
                .cookie_container a{
                    color: $config->link_color;
                }

                .cookie_container .cookie_btn{
                    background:$config->button_background_color;
                    color: $config->button_text_color;
                }
            </style>";

        echo
            "<div class='cookie_container $add_class' style='display: none;'>
                <button type='button' onclick='set_cookie();' class='cookie_btn'>$config->button_text</button>
                <p class='cookie_message'>$config->cookie_content</p>
            </div>";
    }

    public function registerNavigation()
    {
        return [
            'cookie' => [
                'label' => 'Cookie Popup',
                'url' => Backend::url('genuineq/cookie/config/update/1'),
                'icon' => 'icon-check-square-o',
                'order' => 400,
            ]
        ];
    }
}
?>
