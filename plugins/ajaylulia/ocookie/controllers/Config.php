<?php namespace AjayLulia\OCookie\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Input;
use AjayLulia\OCookie\Models\Config as Config_Model;
use Cms\Classes\Theme as CmsTheme;

class Config extends Controller
{
    public $implement = ['Backend\Behaviors\FormController'];    
    public $formConfig = 'config_form.yaml';
    
    public function __construct()
    {
        parent::__construct();
        $this->addCss('/plugins/ajaylulia/ocookie/assets/css/ocookie_admin.css');
        $this->addJs('/plugins/ajaylulia/ocookie/assets/js/attrchange.js');
        
        $code = CmsTheme::getActiveThemeCode();
        $theme = CmsTheme::load($code);
        $preview = $theme->getPreviewImageUrl();
        $this->vars['preview']=$preview;
    }

    public function index(){
        //print_r($this->theme);die;
        // $this->model = new BranchHit_Model();
        // $this->vars['daily_stats']=$this->model->getDailyStats();
        // $this->vars['weekly_stats']=$this->model->getWeeklyStats();
        // $this->vars['monthly_stats']=$this->model->getMonthlyStats();
    }
}
