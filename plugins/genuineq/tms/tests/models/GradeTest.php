<?php namespace Genuineq\Tms\Tests\Models;

use PluginTestCase;
use Genuineq\Tms\Models\Grade;
use System\Classes\PluginManager;

class GradeTest extends PluginTestCase
{
    public function test__createGrade()
    {
        /** Create new grade */
        $grade = $this->helper__createGrade();

        /* Validate created grade */
        $this->assertEquals('test-grade-name', $grade->name);
        $this->assertEquals('test-grade-diacritic-name', $grade->diacritic);
        $this->assertEquals('test-grade-description', $grade->description);
    }

    public function test__updateGrade()
    {
        /** Create new grade */
        $grade = $this->helper__createGrade();

        /* Validate created grade */
        $this->assertEquals('test-grade-name', $grade->name);
        $this->assertEquals('test-grade-diacritic-name', $grade->diacritic);
        $this->assertEquals('test-grade-description', $grade->description);

        /** Update grade */
        $grade->name = 'new-test-grade-name';
        $grade->diacritic = 'new-test-grade-diacritic-name';
        $grade->description = 'new-test-grade-description';
        $grade->save();

        /** Check grade new values */
        $this->assertEquals('new-test-grade-name', $grade->name);
        $this->assertEquals('new-test-grade-diacritic-name', $grade->diacritic);
        $this->assertEquals('new-test-grade-description', $grade->description);
    }

    public function test__deleteGrade()
    {
        /** Create new grade */
        $grade = $this->helper__createGrade();

        /* Validate created grade */
        $this->assertEquals('test-grade-name', $grade->name);
        $this->assertEquals('test-grade-diacritic-name', $grade->diacritic);
        $this->assertEquals('test-grade-description', $grade->description);

        /** Save the ID */
        $gradeId = $grade->id;

        /** Delete grade. */
        $grade->delete();

        /** Search for deleted grade */
        $_grade = Grade::where('id', $gradeId)->first();

        $this->assertEquals(null, $_grade);
    }


    /***********************************************
     ************** Helper Functions ***************
     ***********************************************/

    /**
     * Created an grade object and return's it
     */
    protected function helper__createGrade()
    {
        /** Create new grade */
        $grade = Grade::create([
            'name' => 'test-grade-name',
            'diacritic' => 'test-grade-diacritic-name',
            'description' => 'test-grade-description',
        ]);

        return $grade;
    }
}
