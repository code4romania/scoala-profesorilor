<?php

App::before(function($request)
{
    if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == "https") {
        $this->app['url']->forceScheme("https");
    }
});
