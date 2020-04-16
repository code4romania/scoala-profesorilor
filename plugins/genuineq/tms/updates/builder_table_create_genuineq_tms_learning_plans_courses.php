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
            $table->integer('learning_plan_id')->unsigned();
            $table->integer('course_id')->unsigned();
            $table->integer('school_id')->unsigned()->nullable();
            $table->double('covered_costs', 10, 2);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->primary(['learning_plan_id','course_id'], 'genuineq_tms_learning_plans_courses_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('genuineq_tms_learning_plans_courses');
    }
}
