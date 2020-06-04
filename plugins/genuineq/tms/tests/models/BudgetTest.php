<?php namespace Genuineq\Tms\Tests\Models;

use PluginTestCase;
use Genuineq\Tms\Models\Budget;
use System\Classes\PluginManager;

class BudgetTest extends PluginTestCase
{
    public function test__createBudget()
    {
        /** Create new budget */
        $budget = $this->helper__createBudget();

        /* Validate created budget */
        $this->assertEquals(1, $budget->budgetable_id);
        $this->assertEquals('test-budgetable-type', $budget->budgetable_type);
        $this->assertEquals(100, $budget->budget);
        $this->assertEquals(1, $budget->status);
    }

    public function test__updateBudget()
    {
        /** Create new budget */
        $budget = $this->helper__createBudget();

        /* Validate created budget */
        $this->assertEquals(1, $budget->budgetable_id);
        $this->assertEquals('test-budgetable-type', $budget->budgetable_type);
        $this->assertEquals(100, $budget->budget);
        $this->assertEquals(1, $budget->status);

        /** Update budget */
        $budget->budget = 200;
        $budget->status = 0;
        $budget->save();

        /** Check budget new values */
        $this->assertEquals(200, $budget->budget);
        $this->assertEquals(0, $budget->status);
    }

    public function test__deleteBudget()
    {
        /** Create new budget */
        $budget = $this->helper__createBudget();

        /* Validate created budget */
        $this->assertEquals(1, $budget->budgetable_id);
        $this->assertEquals('test-budgetable-type', $budget->budgetable_type);
        $this->assertEquals(100, $budget->budget);
        $this->assertEquals(1, $budget->status);

        /** Save the ID */
        $budgetId = $budget->id;

        /** Delete budget. */
        $budget->delete();

        /** Search for deleted budget */
        $_budget = Budget::where('id', $budgetId)->first();

        $this->assertEquals(null, $_budget);
    }


    /***********************************************
     ************** Helper Functions ***************
     ***********************************************/

    /**
     * Created an budget object and return's it
     */
    protected function helper__createBudget()
    {
        /** Create new budget */
        $budget = Budget::create([
            'budgetable_id' => 1,
            'budgetable_type' => 'test-budgetable-type',
            'budget' => 100,
            'status' => 1
        ]);

        return $budget;
    }
}
