<?php namespace Genuineq\Tms\Tests\Models;

use PluginTestCase;
use Genuineq\Tms\Models\SchoolLevel;
use System\Classes\PluginManager;

class SchoolLevelTest extends PluginTestCase
{
    public function test__createSchoolLevel()
    {
        /** Create new schoolLevel */
        $schoolLevel = $this->helper__createSchoolLevel();

        /* Validate created schoolLevel */
        $this->assertEquals('test-schoolLevel-name', $schoolLevel->name);
        $this->assertEquals('test-schoolLevel-diacritic-name', $schoolLevel->diacritic);
        $this->assertEquals('test-schoolLevel-description', $schoolLevel->description);
    }

    public function test__updateSchoolLevel()
    {
        /** Create new schoolLevel */
        $schoolLevel = $this->helper__createSchoolLevel();

        /* Validate created schoolLevel */
        $this->assertEquals('test-schoolLevel-name', $schoolLevel->name);
        $this->assertEquals('test-schoolLevel-diacritic-name', $schoolLevel->diacritic);
        $this->assertEquals('test-schoolLevel-description', $schoolLevel->description);

        /** Update schoolLevel */
        $schoolLevel->name = 'new-test-schoolLevel-name';
        $schoolLevel->diacritic = 'new-test-schoolLevel-diacritic-name';
        $schoolLevel->description = 'new-test-schoolLevel-description';
        $schoolLevel->save();

        /** Check schoolLevel new values */
        $this->assertEquals('new-test-schoolLevel-name', $schoolLevel->name);
        $this->assertEquals('new-test-schoolLevel-diacritic-name', $schoolLevel->diacritic);
        $this->assertEquals('new-test-schoolLevel-description', $schoolLevel->description);
    }

    public function test__deleteSchoolLevel()
    {
        /** Create new schoolLevel */
        $schoolLevel = $this->helper__createSchoolLevel();

        /* Validate created schoolLevel */
        $this->assertEquals('test-schoolLevel-name', $schoolLevel->name);
        $this->assertEquals('test-schoolLevel-diacritic-name', $schoolLevel->diacritic);
        $this->assertEquals('test-schoolLevel-description', $schoolLevel->description);

        /** Save the ID */
        $schoolLevelId = $schoolLevel->id;

        /** Delete schoolLevel. */
        $schoolLevel->delete();

        /** Search for deleted schoolLevel */
        $_schoolLevel = SchoolLevel::where('id', $schoolLevelId)->first();

        $this->assertEquals(null, $_schoolLevel);
    }


    /***********************************************
     ************** Helper Functions ***************
     ***********************************************/

    /**
     * Created an schoolLevel object and return's it
     */
    protected function helper__createSchoolLevel()
    {
        /** Create new schoolLevel */
        $schoolLevel = SchoolLevel::create([
            'name' => 'test-schoolLevel-name',
            'diacritic' => 'test-schoolLevel-diacritic-name',
            'description' => 'test-schoolLevel-description',
        ]);

        return $schoolLevel;
    }
}
