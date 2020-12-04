<?php
Route::match(['get', 'post'], '/zen/cleaner/{action}', function ($action) {
    if(!\BackendAuth::check())
        return 'Access error';
    return App::call('Zen\Cleaner\Actions\\'.$action);
})->middleware('web');
