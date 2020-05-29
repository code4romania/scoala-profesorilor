<?php namespace Genuineq\User\Components;

use Log;
use Auth;
use Redirect;
use Carbon\Carbon;
use Cms\Classes\ComponentBase;
use ApplicationException;
use Genuineq\User\Helpers\AuthRedirect;

class Notifications extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'genuineq.user::lang.component.notifications.name',
            'description' => 'genuineq.user::lang.component.notifications.description'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {
        if (Auth::check()) {

            $this->addCss('assets/css/notifications.css');
            $this->addJs('assets/js/notifications.js');

            $this->prepareVars();
        }
    }

    protected function prepareVars()
    {
        $this->page['hasNotifications'] = Auth::getUser()->notifications()->applyUnread()->count();
    }

    /***********************************************
     **************** AJAX handlers ****************
     ***********************************************/

    public function onViewNotifications()
    {
        if (!Auth::check()) {
            return Redirect::to($this->pageUrl(AuthRedirect::loginRequired()));
        }

        $this->prepareVars();
        $this->page['notifications'] = Auth::getUser()->notifications()->applyUnread()->get();
    }

    public function onMarkAllNotificationsAsRead()
    {
        if (!Auth::check()) {
            return Redirect::to($this->pageUrl(AuthRedirect::loginRequired()));
        }

        Auth::getUser()->notifications()->applyUnread()->update(['read_at' => Carbon::now()]);

        $this->page['notifications'] = Auth::getUser()->notifications()->applyUnread()->get();
    }

    public function onMarkNotificationAsRead()
    {
        if (!Auth::check()) {
            return Redirect::to($this->pageUrl(AuthRedirect::loginRequired()));
        }

        Auth::getUser()->notifications()->where('id', post('notificationId'))->update(['read_at' => Carbon::now()]);

        $this->page['notifications'] = Auth::getUser()->notifications()->applyUnread()->get();
    }

    /***********************************************
     ******************* Helpers *******************
     ***********************************************/

    protected function getRecordCountToDisplay()
    {
        return ((int) post('records_per_page')) ?: $this->property('recordsPerPage');
    }
}
