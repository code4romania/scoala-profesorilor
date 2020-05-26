<?php namespace Genuineq\Tms\Components;

use Log;
use Lang;
use Auth;
use Flash;
use Request;
use Redirect;
use ApplicationException;
use Cms\Classes\ComponentBase;
use Genuineq\Tms\Models\Teacher;
use Genuineq\Tms\Models\Appraisal as AppraisalModule;
use Genuineq\User\Helpers\AuthRedirect;

/**
 * Appraisal component
 *
 * Allows to view and update an appraisal.
 */
class Appraisal extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'genuineq.tms::lang.component.appraisal.name',
            'description' => 'genuineq.tms::lang.component.appraisal.description'
        ];
    }

    public function defineProperties()
    {
    }

    /**
     * Executed when this component is initialized
     */
    public function prepareVars()
    {

    }

    /**
     * Executed when this component is bound to a page or layout.
     */
    public function onRun()
    {
        $this->prepareVars();
    }

    /***********************************************
     **************** AJAX handlers ****************
     ***********************************************/

    /***********************************************
     ******************* Common *******************
     ***********************************************/

    /**
     * Searches, filters, orders and paginates appraisals
     *  based on the post options.
     */
    public function onAppraisalSearch()
    {
        /* Extract the appraisals based on the received options. */
        $this->extractAppraisals(/*options*/post(), post('teacherId'));
    }

    /***********************************************
     ****************** Teacher ********************
     ***********************************************/

    /**
     * Extracts all the appraisals of specified teacher.
     */
    public function onTeacherAppraisalsView()
    {
        if (!Auth::check()) {
            return Redirect::to($this->pageUrl(AuthRedirect::loginRequired()));
        }

        /** Extracts all the appraisals of specified teacher. */
        $this->extractAppraisals(/*options*/[], Auth::getUser()->profile->id);

        /** Extracts all the appraisals statics of specified teacher. */
        $this->extractSearchStatics(Auth::getUser()->profile->id);
    }

    /**
     * Function that extracts a specific appraisal.
     */
    public function onTeacherViewGetAppraisalDetails()
    {
        if (!Auth::check()) {
            return Redirect::to($this->pageUrl(AuthRedirect::loginRequired()));
        }

        if (Auth::getUser()->profile->id != post('teacherId')) {
            throw new ApplicationException(Lang::get('genuineq.tms::lang.component.appraisal.message.not_allowed'));
        }

        /** Extract the requested appraisal */
        $appraisal = AppraisalModule::where('id', post('appraisalId'))->where('teacher_id', post('teacherId'))->first();

        if (!$appraisal) {
            throw new ApplicationException(Lang::get('genuineq.tms::lang.component.appraisal.message.not_exists'));
        }

        /** Extract the appraisal. */
        $this->page['appraisal'] = $appraisal;
        /** Extract the teacher. */
        $this->page['teacher'] = Teacher::find(post('teacherId'));
    }

    /**
     * Updates the objectives in a specified appraisal.
     */
    public function onTeacherSaveAppraisalObjectives()
    {
        if (!Auth::check()) {
            return Redirect::to($this->pageUrl(AuthRedirect::loginRequired()));
        }

        if (Auth::getUser()->profile->id != post('teacherId')) {
            throw new ApplicationException(Lang::get('genuineq.tms::lang.component.appraisal.message.not_allowed'));
        }

        /** Extract the requested appraisal */
        $appraisal = AppraisalModule::where('id', post('appraisalId'))->where('teacher_id', post('teacherId'))->first();

        if (!$appraisal) {
            throw new ApplicationException(Lang::get('genuineq.tms::lang.component.appraisal.message.not_exists'));
        }

        /** Save updated objectives. */
        $appraisal->objectives = post('objectives');
        $appraisal->save();

        Flash::success(Lang::get('genuineq.tms::lang.component.appraisal.message.save_successfull'));

        /** Extract the appraisal. */
        $this->page['appraisal'] = $appraisal;
        /** Extract the teacher. */
        $this->page['teacher'] = Teacher::find(post('teacherId'));

        /** Extract the received courses filtering options */
        $options = [
            'searchInput' => post('appraisalSearchInput'),
            'school' => post('appraisalSchools'),
            'sort' => post('appraisalSort'),
            'status' => post('appraisalStatus'),
            'year' => post('appraisalYear'),
            'semester' => post('appraisalSemester'),
            'page' => post('newPage'),
        ];
        /** Extracts all the appraisals of specified teacher. */
        $this->extractAppraisals($options, Auth::getUser()->profile->id);
    }

    /**
     * Updates the objectives in a specified appraisal
     *  and moves it to the 'objectives-set' state.
     */
    public function onTeacherSetAppraisalObjectives()
    {
        if (!Auth::check()) {
            return Redirect::to($this->pageUrl(AuthRedirect::loginRequired()));
        }

        if (Auth::getUser()->profile->id != post('teacherId')) {
            throw new ApplicationException(Lang::get('genuineq.tms::lang.component.appraisal.message.not_allowed'));
        }

        /** Extract the requested appraisal */
        $appraisal = AppraisalModule::where('id', post('appraisalId'))->where('teacher_id', post('teacherId'))->first();

        if (!$appraisal) {
            throw new ApplicationException(Lang::get('genuineq.tms::lang.component.appraisal.message.not_exists'));
        }

        /** Save updated objectives. */
        $appraisal->objectives = post('objectives');
        $appraisal->status = 'objectives-set';
        $appraisal->save();

        Flash::success(Lang::get('genuineq.tms::lang.component.appraisal.message.set_successfull'));

        /** Extract the received courses filtering options */
        $options = [
            'searchInput' => post('appraisalSearchInput'),
            'school' => post('appraisalSchools'),
            'sort' => post('appraisalSort'),
            'status' => post('appraisalStatus'),
            'year' => post('appraisalYear'),
            'semester' => post('appraisalSemester'),
            'page' => post('newPage'),
        ];

        /** Extracts all the appraisals of specified teacher. */
        $this->extractAppraisals($options, Auth::getUser()->profile->id);
    }

    /***********************************************
     ******************* Helpers *******************
     ***********************************************/

    /**
     * Extract the requested appraisals.
     */
    protected function extractAppraisals($options, $teacherId)
    {
        /** Extract the teacher. */
        $this->page['teacher'] = Teacher::find($teacherId);
        /** Extract all appraisals for filtering. */
        $this->page['appraisals'] = AppraisalModule::filterAppraisals($options);
        /** Extract the number of pages. */
        $this->page['appraisalsPages'] = $this->page['appraisals']->lastPage();
        /** Extract the current page. */
        $this->page['appraisalsPage'] = $this->page['appraisals']->currentPage();
    }

    /**
     * Checks if the force secure property is enabled and if so
     * returns a redirect object.
     * @return mixed
     */
    protected function extractSearchStatics($teacherId)
    {
        /** Extract all statuses for filtering. */
        $this->page['appraisalStatuses'] = AppraisalModule::getFilterStatuses();
        /** Extract all years for filtering. */
        $this->page['appraisalYears'] = AppraisalModule::getFilterYears($teacherId);
        /** Extract all semesters for filtering. */
        $this->page['appraisalSemesters'] = AppraisalModule::getFilterSemesters($teacherId);
        /** Extract all sort types for filtering. */
        $this->page['appraisalSortTypes'] = AppraisalModule::getSortingTypes();
        /** Extract schools for filtering. */
        $this->page['appraisalSchools'] = AppraisalModule::getFilterSchools($teacherId);
    }
}
