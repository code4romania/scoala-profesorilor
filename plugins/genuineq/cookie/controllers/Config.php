<?php namespace Genuineq\Cookie\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Input;
use Genuineq\Cookie\Models\Config as Config_Model;
use Cms\Classes\Theme as CmsTheme;

class Config extends Controller
{
    public $implement = [
        'Backend\Behaviors\FormController'
    ];
    public $formConfig = 'config_form.yaml';

    public function __construct()
    {
        parent::__construct();
        $this->addCss('/plugins/genuineq/cookie/assets/css/cookie_admin.css');
        $this->addJs('/plugins/genuineq/cookie/assets/js/attrchange.js');

        $code = CmsTheme::getActiveThemeCode();
        $theme = CmsTheme::load($code);
        $preview = $theme->getPreviewImageUrl();
        $this->vars['preview']=$preview;
    }

    public function index()
    {
        //print_r($this->theme);die;
        // $this->model = new BranchHit_Model();
        // $this->vars['daily_stats']=$this->model->getDailyStats();
        // $this->vars['weekly_stats']=$this->model->getWeeklyStats();
        // $this->vars['monthly_stats']=$this->model->getMonthlyStats();
    }
}
