<?php namespace Genuineq\Tms\Tests\Models;

use PluginTestCase;
use Genuineq\Tms\Models\ContractType;
use System\Classes\PluginManager;

class ContractTypeTest extends PluginTestCase
{
    public function test__createContractType()
    {
        /** Create new contractType */
        $contractType = $this->helper__createContractType();

        /* Validate created contractType */
        $this->assertEquals('test-contractType-name', $contractType->name);
        $this->assertEquals('test-contractType-diacritic-name', $contractType->diacritic);
        $this->assertEquals('test-contractType-description', $contractType->description);
    }

    public function test__updateContractType()
    {
        /** Create new contractType */
        $contractType = $this->helper__createContractType();

        /* Validate created contractType */
        $this->assertEquals('test-contractType-name', $contractType->name);
        $this->assertEquals('test-contractType-diacritic-name', $contractType->diacritic);
        $this->assertEquals('test-contractType-description', $contractType->description);

        /** Update contractType */
        $contractType->name = 'new-test-contractType-name';
        $contractType->diacritic = 'new-test-contractType-diacritic-name';
        $contractType->description = 'new-test-contractType-description';
        $contractType->save();

        /** Check contractType new values */
        $this->assertEquals('new-test-contractType-name', $contractType->name);
        $this->assertEquals('new-test-contractType-diacritic-name', $contractType->diacritic);
        $this->assertEquals('new-test-contractType-description', $contractType->description);
    }

    public function test__deleteContractType()
    {
        /** Create new contractType */
        $contractType = $this->helper__createContractType();

        /* Validate created contractType */
        $this->assertEquals('test-contractType-name', $contractType->name);
        $this->assertEquals('test-contractType-diacritic-name', $contractType->diacritic);
        $this->assertEquals('test-contractType-description', $contractType->description);

        /** Save the ID */
        $contractTypeId = $contractType->id;

        /** Delete contractType. */
        $contractType->delete();

        /** Search for deleted contractType */
        $_contractType = ContractType::where('id', $contractTypeId)->first();

        $this->assertEquals(null, $_contractType);
    }


    /***********************************************
     ************** Helper Functions ***************
     ***********************************************/

    /**
     * Created an contractType object and return's it
     */
    protected function helper__createContractType()
    {
        /** Create new contractType */
        $contractType = ContractType::create([
            'name' => 'test-contractType-name',
            'diacritic' => 'test-contractType-diacritic-name',
            'description' => 'test-contractType-description',
        ]);

        return $contractType;
    }
}
