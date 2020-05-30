<?php namespace Genuineq\Tms\ReportWidgets;

use Lang;
use Genuineq\Tms\Models\Supplier;
use Backend\Classes\ReportWidgetBase;

class TotalSuppliers extends ReportWidgetBase
{
    /**
     * Renders the widget.
     */
    public function render()
    {
        try {
            $this->vars['labelSuppliers'] = Lang::get('genuineq.tms::lang.reportwidgets.totalsuppliers.frontend.label_suppliers');
            $this->vars['totalSuppliers'] = Supplier::all()->count();
        } catch (Exception $ex) {
            $this->vars['error'] = $ex->getMessage();
        }

        return $this->makePartial('widget');
    }

    public function defineProperties()
    {
        return [
            'title' => [
                'title' => Lang::get('genuineq.tms::lang.reportwidgets.totalsuppliers.title'),
                'default' => Lang::get('genuineq.tms::lang.reportwidgets.totalsuppliers.title_default'),
                'type' => 'string',
                'validationPattern' => '^.+$',
                'validationMessage' => Lang::get('genuineq.tms::lang.reportwidgets.totalsuppliers.title_validation'),
            ]
        ];
    }
}
