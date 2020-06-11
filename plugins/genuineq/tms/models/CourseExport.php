<?php namespace Genuineq\Tms\Models;

use Log;
use Lang;
use Genuineq\Tms\Models\Course;
use Genuineq\Tms\Models\Supplier;

class CourseExport extends \Backend\Models\ExportModel
{
    public function exportData($columns, $sessionKey = null)
    {
        $courses = [];
        foreach (Course::all() as $courseKey => $course) {
            foreach ($columns as $key => $column) {
                $courses[$courseKey][$column] = $course->{$column};
            }
        }

        return $courses;
    }
}
