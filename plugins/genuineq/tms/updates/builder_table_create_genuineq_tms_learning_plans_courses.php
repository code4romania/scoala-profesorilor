<?php namespace Genuineq\Tms\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateGenuineqTmsLearningPlansCourses extends Migration
{
    public function up()
    {
        Schema::create('genuineq_tms_learning_plans_courses', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('learning_plan_id');
            $table->integer('course_id');
            $table->integer('school_budget_id')->unsigned()->nullable();
            $table->double('school_covered_costs', 10, 2)->default(0);
            $table->boolean('transport_covered')->default(0)->comment = "Indicates if the transport is payed by the school or not.";
            $table->boolean('accommodation_covered')->default(0)->comment = "Indicates if the transport is payed by the school or not.";
            $table->integer('teacher_budget_id')->unsigned()->nullable();
            $table->double('teacher_covered_costs', 10, 2)->default(0);
            $table->boolean('mandatory')->default(0)->comment = "Indicates if the course is mandatory or not.";
            $table->integer('requestable_id')->unsigned()->nullable();
            $table->string('requestable_type')->nullable();
            $table->boolean('participated')->default(0)->comment = "Indicates if the teacher participated to the course or not.";
            $table->enum('status', ['proposed', 'accepted', 'declined',]);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('genuineq_tms_learning_plans_courses');
    }
}
