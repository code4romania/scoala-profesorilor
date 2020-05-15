<?php namespace Genuineq\Tms\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Courses extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Backend\Behaviors\ImportExportController',
    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $importExportConfig = 'config_import_export.yaml';


    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Genuineq.Tms', 'main-menu-item', 'side-menu-item');
    }
}
