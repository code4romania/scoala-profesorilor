<?php namespace Genuineq\Tms\Tests\Models;

use PluginTestCase;
use Genuineq\Tms\Models\Specialization;
use System\Classes\PluginManager;

class SpecializationTest extends PluginTestCase
{
    public function test__createSpecialization()
    {
        /** Create new specialization */
        $specialization = $this->helper__createSpecialization();

        /* Validate created specialization */
        $this->assertEquals('test-specialization-name', $specialization->name);
        $this->assertEquals('test-specialization-diacritic-name', $specialization->diacritic);
        $this->assertEquals('test-specialization-description', $specialization->description);
    }

    public function test__updateSpecialization()
    {
        /** Create new specialization */
        $specialization = $this->helper__createSpecialization();

        /* Validate created specialization */
        $this->assertEquals('test-specialization-name', $specialization->name);
        $this->assertEquals('test-specialization-diacritic-name', $specialization->diacritic);
        $this->assertEquals('test-specialization-description', $specialization->description);

        /** Update specialization */
        $specialization->name = 'new-test-specialization-name';
        $specialization->diacritic = 'new-test-specialization-diacritic-name';
        $specialization->description = 'new-test-specialization-description';
        $specialization->save();

        /** Check specialization new values */
        $this->assertEquals('new-test-specialization-name', $specialization->name);
        $this->assertEquals('new-test-specialization-diacritic-name', $specialization->diacritic);
        $this->assertEquals('new-test-specialization-description', $specialization->description);
    }

    public function test__deleteSpecialization()
    {
        /** Create new specialization */
        $specialization = $this->helper__createSpecialization();

        /* Validate created specialization */
        $this->assertEquals('test-specialization-name', $specialization->name);
        $this->assertEquals('test-specialization-diacritic-name', $specialization->diacritic);
        $this->assertEquals('test-specialization-description', $specialization->description);

        /** Save the ID */
        $specializationId = $specialization->id;

        /** Delete specialization. */
        $specialization->delete();

        /** Search for deleted specialization */
        $_specialization = Specialization::where('id', $specializationId)->first();

        $this->assertEquals(null, $_specialization);
    }


    /***********************************************
     ************** Helper Functions ***************
     ***********************************************/

    /**
     * Created an specialization object and return's it
     */
    protected function helper__createSpecialization()
    {
        /** Create new specialization */
        $specialization = Specialization::create([
            'name' => 'test-specialization-name',
            'diacritic' => 'test-specialization-diacritic-name',
            'description' => 'test-specialization-description',
        ]);

        return $specialization;
    }
}
