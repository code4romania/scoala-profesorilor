<?php namespace Genuineq\Tms\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateGenuineqTmsCourses extends Migration
{
    public function up()
    {
        Schema::create('genuineq_tms_courses', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name')->comment = "The name of the course.";
            $table->string('slug')->unique()->comment = "The slug of the course.";
            $table->integer('supplier_id')->unsigned()->nullable();
            $table->double('duration', 10, 1)->comment = "The total duration of the course in hours.";
            $table->string('address')->comment = "The address where the course will take place.";
            $table->date('start_date')->comment = "When the course starts.";
            $table->date('end_date')->comment = "When the course ends";
            $table->smallInteger('accredited')->default(0)->comment = "Is the course accredited: 0-no, 1-yes";
            $table->integer('credits')->default(0)->comment = "If number of credits this course has.";
            $table->double('price', 10, 2)->comment = "The total price of the course.";
            $table->text('description')->comment = "The description of the course.";
            $table->smallInteger('status')->default(1)->comment = "The status of the course: 0-disabled, 1-enabled";
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('genuineq_tms_courses');
    }
}
