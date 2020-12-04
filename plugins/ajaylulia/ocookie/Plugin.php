<?php
namespace AjayLulia\OCookie;
use BackendMenu;
use Backend;
use Event;
use AjayLulia\OCookie\Models\Config as Config_Model;
use Cookie;
use Schema;

class Plugin extends \System\Classes\PluginBase
{
    public function pluginDetails()
    {
        return [
            'name' => 'OCookie',
            'description' => 'OCookie for OctoberCMS provides a rich interface to search and
             display dealer/office locations for a company who owns offices in multiple
              locations using Google Maps or Bing Maps.',
            'author' => 'Ajay Lulia'
        ];
    }


    
    
    public function boot(){
        Event::listen('cms.page.beforeRenderPage', function($controller) {
            if(isset($_COOKIE['october-oCookie'])|| !Schema::hasTable('ajaylulia_ocookie_configuration'))
                return;
            $controller->addCss('/plugins/ajaylulia/ocookie/assets/css/ocookie.css');
            $controller->addJs('/plugins/ajaylulia/ocookie/assets/js/ocookie.js');
            $this->renderCookieContent();
        });       

    }

    function renderCookieContent(){
        $config = Config_Model::find(1);
        if($config->display_position=="top")
            $add_class = "cookie_container_top";
        if($config->display_position=="bottom")
            $add_class = "cookie_container_bottom";
        if($config->display_position=="left")
            $add_class = "cookie_container_left";
        if($config->display_position=="right")
            $add_class = "cookie_container_right";
        if($config->display_position=="floating")
            $add_class = "cookie_container_floating";
        if($config->display_position=="rounded")
            $add_class = "cookie_container_rounded";

        // if($config->background_color=="")
        // if($config->text_color=="")
        // if($config->link_color=="top")
        // if($config->button_background_color=="top")
        // if($config->button_text_color=="top")
        echo "<style>
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
        
        echo "<div class='cookie_container $add_class' style='display: none;'>
        <a href='#' onclick='set_oCookie();' class='cookie_btn'>$config->button_text</a>           
        <p class='cookie_message'>$config->cookie_content</p>
     </div>";
    }

    public function registerNavigation()
    {
        return [
            'ocookie' => [
                'label'       => 'OCookie',
                'url'         => Backend::url('ajaylulia/ocookie/config/update/1'),
                'icon'        => 'icon-futbol-o',
                'order'       => 400,
            ]
        ];
    }
}
?>