<?php namespace Genuineq\Tms\FormWidgets;

use Backend\Classes\FormWidgetBase;
use Genuineq\Tms\Models\Skill;


class SkillTagRelation extends FormWidgetBase
{
    public function widgetDetails()
    {
        return [
            'name' => 'SkillTagRelation',
            'description' => 'Fiels for adding skills in a tag style'
        ];
    }


    public function render()
    {
        $this->prepareVars();

        return $this->makePartial('skilltagrelation');
    }


    public function prepareVars()
    {
        $this->vars['id']             = $this->model->id;
        $this->vars['fields']         = Skill::all()->lists('name', 'id');
        $this->vars['name']           = $this->formField->getName() . '[]';
        $this->vars['selectedValues'] = (!empty($this->getLoadValue())) ? ($this->getLoadValue()) : ([]);
    }


    public function loadAssets()
    {
        $this->addCSs('css/skilltagrelation.css');
        $this->addJs('js/skilltagrelation.js');
    }
}
