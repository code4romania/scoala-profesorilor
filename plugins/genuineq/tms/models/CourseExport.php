<?php namespace Genuineq\Tms\Models;

use Lang;
use Genuineq\Tms\Models\Course;
use Genuineq\Tms\Models\Supplier;

class CourseExport extends \Backend\Models\ExportModel
{
    public function exportData($columns, $sessionKey = null)
    {
        $courses = Course::all();
        $courses->each(function($course) use ($columns) {
            $course->addVisible($columns);
        });

        return $courses->toArray();
    }
}
