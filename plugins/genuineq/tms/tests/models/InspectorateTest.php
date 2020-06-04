<?php namespace Genuineq\Tms\Tests\Models;

use PluginTestCase;
use Genuineq\Tms\Models\Inspectorate;
use System\Classes\PluginManager;

class InspectorateTest extends PluginTestCase
{
    public function test__createInspectorate()
    {
        /** Create new inspectorate */
        $inspectorate = $this->helper__createInspectorate();

        /* Validate created inspectorate */
        $this->assertEquals('test-inspectorate-name', $inspectorate->name);
        $this->assertEquals('test-inspectorate-diacritic-name', $inspectorate->diacritic);
        $this->assertEquals('test-inspectorate-description', $inspectorate->description);
    }

    public function test__updateInspectorate()
    {
        /** Create new inspectorate */
        $inspectorate = $this->helper__createInspectorate();

        /* Validate created inspectorate */
        $this->assertEquals('test-inspectorate-name', $inspectorate->name);
        $this->assertEquals('test-inspectorate-diacritic-name', $inspectorate->diacritic);
        $this->assertEquals('test-inspectorate-description', $inspectorate->description);

        /** Update inspectorate */
        $inspectorate->name = 'new-test-inspectorate-name';
        $inspectorate->diacritic = 'new-test-inspectorate-diacritic-name';
        $inspectorate->description = 'new-test-inspectorate-description';
        $inspectorate->save();

        /** Check inspectorate new values */
        $this->assertEquals('new-test-inspectorate-name', $inspectorate->name);
        $this->assertEquals('new-test-inspectorate-diacritic-name', $inspectorate->diacritic);
        $this->assertEquals('new-test-inspectorate-description', $inspectorate->description);
    }

    public function test__deleteInspectorate()
    {
        /** Create new inspectorate */
        $inspectorate = $this->helper__createInspectorate();

        /* Validate created inspectorate */
        $this->assertEquals('test-inspectorate-name', $inspectorate->name);
        $this->assertEquals('test-inspectorate-diacritic-name', $inspectorate->diacritic);
        $this->assertEquals('test-inspectorate-description', $inspectorate->description);

        /** Save the ID */
        $inspectorateId = $inspectorate->id;

        /** Delete inspectorate. */
        $inspectorate->delete();

        /** Search for deleted inspectorate */
        $_inspectorate = Inspectorate::where('id', $inspectorateId)->first();

        $this->assertEquals(null, $_inspectorate);
    }


    /***********************************************
     ************** Helper Functions ***************
     ***********************************************/

    /**
     * Created an inspectorate object and return's it
     */
    protected function helper__createInspectorate()
    {
        /** Create new inspectorate */
        $inspectorate = Inspectorate::create([
            'name' => 'test-inspectorate-name',
            'diacritic' => 'test-inspectorate-diacritic-name',
            'description' => 'test-inspectorate-description',
        ]);

        return $inspectorate;
    }
}
