<?php namespace Zen\Cleaner\Actions;

use Zen\Cleaner\Classes\Core;

class Clean
{
    public function dirCount()
    {
        $core = new Core;
        $core->dirCount();
    }

    public function run()
    {
        $core = new Core;
        $core->clean();
    }
}