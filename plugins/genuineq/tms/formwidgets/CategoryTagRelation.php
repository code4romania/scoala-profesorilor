<?php namespace Genuineq\Tms\FormWidgets;

use Backend\Classes\FormWidgetBase;
use Lang;
use Config;
use Genuineq\Tms\Models\Category;


class CategoryTagRelation extends FormWidgetBase
{
    public function widgetDetails()
    {
        return [
            'name' => 'CategoryTagRelation',
            'description' => 'Fiels for adding categories in a tag style'
        ];
    }


    public function render()
    {
        $this->prepareVars();

        return $this->makePartial('categorytagrelation');
    }


    public function prepareVars()
    {
        $this->vars['id']             = $this->model->id;
        $this->vars['fields']         = Category::all()->lists('name', 'id');
        $this->vars['name']           = $this->formField->getName() . '[]';
        $this->vars['selectedValues'] = (!empty($this->getLoadValue())) ? ($this->getLoadValue()) : ([]);
    }


    public function loadAssets()
    {
        $this->addCSs('css/categorytagrelation.css');
        $this->addJs('js/categorytagrelation.js');
    }
}
