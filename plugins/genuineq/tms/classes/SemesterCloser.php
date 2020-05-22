<?php namespace Genuineq\Tms\Classes;

use Genuineq\User\Models\Semester;

class SemesterCloser
{
    /**
     * Function performs all the operation for closing the first semester.
     */
    public static function closeFirstSemester(array $newData)
    {
        /** Start semester 2 */
        $semester = new Semester();
        $semester = 2;
    }

    /**
     * Function performs all the operation for closing the second semester.
     */
    public static function closeSecondSemester(array $newData)
    {
        /** Start semester 1 */
        $semester = new Semester();
        $semester = 1;
    }
}
