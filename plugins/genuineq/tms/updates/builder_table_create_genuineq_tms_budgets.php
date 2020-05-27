<?php namespace Genuineq\Tms\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateGenuineqTmsBudgets extends Migration
{
    public function up()
    {
        Schema::create('genuineq_tms_budgets', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('semester_id');
            $table->integer('budgetable_id')->unsigned();
            $table->string('budgetable_type');
            $table->double('budget', 10, 2)->nullable();
            $table->smallInteger('status')->default(1);
            $table->timestamp('deleted_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('genuineq_tms_budgets');
    }
}