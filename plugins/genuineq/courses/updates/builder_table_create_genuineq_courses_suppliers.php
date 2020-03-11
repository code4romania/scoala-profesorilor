<?php namespace Genuineq\Courses\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateGenuineqCoursesSuppliers extends Migration
{
    public function up()
    {
        Schema::create('genuineq_courses_suppliers', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name')->comment = "The name of the supplier.";
            $table->string('slug')->comment = "The slug of the supplier.";
            $table->string('email', 75)->comment = "The contact email of the supplier.";
            $table->string('phone', 12)->comment = "The contact phone of the supplier.";
            $table->text('description')->nullable()->comment = "The description of the supplier.";
            $table->smallInteger('status')->unsigned()->default(1)->comment = "The status of the supplier: 0-disabled, 1-enabled";
            $table->timestamp('deleted_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('genuineq_courses_suppliers');
    }
}
