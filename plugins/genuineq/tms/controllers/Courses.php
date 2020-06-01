<?php namespace Genuineq\Tms\Controllers;

use Log;
use Lang;
use Flash;
use BackendMenu;
use ApplicationException;
use Backend\Classes\Controller;
use Genuineq\Tms\Models\Course;

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

    /**
     * Bulk delete records.
     * @return void
     */
    public function index_onDelete()
    {
        if (($checkedIds = post('checked')) && is_array($checkedIds) && count($checkedIds)) {

            foreach ($checkedIds as $courseId) {
                $course = Course::find($courseId);

                if ($course) {
                    /** Archive the post. */
                    $course->status = 0;
                    $course->save();
                }
            }

            Flash::success(Lang::get('backend::lang.list.delete_selected_success'));
        }

        return $this->listRefresh();
    }
}
