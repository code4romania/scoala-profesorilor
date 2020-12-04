<?php namespace Genuineq\User\ReportWidgets;

use Backend\Classes\ReportWidgetBase;
use Exception;
use Lang;
use DB;

class UsersLoginLogging extends ReportWidgetBase
{
    /**
     * Renders the widget.
     */
    public function render()
    {
        try {
            $this->vars['labelUsersLoginLogging'] = Lang::get('genuineq.user::lang.reportwidgets.usersloginlogging.frontend.label_log');

            $this->vars['loginRequestsLog'] = DB::select('SELECT * FROM users_login_log');
        }
        catch (Exception $ex) {
            $this->vars['error'] = $ex->getMessage();
        }

        return $this->makePartial('widget');
    }

    /**
     * Define widget properties
     *
     * @return array
     */
	public function defineProperties()
	{
		return [
			'title' => [
				'title' => Lang::get('genuineq.user::lang.reportwidgets.usersloginlogging.title'),
				'default' => Lang::get('genuineq.user::lang.reportwidgets.usersloginlogging.title_default'),
				'type' => 'string',
				'validationPattern' => '^.+$',
				'validationMessage' => Lang::get('genuineq.user::lang.reportwidgets.usersloginlogging.title_validation')
            ],
        ];
    }

    /** Load assets for ajax loader. */
    public function loadAssets()
    {
        $this->addJs('js/datatables.min.js');
        $this->addCss('css/datatables.css');
    }


}
