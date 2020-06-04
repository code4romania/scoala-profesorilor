<?php namespace Genuineq\Tms\Tests\Models;

use PluginTestCase;
use Genuineq\Tms\Models\Address;
use System\Classes\PluginManager;

class AddressTest extends PluginTestCase
{
    public function test__createAddress()
    {
        /** Create new address */
        $address = $this->helper__createAdress();

        /* Validate created address */
        $this->assertEquals('test-address-name', $address->name);
        $this->assertEquals('test-address-diacritic-name', $address->diacritic);
        $this->assertEquals('test-address-county-name', $address->county);
        $this->assertEquals('XX', $address->auto);
        $this->assertEquals('00000', $address->zip);
        $this->assertEquals(777, $address->population);
        $this->assertEquals(44.44, $address->latitude);
        $this->assertEquals(33.33, $address->longitude);
    }

    public function test__updateAddress()
    {
        /** Create new address */
        $address = $this->helper__createAdress();

        /** Check address exists */
        $this->assertEquals('test-address-name', $address->name);
        $this->assertEquals('test-address-diacritic-name', $address->diacritic);
        $this->assertEquals('test-address-county-name', $address->county);
        $this->assertEquals('XX', $address->auto);
        $this->assertEquals('00000', $address->zip);
        $this->assertEquals(777, $address->population);
        $this->assertEquals(44.44, $address->latitude);
        $this->assertEquals(33.33, $address->longitude);

        /** Update address */
        $address->name = 'new-test-address-name';
        $address->diacritic = 'new-test-address-diacritic-name';
        $address->county = 'new-test-address-county-name';
        $address->auto = 'ZZ';
        $address->zip = '11111';
        $address->population = 444;
        $address->latitude = 11.11;
        $address->longitude = 77.77;
        $address->save();

        /** Check address new values */
        $this->assertEquals('new-test-address-name', $address->name);
        $this->assertEquals('new-test-address-diacritic-name', $address->diacritic);
        $this->assertEquals('new-test-address-county-name', $address->county);
        $this->assertEquals('ZZ', $address->auto);
        $this->assertEquals('11111', $address->zip);
        $this->assertEquals(444, $address->population);
        $this->assertEquals(11.11, $address->latitude);
        $this->assertEquals(77.77, $address->longitude);
    }

    public function test__deleteAddress()
    {
        /** Create new address */
        $address = $this->helper__createAdress();

        /* Validate created address */
        $this->assertEquals('test-address-name', $address->name);
        $this->assertEquals('test-address-diacritic-name', $address->diacritic);
        $this->assertEquals('test-address-county-name', $address->county);
        $this->assertEquals('XX', $address->auto);
        $this->assertEquals('00000', $address->zip);
        $this->assertEquals(777, $address->population);
        $this->assertEquals(44.44, $address->latitude);
        $this->assertEquals(33.33, $address->longitude);

        /** Save the ID */
        $addressId = $address->id;

        /** Delete address. */
        $address->delete();

        /** Search for deleted address */
        $_address = Address::where('id', $addressId)->first();

        $this->assertEquals(null, $_address);
    }


    /***********************************************
     ************** Helper Functions ***************
     ***********************************************/

    /**
     * Created an address object and return's it
     */
    protected function helper__createAdress()
    {
        /** Create new address */
        $address = Address::create([
            'name' => 'test-address-name',
            'diacritic' => 'test-address-diacritic-name',
            'county' => 'test-address-county-name',
            'auto' => 'XX',
            'zip' => '00000',
            'population' => 777,
            'latitude' => 44.44,
            'longitude' => 33.33,
        ]);

        return $address;
    }
}
