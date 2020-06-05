<?php

App::before(function($request)
{
    $this->app['url']->forceScheme("https");
});
