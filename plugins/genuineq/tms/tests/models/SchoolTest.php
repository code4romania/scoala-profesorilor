<?php namespace Genuineq\Tms\Tests\Models;

use PluginTestCase;
use Genuineq\Tms\Models\School;
use Genuineq\Tms\Models\Address;
use Genuineq\Tms\Models\Inspectorate;
use System\Classes\PluginManager;

class SchoolTest extends PluginTestCase
{
    public function test__createSchool()
    {
        /** Create a inspectorate */
        $inspectorate_1 = $this->helper__createInspectorate(1);
        /** Create a address */
        $address_1 = $this->helper__createAdress(1);

        /** Create new school */
        $school = $this->helper__createSchool($inspectorate_1->id, $address_1->id, /**userId*/1);

        /* Validate created school */
        $this->assertEquals('test-school-name', $school->name);
        $this->assertEquals('test-school-slug', $school->slug);
        $this->assertEquals('0722222222', $school->phone);
        $this->assertEquals('test-school-principal', $school->principal);
        $this->assertEquals($inspectorate_1->id, $school->inspectorate_id);
        $this->assertEquals($address_1->id, $school->address_id);
        $this->assertEquals('test-school-description', $school->description);
        $this->assertEquals(/**userId*/1, $school->user_id);
        $this->assertEquals('test-school-contact-name', $school->contact_name);
        $this->assertEquals('contact@email.com', $school->contact_email);
        $this->assertEquals('0733333333', $school->contact_phone);
        $this->assertEquals('test-school-role', $school->contact_role);
        $this->assertEquals(1, $school->status);
    }


    public function test__updateSchool()
    {
        /** Create a inspectorate */
        $inspectorate_1 = $this->helper__createInspectorate(1);
        /** Create a address */
        $address_1 = $this->helper__createAdress(1);

        /** Create new school */
        $school = $this->helper__createSchool($inspectorate_1->id, $address_1->id, /**userId*/1);

        /* Validate created school */
        $this->assertEquals('test-school-name', $school->name);
        $this->assertEquals('test-school-slug', $school->slug);
        $this->assertEquals('0722222222', $school->phone);
        $this->assertEquals('test-school-principal', $school->principal);
        $this->assertEquals($inspectorate_1->id, $school->inspectorate_id);
        $this->assertEquals($address_1->id, $school->address_id);
        $this->assertEquals('test-school-description', $school->description);
        $this->assertEquals(/**userId*/1, $school->user_id);
        $this->assertEquals('test-school-contact-name', $school->contact_name);
        $this->assertEquals('contact@email.com', $school->contact_email);
        $this->assertEquals('0733333333', $school->contact_phone);
        $this->assertEquals('test-school-role', $school->contact_role);
        $this->assertEquals(1, $school->status);

        /** Create a inspectorate */
        $inspectorate_2 = $this->helper__createInspectorate(1);
        /** Create a address */
        $address_2 = $this->helper__createAdress(1);

        /** Update school */
        $school->name = 'new-test-school-name';
        $school->slug = 'new-test-school-slug';
        $school->phone = '0722222223';
        $school->principal = 'new-test-school-principal';
        $school->inspectorate_id = $inspectorate_2->id;
        $school->address_id = $address_2->id;
        $school->description = 'new-test-school-description';
        $school->user_id = /**userId*/2;
        $school->contact_name = 'new-test-school-contact-name';
        $school->contact_email = 'new-contact@email.com';
        $school->contact_phone = '0733333334';
        $school->contact_role = 'new-test-school-role';
        $school->status = 0;
        $school->save();

        /** Check school new values */
        $this->assertEquals('new-test-school-name', $school->name);
        $this->assertEquals('new-test-school-slug', $school->slug);
        $this->assertEquals('0722222223', $school->phone);
        $this->assertEquals('new-test-school-principal', $school->principal);
        $this->assertEquals($inspectorate_2->id, $school->inspectorate_id);
        $this->assertEquals($address_2->id, $school->address_id);
        $this->assertEquals('new-test-school-description', $school->description);
        $this->assertEquals(/**userId*/2, $school->user_id);
        $this->assertEquals('new-test-school-contact-name', $school->contact_name);
        $this->assertEquals('new-contact@email.com', $school->contact_email);
        $this->assertEquals('0733333334', $school->contact_phone);
        $this->assertEquals('new-test-school-role', $school->contact_role);
        $this->assertEquals(0, $school->status);
    }


    public function test__deleteSchool()
    {
        /** Create a inspectorate */
        $inspectorate_1 = $this->helper__createInspectorate(1);
        /** Create a address */
        $address_1 = $this->helper__createAdress(1);

        /** Create new school */
        $school = $this->helper__createSchool($inspectorate_1->id, $address_1->id, /**userId*/1);

        /* Validate created school */
        $this->assertEquals('test-school-name', $school->name);
        $this->assertEquals('test-school-slug', $school->slug);
        $this->assertEquals('0722222222', $school->phone);
        $this->assertEquals('test-school-principal', $school->principal);
        $this->assertEquals($inspectorate_1->id, $school->inspectorate_id);
        $this->assertEquals($address_1->id, $school->address_id);
        $this->assertEquals('test-school-description', $school->description);
        $this->assertEquals(/**userId*/1, $school->user_id);
        $this->assertEquals('test-school-contact-name', $school->contact_name);
        $this->assertEquals('contact@email.com', $school->contact_email);
        $this->assertEquals('0733333333', $school->contact_phone);
        $this->assertEquals('test-school-role', $school->contact_role);
        $this->assertEquals(1, $school->status);

        /** Save the ID */
        $schoolId = $school->id;

        /** Delete school. */
        $school->delete();

        /** Search for deleted school */
        $_school = School::where('id', $schoolId)->first();

        $this->assertEquals(null, $_school);
    }


    public function test__addressName()
    {
        /** Create a inspectorate */
        $inspectorate_1 = $this->helper__createInspectorate(1);
        /** Create a address */
        $address_1 = $this->helper__createAdress(1);

        /** Create new school */
        $school = $this->helper__createSchool($inspectorate_1->id, $address_1->id, /**userId*/1);

        /* Validate created school */
        $this->assertEquals('test-school-name', $school->name);
        $this->assertEquals('test-school-slug', $school->slug);
        $this->assertEquals('0722222222', $school->phone);
        $this->assertEquals('test-school-principal', $school->principal);
        $this->assertEquals($inspectorate_1->id, $school->inspectorate_id);
        $this->assertEquals($address_1->id, $school->address_id);
        $this->assertEquals('test-school-description', $school->description);
        $this->assertEquals(/**userId*/1, $school->user_id);
        $this->assertEquals('test-school-contact-name', $school->contact_name);
        $this->assertEquals('contact@email.com', $school->contact_email);
        $this->assertEquals('0733333333', $school->contact_phone);
        $this->assertEquals('test-school-role', $school->contact_role);
        $this->assertEquals(1, $school->status);

        /** Validate address name */
        $this->assertEquals('test-address-name-1, test-address-county-name-1', $school->address_name);
    }


    public function test__inspectorateName()
    {
        /** Create a inspectorate */
        $inspectorate_1 = $this->helper__createInspectorate(1);
        /** Create a address */
        $address_1 = $this->helper__createAdress(1);

        /** Create new school */
        $school = $this->helper__createSchool($inspectorate_1->id, $address_1->id, /**userId*/1);

        /* Validate created school */
        $this->assertEquals('test-school-name', $school->name);
        $this->assertEquals('test-school-slug', $school->slug);
        $this->assertEquals('0722222222', $school->phone);
        $this->assertEquals('test-school-principal', $school->principal);
        $this->assertEquals($inspectorate_1->id, $school->inspectorate_id);
        $this->assertEquals($address_1->id, $school->address_id);
        $this->assertEquals('test-school-description', $school->description);
        $this->assertEquals(/**userId*/1, $school->user_id);
        $this->assertEquals('test-school-contact-name', $school->contact_name);
        $this->assertEquals('contact@email.com', $school->contact_email);
        $this->assertEquals('0733333333', $school->contact_phone);
        $this->assertEquals('test-school-role', $school->contact_role);
        $this->assertEquals(1, $school->status);

        /** Validate date format */
        $this->assertEquals('test-inspectorate-name-1', $school->inspectorate_name);
    }


    /***********************************************
     ************** Helper Functions ***************
     ***********************************************/

    /**
     * Created an school object and return's it
     */
    protected function helper__createSchool($inspectorateId, $addressId, $userId)
    {
        /** Create new school */
        $school = School::create([
            'name' => 'test-school-name',
            'slug' => 'test-school-slug',
            'phone' => '0722222222',
            'principal' => 'test-school-principal',
            'inspectorate_id' => $inspectorateId,
            'address_id' => $addressId,
            'description' => 'test-school-description',
            'user_id' => $userId,
            'contact_name' => 'test-school-contact-name',
            'contact_email' => 'contact@email.com',
            'contact_phone' => '0733333333',
            'contact_role' => 'test-school-role',
            'status'=> 1,
        ]);

        return $school;
    }

    /**
     * Created an inspectorate object and return's it
     */
    protected function helper__createInspectorate($index)
    {
        /** Create new inspectorate */
        $inspectorate = Inspectorate::create([
            'name' => 'test-inspectorate-name-' . $index,
            'diacritic' => 'test-inspectorate-diacritic-name-' . $index,
            'description' => 'test-inspectorate-description-' . $index,
        ]);

        return $inspectorate;
    }

    /**
     * Created an address object and return's it
     */
    protected function helper__createAdress($index)
    {
        /** Create new address */
        $address = Address::create([
            'name' => 'test-address-name-'  .$index,
            'diacritic' => 'test-address-diacritic-name-' . $index,
            'county' => 'test-address-county-name-' . $index,
            'auto' => 'X' . $index,
            'zip' => '0000' . $index,
            'population' => (777 + $index),
            'latitude' => (44.44 + $index),
            'longitude' => (33.33 + $index),
        ]);

        return $address;
    }
}
