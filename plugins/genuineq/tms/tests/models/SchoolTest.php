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
        $school = $this->helper__createSchool(1, $inspectorate_1->id, $address_1->id, /**userId*/1);

        /* Validate created school */
        $this->assertEquals('test-school-name-1', $school->name);
        $this->assertEquals('test-school-slug-1', $school->slug);
        $this->assertEquals('0722222221', $school->phone);
        $this->assertEquals('test-school-principal-1', $school->principal);
        $this->assertEquals($inspectorate_1->id, $school->inspectorate_id);
        $this->assertEquals($address_1->id, $school->address_id);
        $this->assertEquals('test-school-description-1', $school->description);
        $this->assertEquals(/**userId*/1, $school->user_id);
        $this->assertEquals('test-school-contact-name-1', $school->contact_name);
        $this->assertEquals('contact@email.com-1', $school->contact_email);
        $this->assertEquals('0733333331', $school->contact_phone);
        $this->assertEquals('test-school-role-1', $school->contact_role);
        $this->assertEquals(1, $school->status);
    }


    public function test__updateSchool()
    {
        /** Create a inspectorate */
        $inspectorate_1 = $this->helper__createInspectorate(2);
        /** Create a address */
        $address_1 = $this->helper__createAdress(2);

        /** Create new school */
        $school = $this->helper__createSchool(2, $inspectorate_1->id, $address_1->id, /**userId*/1);

        /* Validate created school */
        $this->assertEquals('test-school-name-2', $school->name);
        $this->assertEquals('test-school-slug-2', $school->slug);
        $this->assertEquals('0722222222', $school->phone);
        $this->assertEquals('test-school-principal-2', $school->principal);
        $this->assertEquals($inspectorate_1->id, $school->inspectorate_id);
        $this->assertEquals($address_1->id, $school->address_id);
        $this->assertEquals('test-school-description-2', $school->description);
        $this->assertEquals(/**userId*/1, $school->user_id);
        $this->assertEquals('test-school-contact-name-2', $school->contact_name);
        $this->assertEquals('contact@email.com-2', $school->contact_email);
        $this->assertEquals('0733333332', $school->contact_phone);
        $this->assertEquals('test-school-role-2', $school->contact_role);
        $this->assertEquals(1, $school->status);

        /** Create a inspectorate */
        $inspectorate_2 = $this->helper__createInspectorate(3);
        /** Create a address */
        $address_2 = $this->helper__createAdress(3);

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
        $inspectorate_1 = $this->helper__createInspectorate(4);
        /** Create a address */
        $address_1 = $this->helper__createAdress(4);

        /** Create new school */
        $school = $this->helper__createSchool(4, $inspectorate_1->id, $address_1->id, /**userId*/1);

        /* Validate created school */
        $this->assertEquals('test-school-name-4', $school->name);
        $this->assertEquals('test-school-slug-4', $school->slug);
        $this->assertEquals('0722222224', $school->phone);
        $this->assertEquals('test-school-principal-4', $school->principal);
        $this->assertEquals($inspectorate_1->id, $school->inspectorate_id);
        $this->assertEquals($address_1->id, $school->address_id);
        $this->assertEquals('test-school-description-4', $school->description);
        $this->assertEquals(/**userId*/1, $school->user_id);
        $this->assertEquals('test-school-contact-name-4', $school->contact_name);
        $this->assertEquals('contact@email.com-4', $school->contact_email);
        $this->assertEquals('0733333334', $school->contact_phone);
        $this->assertEquals('test-school-role-4', $school->contact_role);
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
        $inspectorate_1 = $this->helper__createInspectorate(5);
        /** Create a address */
        $address_1 = $this->helper__createAdress(5);

        /** Create new school */
        $school = $this->helper__createSchool(5, $inspectorate_1->id, $address_1->id, /**userId*/1);

        /* Validate created school */
        $this->assertEquals('test-school-name-5', $school->name);
        $this->assertEquals('test-school-slug-5', $school->slug);
        $this->assertEquals('0722222225', $school->phone);
        $this->assertEquals('test-school-principal-5', $school->principal);
        $this->assertEquals($inspectorate_1->id, $school->inspectorate_id);
        $this->assertEquals($address_1->id, $school->address_id);
        $this->assertEquals('test-school-description-5', $school->description);
        $this->assertEquals(/**userId*/1, $school->user_id);
        $this->assertEquals('test-school-contact-name-5', $school->contact_name);
        $this->assertEquals('contact@email.com-5', $school->contact_email);
        $this->assertEquals('0733333335', $school->contact_phone);
        $this->assertEquals('test-school-role-5', $school->contact_role);
        $this->assertEquals(1, $school->status);

        /** Validate address name */
        $this->assertEquals(($address_1->name . ', ' . $address_1->county), $school->address_name);
    }


    public function test__inspectorateName()
    {
        /** Create a inspectorate */
        $inspectorate_1 = $this->helper__createInspectorate(6);
        /** Create a address */
        $address_1 = $this->helper__createAdress(6);

        /** Create new school */
        $school = $this->helper__createSchool(6, $inspectorate_1->id, $address_1->id, /**userId*/1);

        /* Validate created school */
        $this->assertEquals('test-school-name-6', $school->name);
        $this->assertEquals('test-school-slug-6', $school->slug);
        $this->assertEquals('0722222226', $school->phone);
        $this->assertEquals('test-school-principal-6', $school->principal);
        $this->assertEquals($inspectorate_1->id, $school->inspectorate_id);
        $this->assertEquals($address_1->id, $school->address_id);
        $this->assertEquals('test-school-description-6', $school->description);
        $this->assertEquals(/**userId*/1, $school->user_id);
        $this->assertEquals('test-school-contact-name-6', $school->contact_name);
        $this->assertEquals('contact@email.com-6', $school->contact_email);
        $this->assertEquals('0733333336', $school->contact_phone);
        $this->assertEquals('test-school-role-6', $school->contact_role);
        $this->assertEquals(1, $school->status);

        /** Validate date format */
        $this->assertEquals($inspectorate_1->name, $school->inspectorate_name);
    }


    /***********************************************
     ************** Helper Functions ***************
     ***********************************************/

    /**
     * Created an school object and return's it
     */
    protected function helper__createSchool($index, $inspectorateId, $addressId, $userId)
    {
        /** Create new school */
        $school = School::create([
            'name' => 'test-school-name-' . $index,
            'slug' => 'test-school-slug-' . $index,
            'phone' => '072222222' . $index,
            'principal' => 'test-school-principal-' . $index,
            'inspectorate_id' => $inspectorateId,
            'address_id' => $addressId,
            'description' => 'test-school-description-' . $index,
            'user_id' => $userId,
            'contact_name' => 'test-school-contact-name-' . $index,
            'contact_email' => 'contact@email.com-' . $index,
            'contact_phone' => '073333333' . $index,
            'contact_role' => 'test-school-role-' . $index,
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
            'name' => 'test-school-inspectorate-name-' . $index,
            'diacritic' => 'test-school-inspectorate-diacritic-name-' . $index,
            'description' => 'test-school-inspectorate-description-' . $index,
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
            'name' => 'test-school-address-name-'  .$index,
            'diacritic' => 'test-school-address-diacritic-name-' . $index,
            'county' => 'test-school-address-county-name-' . $index,
            'auto' => 'X' . $index,
            'zip' => '0000' . $index,
            'population' => (777 + $index),
            'latitude' => (44.44 + $index),
            'longitude' => (33.33 + $index),
        ]);

        return $address;
    }
}
