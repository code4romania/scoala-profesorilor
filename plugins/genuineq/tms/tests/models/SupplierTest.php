<?php namespace Genuineq\Tms\Tests\Models;

use PluginTestCase;
use Carbon\Carbon;
use Genuineq\Tms\Models\Supplier;
use System\Classes\PluginManager;

class SupplierTest extends PluginTestCase
{
    public function test__createSupplier()
    {
        /** Create new supplier */
        $supplier = $this->helper__createSupplier();

        /* Validate created supplier */
        $this->assertEquals('test-supplier-name', $supplier->name);
        // $this->assertEquals('test-supplier-slug', $supplier->slug);
        $this->assertEquals('supplier@email.com', $supplier->email);
        $this->assertEquals('0722222222', $supplier->phone);
        $this->assertEquals('test-supplier-description', $supplier->description);
        $this->assertEquals(1, $supplier->status);

        /** Delete supplier. */
        $supplier->delete();
    }

    public function test__updateSupplier()
    {
        /** Create new supplier */
        $supplier = $this->helper__createSupplier();

        /* Validate created supplier */
        $this->assertEquals('test-supplier-name', $supplier->name);
        // $this->assertEquals('test-supplier-slug', $supplier->slug);
        $this->assertEquals('supplier@email.com', $supplier->email);
        $this->assertEquals('0722222222', $supplier->phone);
        $this->assertEquals('test-supplier-description', $supplier->description);
        $this->assertEquals(1, $supplier->status);

        /** Update supplier */
        $supplier->name = 'new-test-supplier-name';
        $supplier->slug = 'new-test-supplier-slug';
        $supplier->email = 'new-supplier@email.com';
        $supplier->phone = '0733333333';
        $supplier->description = 'new-test-supplier-description';
        $supplier->status = 0;
        $supplier->save();

        /** Check supplier new values */
        $this->assertEquals('new-test-supplier-name', $supplier->name);
        $this->assertEquals('new-test-supplier-slug', $supplier->slug);
        $this->assertEquals('new-supplier@email.com', $supplier->email);
        $this->assertEquals('0733333333', $supplier->phone);
        $this->assertEquals('new-test-supplier-description', $supplier->description);
        $this->assertEquals(0, $supplier->status);

        /** Delete supplier. */
        $supplier->delete();
    }

    public function test__deleteSupplier()
    {
        /** Create new supplier */
        $supplier = $this->helper__createSupplier();

        /* Validate created supplier */
        $this->assertEquals('test-supplier-name', $supplier->name);
        // $this->assertEquals('test-supplier-slug', $supplier->slug);
        $this->assertEquals('supplier@email.com', $supplier->email);
        $this->assertEquals('0722222222', $supplier->phone);
        $this->assertEquals('test-supplier-description', $supplier->description);
        $this->assertEquals(1, $supplier->status);

        /** Save the ID */
        $supplierId = $supplier->id;

        /** Delete supplier. */
        $supplier->delete();

        /** Search for deleted supplier */
        $_supplier = Supplier::where('id', $supplierId)->first();

        $this->assertEquals(null, $_supplier);
    }


    /***********************************************
     ************** Helper Functions ***************
     ***********************************************/

    /**
     * Created an supplier object and return's it
     */
    protected function helper__createSupplier()
    {
        /** Create new supplier */
        $supplier = Supplier::create([
            'name' => 'test-supplier-name',
            'slug' => 'test-supplier-slug' . '-' . Carbon::now()->timestamp,
            'email' => 'supplier@email.com',
            'phone' => '0722222222',
            'description' => 'test-supplier-description',
            'status' => 1
        ]);

        return $supplier;
    }
}
