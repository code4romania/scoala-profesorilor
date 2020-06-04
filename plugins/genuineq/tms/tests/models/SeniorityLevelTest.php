<?php namespace Genuineq\Tms\Tests\Models;

use PluginTestCase;
use Genuineq\Tms\Models\SeniorityLevel;
use System\Classes\PluginManager;

class SeniorityLevelTest extends PluginTestCase
{
    public function test__createSeniorityLevel()
    {
        /** Create new seniorityLevel */
        $seniorityLevel = $this->helper__createSeniorityLevel();

        /* Validate created seniorityLevel */
        $this->assertEquals('test-seniorityLevel-name', $seniorityLevel->name);
        $this->assertEquals('test-seniorityLevel-diacritic-name', $seniorityLevel->diacritic);
        $this->assertEquals('test-seniorityLevel-description', $seniorityLevel->description);
    }

    public function test__updateSeniorityLevel()
    {
        /** Create new seniorityLevel */
        $seniorityLevel = $this->helper__createSeniorityLevel();

        /* Validate created seniorityLevel */
        $this->assertEquals('test-seniorityLevel-name', $seniorityLevel->name);
        $this->assertEquals('test-seniorityLevel-diacritic-name', $seniorityLevel->diacritic);
        $this->assertEquals('test-seniorityLevel-description', $seniorityLevel->description);

        /** Update seniorityLevel */
        $seniorityLevel->name = 'new-test-seniorityLevel-name';
        $seniorityLevel->diacritic = 'new-test-seniorityLevel-diacritic-name';
        $seniorityLevel->description = 'new-test-seniorityLevel-description';
        $seniorityLevel->save();

        /** Check seniorityLevel new values */
        $this->assertEquals('new-test-seniorityLevel-name', $seniorityLevel->name);
        $this->assertEquals('new-test-seniorityLevel-diacritic-name', $seniorityLevel->diacritic);
        $this->assertEquals('new-test-seniorityLevel-description', $seniorityLevel->description);
    }

    public function test__deleteSeniorityLevel()
    {
        /** Create new seniorityLevel */
        $seniorityLevel = $this->helper__createSeniorityLevel();

        /* Validate created seniorityLevel */
        $this->assertEquals('test-seniorityLevel-name', $seniorityLevel->name);
        $this->assertEquals('test-seniorityLevel-diacritic-name', $seniorityLevel->diacritic);
        $this->assertEquals('test-seniorityLevel-description', $seniorityLevel->description);

        /** Save the ID */
        $seniorityLevelId = $seniorityLevel->id;

        /** Delete seniorityLevel. */
        $seniorityLevel->delete();

        /** Search for deleted seniorityLevel */
        $_seniorityLevel = SeniorityLevel::where('id', $seniorityLevelId)->first();

        $this->assertEquals(null, $_seniorityLevel);
    }


    /***********************************************
     ************** Helper Functions ***************
     ***********************************************/

    /**
     * Created an seniorityLevel object and return's it
     */
    protected function helper__createSeniorityLevel()
    {
        /** Create new seniorityLevel */
        $seniorityLevel = SeniorityLevel::create([
            'name' => 'test-seniorityLevel-name',
            'diacritic' => 'test-seniorityLevel-diacritic-name',
            'description' => 'test-seniorityLevel-description',
        ]);

        return $seniorityLevel;
    }
}
