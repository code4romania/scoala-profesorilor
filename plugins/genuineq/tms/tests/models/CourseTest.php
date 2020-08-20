<?php namespace Genuineq\Tms\Tests\Models;

use PluginTestCase;
use Genuineq\Tms\Models\Course;
use Genuineq\Tms\Models\Category;
use Genuineq\Tms\Models\Supplier;
use System\Classes\PluginManager;

class CourseTest extends PluginTestCase
{
    public function test__createCourse()
    {
        /** Create a supplier */
        $supplier = $this->helper__createSupplier(1);
        /** Create new course */
        $course = $this->helper__createCourse(1, $supplier->id);

        /* Validate created course */
        $this->assertEquals('test-course-name-1', $course->name);
        $this->assertEquals($supplier->id, $course->supplier_id);
        $this->assertEquals(6.6, $course->duration);
        $this->assertEquals('test-course-address-1', $course->address);
        $this->assertEquals('2020-05-01', $course->start_date);
        $this->assertEquals('2020-05-01', $course->end_date);
        $this->assertEquals(0, $course->accredited);
        $this->assertEquals(0, $course->credits);
        $this->assertEquals(101, $course->price);
        $this->assertEquals('test-course-description-1', $course->description);
        $this->assertEquals(1, $course->status);
    }


    public function test__updateCourse()
    {
        /** Create a supplier */
        $supplier = $this->helper__createSupplier(2);
        /** Create new course */
        $course = $this->helper__createCourse(2, $supplier->id);

        /* Validate created course */
        $this->assertEquals('test-course-name-2', $course->name);
        $this->assertEquals($supplier->id, $course->supplier_id);
        $this->assertEquals(7.6, $course->duration);
        $this->assertEquals('test-course-address-2', $course->address);
        $this->assertEquals('2020-05-02', $course->start_date);
        $this->assertEquals('2020-05-02', $course->end_date);
        $this->assertEquals(0, $course->accredited);
        $this->assertEquals(0, $course->credits);
        $this->assertEquals(102, $course->price);
        $this->assertEquals('test-course-description-2', $course->description);
        $this->assertEquals(1, $course->status);

        /** Create a new supplier */
        $newSupplier = $this->helper__createSupplier(3);

        /** Update course */
        $course->name = 'new-test-course-name';
        $course->slug = 'new-test-course-slug';
        $course->supplier_id = $newSupplier->id;
        $course->duration = 6.5;
        $course->address = 'new-test-course-address';
        $course->start_date = '2020-05-02';
        $course->end_date = '2020-05-03';
        $course->accredited = 1;
        $course->credits = 1;
        $course->price = 200;
        $course->description = 'new-test-course-description';
        $course->status = 0;
        $course->save();

        /** Check course new values */
        $this->assertEquals('new-test-course-name', $course->name);
        $this->assertEquals($newSupplier->id, $course->supplier_id);
        $this->assertEquals(6.5, $course->duration);
        $this->assertEquals('new-test-course-address', $course->address);
        $this->assertEquals('2020-05-02', $course->start_date);
        $this->assertEquals('2020-05-03', $course->end_date);
        $this->assertEquals(1, $course->accredited);
        $this->assertEquals(1, $course->credits);
        $this->assertEquals(200, $course->price);
        $this->assertEquals('new-test-course-description', $course->description);
        $this->assertEquals(0, $course->status);
    }


    public function test__deleteCourse()
    {
        /** Create a supplier */
        $supplier = $this->helper__createSupplier(4);
        /** Create new course */
        $course = $this->helper__createCourse(3, $supplier->id);

        /* Validate created course */
        $this->assertEquals('test-course-name-3', $course->name);
        $this->assertEquals($supplier->id, $course->supplier_id);
        $this->assertEquals(8.6, $course->duration);
        $this->assertEquals('test-course-address-3', $course->address);
        $this->assertEquals('2020-05-03', $course->start_date);
        $this->assertEquals('2020-05-03', $course->end_date);
        $this->assertEquals(0, $course->accredited);
        $this->assertEquals(0, $course->credits);
        $this->assertEquals(103, $course->price);
        $this->assertEquals('test-course-description-3', $course->description);
        $this->assertEquals(1, $course->status);

        /** Save the ID */
        $courseId = $course->id;

        /** Delete course. */
        $course->delete();

        /** Search for deleted course */
        $_course = Course::where('id', $courseId)->first();

        $this->assertEquals(null, $_course);
    }


    public function test__dateFormat()
    {
        /** Create a supplier */
        $supplier = $this->helper__createSupplier(5);
        /** Create new course */
        $course = $this->helper__createCourse(4, $supplier->id);

        /* Validate created course */
        $this->assertEquals('test-course-name-4', $course->name);
        $this->assertEquals($supplier->id, $course->supplier_id);
        $this->assertEquals(9.6, $course->duration);
        $this->assertEquals('test-course-address-4', $course->address);
        $this->assertEquals('2020-05-04', $course->start_date);
        $this->assertEquals('2020-05-04', $course->end_date);
        $this->assertEquals(0, $course->accredited);
        $this->assertEquals(0, $course->credits);
        $this->assertEquals(104, $course->price);
        $this->assertEquals('test-course-description-4', $course->description);
        $this->assertEquals(1, $course->status);

        /** Validate date format */
        $this->assertEquals('04.05.2020', $course->startDate());
        $this->assertEquals('04.05.2020', $course->endDate());
    }


    public function test__categoriesCount()
    {
        /** Create a supplier */
        $supplier = $this->helper__createSupplier(6);
        /** Create new course */
        $course = $this->helper__createCourse(5, $supplier->id);

        /* Validate created course */
        $this->assertEquals('test-course-name-5', $course->name);
        $this->assertEquals($supplier->id, $course->supplier_id);
        $this->assertEquals(10.6, $course->duration);
        $this->assertEquals('test-course-address-5', $course->address);
        $this->assertEquals('2020-05-05', $course->start_date);
        $this->assertEquals('2020-05-05', $course->end_date);
        $this->assertEquals(0, $course->accredited);
        $this->assertEquals(0, $course->credits);
        $this->assertEquals(105, $course->price);
        $this->assertEquals('test-course-description-5', $course->description);
        $this->assertEquals(1, $course->status);

        /** Validate 0 categories */
        $this->assertEquals(0, $course->categories->count());

        /** Create a category */
        $category = $this->helper__createCategory(1);

        /** Attach the category */
        $course->categories()->attach($category);
        $course->reloadRelations();

        /** Validate 1 categories */
        $this->assertEquals(1, $course->categories->count());
    }


    public function test__courseColor()
    {
        /** Create a supplier */
        $supplier = $this->helper__createSupplier(7);
        /** Create new course */
        $course = $this->helper__createCourse(6, $supplier->id);

        /* Validate created course */
        $this->assertEquals('test-course-name-6', $course->name);
        $this->assertEquals($supplier->id, $course->supplier_id);
        $this->assertEquals(11.6, $course->duration);
        $this->assertEquals('test-course-address-6', $course->address);
        $this->assertEquals('2020-05-06', $course->start_date);
        $this->assertEquals('2020-05-06', $course->end_date);
        $this->assertEquals(0, $course->accredited);
        $this->assertEquals(0, $course->credits);
        $this->assertEquals(106, $course->price);
        $this->assertEquals('test-course-description-6', $course->description);
        $this->assertEquals(1, $course->status);

        /** Validate default color */
        $this->assertEquals('#4C025E', $course->color);

        /** Create a category */
        $category_1 = $this->helper__createCategory(2);
        /** Create a category */
        $category_5 = $this->helper__createCategory(4);

        /** Attach the category */
        $course->categories()->attach($category_1);
        $course->reloadRelations();

        /** Validate default color */
        $this->assertEquals('#facfa2', $course->color);

        /** Attach the category */
        $course->categories()->attach($category_5);
        $course->reloadRelations();

        /** Validate default color */
        $this->assertEquals('#facfa3', $course->color);
    }


    /***********************************************
     ************** Helper Functions ***************
     ***********************************************/

    /**
     * Created an course object and return's it
     */
    protected function helper__createCourse($index, $supplierId)
    {
        /** Create new course */
        $course = Course::create([
            'name' => 'test-course-name-' . $index,
            'slug' => 'test-course-slug-' . $index,
            'supplier_id' => $supplierId,
            'duration' => 5.6 + $index,
            'address' => 'test-course-address-' . $index,
            'start_date' => '2020-05-0' . $index,
            'end_date' => '2020-05-0' . $index,
            'accredited' => 0,
            'credits' => 0,
            'price' => 100 + $index,
            'description' => 'test-course-description-' . $index,
            'status' => 1
        ]);

        return $course;
    }

    /**
     * Created an category object and return's it
     */
    protected function helper__createCategory($index)
    {
        /** Create new category */
        $category = Category::create([
            'name' => 'test-course-category-name-' . $index,
            'slug' => 'test-course-category-slug-' . $index,
            'color' => 'FACFA' . $index,
            'icon' => 'test-course-category-icon-' . $index,
            'description' => 'test-course-category-description-' . $index,
        ]);

        return $category;
    }

    /**
     * Created an supplier object and return's it
     */
    protected function helper__createSupplier($index)
    {
        /** Create new supplier */
        $supplier = Supplier::create([
            'name' => 'test-coursesupplier-name-' . $index,
            'slug' => 'test-coursesupplier-slug-' . $index,
            'email' => 'supplier@email.com-' . $index,
            'phone' => '072222222' . $index,
            'description' => 'test-course-supplier-description-' . $index,
        ]);

        return $supplier;
    }
}
