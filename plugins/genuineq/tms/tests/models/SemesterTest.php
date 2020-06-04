<?php namespace Genuineq\Tms\Tests\Models;

use PluginTestCase;
use Genuineq\Tms\Models\Semester;
use System\Classes\PluginManager;

class SemesterTest extends PluginTestCase
{
    public function test__createSemester()
    {
        /** Create new semester */
        $semester = $this->helper__createSemester();

        /* Validate created semester */
        $this->assertEquals(1, $semester->semester);
        $this->assertEquals(2020, $semester->year);
    }

    public function test__updateSemester()
    {
        /** Create new semester */
        $semester = $this->helper__createSemester();

        /* Validate created semester */
        $this->assertEquals(1, $semester->semester);
        $this->assertEquals(2020, $semester->year);

        /** Update semester */
        $semester->semester = 2;
        $semester->year = 2021;
        $semester->save();

        /** Check semester new values */
        $this->assertEquals(2, $semester->semester);
        $this->assertEquals(2021, $semester->year);
    }

    public function test__deleteSemester()
    {
        /** Create new semester */
        $semester = $this->helper__createSemester();

        /* Validate created semester */
        $this->assertEquals(1, $semester->semester);
        $this->assertEquals(2020, $semester->year);

        /** Save the ID */
        $semesterId = $semester->id;

        /** Delete semester. */
        $semester->delete();

        /** Search for deleted semester */
        $_semester = Semester::where('id', $semesterId)->first();

        $this->assertEquals(null, $_semester);
    }


    /***********************************************
     ************** Helper Functions ***************
     ***********************************************/

    /**
     * Created an semester object and return's it
     */
    protected function helper__createSemester()
    {
        /** Create new semester */
        $semester = Semester::create([
            'semester' => 1,
            'year' => 2020,
        ]);

        return $semester;
    }
}
