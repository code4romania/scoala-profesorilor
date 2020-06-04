<?php namespace Genuineq\Tms\Tests\Models;

use PluginTestCase;
use Genuineq\Tms\Models\Teacher;
use Genuineq\Tms\Models\Address;
use Genuineq\Tms\Models\SeniorityLevel;
use System\Classes\PluginManager;

class TeacherTest extends PluginTestCase
{
    public function test__createTeacher()
    {
        /** Create address */
        $address_1 = $this->helper__createAdress(1);
        /** Create seniority level */
        $seniorityLevel_1 = $this->helper__createSeniorityLevel(1);
        /** Create new teacher */
        $teacher = $this->helper__createTeacher(1, $address_1->id, $seniorityLevel_1->id, /*userId*/1);

        /* Validate created teacher */
        $this->assertEquals('test-teacher-name-1', $teacher->name);
        $this->assertEquals('test-teacher-slug-1', $teacher->slug);
        $this->assertEquals('0722222221', $teacher->phone);
        $this->assertEquals('1987-10-21', $teacher->birth_date);
        $this->assertEquals($address_1->id, $teacher->address_id);
        $this->assertEquals('test-teacher-description-1', $teacher->description);
        $this->assertEquals(1, $teacher->user_id);
        $this->assertEquals($seniorityLevel_1->id, $teacher->seniority_level_id);
        $this->assertEquals(1, $teacher->status);
    }

    public function test__updateTeacher()
    {
        /** Create address */
        $address_1 = $this->helper__createAdress(2);
        /** Create seniority level */
        $seniorityLevel_1 = $this->helper__createSeniorityLevel(2);
        /** Create new teacher */
        $teacher = $this->helper__createTeacher(2, $address_1->id, $seniorityLevel_1->id, /*userId*/1);

        /* Validate created teacher */
        $this->assertEquals('test-teacher-name-2', $teacher->name);
        $this->assertEquals('test-teacher-slug-2', $teacher->slug);
        $this->assertEquals('0722222222', $teacher->phone);
        $this->assertEquals('1987-10-22', $teacher->birth_date);
        $this->assertEquals($address_1->id, $teacher->address_id);
        $this->assertEquals('test-teacher-description-2', $teacher->description);
        $this->assertEquals(1, $teacher->user_id);
        $this->assertEquals($seniorityLevel_1->id, $teacher->seniority_level_id);
        $this->assertEquals(1, $teacher->status);

        /** Create address */
        $address_2 = $this->helper__createAdress(3);
        /** Create seniority level */
        $seniorityLevel_2 = $this->helper__createSeniorityLevel(3);

        /** Update teacher */
        $teacher->name = 'new-test-teacher-name';
        $teacher->slug = 'new-test-teacher-slug';
        $teacher->phone = '0722222223';
        $teacher->birth_date = '1987-10-22';
        $teacher->address_id = $address_2->id;
        $teacher->description = 'new-test-teacher-description';
        $teacher->user_id = /*userId*/2;
        $teacher->seniority_level_id = $seniorityLevel_2->id;
        $teacher->status = 0;
        $teacher->save();

        /** Check teacher new values */
        $this->assertEquals('new-test-teacher-name', $teacher->name);
        $this->assertEquals('new-test-teacher-slug', $teacher->slug);
        $this->assertEquals('0722222223', $teacher->phone);
        $this->assertEquals('1987-10-22', $teacher->birth_date);
        $this->assertEquals($address_2->id, $teacher->address_id);
        $this->assertEquals('new-test-teacher-description', $teacher->description);
        $this->assertEquals(2, $teacher->user_id);
        $this->assertEquals($seniorityLevel_2->id, $teacher->seniority_level_id);
        $this->assertEquals(0, $teacher->status);
    }

    public function test__deleteTeacher()
    {
        /** Create address */
        $address_1 = $this->helper__createAdress(4);
        /** Create seniority level */
        $seniorityLevel_1 = $this->helper__createSeniorityLevel(4);
        /** Create new teacher */
        $teacher = $this->helper__createTeacher(3, $address_1->id, $seniorityLevel_1->id, /*userId*/1);

        /* Validate created teacher */
        $this->assertEquals('test-teacher-name-3', $teacher->name);
        $this->assertEquals('test-teacher-slug-3', $teacher->slug);
        $this->assertEquals('0722222223', $teacher->phone);
        $this->assertEquals('1987-10-23', $teacher->birth_date);
        $this->assertEquals($address_1->id, $teacher->address_id);
        $this->assertEquals('test-teacher-description-3', $teacher->description);
        $this->assertEquals(1, $teacher->user_id);
        $this->assertEquals($seniorityLevel_1->id, $teacher->seniority_level_id);
        $this->assertEquals(1, $teacher->status);

        /** Save the ID */
        $teacherId = $teacher->id;

        /** Delete teacher. */
        $teacher->delete();

        /** Search for deleted teacher */
        $_teacher = Teacher::where('id', $teacherId)->first();

        $this->assertEquals(null, $_teacher);
    }


    public function test__dateFormat()
    {
        /** Create address */
        $address_1 = $this->helper__createAdress(5);
        /** Create seniority level */
        $seniorityLevel_1 = $this->helper__createSeniorityLevel(5);
        /** Create new teacher */
        $teacher = $this->helper__createTeacher(4, $address_1->id, $seniorityLevel_1->id, /*userId*/1);

        /* Validate created teacher */
        $this->assertEquals('test-teacher-name-4', $teacher->name);
        $this->assertEquals('test-teacher-slug-4', $teacher->slug);
        $this->assertEquals('0722222224', $teacher->phone);
        $this->assertEquals('1987-10-24', $teacher->birth_date);
        $this->assertEquals($address_1->id, $teacher->address_id);
        $this->assertEquals('test-teacher-description-4', $teacher->description);
        $this->assertEquals(1, $teacher->user_id);
        $this->assertEquals($seniorityLevel_1->id, $teacher->seniority_level_id);
        $this->assertEquals(1, $teacher->status);

        /** Validate date format */
        $this->assertEquals('24-10-1987', $teacher->formated_birth_date);
    }


    public function test__addressName()
    {
        /** Create address */
        $address_1 = $this->helper__createAdress(6);
        /** Create seniority level */
        $seniorityLevel_1 = $this->helper__createSeniorityLevel(6);
        /** Create new teacher */
        $teacher = $this->helper__createTeacher(5, $address_1->id, $seniorityLevel_1->id, /*userId*/1);

        /* Validate created teacher */
        $this->assertEquals('test-teacher-name-5', $teacher->name);
        $this->assertEquals('test-teacher-slug-5', $teacher->slug);
        $this->assertEquals('0722222225', $teacher->phone);
        $this->assertEquals('1987-10-25', $teacher->birth_date);
        $this->assertEquals($address_1->id, $teacher->address_id);
        $this->assertEquals('test-teacher-description-5', $teacher->description);
        $this->assertEquals(1, $teacher->user_id);
        $this->assertEquals($seniorityLevel_1->id, $teacher->seniority_level_id);
        $this->assertEquals(1, $teacher->status);

        /** Validate date format */
        $this->assertEquals(($address_1->name . ', ' . $address_1->county), $teacher->address_name);
    }


    public function test__seniorityName()
    {
        /** Create address */
        $address_1 = $this->helper__createAdress(7);
        /** Create seniority level */
        $seniorityLevel_1 = $this->helper__createSeniorityLevel(7);
        /** Create new teacher */
        $teacher = $this->helper__createTeacher(6, $address_1->id, $seniorityLevel_1->id, /*userId*/1);

        /* Validate created teacher */
        $this->assertEquals('test-teacher-name-6', $teacher->name);
        $this->assertEquals('test-teacher-slug-6', $teacher->slug);
        $this->assertEquals('0722222226', $teacher->phone);
        $this->assertEquals('1987-10-26', $teacher->birth_date);
        $this->assertEquals($address_1->id, $teacher->address_id);
        $this->assertEquals('test-teacher-description-6', $teacher->description);
        $this->assertEquals(1, $teacher->user_id);
        $this->assertEquals($seniorityLevel_1->id, $teacher->seniority_level_id);
        $this->assertEquals(1, $teacher->status);

        /** Validate date format */
        $this->assertEquals($seniorityLevel_1->name, $teacher->seniority);
    }


    /***********************************************
     ************** Helper Functions ***************
     ***********************************************/

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

    /**
     * Created an seniorityLevel object and return's it
     */
    protected function helper__createSeniorityLevel($index)
    {
        /** Create new seniorityLevel */
        $seniorityLevel = SeniorityLevel::create([
            'name' => 'test-seniorityLevel-name-' . $index,
            'diacritic' => 'test-seniorityLevel-diacritic-name-' . $index,
            'description' => 'test-seniorityLevel-description-' . $index,
        ]);

        return $seniorityLevel;
    }

    /**
     * Created an teacher object and return's it
     */
    protected function helper__createTeacher($index, $addressId, $seniorityLevelId, $userId)
    {
        /** Create new teacher */
        $teacher = Teacher::create([
            'name' => 'test-teacher-name-' . $index,
            'slug' => 'test-teacher-slug- . $index',
            'phone' => '072222222' . $index,
            'birth_date' => '1987-10-2' . $index,
            'address_id' => $addressId,
            'description' => 'test-teacher-description-' . $index,
            'user_id' => $userId,
            'seniority_level_id' => $seniorityLevelId,
            'status' => 1,
        ]);

        return $teacher;
    }
}
